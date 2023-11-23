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
    if(!pessoaJaComentou($idpessoa, $coordenadas)){
        $texto = trim($_POST['comentario']);
        $nota = intval($_POST['nota']);
    }

    if (!empty($texto) && $nota >= 1 && $nota <= 5 && !pessoaJaComentou($idpessoa, $coordenadas)) {
        inserirComentario($coordenadas, $idpessoa, $texto, $nota);
        // Redireciona de volta à página principal
        header('Location: mapa.php');
        exit;
    } else {
        excluirComentariosPessoa($idpessoa, $coordenadas);
        // Redireciona de volta à página principal
        header('Location: mapa.php');
        exit;
    }
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
            anchor: 'bottom',
            style: 'border-radius: 50px;',
        }).setHTML(`
        <style>
  .popup .mapboxgl-popup-content {
    border-radius: 20px;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.25);
    border: 2px solid rgba(0, 0, 0, 0.30);
  }
</style>
        <hr style='margin-top:15px;'>
            <div class='popup-title' style='text-align:center;'><h3 style='margin-bottom:0;'>$titulo</h3></div>
            <div style='text-align: center; font-size: 17px;'>";

            if (!empty($comentarios)) {
                $mediaNotas = array_sum(array_column($comentarios, 'nota')) / count($comentarios);
                $numeroEstrelas = $mediaNotas;

                echo number_format($mediaNotas, 1) . " ";
for ($i = 1; $i <= 5; $i++) {
    if ($i <= $numeroEstrelas) {
        echo "★"; // Estrela preenchida
    } elseif ($i - 0.5 <= $numeroEstrelas) {
        echo "✬"; // Estrela meio preenchida
    } else {
        echo "☆"; // Estrela vazia
    }
}
            }

        echo "</div>
        <div class='popup-info' style='text-align:center; margin-top: 15px;'>$informacoes</div>
        <hr style='margin-top:15px;'>
            <div class='popup-comments'>
                <div class='popup-title' style='text-align:center;'><h3>Comentários</h3></div>";

    if (!empty($comentarios)) {
        echo "<div class='comments-container'>";
        foreach ($comentarios as $comentario) {
            echo "<div class='comment'>
                      <p class='comment-name'><strong>{$comentario['nome']} (Nota: {$comentario['nota']}):</strong> {$comentario['texto']}</p>
                  </div>";
        }
        echo "</div>";
echo "</p>";

    } else {
        echo "<h4>Ainda não há comentários neste ponto.</h4>";
    }

    if ($pessoaConectada) {
        if (!pessoaJaComentou($pessoaConectada['id'], $row['coordenadas'])) {
            echo "<form method='POST' action=''>
                  <input type='hidden' name='coordenadas' value='{$row['coordenadas']}'>
                  <div class='form-group'>
                      <textarea class='form-control' name='comentario' placeholder='Digite seu comentário' required></textarea>
                  </div>
                  <div class='form-group' style='display: flex;'>
                      <label for='nota' style='margin-right: 6px;'>Dê uma Nota:</label>
                      <select name='nota' id='nota' required style='border-radius:8px;'>
                          <option value='1'>1 muito ruim</option>
                          <option value='2'>2 ruim</option>
                          <option value='3'>3 medio</option>
                          <option value='4'>4 bom</option>
                          <option value='5'>5 muito bom</option>
                      </select>
                  </div>
                  <div style='text-align:center;'>
                  <button type='submit' class='btn btn-primary' style='border-radius: 12px; background-color: rgb(225, 200, 31); color: black; border-color: black;'>Enviar Comentário</button>
                  </div>
              </form>";
        } else {
            echo "<form method='POST' action=''>
                  <input type='hidden' name='coordenadas' value='{$row['coordenadas']}'>
                  <div style='text-align:center;'>
                  <button type='submit' class='btn btn-primary' style='background-color:orangered; margin-top: 8px; background-color: rgb(225, 200, 31); color: black; border-color: black;'>Excluir Comentário</button>
                  </div>
              </form>";
        }
    } else {
        echo "<div style='text-align: center'><a href='logar.php' class='btn btn-primary' style='text-decoration: none; border-radius: 12px; background-color: rgb(225, 200, 31); color: black; border-color: black;'>Faça login para comentar</a></p></div>";
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

// Função para excluir todos os comentários de uma pessoa em um ponto
function excluirComentariosPessoa($idpessoa, $coordenadas) {
    global $pdo;

    $stmt = $pdo->prepare('DELETE FROM comentario WHERE idpessoa = ? AND idponto = ?');
    $stmt->execute([$idpessoa, $coordenadas]);

}
?>
