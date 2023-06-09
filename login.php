<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o email e senha estão corretos
    $stmt = $pdo->prepare('SELECT * FROM pessoas WHERE email = ? AND senha = ?');
    $stmt->execute([$email, $senha]);
    $pessoa = $stmt->fetch();

    if ($pessoa) {
        session_start();
        $_SESSION['usuario'] = $pessoa;
        header('Location: index.php');
        exit();
    } else {
        echo 'Email ou senha inválidos.';
    }
}
?>