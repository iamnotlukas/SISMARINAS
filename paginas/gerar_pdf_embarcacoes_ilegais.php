<?php
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php');
    exit();
}

require_once('../tcpdf/tcpdf.php'); // Inclua o caminho correto para a biblioteca TCPDF
include '../ConexaoBanco/conexao.php';

// Cria uma instância do objeto TCPDF
$pdf = new TCPDF();

// Definições do PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MN-RC DIAS');
$pdf->SetTitle('SISMARINAS');
$pdf->SetSubject('Relatório de Embarcações Ilegais');
$pdf->SetMargins(10, 15, 10); // Margens laterais reduzidas
$pdf->setPrintHeader(false);
$pdf->AddPage();

// Adicionando as imagens no topo com largura reduzida, mantendo proporção
$pdf->Image('../imagens/cpsp.jpg', 15, 8, 20, 0, 'JPG'); // Imagem à esquerda
$pdf->Image('../imagens/newMarinha.jpg', 175, 11, 20, 0, 'JPG'); // Imagem à direita

// Título do documento
$pdf->SetFont('Helvetica', 'B', 18);
$pdf->Cell(0, 10, 'RELAÇÃO DE EMB E/OU MTA ILEGAIS', 0, 1, 'C');

// Adiciona uma linha de separação
$pdf->Ln(11);

// Consulta para obter as embarcações ilegais
$query = "
    SELECT e.nome AS embarcacao_nome, e.numero_serie, m.nome AS marina_nome
    FROM embarcacoes e
    INNER JOIN marinas m ON e.id_marina = m.id
    WHERE e.status = 'ILEGAL'
    ORDER BY e.nome ASC";
$stmt = $conexao->prepare($query);
$stmt->execute();
$embarcacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estilo para os cabeçalhos da tabela
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->SetFillColor(0, 102, 204); // Cor de fundo azul para os cabeçalhos
$pdf->SetTextColor(255, 255, 255); // Cor do texto branco

// Ajuste das larguras das colunas para preencher mais a página
$widths = [60, 50, 80]; // Largura das colunas

// Cabeçalhos da tabela
$pdf->Cell($widths[0], 8, 'Nome da Embarcação', 1, 0, 'C', 1); // Altura reduzida para 8
$pdf->Cell($widths[1], 8, 'N° de Inscrição', 1, 0, 'C', 1); // Altura reduzida para 8
$pdf->Cell($widths[2], 8, 'Marina', 1, 1, 'C', 1); // Altura reduzida para 8

// Resetando a cor do texto para o corpo
$pdf->SetTextColor(0, 0, 0); // Texto preto

// Corpo da tabela com os dados das embarcações ilegais
$pdf->SetFont('Helvetica', '', 10); // Fonte menor para o corpo da tabela
foreach ($embarcacoes as $embarcacao) {
    $pdf->Cell($widths[0], 6, $embarcacao['embarcacao_nome'], 1, 0, 'C'); // Altura reduzida para 6
    $pdf->Cell($widths[1], 6, $embarcacao['numero_serie'], 1, 0, 'C'); // Altura reduzida para 6
    $pdf->Cell($widths[2], 6, $embarcacao['marina_nome'], 1, 1, 'C'); // Altura reduzida para 6
}

// Adiciona uma linha no final
$pdf->Ln(5);

// Exibe o PDF no navegador
$pdf->Output('embarcacoes_ilegais.pdf', 'I');
?>
