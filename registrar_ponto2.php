<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $titulo = $_POST['titulo'];
    $informacoes = $_POST['rua'] . " - " . $_POST['cidade'] . " - " . $_POST['estado'] . "<br>" . $_POST['horario'];

    // Insira as informações no banco de dados
    $stmt = $pdo->prepare('INSERT INTO pontos (coordenadas, titulo, informacoes, aparecenomapa) VALUES (?, ?, ?, 0)');
    $stmt->execute([$latitude . ',' . $longitude, $titulo, $informacoes]);

    // Redirecione o usuário de volta ao mapa ou faça qualquer outra ação necessária
    header('Location: mapa2.php');
    exit();
}
?>