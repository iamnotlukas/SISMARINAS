<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Conexão ao banco de dados
include '../ConexaoBanco/conexao.php'; // Inclua a conexão

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiasSmartSys - Opções do Administrador</title>
    <link rel="stylesheet" href="../cssPaginas/op.css">
    <style>
        .images-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px;
        }

        .images-container img {
            height: auto;
        }

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

        body, html{
            
            margin: 0;
            padding: 0;
            height: 100%;
            background: rgb(29,0,255);
            background: linear-gradient(0deg, rgba(29,0,255,1) 0%, rgba(255,255,255,1) 100%);
            background-size: cover;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("Você tem certeza que deseja deletar todos os usuários?");
        }
    </script>
</head>
<body>
    <!-- Seção de imagens -->
    <div class="images-container">
        <img src="../imagens/newMarinha.png" style="width:90px;"  alt="Imagem à esquerda">
        <img src="../imagens/SISMARINAS.png" alt="Imagem ao centro">
        <img src="../imagens/cpsp.png" style="width:90px;" alt="Imagem à direita">
    </div>
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
        <h5 style="text-align:center;">Desenvolvido por MN-RC DIAS 24.0729.23</h5>
    </div>
</body>
</html>
