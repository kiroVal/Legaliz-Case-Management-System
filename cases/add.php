<?php
// Debug mode (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../partials/header.php';

$role = $_SESSION['role'];

// Fetch clients list
$clients = $pdo->query('SELECT id, name FROM clients ORDER BY name')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    try {
        if ($role === 'client') {
            // Get client ID from session helper
            $client_id = current_client_id($pdo);

            $stmt = $pdo->prepare('INSERT INTO cases (client_id, lawyer_id, title, description, status) 
                                   VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([
                $client_id,
                NULL, // client cannot assign lawyer
                $_POST['title'],
                $_POST['description'],
                $_POST['status']
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO cases (client_id, lawyer_id, title, description, status) 
                                   VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([
                $_POST['client_id'],
                !empty($_POST['lawyer_id']) ? $_POST['lawyer_id'] : NULL, // optional lawyer
                $_POST['title'],
                $_POST['description'],
                $_POST['status']
            ]);
        }

        // Redirect after success
        header('Location: /cases/list.php');
        exit;

    } catch (PDOException $e) {
        echo "<pre>SQL Error: " . $e->getMessage() . "</pre>";
    }
}
?>

<h3>Add Case</h3>
<form method="post">
    <?php csrf_field(); ?>

    <?php if ($role !== 'client'): ?>
        <!-- Select client -->
        <select name="client_id" class="form-select mb-2" required>
            <option value="">Select client</option>
            <?php foreach ($clients as $cl): ?>
                <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Select lawyer -->
        <select name="lawyer_id" class="form-select mb-2">
            <option value="">Assign lawyer (optional)</option>
            <?php foreach ($pdo->query('SELECT id, username FROM users WHERE role IN ("lawyer","staff","partner")') as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
            <?php endforeach; ?>
        </select>
    <?php else: ?>
        <div class="alert alert-info">This case will be created under your client profile.</div>
    <?php endif; ?>

    <!-- Case details -->
    <input name="title" class="form-control mb-2" placeholder="Title" required>
    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>

    <select name="status" class="form-select mb-2">
        <option value="open">open</option>
        <option value="in_progress">in_progress</option>
        <option value="closed">closed</option>
    </select>

    <button class="btn btn-primary">Create</button>
</form>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
