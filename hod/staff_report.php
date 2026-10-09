<?php
require_once '../includes/config.php';
requireLogin('hod');
$page_title='Staff Report';$active_menu='staff_report';
$db=getDB();$uid=$_SESSION['user_id'];$dept=$_SESSION['dept'];
$staff=$db->query("SELECT u.*,COUNT(l.id) leads,SUM(l.status='Admitted') admitted,SUM(l.status='In Progress') progress,(SELECT COUNT(*) FROM call_history WHERE staff_id=u.id) calls FROM users u LEFT JOIN leads l ON l.assigned_staff=u.id WHERE u.role='staff' AND u.department='".$db->real_escape_string($dept)."' GROUP BY u.id ORDER BY u.staff_level");
require_once '../includes/header.php';
?>
<div class="table-card">
  <div class="table-header"><h3>📈 Staff Performance Report — <?=htmlspecialchars($dept)?></h3></div>
  <table><tr><th>Staff</th><th>Level</th><th>Leads</th><th>Admitted</th><th>In Progress</th><th>Calls</th><th>Rate%</th><th>Logs</th></tr>
  <?php while($s=$staff->fetch_assoc()):$rate=$s['leads']>0?round($s['admitted']/$s['leads']*100):0;?>
  <tr><td><strong><?=htmlspecialchars($s['name'])?></strong></td>
  <td><span class="level-pill">L<?=(int)$s['staff_level']?></span></td>
  <td><?=$s['leads']?></td><td style="color:var(--success);font-weight:700;"><?=$s['admitted']?></td>
  <td style="color:var(--warning);"><?=$s['progress']?></td>
  <td><span class="call-count-badge">📞 <?=$s['calls']?></span></td>
  <td><?=$rate?>%</td>
  <td><a href="<?=BASE?>/hod/call_logs.php?staff_id=<?=$s['id']?>" class="btn btn-info btn-sm">📞 Logs</a></td></tr>
  <?php endwhile;?>
  </table>
</div>
<?php require_once '../includes/footer.php'; ?>
