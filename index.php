<?php
session_start(); // Inicia a sessão

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha = $_POST['senha'];

    // Verifica se a senha está correta
    if ($senha === 'cpspnaac') {
        $_SESSION['autenticado'] = true; // Define a sessão como autenticado
        header('Location: paginas/op.php'); // Redireciona para a página op.php
        exit();
    } else {
        $erro = 'Senha incorreta. Tente novamente.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="cssPaginas/index.css">
</head>
<body>
    <div class="login-container">
        <img src="imagens/logoMarinha.png" style="width: 20%; margin-bottom: 10px;">
        <h2>Acesso Restrito</h2>
        <form method="POST">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Entrar</button>
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
