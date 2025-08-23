<?php
// Edit these for Hostinger
$DB_HOST = "localhost";
$DB_NAME = "u785536991_legal_case_db";
$DB_USER = "u785536991_admin";
$DB_PASS = "TbcL&SFy9p/";
/*$OPENAI_API_KEY = getenv('OPENAI_API_KEY') ?: 'YOUR_OPENAI_API_KEY';*/

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) session_start();

function require_login(){ if(!isset($_SESSION['user_id'])){ header('Location: /login.php'); exit; } }
function is_admin(){ return isset($_SESSION['role']) && $_SESSION['role']==='admin'; }
function is_lawyer_or_staff(){ return isset($_SESSION['role']) && in_array($_SESSION['role'], ['lawyer','staff']); }
function is_client(){ return isset($_SESSION['role']) && $_SESSION['role']==='client'; }

function csrf_token(){ if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); return $_SESSION['csrf_token']; }
function csrf_field(){ echo '<input type="hidden" name="csrf" value="'.htmlspecialchars(csrf_token(), ENT_QUOTES).'">'; }
function csrf_verify(){ if ($_SERVER['REQUEST_METHOD']==='POST'){ $t = $_POST['csrf'] ?? ''; if (!$t || !hash_equals($_SESSION['csrf_token'] ?? '', $t)){ http_response_code(419); die('CSRF validation failed.'); } } }
function current_client_id($pdo){ if(!isset($_SESSION['user_id'])) return null; $stmt=$pdo->prepare('SELECT id FROM clients WHERE user_id=?'); $stmt->execute([$_SESSION['user_id']]); $r=$stmt->fetch(); return $r['id'] ?? null; }
?>