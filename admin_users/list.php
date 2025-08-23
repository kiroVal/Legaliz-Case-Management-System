<?php require_once __DIR__ . '/../partials/header.php'; if(!is_admin()){ http_response_code(403); die('Admins only'); } ?>
<h3>Users</h3><p><a class='btn btn-sm btn-primary' href='/admin_users/add.php'>Add User</a></p>
<table class='table'><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead><tbody>
<?php foreach($pdo->query('SELECT id,name,email,role FROM users ORDER BY id DESC') as $u): ?>
<tr><td><?=$u['id']?></td><td><?=htmlspecialchars($u['name'])?></td><td><?=htmlspecialchars($u['email'])?></td><td><?=htmlspecialchars($u['role'])?></td><td><a class='btn btn-sm btn-outline-danger' href='/admin_users/delete.php?id=<?=$u['id']?>' onclick="return confirm('Delete?')">Delete</a></td></tr>
<?php endforeach; ?>
</tbody></table><?php require_once __DIR__ . '/../partials/footer.php'; ?>