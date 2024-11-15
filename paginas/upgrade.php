<?php
// Conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

// Verifica se o formulário foi enviado via POST para atualizar os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $id_marina = $_POST['id_marina'];
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $endereco = $_POST['endereco'];
    $dt_validade = $_POST['dt_validade'];

    // Atualiza os dados da marina no banco de dados
    $query = "UPDATE marinas SET nome = :nome, cnpj = :cnpj, endereco = :endereco, dt_validade = :dt_validade WHERE id = :id_marina";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':id_marina', $id_marina);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':dt_validade', $dt_validade);

    // Verifica se a atualização foi bem-sucedida
    if ($stmt->execute()) {
        echo '<script>alert("Dados atualizados com sucesso!"); window.location.href = "listagem_marinas.php";</script>';
    } else {
        echo '<script>alert("Erro ao atualizar dados. Tente novamente."); window.location.href = "listagem_marinas.php";</script>';
    }
}
?>
