<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

// Verifica se foi passado um ID de marina na URL
$id_marina = isset($_GET['id_marina']) ? $_GET['id_marina'] : null;

if ($id_marina) {
    // Exibe as embarcações relacionadas à marina selecionada
    $query = "SELECT * FROM embarcacoes WHERE id_marina = :id_marina";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':id_marina', $id_marina);
    $stmt->execute();
    $embarcacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Conta o total de embarcações/motoaquáticas associadas à marina
    $query_count = "SELECT COUNT(*) AS total_embarcacoes FROM embarcacoes WHERE id_marina = :id_marina";
    $stmt_count = $conexao->prepare($query_count);
    $stmt_count->bindParam(':id_marina', $id_marina);
    $stmt_count->execute();
    $total_embarcacoes = $stmt_count->fetch(PDO::FETCH_ASSOC)['total_embarcacoes'];

    // Recupera o nome da marina, o CNPJ, a cidade e a data de validade para exibir no título
    $query_marina = "SELECT nome, cnpj, endereco, dt_validade FROM marinas WHERE id = :id_marina";
    $stmt_marina = $conexao->prepare($query_marina);
    $stmt_marina->bindParam(':id_marina', $id_marina);
    $stmt_marina->execute();
    $marina = $stmt_marina->fetch(PDO::FETCH_ASSOC);
}
else {
    // Exibe todas as marinas cadastradas e conta o total
    $query = "
        SELECT id, nome, cnpj, endereco, dt_validade 
        FROM marinas 
        ORDER BY 
            CASE 
                WHEN UPPER(nome) = 'EMB / MTA NÃO GARAGIADAS' THEN 0 
                ELSE 1 
            END, 
            nome";
    $stmt = $conexao->prepare($query);
    $stmt->execute();
    $marinas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_marinas = count($marinas); // Conta o total de marinas cadastradas
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Marinas</title>
    <link rel="stylesheet" href="../cssPaginas/lista.css">
    <style>
        /* Estilo para texto com data expirada */
        .expired {
            color: red; /* Cor do texto em vermelho */
        }
        /* Estilo para status ILEGAL */
        .ilegal {
            color: red; /* Cor do texto em vermelho para status ILEGAL */
            font-weight: bold;
        }
        /* Estilo para o formulário de edição */
        .edit-form {
            display: none; /* Inicialmente escondido */
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>LISTAGEM DE MARINAS</h2>

        <?php if ($id_marina): ?>
            <h3>Embarcações na Marina <?php echo $marina['nome']; ?></h3>
            <h4 style="text-align:center; margin-bottom:20px;">Total de Embarcações/Motoaquáticas: <?php echo $total_embarcacoes; ?></h4>
            <table>
                <tr>
                    <th>NOME</th>
                    <th>NÚMERO DE INSCRIÇÃO</th>
                    <th>TIPO</th>
                    <th>OBSERVAÇÃO</th>
                    <th>STATUS</th>
                </tr>
                <?php foreach ($embarcacoes as $embarcacao): ?>
                    <tr>
                        <td><?php echo $embarcacao['nome']; ?></td>
                        <td><?php echo $embarcacao['numero_serie']; ?></td>
                        <td><?php echo $embarcacao['tipo']; ?></td>
                        <td><?php echo $embarcacao['observacao']; ?></td>
                        <td class="<?php echo ($embarcacao['status'] === 'ILEGAL') ? 'ilegal' : ''; ?>">
                            <?php echo strtoupper($embarcacao['status']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <a href="listagem_marinas.php">Voltar para a listagem de marinas</a>
        <?php else: ?>
            <h3>Total de Marinas Cadastradas: <?php echo $total_marinas; ?></h3>
            <table>
                <tr>
                    <th>NOME DA MARINA</th>
                    <th>CNPJ</th>
                    <th>MUNICÍPIO</th>
                    <th>VAL. DO CERTIFICADO</th>
                    <th>AÇÃO</th>
                </tr>
                <?php
                $hoje = new DateTime(); // Data atual
                foreach ($marinas as $marina):
                    $data_validade = new DateTime($marina['dt_validade']);
                    $data_formatada = $data_validade->format('d') . strtoupper($data_validade->format('M')) . $data_validade->format('Y'); // Formata a data para o formato 13DEZ2025
                    $linha_expirada = ($data_validade < $hoje) ? 'expired' : ''; // Verifica se a data expirou
                ?>
                    <tr>
                        <td><?php echo $marina['nome']; ?></td>
                        <td><?php echo $marina['cnpj']; ?></td>
                        <td><?php echo $marina['endereco']; ?></td>
                        <td class="<?php echo $linha_expirada; ?>"><?php echo $data_formatada; ?></td>
                        <td>
                            <a href="listagem_marinas.php?id_marina=<?php echo $marina['id']; ?>">Ver Embarcações</a> |
                            <button class="edit-btn" onclick="showEditForm(<?php echo $marina['id']; ?>)">Alterar Dados</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <!-- Formulário de Edição -->
            <div class="edit-form" id="edit-form">
                <h3>Alterar Dados da Marina</h3>
                <form method="POST" action="upgrade.php">
                    <input type="hidden" name="id_marina" id="edit-id-marina">
                    <label for="edit-nome">Nome da Marina:</label>
                    <input type="text" id="edit-nome" name="nome" required>

                    <label for="edit-cnpj">CNPJ:</label>
                    <input type="text" id="edit-cnpj" name="cnpj" maxlength="18" required>

                    <label for="edit-endereco">Endereço:</label>
                    <input type="text" id="edit-endereco" name="endereco" required>

                    <label for="edit-dt_validade">Data de Validade:</label>
                    <input type="date" id="edit-dt_validade" name="dt_validade" required>

                    <button type="submit">Salvar</button>
                    <button type="button" onclick="hideEditForm()">Cancelar</button>
                </form>
            </div>
        <?php endif; ?>
        <div class="button" style="margin: 0 auto; display: grid;">
            <a href="op.php" id="voltar">Voltar</a>      
        </div>
        <h5>Desenvolvido por MN-RC DIAS 24.0729.23</h5>
    </div>

    <script>
        function showEditForm(id) {
            // Preenche os campos do formulário com os dados da marina
            const marinaData = <?php echo json_encode($marinas); ?>;
            const marina = marinaData.find(item => item.id === id);

            document.getElementById('edit-id-marina').value = marina.id;
            document.getElementById('edit-nome').value = marina.nome;
            document.getElementById('edit-cnpj').value = marina.cnpj;
            document.getElementById('edit-endereco').value = marina.endereco;
            document.getElementById('edit-dt_validade').value = marina.dt_validade;

            document.getElementById('edit-form').style.display = 'block';
        }

        function hideEditForm() {
            document.getElementById('edit-form').style.display = 'none';
        }
    </script>
</body>
</html>
