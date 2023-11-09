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
            background-color: #20B2AA;
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
            background-color: #0f2d49;
            border 2px solid rgba(20, 20, 20, 1);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 350px;
            height: 475px;
            margin: 20px;
            margin-left: 180px;
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

        .linkbox {text-decoration: none;
    color: white;
    margin-top: 35px;
    border-radius: 8px;
    padding-left: 4px;
    padding-right: 4px;}
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

    <div class="container-fluid" style="margin: 0; display: flex; align-items: center;"
        <?php

        // Exibe os formulários de cadastro e login em "painéis flutuantes"
        if (!isset($_SESSION['usuario'])) {
            echo '
            <div class="row" style="width:100vw; ">

            <div class="col-md-6">
            <img src="https://i.pinimg.com/originals/d1/3a/86/d13a8682fdaf98f948fbc28f88cbcd1d.png">
                </div>

                <div class="col-md-6" style="margin:auto;">
                    <div class="panel">
                        <h2 style="color:white">Login</h2>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <input placeholder="Digite seu email"  type="email" class="form-control" name="email" required minlength="11" maxlength="50">
                            </div>
                            <div class="mb-3">
                                <input style="margin-top: 25px;" placeholder="Digite sua senha"  type="password" class="form-control" name="senha" required minlength="8" maxlength="20">
                                <button style="margin-top: 52px;" type="submit" class="btn btn-primary">Enviar</button>
                            <a href="registrar.php" class="linkbox">Não tem login? Aperte aqui</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>

    <!-- Incluindo o Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
