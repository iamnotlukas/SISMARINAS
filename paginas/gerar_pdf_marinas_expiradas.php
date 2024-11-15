<?php
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php');
    exit();
}

require_once('../tcpdf/tcpdf.php'); // Inclua o caminho correto para a biblioteca TCPDF
include '../ConexaoBanco/conexao.php';

// Extende a classe TCPDF para incluir cabeçalho e rodapé personalizados
class MYPDF extends TCPDF {
    public function Header() {
        // Logo nas laterais
        $this->Image('../imagens/cpsp.jpg', 15, 8, 20, 0, 'JPG'); // Imagem à esquerda
        $this->Image('../imagens/newMarinha.jpg', 175, 11, 20, 0, 'JPG'); // Imagem à direita
        
        // Título do documento
        $this->SetFont('Helvetica', 'B', 18);
        $this->Ln(11);
        $this->Cell(0, 10, 'MARINAS COM CERTIFICADOS VENCIDOS', 0, 1, 'C');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Configurações iniciais do PDF
$pdf = new MYPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MN-RC DIAS');
$pdf->SetTitle('Marinas com Prazo de Validade Expirado');
$pdf->SetMargins(10, 15, 10); // Margens laterais reduzidas
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();
$pdf->Ln(22);

// Consulta ao banco de dados
$query = "
    SELECT nome, cnpj, endereco, dt_validade
    FROM marinas
    WHERE dt_validade < CURDATE()
    ORDER BY nome ASC";
$stmt = $conexao->prepare($query);
$stmt->execute();
$marinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Configuração do estilo da tabela
$html = '
<style>
    table {
        font-size: 10px; /* Fonte do corpo da tabela */
    }
    th {
        background-color: #0066cc;
        color: #ffffff;
        font-size: 12px; /* Fonte do cabeçalho */
        text-align: center;
        padding: 4px;
    }
    td {
        text-align: center;
        padding: 3px; /* Reduz altura das células */
    }
</style>
<table border="1" cellpadding="3">
    <thead>
        <tr>
            <th>Nome</th>
            <th>CNPJ</th>
            <th>Endereço</th>
            <th>Validade</th>
        </tr>
    </thead>
    <tbody>';

// Preenchimento da tabela
if ($marinas) {
    foreach ($marinas as $marina) {
        $html .= '
        <tr>
            <td>' . htmlspecialchars($marina['nome']) . '</td>
            <td>' . htmlspecialchars($marina['cnpj']) . '</td>
            <td>' . htmlspecialchars($marina['endereco']) . '</td>
            <td>' . date('d/m/Y', strtotime($marina['dt_validade'])) . '</td>
        </tr>';
    }
} else {
    $html .= '
    <tr>
        <td colspan="4" align="center">Não há marinas com validade expirada.</td>
    </tr>';
}

$html .= '
    </tbody>
</table>';

// Escreve o conteúdo no PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Exibe o PDF
$pdf->Output('marinas_expiradas.pdf', 'I');
?>
