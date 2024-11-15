<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Conexão ao banco de dados
include '../ConexaoBanco/conexao.php'; // Inclua a conexão

// Verifica se a solicitação de deleção foi feita
if (isset($_POST['delete'])) {
    // Confirma se a deleção foi solicitada
    try {
        // Executa o DELETE para remover todos os registros
        $stmt = $conexao->prepare("DELETE FROM registros");
        $stmt->execute();
        echo "<script>alert('Todos os registros foram deletados com sucesso.');</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao deletar registros: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiasSmartSys - Opções do Administrador</title>
    <style>
        li form button {
            display: block;
            text-decoration: none;
            color: #FF3D3F;
            padding: 10px;
            text-align: center;
            background-color: white;
            border: 1px solid #FF3D3F;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
            margin: 0 auto;
            width: -webkit-fill-available;
        }

        li form button:hover {
            background-color: #FF3D3F;
            color: white;
        }
    </style>
    <link rel="stylesheet" href="../cssPaginas/op.css">
    <script>
        function confirmDelete() {
            return confirm("Você tem certeza que deseja deletar todos os usuários?");
        }
    </script>
</head>
<body>
    <div class="op-container">
        <h2>Opções do Administrador</h2>
        <ul>
            <li><a href="listagem_marinas.php">Listar Marinas Cadastradas</a></li>
            <li><a href="cadastro.php">Cadastrar Marina</a></li>
            <li><a href="cadEmbarcacao.php">Cadastrar EMB e MTA</a></li>
            <a href="gerar_pdf.php">Gerar PDF das Marinas Cadastradas</a>
            <li><a href="gerar_pdf_marinas_expiradas.php">Gerar PDF de Marinas com certificado vencido</a></li>
            <li><a href="gerar_pdf_embarcacoes_ilegais.php">Gerar PDF EMB / MTA Ilegais</a></li>

        </ul>
    </div>
</body>
</html>