<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

// Função para atualizar o status para "ILEGAL" se a data de vencimento tiver expirado
function atualizarStatusIlegal($conexao) {
    $query = "UPDATE embarcacoes SET status = 'IRREGULAR  ' WHERE dt_validade < CURDATE() AND status = 'REGULAR'";
    $stmt = $conexao->prepare($query);
    $stmt->execute();
}

// Atualiza os status antes de exibir as informações
atualizarStatusIlegal($conexao);

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

    // Conta o total de embarcações/motoaquáticas associadas à marina
    $qtd_marinas = "SELECT COUNT(*) AS total_marinas FROM marinas";
    $stmt_marinas = $conexao->prepare($qtd_marinas);
    $stmt_marinas->execute();
    $total_marinas = $stmt_marinas->fetch(PDO::FETCH_ASSOC)['total_marinas'];

    // Recupera o nome da marina para exibir no título
    $query_marina = "SELECT nome FROM marinas WHERE id = :id_marina";
    $stmt_marina = $conexao->prepare($query_marina);
    $stmt_marina->bindParam(':id_marina', $id_marina);
    $stmt_marina->execute();
    $marina = $stmt_marina->fetch(PDO::FETCH_ASSOC);
} else {
    // Exibe todas as marinas cadastradas
    $query = "
        SELECT id, nome, cnpj, endereco, contato, dt_validade 
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
    $total_marinas = count($marinas);
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
  tr:nth-child(even) {
    background-color: #e1e1e1;
  }

  tr:nth-child(odd) {
    background-color: white;
  }


  td {
    padding: 12px;
    text-align: center;
    border: 1px solid #bcb7b7;
  }

  .expired {
    color: red;
    font-weight: bold;
    /* Cor para itens expirados */
  }

  .ilegal {
    color: red;
    /* Cor para status ilegal */
    font-weight: bold;
    /* Deixar em negrito para destaque */
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
    <?php if ($id_marina): ?>
    <h2>Embarcações na Marina "<?php echo $marina['nome']; ?>"</h2>
    <h4 style="text-align:center; margin-bottom:20px;">Total de Embarcações/Motoaquáticas:
      <?php echo $total_embarcacoes; ?></h4>
    <table>
      <tr>
        <th>NOME</th>
        <th>NÚMERO DE INSCRIÇÃO</th>
        <th>TIPO</th>
        <th>OBSERVAÇÃO</th>
        <th>STATUS</th>
        <th>DATA DE VENCIMENTO</th>
        <th>AÇÕES</th>
      </tr>
      <?php foreach ($embarcacoes as $embarcacao): 
                    $data_vencimento = $embarcacao['dt_validade'] ? new DateTime($embarcacao['dt_validade']) : null;
                    $data_venc_formatada = $data_vencimento ? $data_vencimento->format('d/m/Y') : '---';
                    $classe_expirada = ($data_vencimento && $data_vencimento < new DateTime()) ? 'expired' : '';
                    $classe_status = (strtoupper($embarcacao['status']) === 'IRREGULAR') ? 'ilegal' : '';
                ?>
      <tr>
        <td><?php echo htmlspecialchars($embarcacao['nome']); ?></td>
        <td><?php echo htmlspecialchars($embarcacao['numero_serie']); ?></td>
        <td><?php echo htmlspecialchars($embarcacao['tipo']); ?></td>
        <td><?php echo htmlspecialchars($embarcacao['observacao']); ?></td>
        <td class="<?php echo $classe_status; ?>"><?php echo strtoupper(htmlspecialchars($embarcacao['status'])); ?>
        </td>
        <td class="<?php echo $classe_expirada; ?>"><?php echo $data_venc_formatada; ?></td>
        <td>
          <a href="editar_emb.php?id=<?php echo $embarcacao['id']; ?>">Alterar Dados</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <a href="listagem_marinas.php">Voltar para a listagem de marinas</a>
    <?php else: ?>
    <h2>Lista de Marinas</h2>
    <h4 style="text-align:center; margin-bottom:20px;">Total de Marinas Cadastradas: <?php echo $total_marinas; ?></h4>

    <table>
      <tr>
        <th>NOME DA MARINA</th>
        <th>CNPJ</th>
        <th>MUNICÍPIO</th>
        <th>CONTATO</th>
        <th>VAL. DO CERTIFICADO</th>
        <th>AÇÃO</th>
      </tr>
      <?php foreach ($marinas as $marina): 
                    $data_validade = new DateTime($marina['dt_validade']);
                    $hoje = new DateTime();
                    $linha_expirada = ($data_validade < $hoje) ? 'expired' : '';
                ?>
      <tr>
        <td><?php echo $marina['nome']; ?></td>
        <td><?php echo $marina['cnpj']; ?></td>
        <td><?php echo $marina['endereco']; ?></td>
        <td><?php echo $marina['contato']; ?></td>
        <td class="<?php echo $linha_expirada; ?>">
          <?php echo $data_validade->format('d/m/Y'); ?>
        </td>
        <td>
          <a href="listagem_marinas.php?id_marina=<?php echo $marina['id']; ?>">Ver Embarcações</a> |
          <a href="editar_marina.php?id_marina=<?php echo $marina['id']; ?>">Alterar Dados</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php endif; ?>
    <div class="button" style="margin: 0 auto; display: grid;">
      <a href="op.php" id="voltar">Voltar</a>
    </div>
    <h5>Desenvolvido por MN-RC DIAS 24.0729.23</h5>
  </div>
</body>

</html>