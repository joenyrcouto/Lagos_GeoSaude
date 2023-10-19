<?php
require 'conexao.php';

// Função para recuperar os comentários de um ponto específico
function getComentarios($coordenadas) {
    global $pdo;

    $stmt = $pdo->prepare('SELECT c.texto, p.nome FROM comentario c
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

    if (!empty($texto)) {
        inserirComentario($coordenadas, $idpessoa, $texto);
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
$stmt = $pdo->query("SELECT * FROM pontos WHERE aparecenomapa = 0");
while ($row = $stmt->fetch()) {
    $coordenadas = explode(',', $row['coordenadas']);
    $titulo = $row['titulo'];
    $informacoes = $row['informacoes'];

    // Obter os comentários do ponto específico
    $comentarios = getComentarios($row['coordenadas']);

    echo "(function() {"; // Função anônima para criar um escopo separado para cada marcador
    echo "var marker = new mapboxgl.Marker({ color: 'yellow' })"
        . ".setLngLat([$coordenadas[0], $coordenadas[1]])"
        . ".setPopup(new mapboxgl.Popup({
            closeButton: false,
            className: 'popup',
            anchor: 'bottom'
        }).setHTML(`
            <div class='popup-title'><h3>$titulo</h3></div>
            <div class='popup-info'>$informacoes</div>
            <div class='popup-comments'>";
               /* <div class='popup-title'><h3>Comentários</h3></div>"; */

    /* if (!empty($comentarios)) {
        echo "<div class='comments-container'>";
        foreach ($comentarios as $comentario) {
            echo "<div class='comment'>
                      <p class='comment-name'><strong>{$comentario['nome']}:</strong> {$comentario['texto']}</p>
                  </div>";
        }
        echo "</div>";
    } else {
        echo "<p>Ainda não há comentários neste ponto.</p>";
    }

    if ($pessoaConectada) {
        echo "<form method='POST' action=''>
                  <input type='hidden' name='coordenadas' value='{$row['coordenadas']}'>
                  <div class='form-group'>
                      <textarea class='form-control' name='comentario' placeholder='Digite seu comentário'></textarea>
                  </div>
                  <button type='submit' class='btn btn-primary'>Enviar Comentário</button>
              </form>";
    } else {
        echo "<p><strong>Faça login para deixar um comentário.</strong></p>";
    } */

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
        center: [marker.getLngLat().lng, marker.getLngLat().lat + relativeOffset * 0.01+0.006], // Ajuste conforme necessário
        zoom: 14 // Zoom desejado
    });";
    echo "}";
    echo "});";

    echo "})();"; // Feche a função anônima imediatamente
}

echo "});"; // Feche a função on('load')

// Função para inserir um novo comentário
function inserirComentario($coordenadas, $idpessoa, $texto) {
    global $pdo;

    $stmt = $pdo->prepare('INSERT INTO comentario (idponto, idpessoa, texto) VALUES (?, ?, ?)');
    $stmt->execute([$coordenadas, $idpessoa, $texto]);
}
?>
