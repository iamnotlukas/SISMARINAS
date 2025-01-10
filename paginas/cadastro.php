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

    // Verifica se o CNPJ já existe no banco de dados
    $checkQuery = "SELECT COUNT(*) FROM marinas WHERE cnpj = :cnpj";
    $stmt = $conexao->prepare($checkQuery);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // Caso o CNPJ já exista, exibe a mensagem de erro
        $erro = "CNPJ já usado.";
    } else {
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
            // Configura a mensagem de sucesso para ser exibida no HTML
            $successMessage = "Marina Cadastrada com Sucesso!";
        } else {
            // Caso não seja bem-sucedido, atribui uma mensagem de erro genérico
            $erro = "Erro ao cadastrar a marina.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastramento de Marina</title>
  <link rel="stylesheet" href="../cssPaginas/cadNew.css">
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

  function mascaraTelefone(input) {
    let valor = input.value.replace(/\D/g, ''); // Remove tudo que não é número
    valor = valor.replace(/^(\d{2})(\d)/, "($1) $2");
    valor = valor.replace(/(\d{4})(\d)/, "$1-$2");
    input.value = valor.substring(0, 15); // Limita ao comprimento do telefone com máscara
  }

  // Redireciona após a exibição da mensagem
  function redirectToOp() {
    setTimeout(function() {
      window.location.href = 'op.php';
    }, 2000); // Ajuste o tempo de redirecionamento (2 segundos)
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
      2px 2px 0px #ffffff,
      /* Borda superior e à direita */
      -2px -2px 0px #ffffff,
      /* Borda inferior e à esquerda */
      2px -2px 0px #ffffff,
      /* Borda inferior e à direita */
      -2px 2px 0px #ffffff;
    /* Borda superior e à esquerda */
  }

  /* Estilo para a mensagem de erro */
  .error {
    color: red;
    font-size: 16px;
    margin-top: 10px;
  }

  body,
  html {

    margin: 0;
    padding: 0;
    height: 100%;
    background: rgb(80, 81, 254);
    background: linear-gradient(0deg, rgba(80, 81, 254, 1) 0%, rgba(255, 255, 255, 1) 100%);
  }
  </style>
</head>

<body>
  <div class="login-container">
    <img src="../imagens/SISMARINAS.png" alt="Logo da Marinha" style="width: 50%;">
    <h2>Cadastramento de Marina</h2>
    <form method="POST">
      <label for="nome">Nome da Marina:</label>
      <input type="text" id="nome" name="nome" required>

      <label for="cnpj">CNPJ:</label>
      <input type="text" id="cnpj" name="cnpj" maxlength="18" oninput="mascaraCNPJ(this)" required>

      <label for="endereco">Endereço:</label>
      <input type="text" id="endereco" name="endereco">

      <label for="contato">Contato:</label>
      <input type="text" id="contato" name="contato" maxlength="50" oninput="mascaraTelefone(this)">

      <label for="dt_validade">Data de Validade do Certificado:</label>
      <input type="date" id="dt_validade" name="dt_validade" required>

      <button type="submit">Cadastrar</button>
      <button onclick="window.location.href='op.php';">Voltar</button>
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
    redirectToOp(); // Chama a função para redirecionar após 2 segundos
    </script>
  </div>
  <?php endif; ?>
</body>

</html>