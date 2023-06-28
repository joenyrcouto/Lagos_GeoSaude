<?php
require 'conexao.php';

$mensagem = ''; // Variável para armazenar a mensagem de aviso

if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
    // Ler o conteúdo do arquivo enviado
    $conteudo = file_get_contents($_FILES['arquivo']['tmp_name']);

    // Dividir o conteúdo em linhas
    $linhas = explode(PHP_EOL, $conteudo);

    $pontosAdicionados = 0; // Contador de pontos adicionados
    $pontosIgnorados = 0; // Contador de pontos ignorados

    // Processar cada linha do arquivo
    foreach ($linhas as $linha) {
        // Dividir a linha em partes usando o ponto-e-vírgula como separador
        $partes = explode(';', $linha);

        // Verificar se há informações suficientes na linha
        if (count($partes) >= 2) {
            // Extrair as coordenadas da primeira parte
            $coordenadas = explode(',', trim($partes[0]));

            // Verificar se as coordenadas são válidas
            if (count($coordenadas) == 2) {
                $latitude = trim($coordenadas[1]);
                $longitude = trim($coordenadas[0]);

                // Verificar se já existe um ponto com as mesmas coordenadas no banco de dados
                $sql = "SELECT COUNT(*) FROM pontos WHERE coordenadas = :coordenadas";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':coordenadas', $latitude . ',' . $longitude);
                $stmt->execute();
                $existePonto = $stmt->fetchColumn();

                if ($existePonto) {
                    $pontosIgnorados++;
                    continue; // Ponto já existente, ignorar e continuar
                }

                // Extrair as informações do ponto da segunda parte
                $titulo = trim($partes[1]);
                $informacoes = isset($partes[2]) ? trim($partes[2]) : '';

                // Inserir o novo ponto no banco de dados
                $sql = "INSERT INTO pontos (coordenadas, titulo, informacoes, aparecenomapa) VALUES (:coordenadas, :titulo, :informacoes, 1)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':coordenadas', $latitude . ',' . $longitude);
                $stmt->bindValue(':titulo', $titulo);
                $stmt->bindValue(':informacoes', $informacoes);
                $stmt->execute();

                $pontosAdicionados++;
            }
        }
    }

    $mensagem = "Arquivo processado. Foram adicionados $pontosAdicionados ponto(s) e $pontosIgnorados ponto(s) foram ignorados.";
} else {
    $mensagem = "Ocorreu um erro ao processar o arquivo.";
}

header('Location: listamarcadores.php?mensagem=' . urlencode($mensagem)); // Redirecionar de volta ao mapa com a mensagem de aviso
exit();
?>