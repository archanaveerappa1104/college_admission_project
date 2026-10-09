<?php
require_once 'includes/config.php';
$role=in_array($_GET['role']??'',['admin','hod','staff'])?$_GET['role']:'admin';
$msg='';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=trim($_POST['email']);
    $newp=trim($_POST['new_password']);
    $db=getDB();
    $st=$db->prepare("SELECT id FROM users WHERE email=? AND role=?");
    $st->bind_param("ss",$email,$role);$st->execute();
    $u=$st->get_result()->fetch_assoc();
    if($u){
        $hash=password_hash($newp,PASSWORD_BCRYPT);
        $db->query("UPDATE users SET password='$hash' WHERE id=".$u['id']);
        $msg='Password reset! You can now login.';
    }else{$err='Email not found for this role.';}
}
?><!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Forgot Password</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1a3c5e,#0d2137);padding:20px;}
.card{background:#fff;border-radius:20px;padding:40px;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,.3);text-align:center;}
h1{font-size:22px;color:#1a3c5e;margin-bottom:6px;}
p{color:#718096;font-size:13px;margin-bottom:22px;}
label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;color:#4a5568;margin-bottom:5px;text-align:left;letter-spacing:.5px;}
input{width:100%;padding:11px 13px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:'Inter',sans-serif;margin-bottom:14px;}
input:focus{outline:none;border-color:#1a3c5e;}
button{width:100%;padding:13px;background:#1a3c5e;color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;}
.ok{background:#d4edda;color:#155724;padding:12px;border-radius:8px;margin-bottom:14px;font-size:13px;}
.er{background:#f8d7da;color:#721c24;padding:12px;border-radius:8px;margin-bottom:14px;font-size:13px;}
a{display:block;margin-top:14px;color:#1a3c5e;font-size:13px;text-decoration:none;}
</style></head><body>
<div class="card">
  <div style="font-size:40px;margin-bottom:12px;">🔑</div>
  <h1>Reset Password</h1>
  <p>Enter your registered email and set a new password</p>
  <?php if($msg):?><div class="ok">✅ <?=htmlspecialchars($msg)?></div><?php endif;?>
  <?php if($err):?><div class="er">❌ <?=htmlspecialchars($err)?></div><?php endif;?>
  <form method="POST">
    <label>Email Address</label>
    <input type="email" name="email" required placeholder="Your registered email">
    <label>New Password</label>
    <input type="password" name="new_password" required placeholder="New password" minlength="6">
    <button type="submit">🔑 Reset Password</button>
  </form>
  <a href="<?=BASE?>/login_<?=$role?>.php">← Back to Login</a>
</div>
</body></html>
