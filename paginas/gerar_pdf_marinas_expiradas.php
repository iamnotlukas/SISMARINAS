<?php
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php');
    exit();
}

require_once('../tcpdf/tcpdf.php'); // Inclua o caminho correto para a biblioteca TCPDF
include '../ConexaoBanco/conexao.php';

class MYPDF extends TCPDF {
    public function Header() {
        // Logo nas laterais
        $this->Image('../imagens/cpsp.jpg', 15, 8, 20, 0, 'JPG'); // Imagem à esquerda, largura ajustada
        $this->Image('../imagens/newMarinha.jpg', 175, 8, 20, 0, 'JPG'); // Imagem à direita, largura ajustada
        
        // Título do documento
        $this->SetFont('Helvetica', 'B', 18);
        $this->Ln(11);
        $this->Cell(0, 10, 'Marinas com Prazo de Validade Expirado', 0, 1, 'C');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

$pdf = new MYPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MN-RC DIAS');
$pdf->SetTitle('Marinas com Prazo de Validade Expirado');
$pdf->SetMargins(10, 15, 10); // Margens laterais reduzidas
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(21);

$query = "
    SELECT nome, cnpj, endereco, dt_validade
    FROM marinas
    WHERE dt_validade < CURDATE()
    ORDER BY nome ASC";
$stmt = $conexao->prepare($query);
$stmt->execute();
$marinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '<table border="1" cellpadding="5">
            <thead>
                <tr>';
$html .= '<th style="background-color: #0066cc; color: #ffffff;">Nome</th>';
$html .= '<th style="background-color: #0066cc; color: #ffffff;">CNPJ</th>';
$html .= '<th style="background-color: #0066cc; color: #ffffff;">Endereço</th>';
$html .= '<th style="background-color: #0066cc; color: #ffffff;">Validade</th>';
$html .= '</tr>
            </thead>
            <tbody>';
if ($marinas) {
    foreach ($marinas as $marina) {
        $html .= '<tr>
                    <td>' . $marina['nome'] . '</td>
                    <td>' . $marina['cnpj'] . '</td>
                    <td>' . $marina['endereco'] . '</td>
                    <td>' . date('d/m/Y', strtotime($marina['dt_validade'])) . '</td>
                  </tr>';
    }
} else {
    $html .= '<tr>
                <td colspan="4" align="center">Nao ha marinas com validade expirada.</td>
              </tr>';
}
$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('marinas_expiradas.pdf', 'I');
?>
