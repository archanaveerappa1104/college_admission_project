<?php
require_once '../includes/config.php';
requireLogin('hod');
$page_title='My Leads';$active_menu='leads';
$db=getDB();$uid=$_SESSION['user_id'];
$leads=$db->query("SELECT l.*,s.name staff_name,(SELECT COUNT(*) FROM call_history WHERE lead_id=l.id) calls FROM leads l LEFT JOIN users s ON l.assigned_staff=s.id WHERE l.assigned_hod=$uid ORDER BY l.created_at DESC");
require_once '../includes/header.php';
?>
<div class="table-card">
  <div class="table-header"><h3>📋 My Leads</h3><a href="<?=BASE?>/hod/assign_staff.php" class="btn btn-primary btn-sm">🔗 Assign Staff</a></div>
  <div style="overflow-x:auto;"><table>
  <tr><th>#</th><th>Name</th><th>Phone</th><th>Level</th><th>Department</th><th>Source</th><th>Status</th><th>Staff</th><th>Calls</th><th>Action</th></tr>
  <?php while($l=$leads->fetch_assoc()):?>
  <tr><td><?=$l['id']?></td><td><strong><?=htmlspecialchars($l['name'])?></strong></td>
  <td><?=htmlspecialchars($l['phone'])?></td><td><?=$l['course_level']?></td>
  <td style="font-size:11px;"><?=htmlspecialchars($l['department_applied'])?></td>
  <td><?=htmlspecialchars($l['source'])?></td><td><?=sBadge($l['status'])?></td>
  <td style="font-size:12px;"><?=htmlspecialchars($l['staff_name']??'—')?></td>
  <td><span class="call-count-badge">📞 <?=$l['calls']?></span></td>
  <td><a href="<?=BASE?>/hod/staff_calls.php?id=<?=$l['id']?>" class="btn btn-info btn-sm">📞 Calls</a></td></tr>
  <?php endwhile;?>
  </table></div>
</div>
<?php require_once '../includes/footer.php'; ?>
