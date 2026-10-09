<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='Admin Dashboard';$active_menu='dashboard';
$db=getDB();

$total=$db->query("SELECT COUNT(*) c FROM leads")->fetch_assoc()['c']??0;
$admitted=$db->query("SELECT COUNT(*) c FROM leads WHERE status='Admitted'")->fetch_assoc()['c']??0;
$rejected=$db->query("SELECT COUNT(*) c FROM leads WHERE status='Rejected'")->fetch_assoc()['c']??0;
$progress=$db->query("SELECT COUNT(*) c FROM leads WHERE status='In Progress'")->fetch_assoc()['c']??0;
$webinar=$db->query("SELECT COUNT(*) c FROM leads WHERE source='Webinar'")->fetch_assoc()['c']??0;
$calls=$db->query("SELECT COUNT(*) c FROM call_history")->fetch_assoc()['c']??0;

require_once '../includes/header.php';
?>
<div class="stats-grid">
  <div class="stat-card"><div class="stat-label">Total Leads</div><div class="stat-value"><?=$total?></div></div>
  <div class="stat-card green"><div class="stat-label">Admitted</div><div class="stat-value"><?=$admitted?></div></div>
  <div class="stat-card red"><div class="stat-label">Rejected</div><div class="stat-value"><?=$rejected?></div></div>
  <div class="stat-card yellow"><div class="stat-label">In Progress</div><div class="stat-value"><?=$progress?></div></div>
  <div class="stat-card blue"><div class="stat-label">Via Webinar</div><div class="stat-value"><?=$webinar?></div></div>
  <div class="stat-card"><div class="stat-label">Total Calls</div><div class="stat-value"><?=$calls?></div></div>
</div>
<?php require_once '../includes/footer.php'; ?>
