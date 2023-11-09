<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sugestão de pontos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sugestão de pontos</h1>

        <!-- Formulário de pesquisa -->
        <form method="GET" action="">
            <div class="input-group mb-3">
                <input type="text" name="pesquisa" class="form-control" placeholder="Pesquisar por título de ponto">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
            </div>
        </form>

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
            <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editarModal".$ponto['coordenadas']."'>Editar</button>
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
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p class='mt-4'>Não há pontos para exibir (Vá ao mapa e clique neste para abrir um formulário de sugestão de ponto)!!</p>";
        }
        ?>

        <!-- Botão para voltar à página mapa -->
        <a href="index.php" class="btn btn-primary mt-4">Voltar ao Mapa</a>

        <!-- Botão para voltar carregar arquivo -->

        <!-- Modal de Edição -->
<div class="modal fade" id="editarModal<?php echo $ponto['coordenadas']; ?>" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarModalLabel">Editar Informações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário de edição com campos preenchidos com as informações existentes -->
                <form action="editar_ponto.php" method="post">
                    <input type="hidden" name="coordenadas" value="<?php echo $ponto['coordenadas']; ?>">
                    <div class="mb-3">
                        <label for="editarTitulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" id="editarTitulo" name="editarTitulo" value="<?php echo $ponto['titulo']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editarInformacoes" class="form-label">Informações:</label>
                        <textarea class="form-control" id="editarInformacoes" name="editarInformacoes"><?php echo $ponto['informacoes']; ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
        <?php
        require 'conexao.php';

            $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
if ($isAdmin) {
    echo "
        <form action='adicionar_pontos.php' method='post' enctype='multipart/form-data' class='mt-4'>
            <div class='mb-3'>
                <label for='arquivo' class='form-label'>Enviar arquivo de pontos:</label>
                <input class='form-control' type='file' id='arquivo' name='arquivo' accept='.txt'>
            </div>
            <button type='submit' class='btn btn-primary'>Enviar</button>
        </form>
    ";
}

// Verificar se há uma mensagem na URL
if (isset($_GET['mensagem'])) {
    $mensagem = $_GET['mensagem'];
    // Exibir a mensagem na página
    echo "<div class='alert alert-info'>$mensagem</div>";
}
?>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>