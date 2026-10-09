<?php
require_once 'includes/config.php';
if(isset($_SESSION['user_id'])){header('Location: '.BASE.'/'.$_SESSION['role'].'/dashboard.php');exit;}
?><!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>College Admission CRM</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg,#1a3c5e 0%,#0d2137 60%,#2a2060 100%);padding:20px;}
h1{font-family:'Playfair Display',serif;color:#fff;font-size:34px;margin-bottom:8px;text-align:center;}
p{color:rgba(255,255,255,.6);font-size:14px;margin-bottom:36px;text-align:center;}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;max-width:580px;width:100%;}
.card{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:16px;padding:30px 16px;text-align:center;text-decoration:none;transition:all .2s;}
.card:hover{background:rgba(255,255,255,.18);transform:translateY(-4px);}
.ci{font-size:38px;margin-bottom:10px;}
.ct{color:#fff;font-weight:700;font-size:16px;}
.cs{color:rgba(255,255,255,.5);font-size:12px;margin-top:4px;}
</style></head><body>
<div style="font-size:58px;margin-bottom:18px;">🎓</div>
<h1>College Admission CRM</h1>
<p>Enquiry & Lead Management System — Select your role to login</p>
<div class="cards">
  <a href="<?=BASE?>/login_admin.php" class="card"><div class="ci">👑</div><div class="ct">Admin</div><div class="cs">Full access</div></a>
  <a href="<?=BASE?>/login_hod.php" class="card"><div class="ci">🏫</div><div class="ct">HOD</div><div class="cs">Department head</div></a>
  <a href="<?=BASE?>/login_staff.php" class="card"><div class="ci">👤</div><div class="ct">Staff</div><div class="cs">Call & track leads</div></a>
</div>
</body></html>
