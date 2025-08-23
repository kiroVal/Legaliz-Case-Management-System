<?php 
session_start();
require_once __DIR__ . '/../partials/header.php'; 
?>
<h3>Billing</h3>

<p>
<?php if ($_SESSION['role'] === 'client'): ?>
    <button class="btn btn-sm btn-secondary" disabled>Create Invoice (Disabled)</button>
<?php else: ?>
    <a class='btn btn-sm btn-primary' href='/billing/add.php'>Create Invoice</a>
<?php endif; ?>
</p>

<table class='table'>
    <thead>
        <tr>
            <th>ID</th><th>Case</th><th>Amount</th><th>Status</th><th>PDF</th>
        </tr>
    </thead>
    <tbody>
<?php foreach($pdo->query('SELECT b.*, c.title AS case_title 
        FROM billing b 
        LEFT JOIN cases c ON c.id=b.case_id 
        ORDER BY b.id DESC') as $i): ?>
    <tr>
        <td><?=$i['id']?></td>
        <td><?=htmlspecialchars($i['case_title'])?></td>
        <td>₱<?=number_format((float)$i['amount'],2)?></td>
        <td><?=htmlspecialchars($i['status'])?></td>
        <td>
            <a class='btn btn-sm btn-outline-primary' href='/billing/invoice_pdf.php?id=<?=$i['id']?>' target='_blank'>
                Download PDF
            </a>
        </td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
