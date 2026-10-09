<?php
require_once '../includes/config.php';
requireLogin('staff');
$page_title='Staff Dashboard';$active_menu='dashboard';
$db=getDB();$uid=$_SESSION['user_id'];$today=date('Y-m-d');
$stats=['total'=>0,'admitted'=>0,'progress'=>0,'calls'=>0];
$r=$db->query("SELECT status,COUNT(*) c FROM leads WHERE assigned_staff=$uid GROUP BY status");
while($row=$r->fetch_assoc()){
    $stats['total']+=$row['c'];
    if($row['status']==='Admitted')$stats['admitted']+=$row['c'];
    elseif($row['status']==='In Progress')$stats['progress']+=$row['c'];
}
$stats['calls']=$db->query("SELECT COUNT(*) c FROM call_history WHERE staff_id=$uid")->fetch_assoc()['c']??0;
$reminders=$db->query("SELECT * FROM leads WHERE assigned_staff=$uid AND reminder_date='$today' AND status='In Progress' ORDER BY name");
$leads=$db->query("SELECT *,(SELECT COUNT(*) FROM call_history WHERE lead_id=leads.id) calls FROM leads WHERE assigned_staff=$uid ORDER BY reminder_date ASC,created_at DESC LIMIT 8");
require_once '../includes/header.php';
?>
<div class="stats-grid">
  <div class="stat-card"><div class="stat-label">My Leads</div><div class="stat-value"><?=$stats['total']?></div></div>
  <div class="stat-card green"><div class="stat-label">Admitted</div><div class="stat-value"><?=$stats['admitted']?></div></div>
  <div class="stat-card yellow"><div class="stat-label">In Progress</div><div class="stat-value"><?=$stats['progress']?></div></div>
  <div class="stat-card red"><div class="stat-label">Today Reminders</div><div class="stat-value"><?=$reminders?$reminders->num_rows:0?></div></div>
  <div class="stat-card blue"><div class="stat-label">My Total Calls</div><div class="stat-value"><?=$stats['calls']?></div></div>
</div>
<?php if($reminders&&$reminders->num_rows>0):?>
<div class="rem-alert">
  <h4>🔔 Today's Follow-up Reminders (<?=$reminders->num_rows?>)</h4>
  <table><tr><th>Name</th><th>Phone</th><th>Department</th><th>Action</th></tr>
  <?php while($rem=$reminders->fetch_assoc()):?>
  <tr><td><strong><?=htmlspecialchars($rem['name'])?></strong></td>
  <td><?=htmlspecialchars($rem['phone'])?></td>
  <td style="font-size:12px;"><?=htmlspecialchars($rem['department_applied'])?></td>
  <td><a href="<?=BASE?>/staff/log_call.php?id=<?=$rem['id']?>" class="btn btn-primary btn-sm">📞 Log Call</a></td></tr>
  <?php endwhile;?>
  </table>
</div>
<?php endif;?>
<div class="table-card">
  <div class="table-header"><h3>📋 My Assigned Leads</h3><a href="<?=BASE?>/staff/leads.php" class="btn btn-outline btn-sm">View All</a></div>
  <div style="overflow-x:auto;"><table>
  <tr><th>Name</th><th>Phone</th><th>Department</th><th>Source</th><th>Status</th><th>Calls</th><th>Reminder</th><th>Action</th></tr>
  <?php while($l=$leads->fetch_assoc()):$isToday=($l['reminder_date']===$today);?>
  <tr><td><strong><?=htmlspecialchars($l['name'])?></strong></td>
  <td><?=htmlspecialchars($l['phone'])?></td>
  <td style="font-size:11px;"><?=htmlspecialchars($l['department_applied'])?></td>
  <td><?=htmlspecialchars($l['source'])?></td>
  <td><?=sBadge($l['status'])?></td>
  <td><span class="call-count-badge">📞 <?=$l['calls']?></span></td>
  <td style="<?=$isToday?'color:var(--danger);font-weight:700;':''?>"><?=$l['reminder_date']?date('d M Y',strtotime($l['reminder_date'])):'—'?></td>
  <td><a href="<?=BASE?>/staff/log_call.php?id=<?=$l['id']?>" class="btn btn-primary btn-sm">📞 Log Call</a></td></tr>
  <?php endwhile;?>
  </table></div>
</div>
<?php require_once '../includes/footer.php'; ?>
