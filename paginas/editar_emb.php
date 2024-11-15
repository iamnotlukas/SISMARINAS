<?php
// Inclui o arquivo de conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

// Verifica se o ID da embarcação ou motoaquática foi fornecido via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara a consulta SQL para buscar os dados da embarcação ou motoaquática
    $query = "SELECT * FROM embarcacoes WHERE id = :id";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Verifica se encontrou os dados
    $embarcacao = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $numero_serie = $_POST['numero_serie'];
    $tipo = $_POST['tipo'];
    $observacao = $_POST['observacao'];
    $proporcao_motor = $_POST['proporcao_motor'];

    // Prepara a consulta SQL para atualizar os dados no banco de dados
    $query = "UPDATE embarcacoes SET nome = :nome, numero_serie = :numero_serie, tipo = :tipo, observacao = :observacao, proporcao_motor = :proporcao_motor WHERE id = :id";
    $stmt = $conexao->prepare($query);
    
    // Faz o bind dos parâmetros
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':numero_serie', $numero_serie);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':observacao', $observacao);
    $stmt->bindParam(':proporcao_motor', $proporcao_motor);
    $stmt->bindParam(':id', $id);

    // Executa a consulta e verifica se a atualização foi bem-sucedida
    if ($stmt->execute()) {
        $successMessage = "Embarcação atualizada com sucesso!";
    } else {
        $erro = "Erro ao atualizar a embarcação.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração de Embarcação</title>
    <link rel="stylesheet" href="../cssPaginas/cadNew.css">
    <script>
        // Redireciona após a exibição da mensagem
        function redirectToListagem() {
            setTimeout(function() {
                window.location.href = 'listagem_marinas.php'; // Redireciona para a listagem das embarcações
            }, 4000); // Ajuste o tempo de redirecionamento (2 segundos)
        }
    </script>
    <style>
        /* Estilo para a mensagem de sucesso */
        .success-message {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #007BFF;
            font-size: 30px;
            z-index: 9999;
            font-weight: bold;
            text-shadow: 
                2px 2px 0px #ffffff,   /* Borda superior e à direita */
                -2px -2px 0px #ffffff, /* Borda inferior e à esquerda */
                2px -2px 0px #ffffff,  /* Borda inferior e à direita */
                -2px 2px 0px #ffffff;  /* Borda superior e à esquerda */
        }

        /* Estilo para a mensagem de erro */
        .error {
            color: red;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="../imagens/logoMarinha.png" alt="Logo da Marinha" style="width: 60px;">
        <h2>Alteração de Embarcação</h2>

        <form method="POST">
            <label for="nome">Nome da Embarcação:</label>
            <input type="text" id="nome" name="nome" value="<?php echo isset($embarcacao['nome']) ? $embarcacao['nome'] : ''; ?>" required>

            <label for="numero_serie">Número de Série:</label>
            <input type="text" id="numero_serie" name="numero_serie" value="<?php echo isset($embarcacao['numero_serie']) ? $embarcacao['numero_serie'] : ''; ?>" required>

            <label for="tipo">Tipo:</label>
            <input type="text" id="tipo" name="tipo" value="<?php echo isset($embarcacao['tipo']) ? $embarcacao['tipo'] : ''; ?>" required>

            <label for="observacao">Observação:</label>
            <textarea id="observacao" name="observacao" required><?php echo isset($embarcacao['observacao']) ? $embarcacao['observacao'] : ''; ?></textarea>

            <label for="proporcao_motor">Proporção do Motor:</label>
            <input type="text" id="proporcao_motor" name="proporcao_motor" value="<?php echo isset($embarcacao['proporcao_motor']) ? $embarcacao['proporcao_motor'] : ''; ?>" required>

            <button type="submit">Atualizar</button>
            <h5>Desenvolvido por MN-RC DIAS 24.0729.23</h5>
        </form>

        <!-- Exibe a mensagem de erro, se houver -->
        <?php if (isset($erro)): ?>
            <p class="error"><?php echo $erro; ?></p>
        <?php endif; ?>
    </div>

    <!-- Exibe a mensagem de sucesso, se houver -->
    <?php if (isset($successMessage)): ?>
        <div class="success-message">
            <?php echo $successMessage; ?>
            <script>
                redirectToListagem(); // Chama a função para redirecionar após 2 segundos
            </script>
        </div>
    <?php endif; ?>
</body>
</html>
