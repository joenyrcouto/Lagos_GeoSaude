<!DOCTYPE html>
<html>
<head>
    <title>Página de Login</title>
    <!-- Incluindo o Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .message {
            padding: 10px;
            margin-top: 10px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();

        // Exibe as mensagens de erro
        if (isset($_SESSION['login_message'])) {
            $message_class = $_SESSION['login_message']['success'] ? 'success' : 'warning';
            echo '<p class="message ' . $message_class . '">' . $_SESSION['login_message']['message'] . '</p>';
            unset($_SESSION['login_message']);
        }

        // Verifica se o usuário está conectado
        if (isset($_SESSION['usuario'])) {
            echo '<p class="message success">Você está conectado como ' . $_SESSION['usuario']['nome'] . '. <a href="logout.php">Desconectar</a></p>';
        } else {
            echo '<p class="message warning">Você não está conectado.</p>';
        }

        // Exibe o link para a página mapa.php
        echo '<a href="mapa.php" class="btn btn-primary" style="margin-bottom: 25px;">Ir para o mapa</a>';

        // Exibe os formulários de cadastro e login
        if (!isset($_SESSION['usuario'])) {
            echo '
            <h2>Cadastro</h2>
            <form action="cadastrar.php" method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" name="nome" required minlength="2" maxlength="20">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" required minlength="11" maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" name="senha" required minlength="8" maxlength="20">
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>
            <br>
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" required minlength="11" maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" name="senha" required minlength="8" maxlength="20">
                </div>
                <button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Login</button>
            </form>';
        }
        ?>
    </div>

    <!-- Incluindo o Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>