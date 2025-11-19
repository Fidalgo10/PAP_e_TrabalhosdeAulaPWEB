<?php
session_start();
if (!isset($_SESSION['iduser'])) die('Acesso negado');

require 'vendor/autoload.php';
use Dompdf\Dompdf;

if (isset($_POST['html'])) {
    $htmlRecebido = $_POST['html'];
    $filename = $_POST['filename'] ?? 'exportacao.pdf';
    $title = $_POST['title'] ?? 'Exportação de Dados';
    $perfil = $_SESSION['perfil'] ?? 'utilizador';
    $titleColor = ($perfil === 'administrador') ? '#b91010' : '#009CFF';

    $dompdf = new Dompdf();
    $dompdf->set_option('isRemoteEnabled', true); // permite carregar imagens externas

    // Caminho do logo
    $logoPath = 'img/Instituto_Superior_de_Engenharia_de_Coimbra_logo.png';
    if (file_exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $logoHTML = '<div style="text-align:center; margin-bottom:15px;">
                        <img src="'.$logoBase64.'" style="width:150px;">
                     </div>';
    } else {
        $logoHTML = '';
    }

    // Estilos do PDF
    $style = '
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
            h2 { text-align: center; color: ' . $titleColor . '; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; }
            th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
            th { background-color: ' . $titleColor . '; color: #fff; }
            tr:nth-child(even) { background-color: #f9f9f9; }
        </style>
    ';

    // Conteúdo final do PDF
    $pdfContent = $style . $logoHTML . '<h2>'.$title.'</h2>' . $htmlRecebido;

    $dompdf->loadHtml($pdfContent);
    $dompdf->setPaper('A4', 'portrait'); // ou 'landscape' se tabela for larga
    $dompdf->render();

    $dompdf->stream($filename, ["Attachment" => true]);
} else {
    echo "Nenhum conteúdo recebido para exportar.";
}
