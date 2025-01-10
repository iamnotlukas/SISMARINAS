<?php
require_once '../ConexaoBanco/conexao.php'; // Certifique-se de que o arquivo está correto e funcionando

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['termo'])) {
    $termo = $_POST['termo'];

    try {
        // Consulta SQL: buscar apenas dados da tabela embarcacoes e informações relacionadas da marina
        $sql = "
            SELECT 
                e.nome AS nome_embarcacao,
                e.numero_serie AS numero_inscricao,
                m.nome AS nome_marina,
                m.cnpj AS cnpj_marina,
                m.endereco AS local_marina,
                m.contato AS contato
            FROM embarcacoes e
            INNER JOIN marinas m ON e.id_marina = m.id
            WHERE e.nome LIKE :termo
               OR e.numero_serie LIKE :termo
        ";

        // Preparar a consulta
        $stmt = $conexao->prepare($sql);

        // Adicionar o termo de busca, com % para busca parcial
        $stmt->bindValue(':termo', '%' . $termo . '%');
        $stmt->execute();

        // Exibir resultados
        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<thead>
                    <tr>
                        <th>Nome da Embarcação</th>
                        <th>Número de Inscrição</th>
                        <th>Nome da Marina</th>
                        <th>CNPJ da Marina</th>
                        <th>Local da Marina</th>
                        <th>Contato</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nome_embarcacao']) . "</td>";
                echo "<td>" . htmlspecialchars($row['numero_inscricao']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nome_marina']) . "</td>";
                echo "<td class='cnpj'>" . htmlspecialchars($row['cnpj_marina']) . "</td>";
                echo "<td>" . htmlspecialchars($row['local_marina']) . "</td>";
                echo "<td>" . htmlspecialchars($row['contato']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Nenhum resultado encontrado.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Erro ao realizar a busca: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Termo de busca não informado.</p>";
}