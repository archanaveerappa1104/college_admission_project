<?php
require_once '../includes/config.php';
requireLogin('staff');
$page_title='Reminders';$active_menu='reminders';
$db=getDB();$uid=$_SESSION['user_id'];$today=date('Y-m-d');
$overdue=$db->query("SELECT * FROM leads WHERE assigned_staff=$uid AND reminder_date<'$today' AND status='In Progress' ORDER BY reminder_date ASC");
$todayR=$db->query("SELECT * FROM leads WHERE assigned_staff=$uid AND reminder_date='$today' AND status='In Progress' ORDER BY name");
$upcoming=$db->query("SELECT * FROM leads WHERE assigned_staff=$uid AND reminder_date>'$today' AND status='In Progress' ORDER BY reminder_date ASC");
require_once '../includes/header.php';
?>
<?php if($overdue->num_rows):?>
<div class="table-card" style="border-left:4px solid var(--danger);margin-bottom:22px;">
  <div class="table-header" style="background:#fff5f5;"><h3 style="color:var(--danger);">⚠️ Overdue Reminders (<?=$overdue->num_rows?>)</h3></div>
  <table><tr><th>Name</th><th>Phone</th><th>Department</th><th>Due Date</th><th>Action</th></tr>
  <?php while($r=$overdue->fetch_assoc()):?>
  <tr><td><strong><?=htmlspecialchars($r['name'])?></strong></td><td><?=htmlspecialchars($r['phone'])?></td>
  <td style="font-size:12px;"><?=htmlspecialchars($r['department_applied'])?></td>
  <td style="color:var(--danger);font-weight:700;"><?=date('d M Y',strtotime($r['reminder_date']))?></td>
  <td><a href="<?=BASE?>/staff/log_call.php?id=<?=$r['id']?>" class="btn btn-primary btn-sm">📞 Log Call</a></td></tr>
  <?php endwhile;?>
  </table>
</div>
<?php endif;?>
<div class="table-card" style="border-left:4px solid var(--danger);margin-bottom:22px;">
  <div class="table-header" style="background:#fff5f5;"><h3 style="color:var(--danger);">🔔 Today's Reminders (<?=$todayR->num_rows?>)</h3></div>
  <?php if($todayR->num_rows==0):?><p style="padding:20px;color:var(--muted);">No reminders for today.</p>
  <?php else:?>
  <table><tr><th>Name</th><th>Phone</th><th>Department</th><th>Status</th><th>Action</th></tr>
  <?php while($r=$todayR->fetch_assoc()):?>
  <tr><td><strong><?=htmlspecialchars($r['name'])?></strong></td><td><?=htmlspecialchars($r['phone'])?></td>
  <td style="font-size:12px;"><?=htmlspecialchars($r['department_applied'])?></td>
  <td><?=sBadge($r['status'])?></td>
  <td><a href="<?=BASE?>/staff/log_call.php?id=<?=$r['id']?>" class="btn btn-primary btn-sm">📞 Log Call</a></td></tr>
  <?php endwhile;?>
  </table><?php endif;?>
</div>
<div class="table-card">
  <div class="table-header"><h3>📅 Upcoming Reminders</h3></div>
  <?php if($upcoming->num_rows==0):?><p style="padding:20px;color:var(--muted);">No upcoming reminders.</p>
  <?php else:?>
  <table><tr><th>Name</th><th>Phone</th><th>Department</th><th>Reminder Date</th><th>Action</th></tr>
  <?php while($r=$upcoming->fetch_assoc()):?>
  <tr><td><strong><?=htmlspecialchars($r['name'])?></strong></td><td><?=htmlspecialchars($r['phone'])?></td>
  <td style="font-size:12px;"><?=htmlspecialchars($r['department_applied'])?></td>
  <td><?=date('d M Y',strtotime($r['reminder_date']))?></td>
  <td><a href="<?=BASE?>/staff/log_call.php?id=<?=$r['id']?>" class="btn btn-primary btn-sm">📞 Log Call</a></td></tr>
  <?php endwhile;?>
  </table><?php endif;?>
</div>
<?php require_once '../includes/footer.php'; ?>
