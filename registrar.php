<!DOCTYPE html>
<html>
<head>
<link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
<link rel="manifest" href="favicon_io/site.webmanifest">
    <title>Lagos GeoSaúde</title>
    <!-- Incluindo o Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #0b2236df;
        }
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
            margin-left: 140px;
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
    .popup {
            margin: 0;
            display: none;
            position: fixed;
            top: 5%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            padding: 3px;
            color: #fff;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            height: auto;
            transition: opacity 2s ease;
        }
    </style>
</head>
<body>
<?php
session_start();

// Exibe as mensagens de erro
if (isset($_SESSION['login_message'])) {
    $message_class = $_SESSION['login_message']['success'] ? 'success' : 'warning';
    $message = $_SESSION['login_message']['message'];
    unset($_SESSION['login_message']);
}
?>
<!-- Exibe a mensagem como um popup -->
<?php if (isset($message)): ?>
        <div class="popup <?php echo $message_class; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="container-fluid" style="margin: 0; margin-top: 90px; display: flex; align-items: center;"
        <?php

        // Exibe os formulários de cadastro e login em "painéis flutuantes"
        if (!isset($_SESSION['usuario'])) {
            echo '
            <div class="row" style="width:100vw; ">

            <div class="col-md-6" style="text-align: center; margin-left: 90px;">
            <img src="imgs/registroimg.svg" style="width: 95vh;">
                </div>
                
                <div class="col-md-6" style="margin:0; text-align: center; width: 200px;">
                    <div class="panel">
                        <h2 style="color:white; margin-bottom:20px;">Log up</h2>
                        <form action="cadastrar.php" method="POST">
                            <div class="mb-3">
                                <input placeholder="Digite um nome" type="text" class="form-control" name="nome" required minlength="2" maxlength="20">
                            </div>
                            <div class="mb-3">
                                <input placeholder="Digite um email"  type="email" class="form-control" name="email" required minlength="11" maxlength="50">
                            </div>
                            <div class="mb-3">
                                <input placeholder="Digite uma senha"  type="password" class="form-control" name="senha" required minlength="8" maxlength="20">
                                <button style="margin-top: 34px;" type="submit" class="btn btn-primary">Criar</button>
                            <a href="logar.php" class="linkbox">Já tem login? Aperte aqui</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>

    <!-- Botão para voltar à página mapa -->
    <div style="width:100%; text-align: right; padding-right:40px;"><a href="mapa.php" class='btn btn-primary mt-4' style="background-color: #0f2d49; border-color: #0b2236df;">Ir ao Mapa</a></div>

    <a href="https://storyset.com/work" style="text-decoration: none; color: white; margin-left:4px;">Image illustrations by Storyset</a>

    <!-- Incluindo o Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
        // Exibe a mensagem de erro com animação de desvanecimento
        window.onload = function() {
            var popup = document.querySelector('.popup');
            if (popup) {
                popup.style.display = 'block';
                setTimeout(function() {
                    popup.style.opacity = '0';
                    setTimeout(function() {
                        popup.style.display = 'none';
                    }, 500);
                }, 4000); // 3000 milissegundos (3 segundos) - ajuste conforme necessário
            }
        }
    </script>
</body>
</html>