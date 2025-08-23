<?php 
session_start();
require_once __DIR__ . '/../partials/header.php'; 

$role = $_SESSION['role']; 
$uid  = $_SESSION['user_id'];

// Handle lawyer assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_case_id'])) {
    csrf_verify();
    $case_id   = $_POST['assign_case_id'];
    $lawyer_id = $_POST['lawyer_id'] !== '' ? $_POST['lawyer_id'] : null; 

    $stmt = $pdo->prepare("UPDATE cases SET lawyer_id = ? WHERE id = ?");
    $stmt->execute([$lawyer_id, $case_id]);
    header("Location: /cases/list.php");
    exit;
}

// Handle case delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_case_id'])) {
    csrf_verify();
    $case_id = $_POST['delete_case_id'];

    $stmt = $pdo->prepare("DELETE FROM cases WHERE id = ?");
    $stmt->execute([$case_id]);
    header("Location: /cases/list.php");
    exit;
}

// Partner view toggle (default = all)
$partner_view = ($role === 'partner' && isset($_GET['view']) && $_GET['view'] === 'my') ? 'my' : 'all';

?>
<h3>Cases</h3>

<p>
<?php if ($role === 'client'): ?>
    <button class="btn btn-sm btn-secondary" disabled>Add Case (Disabled)</button>
<?php else: ?>
    <a class='btn btn-sm btn-primary' href='/cases/add.php'>Add Case</a>
<?php endif; ?>
</p>

<?php if ($role === 'partner'): ?>
    <div class="mb-3">
        <a href="?view=all" class="btn btn-sm <?= $partner_view==='all' ? 'btn-success' : 'btn-outline-success' ?>">All Cases</a>
        <a href="?view=my" class="btn btn-sm <?= $partner_view==='my' ? 'btn-primary' : 'btn-outline-primary' ?>">My Cases</a>
    </div>
<?php endif; ?>

<table class='table'>
    <thead>
        <tr>
            <th>ID</th><th>Title</th><th>Client</th><th>Lawyer</th><th>Status</th>
            <?php if ($role === 'admin' || $role === 'partner'): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
<?php
// ==========================
// Case visibility rules
// ==========================
if ($role === 'admin') { 
    // Admins see ALL cases
    $rows = $pdo->query('SELECT cases.*, clients.name AS client_name, u.username AS lawyer_name 
        FROM cases 
        LEFT JOIN clients ON clients.id=cases.client_id 
        LEFT JOIN users u ON u.id=cases.lawyer_id 
        ORDER BY cases.id DESC')->fetchAll(); 
}
elseif ($role === 'partner') { 
    if ($partner_view === 'my') {
        // Partner only sees their assigned cases
        $stmt=$pdo->prepare('SELECT cases.*, clients.name AS client_name, u.username AS lawyer_name 
            FROM cases 
            LEFT JOIN clients ON clients.id=cases.client_id 
            LEFT JOIN users u ON u.id=cases.lawyer_id 
            WHERE cases.lawyer_id=? 
            ORDER BY cases.id DESC'); 
        $stmt->execute([$uid]); 
        $rows=$stmt->fetchAll();
    } else {
        // Partner sees ALL cases
        $rows = $pdo->query('SELECT cases.*, clients.name AS client_name, u.username AS lawyer_name 
            FROM cases 
            LEFT JOIN clients ON clients.id=cases.client_id 
            LEFT JOIN users u ON u.id=cases.lawyer_id 
            ORDER BY cases.id DESC')->fetchAll(); 
    }
}
elseif ($role === 'client') { 
    // Clients see only their own cases
    $cid = current_client_id($pdo); 
    $stmt=$pdo->prepare('SELECT cases.*, clients.name AS client_name, u.username AS lawyer_name 
        FROM cases 
        LEFT JOIN clients ON clients.id=cases.client_id 
        LEFT JOIN users u ON u.id=cases.lawyer_id 
        WHERE cases.client_id=? 
        ORDER BY cases.id DESC'); 
    $stmt->execute([$cid]); 
    $rows=$stmt->fetchAll(); 
}
else { 
    // Lawyers see cases assigned to them OR unassigned
    $stmt=$pdo->prepare('SELECT cases.*, clients.name AS client_name, u.username AS lawyer_name 
        FROM cases 
        LEFT JOIN clients ON clients.id=cases.client_id 
        LEFT JOIN users u ON u.id=cases.lawyer_id 
        WHERE cases.lawyer_id=? OR cases.lawyer_id IS NULL
        ORDER BY cases.id DESC'); 
    $stmt->execute([$uid]); 
    $rows=$stmt->fetchAll(); 
}

// fetch lawyers for dropdown
$lawyers = [];
if ($role === 'admin' || $role === 'partner') {
    $lawyers = $pdo->query("SELECT id, username FROM users WHERE role='lawyer' OR role='partner' ORDER BY username")->fetchAll();
}

// render rows
foreach ($rows as $c): ?>
    <tr>
        <td><?=$c['id']?></td>
        <td><?=htmlspecialchars($c['title'])?></td>
        <td><?=htmlspecialchars($c['client_name'])?></td>
        <td>
            <?php if ($role === 'admin' || $role === 'partner'): ?>
                <form method="post" style="display:inline-block">
                    <?php csrf_field(); ?>
                    <input type="hidden" name="assign_case_id" value="<?=$c['id']?>">
                    <select name="lawyer_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Unassigned</option>
                        <?php foreach ($lawyers as $l): ?>
                            <option value="<?=$l['id']?>" <?=$c['lawyer_id']==$l['id'] ? 'selected' : ''?>>
                                <?=htmlspecialchars($l['username'])?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            <?php else: ?>
                <?=htmlspecialchars($c['lawyer_name'] ?? 'Unassigned')?>
            <?php endif; ?>
        </td>
        <td><?=htmlspecialchars($c['status'])?></td>
        
        <?php if ($role === 'admin' || $role === 'partner'): ?>
        <td>
            <form method="post" onsubmit="return confirm('Are you sure you want to delete this case?');" style="display:inline-block">
                <?php csrf_field(); ?>
                <input type="hidden" name="delete_case_id" value="<?=$c['id']?>">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
