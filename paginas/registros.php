<?php
session_start(); // Inicia a sessão
include '../ConexaoBanco/conexao.php'; // Inclua a conexão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../index.php'); // Redireciona para a página de login
    exit();
}

// Inicializa variáveis
$usuario = null;
$cpf = null;
$motivo = null;

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $motivo = $_POST['motivo'];

    try {
        // Verifica se o CPF existe na tabela usuarios
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE cpf = :cpf");
        $stmt->execute(['cpf' => $cpf]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            // Se o CPF existe, insira na tabela de registros
            $stmt = $conexao->prepare("INSERT INTO registros (cpf, nome_completo, data_nascimento, observacoes, motivo) VALUES (:cpf, :nome_completo, :data_nascimento, :observacoes, :motivo)");
            $stmt->execute([
                'cpf' => $usuario['cpf'],
                'nome_completo' => strtoupper($usuario['nome_completo']),
                'data_nascimento' => $usuario['data_nascimento'],
                'observacoes' => $usuario['observacoes'],
                'motivo' => $motivo
            ]);
            echo "<script>alert('Pessoa registrada com sucesso');</script>";
        } else {
            echo "<script>alert('Pessoa não cadastrada');</script>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso de Usuários</title>
    <link rel="stylesheet" href="../cssPaginas/registro.css">
    <style>
        .registro-container {
            text-align: center; /* Centraliza todo o conteúdo dentro da div */
        }

        button {
            background-color: #007bff; /* Exemplo de cor de fundo */
            color: white; /* Cor do texto */
            border: none; /* Remove bordas */
            padding: 10px 20px; /* Espaçamento interno */
            text-align: center; /* Centraliza o texto */
            text-decoration: none; /* Remove sublinhado */
            display: inline-block; /* Permite definir largura e altura */
            font-size: 16px; /* Tamanho da fonte */
            margin: 10px 2px; /* Margem */
            cursor: pointer; /* Cursor de ponteiro */
            border-radius: 4px; /* Bordas arredondadas */
        }

        button a {
            color: white; /* Mantém a cor do texto branco dentro do link */
            text-decoration: none; /* Remove sublinhado do link */
        }

        button:hover {
            background-color: #0056b3; /* Cor de fundo ao passar o mouse */
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <img src="../imagens/logoMarinha.png" style="width: 20%; margin-bottom: 10px; display: block; margin-left: auto; margin-right: auto;">
        <h2>Registro de Usuário</h2>
        <form method="POST">
            <label for="cpf">Digite seu CPF:</label>
            <input type="text" id="cpf" name="cpf" maxlength="11" required>
            
            <label for="motivo">Selecione o Motivo:</label>
            <select id="motivo" name="motivo" required>
                <option value="saude">Saúde</option>
                <option value="veteranos">Veteranos</option>
                <option value="reuniao">Reunião</option>
                <option value="acompanhar">Acompanhar</option>
                <option value="empresa">Empresa</option>
                <option value="fisioterapia">Fisioterapia</option>
                <option value="outros">Outros</option>
            </select>

            <button type="submit">Registrar</button>
            <button type="button" onclick="window.location.href='op.php'" style="margin: 10px 0; color: white; text-decoration: none;">Voltar</button>
        </form>
        <h5 style="text-align: center;">Desenvolvido por MN-RC DIAS 24.0729.23</h5>
    </div>
</body>
</html>
