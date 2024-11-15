<?php
require_once('../tcpdf/tcpdf.php'); // Caminho para o arquivo TCPDF

// Inclui a conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

// Cria uma instância do objeto TCPDF
$pdf = new TCPDF();

// Definições do PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MN-RC DIAS');
$pdf->SetTitle('Listagem de Marinas');
$pdf->SetSubject('Relatório de Marinas');
$pdf->SetMargins(10, 15, 10); // Margens laterais reduzidas
$pdf->setPrintHeader(false);

$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

// Adicionando as imagens no topo com largura reduzida, mantendo proporção
$pdf->Image('../imagens/cpsp.jpg', 15, 8, 20, 0, 'JPG'); // Imagem à esquerda, largura ajustada para 40
$pdf->Image('../imagens/newMarinha.jpg', 175, 8, 20, 0, 'JPG'); // Imagem à direita, largura ajustada para 40

// Título do documento
$pdf->SetFont('Helvetica', 'B', 18);
$pdf->Cell(0, 10, 'Listagem de Marinas Cadastradas', 0, 1, 'C');

// Adiciona uma linha de separação
$pdf->Ln(10);

// Define a fonte para o corpo do documento
$pdf->SetFont('Helvetica', '', 12);

// Consulta para obter as marinas cadastradas, excluindo a marina 'EMB / MTA NÃO GARAGIADAS'
$query = "SELECT nome, cnpj, endereco FROM marinas WHERE nome != 'EMB / MTA NÃO GARAGIADAS'";
$stmt = $conexao->prepare($query);
$stmt->execute();
$marinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estilo para os cabeçalhos da tabela
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->SetFillColor(0, 102, 204); // Cor de fundo azul para os cabeçalhos
$pdf->SetTextColor(255, 255, 255); // Cor do texto branco

// Ajustando as larguras das células para que fiquem mais balanceadas
$largura_total = 180; // O total disponível para as três colunas é 180
$largura_nome = 100;  // A coluna "Nome da Marina" ocupará 100
$largura_endereco = 40; // A coluna "Endereço" ocupará 40
$largura_cnpj = 50; // A coluna "CNPJ" foi aumentada para 50

// Definindo as células com as larguras ajustadas
$pdf->Cell($largura_nome, 10, 'Nome da Marina', 1, 0, 'C', 1); // 1 para o preenchimento
$pdf->Cell($largura_endereco, 10, 'Cidade', 1, 0, 'C', 1); // 1 para o preenchimento
$pdf->Cell($largura_cnpj, 10, 'CNPJ', 1, 1, 'C', 1); // 1 para o preenchimento

// Resetando a cor do texto para o corpo
$pdf->SetTextColor(0, 0, 0); // Texto preto

// Corpo da tabela com os dados das marinas
$pdf->SetFont('Helvetica', '', 12);
foreach ($marinas as $marina) {
    $pdf->Cell($largura_nome, 10, $marina['nome'], 1, 0, 'C');
    $pdf->Cell($largura_endereco, 10, $marina['endereco'], 1, 0, 'C');
    $pdf->Cell($largura_cnpj, 10, $marina['cnpj'], 1, 1, 'C');
}

// Adiciona uma linha no final
$pdf->Ln(5);

// Exibe o PDF no navegador
$pdf->Output('listagem_marinas.pdf', 'I');
?>
