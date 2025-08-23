<?php require_once __DIR__ . '/config.php'; ?>
<?php
if(isset($_SESSION['user_id'])){ header('Location: /dashboard.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){ csrf_verify();
  $email = trim($_POST['email'] ?? ''); $pass = $_POST['password'] ?? '';
  $stmt = $pdo->prepare('SELECT * FROM users WHERE email=?'); $stmt->execute([$email]); $user = $stmt->fetch();
  if($user && password_verify($pass, $user['password'])){
    $_SESSION['user_id']=$user['id']; $_SESSION['name']=$user['username'] ?? $user['name'] ?? 'User'; $_SESSION['role']=$user['role'];
    header('Location: /dashboard.php'); exit;
  } else { $error = 'Invalid credentials.'; }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login • LCM</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/custom.css">
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4">
          <div class="text-center mb-3">
            <div class="fw-bold fs-4"><i class="bi bi-balance-scale"></i> Legal Case Management</div>
            <div class="text-secondary">Welcome back</div>
          </div>
          <?php if(!empty($error)): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
          <form method="post" novalidate>
            <?php csrf_field(); ?>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Login</button>
          </form>
          <div class="text-center mt-3">
            <a href="/register.php">Create an account</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
