<?php require_once __DIR__ . '/../config.php'; require_login(); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Legal Case Management</title>

<!-- Local Bootstrap first -->
<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
<!-- Fallback to CDN if local file missing -->
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" onerror="console.warn('CDN CSS failed; using local')">

<!-- Bootstrap Icons (local placeholder + CDN fallback) -->
<link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" onerror="console.warn('CDN icons failed; using local')">

<!-- Custom theme -->
<link rel="stylesheet" href="/assets/css/custom.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/dashboard.php"><i class="bi bi-balance-scale"></i> LCM</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav" aria-controls="topnav" aria-expanded="false">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="topnav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2"><span class="badge badge-role rounded-pill"><?=htmlspecialchars($_SESSION['role'] ?? '')?></span></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i> <?=htmlspecialchars($_SESSION['name'] ?? 'User')?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="d-flex">
  <aside class="sidebar p-3">
    <div class="small text-uppercase text-secondary mb-2">Navigation</div>
    <a href="/dashboard.php" class="<?=(basename($_SERVER['PHP_SELF'])==='dashboard.php'?'active':'')?>"><i class="bi bi-grid-1x2-fill me-2"></i> Dashboard</a>
    <a href="/clients/list.php" class=""><i class="bi bi-people-fill me-2"></i> Clients</a>
    <a href="/cases/list.php" class=""><i class="bi bi-folder2-open me-2"></i> Cases</a>
    <a href="/documents/list.php" class=""><i class="bi bi-file-earmark-text me-2"></i> Documents</a>
    <a href="/schedules/list.php" class=""><i class="bi bi-calendar-event me-2"></i> Schedules</a>
    <a href="/billing/list.php" class=""><i class="bi bi-receipt me-2"></i> Billing</a>
    <a href="/ai_lookup.php" class=""><i class="bi bi-robot me-2"></i> AI Lookup</a>
    <?php if(is_admin()): ?>
      <div class="small text-uppercase text-secondary mt-3 mb-2">Admin</div>
      <a href="/admin_users/list.php"><i class="bi bi-shield-lock me-2"></i> Users</a>
    <?php endif; ?>
  </aside>
  <main class="main">
