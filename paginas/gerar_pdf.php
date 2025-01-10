<?php
require_once('../tcpdf/tcpdf.php'); // Caminho para o arquivo TCPDF

// Inclui a conexão com o banco de dados
include '../ConexaoBanco/conexao.php';

// Cria uma instância do objeto TCPDF
class MYPDF extends TCPDF {
    public function Header() {
        // Adiciona logos nas laterais
        $this->Image('../imagens/cpsp.jpg', 15, 8, 20, 0, 'JPG'); // Imagem à esquerda
        $this->Image('../imagens/newMarinha.jpg', 175, 11, 20, 0, 'JPG'); // Imagem à direita

        // Título do documento
        $this->SetFont('Helvetica', 'B', 18);
        $this->Ln(11);
        $this->Cell(0, 10, 'RELAÇÃO DE MARINAS CADASTRADAS', 0, 1, 'C');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

$pdf = new MYPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MN-RC DIAS');
$pdf->SetTitle('Listagem de Marinas');
$pdf->SetMargins(5, 15, 5); // Margens laterais reduzidas
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();
$pdf->Ln(30);

// Consulta para obter as marinas cadastradas, excluindo a marina "EMB / MTA NÃO GARAGIADAS"
$query = "SELECT nome, cnpj, endereco, contato FROM marinas WHERE nome != 'EMB / MTA NÃO GARAGIADAS'";
$stmt = $conexao->prepare($query);
$stmt->execute();
$marinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Configuração do estilo da tabela
$html = '
<style>
    table {
        font-size: 10px; /* Fonte do corpo */
        width: 100%; /* Certifica-se de que a tabela preencha toda a largura */
        border-collapse: collapse;
    }
    th {
        background-color: #0066cc; /* Cor azul no cabeçalho */
        color: #ffffff; /* Texto branco */
        text-align: center;
        padding: 5px;
    }
    td {
        text-align: center;
        padding: 5px;
    }
    th:nth-child(1) { width: 35%; } /* Nome da Marina */
    th:nth-child(2) { width: 30%; } /* Endereço */
    th:nth-child(3) { width: 20%; } /* CNPJ */
    th:nth-child(4) { width: 15%; } /* Contato */
</style>

<table border="1">
    <thead>
        <tr>
            <th>Nome da Marina</th>
            <th>Endereço</th>
            <th>CNPJ</th>
            <th>Contato</th>
        </tr>
    </thead>
    <tbody>';

// Preenchendo a tabela com dados
if ($marinas) {
    foreach ($marinas as $marina) {
        $html .= '
        <tr>
            <td>' . htmlspecialchars($marina['nome']) . '</td>
            <td>' . htmlspecialchars($marina['endereco']) . '</td>
            <td>' . htmlspecialchars($marina['cnpj']) . '</td>
            <td>' . htmlspecialchars($marina['contato']) . '</td>
        </tr>';
    }
} else {
    $html .= '
    <tr>
        <td colspan="4" align="center">Não há marinas cadastradas.</td>
    </tr>';
}

$html .= '
    </tbody>
</table>';

// Escrevendo o conteúdo no PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Exibindo o PDF no navegador
$pdf->Output('listagem_marinas.pdf', 'I');
?>