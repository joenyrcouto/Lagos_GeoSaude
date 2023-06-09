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
        echo 'O email fornecido já está cadastrado.';
    } else {
        // Insere os dados na tabela de pessoas
        $stmt = $pdo->prepare('INSERT INTO pessoas (nome, email, senha, admin) VALUES (?, ?, ?, false)');
        $stmt->execute([$nome, $email, $senha]);

        echo 'Cadastro realizado com sucesso.';
    }
}
?>