<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conexao.php'; // Certifique-se de incluir o arquivo de conexão com o banco de dados

    // Obtenha as coordenadas do ponto a ser editado
    $coordenadas = $_POST['coordenadas'];

    // Obtenha os dados do formulário de edição
    $novoTitulo = $_POST['editarTitulo'];
    $novasInformacoes = $_POST['editarInformacoes'];

    // Recupere as informações atuais do ponto com base nas coordenadas
    $sql = "SELECT titulo, informacoes FROM pontos WHERE coordenadas = :coordenadas";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':coordenadas', $coordenadas);
    $stmt->execute();
    $ponto = $stmt->fetch();

    if ($ponto['titulo'] === $novoTitulo && $ponto['informacoes'] === $novasInformacoes) {
        // Se as informações são as mesmas, exiba uma mensagem de aviso
        header("Location: listamarcadores.php?mensagem=As informações enviadas são inalteradas.");
    } else {
        // As informações foram alteradas, atualize o banco de dados
        $sql = "UPDATE pontos SET titulo = :novoTitulo, informacoes = :novasInformacoes WHERE coordenadas = :coordenadas";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':novoTitulo', $novoTitulo);
        $stmt->bindParam(':novasInformacoes', $novasInformacoes);
        $stmt->bindParam(':coordenadas', $coordenadas);

        if ($stmt->execute()) {
            // Redirecione de volta para a página anterior com uma mensagem de sucesso
            header("Location: listamarcadores.php?mensagem=Informações atualizadas com sucesso");
        } else {
            echo "Erro ao atualizar as informações. Por favor, tente novamente.";
        }
    }
} else {
    echo "Acesso não autorizado.";
}
