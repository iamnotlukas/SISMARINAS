<?php
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php');
    exit();
}

require_once('../tcpdf/tcpdf.php');
include '../ConexaoBanco/conexao.php';

// Cria uma instância do objeto TCPDF
$pdf = new TCPDF();

// Definições do PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MN-RC DIAS');
$pdf->SetTitle('SISMARINAS');
$pdf->SetSubject('Relatório de Embarcações Irregulares');
$pdf->SetMargins(10, 15, 10);
$pdf->setPrintHeader(false);
$pdf->AddPage();

// Adicionando as imagens no topo
$pdf->Image('../imagens/cpsp.jpg', 15, 8, 20, 0, 'JPG');
$pdf->Image('../imagens/newMarinha.jpg', 175, 11, 20, 0, 'JPG');

// Título do documento
$pdf->SetFont('Helvetica', 'B', 18);
$pdf->Cell(0, 10, 'RELAÇÃO DE EMB E/OU MTA IRREGULARES', 0, 1, 'C');

// Adiciona uma linha de separação
$pdf->Ln(25);

// Consulta para obter as embarcações ilegais
$query = "
    SELECT e.nome AS embarcacao_nome, e.numero_serie, m.nome AS marina_nome, m.contato AS contato
    FROM embarcacoes e
    INNER JOIN marinas m ON e.id_marina = m.id
    WHERE e.status = 'IRREGULAR'
    ORDER BY e.nome ASC";
$stmt = $conexao->prepare($query);
$stmt->execute();
$embarcacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estilo para os cabeçalhos da tabela
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->SetFillColor(0, 102, 204);
$pdf->SetTextColor(255, 255, 255);

$widths = [60, 40, 65, 30];

// Cabeçalhos da tabela
foreach (['Nome da Embarcação', 'N° de Inscrição', 'Marina', 'Contato'] as $i => $header) {
    $pdf->Cell($widths[$i], 8, $header, 1, 0, 'C', 1);
}
$pdf->Ln(); // Fecha a linha

// Resetando a cor do texto para o corpo
$pdf->SetTextColor(0, 0, 0);

// Corpo da tabela com os dados
$pdf->SetFont('Helvetica', '', 10);
foreach ($embarcacoes as $embarcacao) {
    $pdf->Cell($widths[0], 6, $embarcacao['embarcacao_nome'], 1, 0, 'C');
    $pdf->Cell($widths[1], 6, $embarcacao['numero_serie'], 1, 0, 'C');
    $pdf->Cell($widths[2], 6, $embarcacao['marina_nome'], 1, 0, 'C');
    $pdf->Cell($widths[3], 6, $embarcacao['contato'], 1, 1, 'C'); // Fecha a linha
}

// Adiciona uma linha no final
$pdf->Ln(5);

// Exibe o PDF no navegador
$pdf->Output('embarcacoes_irregulares.pdf', 'I');
?>