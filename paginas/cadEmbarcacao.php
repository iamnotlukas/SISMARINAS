<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_marina = $_POST['id_marina'];
    $nome = strtoupper($_POST['nome']); // Converte o nome da embarcação para maiúsculas
    $numero_serie = $_POST['numero_serie'];
    $tipo = $_POST['tipo'];
    $observacao = $_POST['observacao'];
    $proporcao_motor = $_POST['proporcao_motor'];
    $status = strtoupper($_POST['status']); // Captura o status e converte para caixa alta

    // Validações básicas
    if (strlen($numero_serie) < 1) {
        $erro = 'O número de série é obrigatório.';
    } elseif (strlen($observacao) > 200) {
        $erro = 'A observação não pode exceder 200 caracteres.';
    } elseif (!in_array($status, ['LEGAL', 'ILEGAL'])) { // Validação do status
        $erro = 'Status inválido.';
    } else {
        try {
            // Prepara a query para inserir os dados no banco de dados
            $query = "INSERT INTO embarcacoes (id_marina, nome, numero_serie, tipo, observacao, proporcao_motor, status)
                      VALUES (:id_marina, :nome, :numero_serie, :tipo, :observacao, :proporcao_motor, :status)";
            
            $stmt = $conexao->prepare($query);
            
            // Vincula os valores aos parâmetros
            $stmt->bindParam(':id_marina', $id_marina);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':numero_serie', $numero_serie);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':observacao', $observacao);
            $stmt->bindParam(':proporcao_motor', $proporcao_motor);
            $stmt->bindParam(':status', $status);
            
            // Executa a query
            $stmt->execute();
            
            // Exibe mensagem de sucesso com JavaScript
            echo "<script>alert('Embarcação cadastrada com sucesso!');</script>";
        } catch (PDOException $e) {
            // Em caso de erro, exibe mensagem de erro
            $erro = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar EMB ou MTA</title>
    <link rel="stylesheet" href="../cssPaginas/cadEmbarcacao.css">

    <script>
function mascaraNumeroInscricao(input) {
    let valor = input.value.toUpperCase().replace(/[^A-Za-z0-9]/g, ''); // Remove tudo que não é número ou letra

    // Limita o número de inscrição a 8 caracteres: 6 números + 2 letras
    if (valor.length > 8) {
        valor = valor.substring(0, 8);
    }

    // Adiciona o hífen após os 6 primeiros caracteres
    let numeroFormatado = valor;
    if (valor.length > 6) {
        numeroFormatado = valor.substring(0, 6) + '-' + valor.substring(6, 8);
    }

    // Atualiza o valor do campo
    input.value = numeroFormatado;
}
</script>


</head>
<body>
    <div class="login-container">
    <img src="../imagens/logoMarinha.png" style="width: 10%;/* margin-bottom: 10px; */display: grid;align-items: center;margin: 20px auto;"> 
      <h2>Cadastro de Embarcação / MTA</h2>
        
      <form method="POST">
    <!-- Campo de seleção para escolher a marina -->
    <label for="id_marina">Selecione a Marina:</label>
    <select name="id_marina" id="id_marina" required>
        <option value="">Selecione uma marina</option>
        <?php
        // Lista as marinas cadastradas no banco de dados
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
    <input type="text" id="numero_serie" name="numero_serie" maxlength="12" oninput="mascaraNumeroInscricao(this)" required>

    <label for="tipo">Tipo:</label>
    <select name="tipo" id="tipo" required>
        <option value="Embarcação">Embarcação</option>
        <option value="Motoaquática">Motoaquática</option>
    </select>

    <label for="observacao">Observação (máx. 200 caracteres):</label>
    <textarea id="observacao" name="observacao" maxlength="200"></textarea>

    <label for="proporcao_motor">Proporção do Motor:</label>
    <input type="text" id="proporcao_motor" name="proporcao_motor">

    <!-- Seletor para status -->
    <label for="status">Status:</label>
    <select name="status" id="status" required>
        <option value="LEGAL">LEGAL</option>
        <option value="ILEGAL">ILEGAL</option>
    </select>

    <button type="submit">Cadastrar Embarcação</button>
    <h5>Desenvolvido por MN-RC DIAS 24.0729.23</h5>
</form>
        <?php
        // Exibe a mensagem de erro ou sucesso, se houver
        if (isset($erro)) {
            echo '<p class="error">' . $erro . '</p>';
        }
        ?>
    </div>
</body>
</html>
