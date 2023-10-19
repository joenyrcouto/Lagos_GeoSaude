<?php
require 'conexao.php';

// Função para verificar se a pessoa já comentou em um ponto
function pessoaJaComentou($idpessoa, $coordenadas) {
    global $pdo;

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM comentario WHERE idpessoa = ? AND idponto = ?');
    $stmt->execute([$idpessoa, $coordenadas]);
    $count = $stmt->fetchColumn();

    return $count > 0;
}

// Função para excluir todos os comentários de uma pessoa em um ponto
function excluirComentariosPessoa($idpessoa, $coordenadas) {
    global $pdo;

    $stmt = $pdo->prepare('DELETE FROM comentario WHERE idpessoa = ? AND idponto = ?');
    $stmt->execute([$idpessoa, $coordenadas]);
}

// Função para recuperar os comentários de um ponto específico
function getComentarios($coordenadas) {
    global $pdo;

    $stmt = $pdo->prepare('SELECT c.id, c.texto, c.nota, p.nome FROM comentario c
                           JOIN pessoas p ON c.idpessoa = p.id
                           WHERE c.idponto = ?');
    $stmt->execute([$coordenadas]);
    $comentarios = $stmt->fetchAll();

    return $comentarios;
}

// Verifica se a pessoa está conectada
session_start();
$pessoaConectada = $_SESSION['usuario'] ?? null;

// Verifica se foi enviado um novo comentário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pessoaConectada) {
    $coordenadas = $_POST['coordenadas'];
    $idpessoa = $pessoaConectada['id'];
    $texto = trim($_POST['comentario']);
    $nota = intval($_POST['nota']);

    if (!empty($texto) && $nota >= 1 && $nota <= 5 && !pessoaJaComentou($idpessoa, $coordenadas)) {
        inserirComentario($coordenadas, $idpessoa, $texto, $nota);
        // Redireciona de volta à página principal
        header('Location: index.php');
        exit;
    }
}

// Verifica se a pessoa deseja excluir seus comentários em um ponto
if (isset($_POST['excluirComentarios']) && $pessoaConectada) {
    $coordenadas = $_POST['coordenadas'];
    $idpessoa = $pessoaConectada['id'];
    excluirComentariosPessoa($idpessoa, $coordenadas);

    // Redireciona para a página index.php após a exclusão
    header('Location: index.php');
    exit;
}

// Adicione o código JavaScript para impedir que o clique no marcador propague para o mapa
echo "map.on('load', function() {";

// Variável para manter o rastreamento do popup atualmente aberto
echo "var currentPopup = null;";

// Retrieve data from the database
$stmt = $pdo->query("SELECT * FROM pontos WHERE aparecenomapa = 1");
while ($row = $stmt->fetch()) {
    $coordenadas = explode(',', $row['coordenadas']);
    $titulo = $row['titulo'];
    $informacoes = $row['informacoes'];

    // Obter os comentários do ponto específico
    $comentarios = getComentarios($row['coordenadas']);

    echo "(function() {"; // Função anônima para criar um escopo separado para cada marcador
    echo "var marker = new mapboxgl.Marker()"
        . ".setLngLat([$coordenadas[0], $coordenadas[1]])"
        . ".setPopup(new mapboxgl.Popup({
            closeButton: false,
            className: 'popup',
            anchor: 'bottom'
        }).setHTML(`
            <div class='popup-title'><h3>$titulo</h3></div>
            <div class='popup-info'>$informacoes</div>
            <div class='popup-comments'>
                <div class='popup-title'><h3>Comentários</h3></div>";

    if (!empty($comentarios)) {
        echo "<div class='comments-container'>";
        foreach ($comentarios as $comentario) {
            echo "<div class='comment'>
                      <p class='comment-name'><strong>{$comentario['nome']} (Nota: {$comentario['nota']}):</strong> {$comentario['texto']}</p>
                  </div>";
        }
        echo "</div>";
        $mediaNotas = array_sum(array_column($comentarios, 'nota')) / count($comentarios);
        echo "<p>Média das Notas: " . number_format($mediaNotas, 2) . "</p>";
    } else {
        echo "<p>Ainda não há comentários neste ponto.</p>";
    }

    if ($pessoaConectada) {
        if (!pessoaJaComentou($pessoaConectada['id'], $row['coordenadas'])) {
            echo "<form method='POST' action=''>
                  <input type='hidden' name='coordenadas' value='{$row['coordenadas']}'>
                  <div class='form-group'>
                      <textarea class='form-control' name='comentario' placeholder='Digite seu comentário' required></textarea>
                  </div>
                  <div class='form-group'>
                      <label for='nota'>Selecione a Nota (1-5): </label>
                      <select name='nota' id='nota' required>
                          <option value='1'>1</option>
                          <option value='2'>2</option>
                          <option value='3'>3</option>
                          <option value='4'>4</option>
                          <option value='5'>5</option>
                      </select>
                  </div>
                  <button type='submit' class='btn btn-primary'>Enviar Comentário</button>
              </form>";
        } else {
            echo "<form method='POST' action=''>
                  <input type='hidden' name='coordenadas' value='{$row['coordenadas']}'>
                  <button type='submit' name='excluirComentarios' class='btn btn-danger'>Excluir Comentários</button>
              </form>";
        }
    } else {
        echo "<p><strong>Faça login para deixar um comentário.</strong></p>";
    }

    echo "`
        )).addTo(map);\n";

    // Adicione o código JavaScript para impedir que o clique no marcador propague para o mapa
    echo "marker.getElement().addEventListener('click', function(e) {";
    echo "e.stopPropagation();";
    echo "if (currentPopup === marker.getPopup()) {";
    echo "marker.togglePopup();";
    echo "currentPopup = null;";
    echo "} else {";
    echo "if (currentPopup) {";
    echo "currentPopup.remove();";
    echo "}";
    echo "marker.togglePopup();";
    echo "currentPopup = marker.getPopup();";
    echo "var windowHeight = window.innerHeight;";
    echo "var mapHeight = map.getCanvas().clientHeight;";
    echo "var relativeOffset = (windowHeight - mapHeight) / windowHeight;";
    echo "map.easeTo({
        center: [marker.getLngLat().lng, marker.getLngLat().lat + relativeOffset * 0.01+0.01], // Ajuste conforme necessário
        zoom: 14 // Zoom desejado
    });";
    echo "}";
    echo "});";

    echo "})();"; // Feche a função anônima imediatamente
}

echo "});"; // Feche a função on('load')

// Função para inserir um novo comentário
function inserirComentario($coordenadas, $idpessoa, $texto, $nota) {
    global $pdo;

    $stmt = $pdo->prepare('INSERT INTO comentario (idponto, idpessoa, texto, nota) VALUES (?, ?, ?, ?)');
    $stmt->execute([$coordenadas, $idpessoa, $texto, $nota]);
}
?>
