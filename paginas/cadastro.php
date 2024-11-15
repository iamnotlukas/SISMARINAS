<?php
// Inclui o arquivo de conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $endereco = $_POST['endereco'];
    $contato = $_POST['contato'];
    $dt_validade = $_POST['dt_validade'];

    // Prepara a consulta SQL para inserir os dados no banco de dados
    $query = "INSERT INTO marinas (nome, cnpj, endereco, contato, dt_validade) 
              VALUES (:nome, :cnpj, :endereco, :contato, :dt_validade)";
    $stmt = $conexao->prepare($query);
    
    // Faz o bind dos parâmetros
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':contato', $contato);
    $stmt->bindParam(':dt_validade', $dt_validade);

    // Executa a consulta e verifica se a inserção foi bem-sucedida
    if ($stmt->execute()) {
        echo "<script>alert('Marina cadastrada com sucesso!'); window.location.href = 'listagem_marinas.php';</script>";
    } else {
        $erro = "Erro ao cadastrar a marina.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastramento de Marina</title>
    <link rel="stylesheet" href="../cssPaginas/cadastro.css">
    <script>
        // Função para formatar o CNPJ enquanto o usuário digita
        function mascaraCNPJ(input) {
            let valor = input.value.replace(/\D/g, ''); // Remove tudo que não é número
            valor = valor.replace(/^(\d{2})(\d)/, "$1.$2");
            valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
            valor = valor.replace(/\.(\d{3})(\d)/, ".$1/$2");
            valor = valor.replace(/(\d{4})(\d)/, "$1-$2");
            input.value = valor.substring(0, 18); // Limita ao comprimento do CNPJ com máscara
        }
    </script>
</head>
<body>
    <div class="login-container">
        <img src="../imagens/logoMarinha.png" alt="Logo da Marinha" style="width: 60px;">
        <h2>Cadastramento de Marina</h2>
        <form method="POST">
            <label for="nome">Nome da Marina:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj" maxlength="18" oninput="mascaraCNPJ(this)" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco">

            <label for="contato">Contato (máx. 50 caracteres):</label>
            <input type="text" id="contato" name="contato" maxlength="50">

            <label for="dt_validade">Data de Validade do Certificado:</label>
            <input type="date" id="dt_validade" name="dt_validade" required>

            <button type="submit">Cadastrar</button>
            <h5>Desenvolvido por MN-RC DIAS 24.0729.23</h5>
        </form>
        <?php
        // Exibe a mensagem de erro, se houver
        if (isset($erro)) {
            echo '<p class="error">' . $erro . '</p>';
        }
        ?>
    </div>
</body>
</html>
