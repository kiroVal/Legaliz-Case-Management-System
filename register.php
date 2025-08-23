<?php require_once __DIR__ . '/config.php'; ?>
<?php
if(isset($_SESSION['user_id'])){ header('Location: /dashboard.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){ csrf_verify();
  $name=trim($_POST['name'] ?? ''); $email=trim($_POST['email'] ?? ''); $pwd=$_POST['password'] ?? '';
  if(!$name || !$email || !$pwd){ $error='All fields required.'; }
  else {
    $hash = password_hash($pwd, PASSWORD_DEFAULT);
    try {
      $stmt = $pdo->prepare('INSERT INTO users (username,email,password,role) VALUES (?,?,?,"client")');
      $stmt->execute([$name,$email,$hash]);
      $uid = $pdo->lastInsertId();
     
      header('Location: /login.php?registered=1'); exit;
    } catch (PDOException $e){ 
      if($e->getCode()==23000){ $error='Email already registered.'; }
      else { $error='Registration failed: '.$e->getMessage(); }
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register • LCM</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/custom.css">
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6">
        <div class="card p-4">
          <div class="text-center mb-3">
            <div class="fw-bold fs-4"><i class="bi bi-balance-scale"></i> Create Account</div>
            <div class="text-secondary">Client accounts are created here</div>
          </div>
          <?php if(!empty($error)): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
          <form method="post" novalidate>
            <?php csrf_field(); ?>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Full Name</label>
                <input name="name" class="form-control" required>
              </div>
              <div class="col-12">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="col-12">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
            </div>
            <button class="btn btn-primary w-100 mt-3">Register</button>
          </form>
          <div class="text-center mt-3">
            <a href="/login.php">Already have an account?</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
