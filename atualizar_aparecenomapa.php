<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se é a ação de apagar registro
    if (isset($_POST['apagar'])) {
        $coordenadas = $_POST['coordenadas'];

        // Executa o comando DELETE para remover o registro
        $stmt = $pdo->prepare('DELETE FROM pontos WHERE coordenadas = ?');
        $stmt->execute([$coordenadas]);

        // Redireciona de volta para a página anterior
        header('Location: listamarcadores.php');
        exit();
    }

    // Verifica se é a ação de atualizar a aparência no mapa
    if (isset($_POST['aparecenomapa'])) {
        $coordenadas = $_POST['coordenadas'];
        $aparecenomapa = $_POST['aparecenomapa'];

        // Executa o comando UPDATE para atualizar o campo aparecenomapa e as coordenadas
        $stmt = $pdo->prepare('UPDATE pontos SET aparecenomapa = ?, coordenadas = ? WHERE coordenadas = ?');
        $stmt->execute([$aparecenomapa, $coordenadas, $coordenadas]);

        // Redireciona de volta para a página anterior
        header('Location: listamarcadores.php');
        exit();
    }
}
?>