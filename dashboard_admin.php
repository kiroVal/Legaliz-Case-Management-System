<?php require_once __DIR__ . '/config.php'; require_login(); include __DIR__ . '/partials/header.php'; ?>
<h2 class="mb-4">Admin Dashboard</h2>
<div class="row g-4">
  <div class="col-md-4">
    <div class="card p-3 stat">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-secondary">Users</div>
          <div class="h3">Manage Accounts</div>
        </div>
        <i class="bi bi-people-fill fs-1"></i>
      </div>
      <a href="/admin_users/list.php" class="btn btn-primary mt-3">Manage Users</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 stat">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-secondary">Cases</div>
          <div class="h3">All Cases</div>
        </div>
        <i class="bi bi-folder2-open fs-1"></i>
      </div>
      <a href="/cases/list.php" class="btn btn-primary mt-3">View Cases</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 stat">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-secondary">AI</div>
          <div class="h3">Case Lookup</div>
        </div>
        <i class="bi bi-robot fs-1"></i>
      </div>
      <a href="/ai_lookup.php" class="btn btn-primary mt-3">Open AI Lookup</a>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
