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

        // Verifica se a pessoa é um administrador
        $isAdmin = $pessoa['admin'];
        $_SESSION['isAdmin'] = $isAdmin;

        header('Location: index.php');
        exit();
    } else {
        session_start();
        $_SESSION['login_message'] = [
            'success' => false,
            'message' => 'Email ou senha inválidos.'
        ];
        header('Location: index.php');
        exit();
    }
}
?>
