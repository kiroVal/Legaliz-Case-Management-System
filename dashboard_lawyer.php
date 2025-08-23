<?php require_once __DIR__ . '/config.php'; require_login(); include __DIR__ . '/partials/header.php'; ?>
<h2 class="mb-4">My Workspace</h2>
<div class="row g-4">
  <div class="col-md-4">
    <div class="card p-3 stat">
      <div class="d-flex justify-content-between align-items-center">
        <div><div class="text-secondary">Cases</div><div class="h3">Assigned</div></div>
        <i class="bi bi-briefcase fs-1"></i>
      </div>
      <a href="/cases/list.php" class="btn btn-primary mt-3">View Assigned Cases</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 stat">
      <div class="d-flex justify-content-between align-items-center">
        <div><div class="text-secondary">Documents</div><div class="h3">Repository</div></div>
        <i class="bi bi-file-earmark-text fs-1"></i>
      </div>
      <a href="/documents/list.php" class="btn btn-primary mt-3">Manage Documents</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 stat">
      <div class="d-flex justify-content-between align-items-center">
        <div><div class="text-secondary">Calendar</div><div class="h3">Schedules</div></div>
        <i class="bi bi-calendar-event fs-1"></i>
      </div>
      <a href="/schedules/list.php" class="btn btn-primary mt-3">Open Calendar</a>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
