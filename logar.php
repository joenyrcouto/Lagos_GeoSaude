<!DOCTYPE html>
<html>
<head>
    <title>Página de Login</title>
    <!-- Incluindo o Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .message {
            padding: 10px;
            margin-bottom: 10px;
            color: #fff;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
        }

        .success {
            background-color: #28a745;
        }

        .warning {
            background-color: #ff9800;
            margin-bottom: 0px;
            margin-left: 6%;
            margin-right: 6%;
            width: 88%;
            height: 12vh;
            display: flex;
            align-items: center; /* Centralize verticalmente */
            justify-content: center; /* Centralize horizontalmente */
            font-size: 25px;
        }

        .panel {
            background-color: #cdc2ff99;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            padding: 20px;
            height: 430px;
            margin: 20px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
            text-align: center;
            display: grid;
    place-items: center;
        }
        h2 {font-size: 40px;
        font-weight: 600;}
        .mb-3 {display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: start;}
        .container-fluid {
            height: 76vh;
        }
        form {width: 100%}

        button {
            width: 120px;
            height: 45px;
        }

        @media (max-width: 950px){
        .row {
            display: block;
            text-align: center;
        }
        .col-md-6 {display: inline-block;
        width: 90%;}
        .container-fluid {
            height: 950px
        }
        .message {
             font-size: 20px
        }
        }
    </style>
</head>
<body>
<div class="sky" style="z-index:-1;">
        <div class="rain"></div>
    </div>

<?php
session_start();

// Exibe as mensagens de erro
if (isset($_SESSION['login_message'])) {
    $message_class = $_SESSION['login_message']['success'] ? 'success' : 'warning';
    echo '<p class="message ' . $message_class . '">' . $_SESSION['login_message']['message'] . '</p>';
    unset($_SESSION['login_message']);
} else {
    // Verifica se o usuário está conectado
    if (isset($_SESSION['usuario'])) {
        echo '<p class="message success">Você está conectado como ' . $_SESSION['usuario']['nome'] . '. <a href="logout.php">Desconectar</a></p>';
    } else {
        echo '<p class="message warning">Faça o login para liberar funções de usuário no site do site</p>';
    }
}
?>
    <div class="container-fluid" style="margin: 0; display: flex; align-items: center;">
        <?php

        // Exibe os formulários de cadastro e login em "painéis flutuantes"
        if (!isset($_SESSION['usuario'])) {
            echo '
            <div class="row" style="width:100vw; ">
                <div class="col-md-6">
                    <div class="panel">
                        <h2>Cadastro</h2>
                        <form action="cadastrar.php" method="POST">
                            <div class="mb-3">
                                <input placeholder="Digite um nome" type="text" class="form-control" name="nome" required minlength="2" maxlength="20">
                            </div>
                            <div class="mb-3">
                                <input placeholder="Digite um email"  type="email" class="form-control" name="email" required minlength="11" maxlength="50">
                            </div>
                            <div class="mb-3">
                                <input placeholder="Digite uma senha"  type="password" class="form-control" name="senha" required minlength="8" maxlength="20">
                            </div>
                            <button style="margin-top: 34px;" type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel">
                        <h2>Login</h2>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <input placeholder="Digite seu email"  type="email" class="form-control" name="email" required minlength="11" maxlength="50">
                            </div>
                            <div class="mb-3">
                                <input style="margin-top: 25px;" placeholder="Digite sua senha"  type="password" class="form-control" name="senha" required minlength="8" maxlength="20">
                            </div>
                            <button style="margin-top: 52px;" type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>

    <a href="index.php" class="btn btn-primary" style="margin-left: 6%; margin-right: 6%; width: 88%; height: 12vh; font-size:30px; display: flex; align-items: center; justify-content: center;">Clique aqui para ir ao mapa</a>

    <!-- Incluindo o Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
