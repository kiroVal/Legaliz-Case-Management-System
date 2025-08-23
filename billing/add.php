<?php require_once __DIR__ . '/../partials/header.php'; $cases = $pdo->query('SELECT id,title FROM cases ORDER BY id DESC')->fetchAll(); if($_SERVER['REQUEST_METHOD']==='POST'){ csrf_verify(); $inv = 'INV-'.date('Y').'-'.str_pad(rand(1,9999),4,'0',STR_PAD_LEFT); $stmt=$pdo->prepare('INSERT INTO billing (case_id,amount,description,status,invoice_number,pdf_path) VALUES (?,?,?,?,?,?)'); $stmt->execute([$_POST['case_id'],$_POST['amount'],$_POST['description'],$_POST['status'],$inv,NULL]); header('Location: /billing/list.php'); exit; } ?>
<h3>Create Invoice</h3>
<form method='post'><?php csrf_field(); ?>
<select name='case_id' class='form-select mb-2' required><option value=''>Select case</option><?php foreach($cases as $c): ?><option value='<?=$c['id']?>'><?=htmlspecialchars($c['title'])?></option><?php endforeach; ?></select>
<input name='amount' class='form-control mb-2' placeholder='Amount' required>
<textarea name='description' class='form-control mb-2' placeholder='Description'></textarea>
<select name='status' class='form-select mb-2'><option value='unpaid'>unpaid</option><option value='paid'>paid</option></select>
<button class='btn btn-primary'>Create</button></form>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>