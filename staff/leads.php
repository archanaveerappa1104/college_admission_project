<?php
require_once '../includes/config.php';
requireLogin('staff');
$page_title='My Leads';$active_menu='leads';
$db=getDB();$uid=$_SESSION['user_id'];$today=date('Y-m-d');
$leads=$db->query("SELECT *,(SELECT COUNT(*) FROM call_history WHERE lead_id=leads.id) calls FROM leads WHERE assigned_staff=$uid ORDER BY reminder_date ASC,created_at DESC");
require_once '../includes/header.php';
?>
<div class="table-card">
  <div class="table-header"><h3>📋 All My Leads</h3></div>
  <div style="overflow-x:auto;"><table>
  <tr><th>#</th><th>Name</th><th>Phone</th><th>Department</th><th>Source</th><th>Status</th><th>Calls</th><th>Reminder</th><th>Action</th></tr>
  <?php while($l=$leads->fetch_assoc()):$isToday=($l['reminder_date']===$today);?>
  <tr><td><?=$l['id']?></td>
  <td><strong><?=htmlspecialchars($l['name'])?></strong></td>
  <td><?=htmlspecialchars($l['phone'])?></td>
  <td style="font-size:11px;"><?=htmlspecialchars($l['department_applied'])?></td>
  <td><?=htmlspecialchars($l['source'])?></td>
  <td><?=sBadge($l['status'])?></td>
  <td><span class="call-count-badge">📞 <?=$l['calls']?></span></td>
  <td <?=$isToday?'style="color:var(--danger);font-weight:700;"':''?>><?=$l['reminder_date']?date('d M Y',strtotime($l['reminder_date'])):'—'?></td>
  <td><a href="<?=BASE?>/staff/log_call.php?id=<?=$l['id']?>" class="btn btn-primary btn-sm">📞 Log Call</a></td></tr>
  <?php endwhile;?>
  </table></div>
</div>
<?php require_once '../includes/footer.php'; ?>
