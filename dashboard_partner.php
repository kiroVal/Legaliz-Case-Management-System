<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'partner') {
    header("Location: login.php");
    exit();
}
include 'partials/header.php';
?>

<div class="container mt-4">
    <h2>Welcome, Partner!</h2>
    <p>This is your Partner Dashboard. You have access to all features except User Management.</p>

    <div class="row">
        <div class="col-md-3">
            <a href="clients/list.php" class="btn btn-primary btn-block mb-3">Manage Clients</a>
        </div>
        <div class="col-md-3">
            <a href="cases/list.php" class="btn btn-primary btn-block mb-3">Manage Cases</a>
        </div>
        <div class="col-md-3">
            <a href="billing/list.php" class="btn btn-primary btn-block mb-3">Billing</a>
        </div>
        <div class="col-md-3">
            <a href="documents/list.php" class="btn btn-primary btn-block mb-3">Documents</a>
        </div>
        <div class="col-md-3">
            <a href="schedules/list.php" class="btn btn-primary btn-block mb-3">Schedules</a>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
