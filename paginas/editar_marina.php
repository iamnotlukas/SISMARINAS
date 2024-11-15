<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

// Verifica se o ID da marina foi passado
$id_marina = isset($_GET['id_marina']) ? $_GET['id_marina'] : null;

// Recupera os dados da marina para exibir no formulário
if ($id_marina) {
    $query_marina = "SELECT * FROM marinas WHERE id = :id_marina";
    $stmt = $conexao->prepare($query_marina);
    $stmt->bindParam(':id_marina', $id_marina);
    $stmt->execute();
    $marina = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $endereco = $_POST['endereco'];
    $dt_validade = $_POST['dt_validade'];

    $update_query = "UPDATE marinas SET nome = :nome, cnpj = :cnpj, endereco = :endereco, dt_validade = :dt_validade WHERE id = :id_marina";
    $stmt_update = $conexao->prepare($update_query);
    $stmt_update->bindParam(':nome', $nome);
    $stmt_update->bindParam(':cnpj', $cnpj);
    $stmt_update->bindParam(':endereco', $endereco);
    $stmt_update->bindParam(':dt_validade', $dt_validade);
    $stmt_update->bindParam(':id_marina', $id_marina);

    if ($stmt_update->execute()) {
        // Após a atualização, exibe a mensagem de sucesso
        $update_success = true;
        header('Refresh: 2; url=listagem_marinas.php'); // Redireciona após 2 segundos
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Marina</title>
    <link rel="stylesheet" href="../cssPaginas/editar.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding-top: 50px;
        }
        h2 {
            text-align: center;
        }
        form {
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }
        input[type="text"], input[type="date"], input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 24px;
            display: none;
        }
        .message.active {
            display: flex;
        }
        .message span {
            background-color: #4CAF50;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Marina</h2>
        
        <?php if (isset($update_success) && $update_success): ?>
            <div class="message active">
                <span>Dados atualizados com sucesso!</span>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label for="nome">Nome da Marina:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($marina['nome']); ?>" required>

            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($marina['cnpj']); ?>" maxlength="18" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($marina['endereco']); ?>" required>

            <label for="dt_validade">Data de Validade:</label>
            <input type="date" id="dt_validade" name="dt_validade" value="<?php echo htmlspecialchars($marina['dt_validade']); ?>" required>

            <input type="submit" value="Atualizar">
        </form>
    </div>

    <script>
        // Adiciona a classe 'active' à mensagem de sucesso se ela for exibida
        if (document.querySelector('.message.active')) {
            setTimeout(function() {
                document.querySelector('.message').classList.remove('active');
            }, 2000); // Esconde a mensagem após 2 segundos
        }
    </script>
</body>
</html>
