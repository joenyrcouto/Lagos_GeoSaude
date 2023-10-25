<?php
require 'conexao.php';

$mensagem = ''; // Variável para armazenar a mensagem de aviso

if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
    $pontosAdicionados = 0; // Contador de pontos adicionados
    $pontosIgnorados = 0; // Contador de pontos ignorados

    $file = fopen($_FILES['arquivo']['tmp_name'], 'r'); // Abrir o arquivo em modo leitura

    // Processar cada linha do arquivo
    while (($linha = fgets($file)) !== false) {
        // Ignorar linhas vazias
        if (empty($linha)) {
            continue;
        }

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
                $stmt->bindValue(':coordenadas', $latitude . ',' . $longitude, PDO::PARAM_STR);
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
                $stmt->bindValue(':coordenadas', $latitude . ',' . $longitude, PDO::PARAM_STR);
                $stmt->bindValue(':titulo', $titulo, PDO::PARAM_STR);
                $stmt->bindValue(':informacoes', $informacoes, PDO::PARAM_STR);
                $stmt->execute();

                $pontosAdicionados++;
            }
        }
    }

    fclose($file); // Fechar o arquivo

    $mensagem = "Arquivo processado. Foram adicionados $pontosAdicionados ponto(s) e $pontosIgnorados ponto(s) foram ignorados.";
} else {
    $mensagem = "Ocorreu um erro ao processar o arquivo.";
}

header('Location: listamarcadores.php?mensagem=' . urlencode($mensagem)); // Redirecionar de volta ao mapa com a mensagem de aviso
exit();
?>