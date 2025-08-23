<?php require_once __DIR__ . '/config.php'; require_login();
$role = $_SESSION['role'] ?? 'client';
if($role==='admin') include __DIR__ . '/dashboard_admin.php';
elseif(in_array($role,['lawyer','staff'])) include __DIR__ . '/dashboard_lawyer.php';
else include __DIR__ . '/dashboard_client.php';
