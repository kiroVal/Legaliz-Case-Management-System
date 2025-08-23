<?php require_once __DIR__ . '/config.php'; require_login();
$error=''; $result='';
if($_SERVER['REQUEST_METHOD']==='POST'){ csrf_verify(); $q = trim($_POST['query'] ?? ''); if(!$q){ $error='Enter a query.'; } else {
    $payload = json_encode(['model'=>'gpt-4o-mini','messages'=>[['role'=>'system','content'=>'You are a legal assistant. Provide concise, neutral summaries.'],['role'=>'user','content'=>$q]],'temperature'=>0.2]);
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Authorization: Bearer '.($OPENAI_API_KEY)]);
    curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $resp = curl_exec($ch);
    if($resp===false){ $error='cURL error: '.curl_error($ch); }
    curl_close($ch);
    if(!$error){ $data = json_decode($resp, true); if(isset($data['choices'][0]['message']['content'])){ $result = $data['choices'][0]['message']['content']; $stmt=$pdo->prepare('INSERT INTO ai_queries (user_id,query,response) VALUES (?,?,?)'); $stmt->execute([$_SESSION['user_id'],$q,$result]); } else { $error='AI error - check key/quota.'; } }
} }
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<h3>AI Case Lookup</h3>
<form method='post'><?php csrf_field(); ?><textarea name='query' class='form-control mb-2' rows=4 required><?=htmlspecialchars($_POST['query'] ?? '')?></textarea><button class='btn btn-primary'>Ask AI</button></form>
<?php if($error): ?><div class='alert alert-danger'><?=htmlspecialchars($error)?></div><?php endif; ?>
<?php if($result): ?><div class='card'><div class='card-body'><pre style='white-space:pre-wrap'><?=htmlspecialchars($result)?></pre></div></div><?php endif; ?>
<h5 class='mt-4'>Your past queries</h5><ul><?php foreach($pdo->prepare('SELECT query, created_at FROM ai_queries WHERE user_id=? ORDER BY id DESC LIMIT 20')->execute([$_SESSION['user_id']])?:[] as $r){ /* no-op */ } ?></ul>
<?php include __DIR__ . '/partials/footer.php'; ?>