<?php
require_once 'includes/config.php';
if(isset($_SESSION['user_id'])&&$_SESSION['role']==='staff'){header('Location: '.BASE.'/staff/dashboard.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=trim($_POST['email']);$pass=trim($_POST['password']);
    try{
        $db=getDB();
        $stmt=$db->prepare("SELECT * FROM users WHERE email=? AND role='staff' AND is_active=1");
        if(!$stmt)throw new Exception("Tables missing - import database.sql first.");
        $stmt->bind_param("s",$email);$stmt->execute();
        $user=$stmt->get_result()->fetch_assoc();
        if($user&&password_verify($pass,$user['password'])){
            $_SESSION['user_id']=$user['id'];$_SESSION['user_name']=$user['name'];
            $_SESSION['role']='staff';$_SESSION['dept']=$user['department'];
            $_SESSION['staff_level']=$user['staff_level']??null;
            header('Location: '.BASE.'/staff/dashboard.php');exit;
        }else{$error='Invalid email or password.';}
    }catch(Exception $e){$error='DB Error: '.$e->getMessage();}
}
?><!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Staff Login | College CRM</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#854f0b 0%,#BA7517 100%);padding:20px;}
.card{background:#fff;border-radius:20px;padding:44px 40px;width:100%;max-width:420px;box-shadow:0 30px 80px rgba(0,0,0,.3);text-align:center;}
.icon{width:68px;height:68px;background:linear-gradient(135deg,#854f0b,#BA7517);border-radius:16px;display:inline-flex;align-items:center;justify-content:center;font-size:30px;margin-bottom:13px;}
h1{font-family:'Playfair Display',serif;color:#854f0b;font-size:24px;margin-bottom:4px;}
.sub{color:#718096;font-size:13px;}
.rtag{display:inline-block;background:#faeeda;color:#854f0b;font-size:11px;font-weight:700;padding:3px 12px;border-radius:20px;margin-top:9px;text-transform:uppercase;letter-spacing:.5px;}
.fg{margin-bottom:16px;margin-top:20px;text-align:left;}
label{display:block;font-size:11px;font-weight:700;color:#4a5568;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;}
.iw{position:relative;}
.iw input{width:100%;padding:12px 44px 12px 14px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;font-family:'Inter',sans-serif;color:#2d3748;transition:border-color .2s;}
.iw input:focus{outline:none;border-color:#854f0b;}
.eye{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;color:#a0aec0;}
.lbtn{width:100%;padding:14px;background:linear-gradient(135deg,#854f0b,#BA7517);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;margin-top:4px;transition:all .2s;}
.lbtn:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.2);}
.err{background:#fff5f5;border:1px solid #fed7d7;color:#c53030;padding:11px 14px;border-radius:8px;font-size:13px;margin-bottom:14px;text-align:left;line-height:1.5;}
.links{margin-top:20px;padding-top:16px;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;}
.links a{font-size:12.5px;color:#718096;text-decoration:none;cursor:pointer;}
.flink{color:#e8a020!important;font-weight:700;}
</style></head><body>
<div class="card">
  <div class="icon">👤</div>
  <h1>Staff</h1>
  <div class="sub">College Admission CRM</div>
  <div class="rtag">Staff Member Login</div>
  <?php if($error):?><div class="err" style="margin-top:16px;">⚠️ <?=htmlspecialchars($error)?></div><?php endif;?>
  <form method="POST" autocomplete="off">
    <div class="fg"><label>Email Address</label><div class="iw"><input type="email" name="email" required value="<?=htmlspecialchars($_POST['email']??'')?>"></div></div>
    <div class="fg" style="margin-top:0;"><label>Password</label>
      <div class="iw"><input type="password" name="password" id="pwdstaff" required>
      <button type="button" class="eye" onclick="var e=document.getElementById('pwdstaff');e.type=e.type==='password'?'text':'password';this.textContent=e.type==='password'?'👁':'🙈';">👁</button></div>
    </div>
    <button type="submit" class="lbtn">Login as Staff →</button>
  </form>
  <div class="links">
    <a href="<?=BASE?>/forgot_password.php?role=staff" class="flink">🔑 Forgot Password?</a>
    <div style="display:flex;gap:14px;">
