<!DOCTYPE html>
<html>
<head>
<link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
<link rel="manifest" href="favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <title>Lagos GeoSaude</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b2236df;
        }
        table {
            margin-top: 20px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .table-container {
            max-height: 200px; /* Defina a altura máxima desejada */
            overflow-y: auto;
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="width:100%; text-align: center; padding-bottom: 10px;"><h1 style="font-weight: 800; color: #06131edf;">Sugestões de pontos</h1></div>

        <!-- Formulário de pesquisa -->
        <form method="GET" action="">
            <div class="input-group mb-3">
                <input type="text" name="pesquisa" class="form-control" placeholder="Pesquisar por título de ponto">
                <button class="btn btn-primary" type="submit" style="background-color: #0b2236df; border-color: #0b2236df;">Pesquisar</button>
            </div>
        </form>

        <div class="table-container">
            <?php
            require 'conexao.php';

            // Recuperar os pontos que têm aparecenomapa igual a 0
            $sql = "SELECT * FROM pontos WHERE aparecenomapa = 0";

            // Verificar se foi feita uma pesquisa
            if (isset($_GET['pesquisa'])) {
                $pesquisa = $_GET['pesquisa'];

                // Verificar se a pesquisa não está vazia
                if (!empty($pesquisa)) {
                    // Adicionar a condição de pesquisa ao SQL
                    $sql .= " AND titulo LIKE '%$pesquisa%'";
                }
            }

            $stmt = $pdo->query($sql);
            $pontos = $stmt->fetchAll();
            session_start();
            $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];

            // Verificar se há algum ponto a ser exibido
            if (count($pontos) > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Coordenadas</th><th>Título</th><th>Informações</th></tr></thead>";
                echo "<tbody>";

                foreach ($pontos as $ponto) {
                    echo "<tr>";
                    echo "<td>".$ponto['coordenadas']."</td>";
                    echo "<td>".$ponto['titulo']."</td>";
                    echo "<td>".$ponto['informacoes']."</td>";

                    if ($isAdmin) {
                        echo "<td>
                                <button type='button' class='btn btn-primary' style='background-color: #0b2236df; border-color: #0b2236df;' onclick='openPopup(\"editarPopup".$ponto['coordenadas']."\")'>Editar</button>
                              </td>";
                        echo "<td>
                                <form action='atualizar_aparecenomapa.php' method='post'>
                                    <input type='hidden' name='coordenadas' value='".$ponto['coordenadas']."'>
                                    <button type='submit' name='apagar' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja apagar o registro?\")'>Apagar Registro</button>
                                </form>
                              </td>";
                        echo "<td>
                                <form action='atualizar_aparecenomapa.php' method='post'>
                                    <input type='hidden' name='coordenadas' value='".$ponto['coordenadas']."'>
                                    <button type='submit' name='aparecenomapa' value='1' class='btn btn-success'>Aprovar</button>
                                </form>
                              </td>";
                    }

                    echo "</tr>";

                    // Div de Edição (popup)
                    echo "
                        <div id='editarPopup".$ponto['coordenadas']."' class='popup' style='background-color: #0f2d49fe; color:white;'>
                            <h2>Editar Informações</h2>
                            <!-- Formulário de edição com campos preenchidos com as informações existentes -->
                            <form action='editar_ponto.php' method='post'>
                                <input type='hidden' name='coordenadas' value='".$ponto['coordenadas']."'>
                                <div class='mb-3'>
                                    <label for='editarTitulo' class='form-label'>Título:</label>
                                    <input type='text' class='form-control' id='editarTitulo' name='editarTitulo' value='".$ponto['titulo']."'>
                                </div>
                                <div class='mb-3'>
                                    <label for='editarInformacoes' class='form-label'>Informações:</label>
                                    <textarea class='form-control' id='editarInformacoes' name='editarInformacoes'>".$ponto['informacoes']."</textarea>
                                </div>
                                <button type='submit' class='btn btn-primary'>Salvar</button>
                                <button type='button' onclick='closePopup(\"editarPopup".$ponto['coordenadas']."\")' class='btn btn-secondary'>Cancelar</button>
                            </form>
                        </div>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p class='mt-4' style='text-align:center; font-size: 20px; font-weight: 650;'>Não há pontos para exibir!!</p>";
            }
            ?>
        </div>

        <?php
        $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
        if ($isAdmin) {
            echo "
                <form action='adicionar_pontos.php' method='post' enctype='multipart/form-data' class='mt-4'>
                    <div class='mb-3'>
                        <label for='arquivo' class='form-label'>Enviar arquivo de pontos:</label>
                        <input class='form-control' type='file' id='arquivo' name='arquivo' accept='.txt'>
                    </div>
                    <button type='submit' class='btn btn-primary' style='background-color: #0b2236df; border-color: #0b2236df;'>Enviar</button>
                </form>
            ";
        }

        // Verificar se há uma mensagem na URL
        if (isset($_GET['mensagem'])) {
            $mensagem = $_GET['mensagem'];
            // Exibir a mensagem na página
            echo "<div class='alert alert-info' style='margin-top: 8px;'>$mensagem</div>";
        }
        ?>

        <!-- Botão para voltar à página mapa -->
        <div style="width:100%; text-align: right;"><a href="mapa.php" class='btn btn-primary mt-4' style="background-color: #0b2236df; border-color: #0b2236df;">Ir ao Mapa</a></div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openPopup(popupId) {
            document.getElementById(popupId).style.display = 'block';
        }

        function closePopup(popupId) {
            document.getElementById(popupId).style.display = 'none';
        }
    </script>
</body>
</html>
