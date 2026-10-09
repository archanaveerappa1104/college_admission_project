<?php
require_once 'includes/config.php';
$done=false;$error='';$count=0;
if($_SERVER['REQUEST_METHOD']==='POST'){
    $hash=password_hash('admin123',PASSWORD_BCRYPT);
    $r=getDB()->query("UPDATE users SET password='$hash'");
    if($r){$count=getDB()->affected_rows;$done=true;}else{$error=getDB()->error;}
}
?><!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reset Passwords</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
<style>*{margin:0;padding:0;box-sizing:border-box;}body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1a3c5e,#0d2137);padding:20px;}.card{background:#fff;border-radius:16px;padding:40px;max-width:500px;width:100%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.3);}h1{color:#1a3c5e;margin:12px 0 8px;}.sub{color:#718096;font-size:13px;margin-bottom:22px;}.ok{background:#d4edda;padding:14px;border-radius:8px;color:#155724;font-size:13px;line-height:1.8;margin-bottom:16px;}.er{background:#f8d7da;padding:14px;border-radius:8px;color:#721c24;font-size:13px;margin-bottom:14px;}.btn{width:100%;padding:14px;background:#1a3c5e;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;}.row{display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #e2e8f0;font-size:13px;}.row:last-child{border:none;}.creds{background:#f8f9fa;border-radius:8px;padding:14px;margin:14px 0;text-align:left;}.warn{background:#fff3cd;padding:12px;border-radius:8px;font-size:12px;color:#856404;margin-top:14px;line-height:1.6;}.go{display:block;margin-top:12px;padding:12px;background:#28a745;color:#fff;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;}</style></head><body>
<div class="card">
<?php if($done):?>
  <div style="font-size:50px;">✅</div><h1>Done!</h1>
  <div class="ok"><?=$count?> users updated. New password: <strong>admin123</strong></div>
  <div class="creds">
    <div class="row"><span>👑 Admin</span><span><strong>admin@college.edu</strong></span></div>
    <div class="row"><span>🏫 HOD CSE</span><span><strong>hod.cse@college.edu</strong></span></div>
    <div class="row"><span>🏫 HOD PG</span><span><strong>hod.pg@college.edu</strong></span></div>
    <div class="row"><span>👤 Arjun S</span><span><strong>arjun@college.edu</strong></span></div>
  </div>
  <a href="<?=BASE?>/login_admin.php" class="go">→ Go to Admin Login</a>
  <div class="warn">⚠️ Delete reset_passwords.php after use!</div>
<?php elseif($error):?>
  <div style="font-size:50px;">❌</div><h1>Error</h1>
  <div class="er"><?=htmlspecialchars($error)?><br>Import database.sql first!</div>
<?php else:?>
  <div style="font-size:50px;">🔑</div>
  <h1>Reset All Passwords</h1>
  <div class="sub">Resets all user passwords to <strong>admin123</strong></div>
  <form method="POST"><button class="btn">🔑 Reset All Passwords to "admin123"</button></form>
<?php endif;?>
</div></body></html>
