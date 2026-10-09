<?php
$db2=getDB(); $uid=$_SESSION['user_id']; $today=date('Y-m-d'); $rem_rows=[];
if(isset($_SESSION['role'])&&$_SESSION['role']==='staff'){
    $r=$db2->query("SELECT id,name,phone FROM leads WHERE reminder_date='$today' AND assigned_staff=$uid AND status='In Progress'");
    $rem_rows=$r?$r->fetch_all(MYSQLI_ASSOC):[];
}
$B=BASE;
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=htmlspecialchars($page_title??'CRM')?> | College CRM</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?=$B?>/assets/css/style.css">
</head><body>
<div class="sidebar" style="background:<?=($_SESSION['role']??'')==='hod'?'#0f6e56':(($_SESSION['role']??'')==='staff'?'#854f0b':'#1a3c5e')?>;">
  <div class="sidebar-logo">
    <div class="logo-box" style="background:<?=($_SESSION['role']??'')==='hod'?'#1D9E75':(($_SESSION['role']??'')==='staff'?'#BA7517':'#e8a020')?>;">🎓</div>
    <div class="logo-text"><h2>College CRM</h2><p>Admission System</p></div>
  </div>
  <div class="sidebar-user">
    <div class="role-badge" style="background:<?=($_SESSION['role']??'')==='hod'?'#1D9E75':(($_SESSION['role']??'')==='staff'?'#BA7517':'#e8a020')?>;color:<?=($_SESSION['role']??'')==='hod'?'#04342c':(($_SESSION['role']??'')==='staff'?'#412402':'#1a3c5e')?>;"><?=strtoupper($_SESSION['role']??'')?></div>
    <div class="user-name"><?=htmlspecialchars($_SESSION['user_name']??'')?></div>
    <?php if(!empty($_SESSION['dept'])):?><div class="user-dept"><?=htmlspecialchars($_SESSION['dept'])?></div><?php endif;?>
    <?php if(!empty($_SESSION['staff_level'])):?><div class="level-badge">Level <?=(int)$_SESSION['staff_level']?></div><?php endif;?>
  </div>
  <nav class="nav-section">
    <div class="nav-label">Navigation</div>
    <?php if(($_SESSION['role']??'')==='admin'):?>
    <a href="<?=$B?>/admin/dashboard.php" class="nav-item <?=$active_menu==='dashboard'?'active':''?>"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="<?=$B?>/admin/leads.php" class="nav-item <?=$active_menu==='leads'?'active':''?>"><span class="nav-icon">📋</span> All Leads</a>
    <a href="<?=$B?>/admin/add_lead.php" class="nav-item <?=$active_menu==='add_lead'?'active':''?>"><span class="nav-icon">➕</span> Add Lead</a>
    <a href="<?=$B?>/admin/upload_leads.php" class="nav-item <?=$active_menu==='upload'?'active':''?>"><span class="nav-icon">📤</span> Upload CSV</a>
    <a href="<?=$B?>/admin/assign_hod.php" class="nav-item <?=$active_menu==='assign_hod'?'active':''?>"><span class="nav-icon">🔗</span> Assign HOD</a>
    <a href="<?=$B?>/admin/users.php" class="nav-item <?=$active_menu==='users'?'active':''?>"><span class="nav-icon">👥</span> Manage Users</a>
    <a href="<?=$B?>/admin/reports.php" class="nav-item <?=$active_menu==='reports'?'active':''?>"><span class="nav-icon">📈</span> Reports</a>
    <a href="<?=$B?>/admin/hod_report.php" class="nav-item <?=$active_menu==='hod_report'?'active':''?>"><span class="nav-icon">👩‍💼</span> HOD Report</a>
    <a href="<?=$B?>/admin/staff_report.php" class="nav-item <?=$active_menu==='staff_report'?'active':''?>"><span class="nav-icon">👤</span> Staff Report</a>
    <?php elseif(($_SESSION['role']??'')==='hod'):?>
    <a href="<?=$B?>/hod/dashboard.php" class="nav-item <?=$active_menu==='dashboard'?'active':''?>"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="<?=$B?>/hod/leads.php" class="nav-item <?=$active_menu==='leads'?'active':''?>"><span class="nav-icon">📋</span> My Leads</a>
    <a href="<?=$B?>/hod/assign_staff.php" class="nav-item <?=$active_menu==='assign_staff'?'active':''?>"><span class="nav-icon">🔗</span> Assign Staff</a>
    <a href="<?=$B?>/hod/staff_report.php" class="nav-item <?=$active_menu==='staff_report'?'active':''?>"><span class="nav-icon">📈</span> Staff Report</a>
    <a href="<?=$B?>/hod/call_logs.php" class="nav-item <?=$active_menu==='call_logs'?'active':''?>"><span class="nav-icon">📞</span> Call Logs</a>
    <?php elseif(($_SESSION['role']??'')==='staff'):?>
    <a href="<?=$B?>/staff/dashboard.php" class="nav-item <?=$active_menu==='dashboard'?'active':''?>"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="<?=$B?>/staff/leads.php" class="nav-item <?=$active_menu==='leads'?'active':''?>"><span class="nav-icon">📋</span> My Leads</a>
    <a href="<?=$B?>/staff/reminders.php" class="nav-item <?=$active_menu==='reminders'?'active':''?>"><span class="nav-icon">🔔</span> Reminders</a>
    <?php endif;?>
  </nav>
  <div class="sidebar-footer"><a href="<?=$B?>/logout.php" class="logout-btn"><span>🚪</span> Logout</a></div>
</div>
<div class="main">
  <div class="topbar">
    <div class="page-title"><?=htmlspecialchars($page_title??'Dashboard')?></div>
    <div class="topbar-right">
      <span class="datetime" id="live-clock"></span>
      <?php if(!empty($rem_rows)):?>
      <div class="bell-wrap" onclick="document.getElementById('remPop').classList.toggle('show')">
        🔔<span class="bell-count"><?=count($rem_rows)?></span>
        <div class="reminder-popup" id="remPop">
          <div class="reminder-popup-head">🔔 Today's Reminders (<?=count($rem_rows)?>)</div>
          <?php foreach($rem_rows as $rr):?>
          <div class="reminder-row"><strong><?=htmlspecialchars($rr['name'])?></strong><div style="font-size:12px;color:#718096;">📞 <?=htmlspecialchars($rr['phone'])?></div></div>
          <?php endforeach;?>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
  <div class="content">
    <?php if(isset($_GET['msg'])):?><div class="alert alert-success">✅ <?=htmlspecialchars($_GET['msg'])?></div><?php endif;?>
    <?php if(isset($_GET['err'])):?><div class="alert alert-danger">❌ <?=htmlspecialchars($_GET['err'])?></div><?php endif;?>
