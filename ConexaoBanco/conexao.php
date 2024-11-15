<?php
// Definindo as variáveis de conexão
$host = 'localhost';        // Endereço do servidor MySQL
$banco = 'sismarinas';    // Nome do banco de dados
$usuario = 'root';          // Usuário do banco de dados MySQL
$senha = '';                // Senha do banco de dados (preencha conforme necessário)

try {
    // Criando a conexão com PDO
    $conexao = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);

    // Configurando o PDO para lançar exceções em caso de erro
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Conexão bem-sucedida
    // echo "Conexão realizada com sucesso!";
} catch (PDOException $e) {
    // Caso ocorra um erro, exibe a mensagem de erro
    die("Erro na conexão: " . $e->getMessage());
}
?>
