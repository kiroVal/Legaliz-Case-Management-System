<?php require_once __DIR__ . '/config.php'; require_login(); include __DIR__ . '/partials/header.php'; ?>
<h2 class="mb-4">Client Dashboard</h2>
<div class="row g-4">
  <div class="col-md-4">
    <div class="card p-3 stat">
      <div class="d-flex justify-content-between align-items-center">
        <div><div class="text-secondary">My</div><div class="h3">Cases</div></div>
        <i class="bi bi-folder2 fs-1"></i>
      </div>
      <a href="/cases/list.php" class="btn btn-primary mt-3">View My Cases</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 stat">
      <div class="d-flex justify-content-between align-items-center">
        <div><div class="text-secondary">Billing</div><div class="h3">Invoices</div></div>
        <i class="bi bi-receipt fs-1"></i>
      </div>
      <a href="/billing/list.php" class="btn btn-primary mt-3">My Invoices</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 stat">
      <div class="d-flex justify-content-between align-items-center">
        <div><div class="text-secondary">Help</div><div class="h3">AI Lookup</div></div>
        <i class="bi bi-robot fs-1"></i>
      </div>
      <a href="/ai_lookup.php" class="btn btn-primary mt-3">Ask AI</a>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
