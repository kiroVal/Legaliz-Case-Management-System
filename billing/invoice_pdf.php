<?php require_once __DIR__ . '/../config.php'; require_login();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT b.*, c.title AS case_title, cl.name AS client_name, cl.email AS client_email FROM billing b LEFT JOIN cases c ON c.id=b.case_id LEFT JOIN clients cl ON cl.id=c.client_id WHERE b.id=?');
$stmt->execute([$id]); $inv = $stmt->fetch();
if(!$inv){ http_response_code(404); die('Invoice not found'); }

// If dompdf is available via Composer, use it
if (file_exists(__DIR__ . '/../vendor/autoload.php')){
    require_once __DIR__ . '/../vendor/autoload.php';
    use Dompdf\Dompdf;
    $html = '<h2>Legal Case Management System</h2>';
    $html .= '<p>Invoice #: '.htmlspecialchars($inv['invoice_number'] ?? 'INV-').'<br>Date: '.htmlspecialchars($inv['issued_at']).'</p>';
    $html .= '<p>Client: '.htmlspecialchars($inv['client_name']).' ('.htmlspecialchars($inv['client_email']).')</p>';
    $html .= '<p>Case: '.htmlspecialchars($inv['case_title']).'</p>';
    $html .= '<table width="100%" style="border-collapse:collapse"><tr><th style="text-align:left">Description</th><th style="text-align:right">Amount</th></tr>';
    $html .= '<tr><td>'.htmlspecialchars($inv['description']).'</td><td style="text-align:right">₱'.number_format((float)$inv['amount'],2).'</td></tr>';
    $html .= '<tr><td style="text-align:right"><strong>Total</strong></td><td style="text-align:right"><strong>₱'.number_format((float)$inv['amount'],2).'</strong></td></tr></table>';
    $html .= '<p style="font-size:0.9em;color:#666">This is a system-generated invoice.</p>';
    $dompdf = new Dompdf(); $dompdf->loadHtml($html); $dompdf->setPaper('A4','portrait'); $dompdf->render();
    $dompdf->stream('invoice_'.$inv['id'].'.pdf', ['Attachment'=>0]);
    exit;
} else {
    // Serve sample PDF fallback
    $sample = __DIR__ . '/invoices/sample_invoice.pdf';
    if (file_exists($sample)){
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="invoice_'.$inv['id'].'.pdf"');
        readfile($sample);
        exit;
    } else { echo 'PDF generation requires dompdf or a sample PDF present.'; exit; }
}
