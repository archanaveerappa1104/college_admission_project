<?php
require_once '../includes/config.php';
requireLogin('admin');
$id=(int)($_GET['id']??0);
$db=getDB();
$hod=$db->query("SELECT * FROM users WHERE id=$id AND role='hod'")->fetch_assoc();
if(!$hod){header('Location: '.BASE.'/admin/hod_report.php');exit;}
$page_title='HOD Detail — '.htmlspecialchars($hod['name']);$active_menu='hod_report';
$staff=$db->query("SELECT u.*,COUNT(l.id) leads,SUM(l.status='Admitted') admitted,(SELECT COUNT(*) FROM call_history WHERE staff_id=u.id) calls FROM users u LEFT JOIN leads l ON l.assigned_staff=u.id WHERE u.role='staff' AND u.department='".$db->real_escape_string($hod['department'])."' GROUP BY u.id ORDER BY u.staff_level");
require_once '../includes/header.php';
?>
<div class="form-card" style="margin-bottom:22px;">
  <h3>👩‍💼 HOD: <?=htmlspecialchars($hod['name'])?> — <?=htmlspecialchars($hod['department'])?></h3>
</div>
<div class="table-card">
  <div class="table-header"><h3>👤 Staff Performance</h3><a href="<?=BASE?>/admin/hod_report.php" class="btn btn-outline btn-sm">← Back</a></div>
  <table><tr><th>Staff Name</th><th>Email</th><th>Level</th><th>Leads</th><th>Admitted</th><th>Calls</th><th>Rate%</th></tr>
  <?php while($s=$staff->fetch_assoc()):$rate=$s['leads']>0?round($s['admitted']/$s['leads']*100):0;?>
  <tr><td><strong><?=htmlspecialchars($s['name'])?></strong></td><td style="font-size:12px;"><?=htmlspecialchars($s['email'])?></td>
  <td><span class="level-pill">L<?=(int)$s['staff_level']?></span></td>
  <td><?=$s['leads']?></td><td style="color:var(--success);font-weight:700;"><?=$s['admitted']?></td>
  <td><span class="call-count-badge">📞 <?=$s['calls']?></span></td><td><?=$rate?>%</td></tr>
  <?php endwhile;?>
  </table>
</div>
<?php require_once '../includes/footer.php'; ?>
