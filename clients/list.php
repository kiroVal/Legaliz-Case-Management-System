<?php 
session_start();
require_once __DIR__ . '/../partials/header.php'; 
?>
<h3>Clients</h3>

<p>
<?php if ($_SESSION['role'] === 'client'): ?>
    <!-- Disabled button for clients -->
    <button class="btn btn-sm btn-secondary" disabled>Add Client (Disabled)</button>
<?php else: ?>
    <!-- Active link for others -->
    <a class='btn btn-sm btn-primary' href='/clients/add.php'>Add Client</a>
<?php endif; ?>
</p>

<table class='table'>
    <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($pdo->query('SELECT * FROM clients ORDER BY id DESC') as $c): ?>
            <tr>
                <td><?=$c['id']?></td>
                <td><?=htmlspecialchars($c['name'])?></td>
                <td><?=htmlspecialchars($c['email'])?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
