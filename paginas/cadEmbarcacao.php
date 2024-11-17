<?php
// Inclui o arquivo de conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $id_marina = $_POST['id_marina'];
    $nome = strtoupper($_POST['nome']);
    $numero_serie = $_POST['numero_serie'];
    $tipo = $_POST['tipo'];
    $observacao = $_POST['observacao'];
    $proporcao_motor = $_POST['proporcao_motor'];
    $status = strtoupper($_POST['status']);
    $data_validade = $_POST['data_validade']; // Captura a data de vencimento

    // Validações básicas
    if (strlen($numero_serie) < 1) {
        $erro = "O número de série é obrigatório.";
    } elseif (strlen($observacao) > 200) {
        $erro = "A observação não pode exceder 200 caracteres.";
    } elseif (!in_array($status, ['LEGAL', 'ILEGAL'])) {
        $erro = "Status inválido.";
    } elseif (!$data_validade || !strtotime($data_validade)) {
        $erro = "Data de validade inválida ou não preenchida.";
    } else {
        try {
            // Insere os dados no banco de dados
            $query = "INSERT INTO embarcacoes (id_marina, nome, numero_serie, tipo, observacao, proporcao_motor, status, dt_validade) 
                      VALUES (:id_marina, :nome, :numero_serie, :tipo, :observacao, :proporcao_motor, :status, :data_validade)";
            $stmt = $conexao->prepare($query);

            $stmt->bindParam(':id_marina', $id_marina);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':numero_serie', $numero_serie);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':observacao', $observacao);
            $stmt->bindParam(':proporcao_motor', $proporcao_motor);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':data_validade', $data_validade);

            if ($stmt->execute()) {
                $successMessage = "EMB / MTA cadastrada com sucesso!";
            } else {
                $erro = "Erro ao cadastrar EMB / MTA.";
            }
        } catch (PDOException $e) {
            $erro = "Erro no sistema: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar EMB / MTA</title>
    <link rel="stylesheet" href="../cssPaginas/cadEmbarcacao.css">
    <style>
        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            border: 2px solid #28a745;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            font-size: 18px;
            color: #28a745;
            font-weight: bold;
            text-align: center;
            z-index: 9999;
        }

        .error {
            color: red;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
    <script>
        // Redireciona após 2 segundos
        function redirectToListagem() {
            setTimeout(function() {
                window.location.href = 'listagem_marinas.php'; // Alterar para a página desejada
            }, 2000); // 2000ms = 2 segundos
        }
    </script>
</head>
<body>
    <div class="login-container">
        <img src="../imagens/logoMarinha.png" style="width: 10%; margin: 20px auto;">
        <h2>Cadastro de Embarcação / MTA</h2>
        <form method="POST">
            <label for="id_marina">Selecione a Marina:</label>
            <select name="id_marina" id="id_marina" required>
                <option value="">Selecione uma marina</option>
                <?php
                $query = "SELECT id, nome FROM marinas";
                $stmt = $conexao->prepare($query);
                $stmt->execute();
                $marinas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($marinas as $marina) {
                    echo "<option value='" . $marina['id'] . "'>" . $marina['nome'] . "</option>";
                }
                ?>
            </select>

            <label for="nome">Nome da Embarcação:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="numero_serie">Número de Inscrição:</label>
            <input type="text" id="numero_serie" name="numero_serie" maxlength="12" required>

            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" required>
                <option value="Embarcação">Embarcação</option>
                <option value="Motoaquática">Motoaquática</option>
            </select>

            <label for="observacao">Observação:</label>
            <textarea id="observacao" name="observacao" maxlength="200"></textarea>

            <label for="proporcao_motor">Proporção do Motor:</label>
            <input type="text" id="proporcao_motor" name="proporcao_motor">

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="LEGAL">LEGAL</option>
                <option value="ILEGAL">ILEGAL</option>
            </select>

            <label for="data_validade">Data de Vencimento:</label>
            <input type="date" id="data_validade" name="data_validade" required>

            <button type="submit">Cadastrar</button>
            <button onclick="window.location.href='op.php';">Voltar</button>
            <h5 style="text-align:center;">Desenvolvido por MN-RC DIAS 24.0729.23</h5>

        </form>
        <?php if (isset($erro)): ?>
            <p class="error"><?php echo $erro; ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Exibe a mensagem de sucesso, se houver -->
        <?php if (isset($successMessage)): ?>
            <div class="success-message">
                <?php echo $successMessage; ?>
            </div>
            <script>
                redirectToListagem();
                </script>
    <?php endif; ?>

</body>
</html>
