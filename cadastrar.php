<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o email já está cadastrado
    $stmt = $pdo->prepare('SELECT * FROM pessoas WHERE email = ?');
    $stmt->execute([$email]);
    $pessoa = $stmt->fetch();

    if ($pessoa) {
        session_start();
        $_SESSION['login_message'] = [
            'success' => false,
            'message' => 'O email fornecido já está cadastrado.'
        ];
        header('Location: index.php');
        exit();
    } else {
        // Insere os dados na tabela de pessoas
        $stmt = $pdo->prepare('INSERT INTO pessoas (nome, email, senha, admin) VALUES (?, ?, ?, false)');
        $stmt->execute([$nome, $email, $senha]);

        session_start();
        $_SESSION['login_message'] = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso.'
        ];
        header('Location: index.php');
        exit();
    }
}
?>