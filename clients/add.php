<?php 
require_once __DIR__ . '/../partials/header.php'; 

// Fetch all users with role = client that are NOT yet in clients table
$users = $pdo->query("
    SELECT u.id, u.username, u.email 
    FROM users u
    LEFT JOIN clients c ON u.id = c.user_id
    WHERE u.role = 'client' AND c.id IS NULL
")->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD']==='POST'){ 
    csrf_verify();

    $user_id = $_POST['user_id'] ?? null;

    if($user_id){
        // Get user info
        $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id=? AND role='client'");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            // Insert into clients
            $stmt = $pdo->prepare('INSERT INTO clients (name,contact,email,address,user_id) VALUES (?,?,?,?,?)');
            $stmt->execute([
                $user['username'],
                $_POST['contact'] ?? '',
                $user['email'],
                $_POST['address'] ?? '',
                $user_id
            ]);
        }
    }
    header('Location: /clients/list.php'); 
    exit;
}
?>

<h3>Add Client from Users</h3>
<form method='post'>
    <?php csrf_field(); ?>

    <div class="mb-2">
        <label class="form-label">Select User (role: client)</label>
        <select name="user_id" class="form-control" required>
            <option value="">-- Select Client User --</option>
            <?php if(empty($users)): ?>
                <option disabled>No available client users</option>
            <?php else: ?>
                <?php foreach($users as $u): ?>
                    <option value="<?=$u['id']?>"><?=$u['username']?> (<?=$u['email']?>)</option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <input name='contact' class='form-control mb-2' placeholder='Contact'>
    <textarea name='address' class='form-control mb-2' placeholder='Address'></textarea>
    
    <button class='btn btn-primary'>Save</button>
</form>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
