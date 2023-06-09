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

// Retrieve data from the database
$stmt = $pdo->query("SELECT * FROM pontos WHERE aparecenomapa = 1");
while ($row = $stmt->fetch()) {
    $coordenadas = explode(',', $row['coordenadas']);
    $titulo = $row['titulo'];
    $informacoes = $row['informacoes'];

    // Obter os comentários do ponto específico
    $comentarios = getComentarios($row['coordenadas']);

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
    }

    echo "`
        )).addTo(map);\n";
}

// Adicione o código JavaScript para impedir que o clique no marcador propague para o mapa
echo "marker.getElement().addEventListener('click', function(e) {
    e.stopPropagation();
    marker.togglePopup();
});";

// Verifica se foi enviado um novo comentário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pessoaConectada) {
    $coordenadas = $_POST['coordenadas'];
    $idpessoa = $pessoaConectada['id'];
    $texto = $_POST['comentario'];

    inserirComentario($coordenadas, $idpessoa, $texto);
}

// Função para inserir um novo comentário
function inserirComentario($coordenadas, $idpessoa, $texto) {
    global $pdo;

    $stmt = $pdo->prepare('INSERT INTO comentario (idponto, idpessoa, texto) VALUES (?, ?, ?)');
    $stmt->execute([$coordenadas, $idpessoa, $texto]);
}
?>
