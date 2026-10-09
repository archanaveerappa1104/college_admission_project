<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='Department & Staff Levels';$active_menu='dept_staff';
$db=getDB();
$depts=['Computer Science','PG Department'];
require_once '../includes/header.php';
?>
<?php foreach($depts as $dept):
$staff=$db->query("SELECT * FROM users WHERE role='staff' AND department='".$db->real_escape_string($dept)."' ORDER BY staff_level");
?>
<div class="table-card" style="margin-bottom:22px;">
  <div class="table-header"><h3>🏢 <?=htmlspecialchars($dept)?></h3></div>
  <table><tr><th>Staff Name</th><th>Email</th><th>Phone</th><th>Level</th><th>Status</th></tr>
  <?php while($s=$staff->fetch_assoc()):?>
  <tr><td><strong><?=htmlspecialchars($s['name'])?></strong></td>
  <td style="font-size:12px;"><?=htmlspecialchars($s['email'])?></td>
  <td><?=htmlspecialchars($s['phone'])?></td>
  <td><span class="level-pill">Level <?=(int)$s['staff_level']?></span></td>
  <td><?=$s['is_active']?'<span class="badge badge-admitted">Active</span>':'<span class="badge badge-rejected">Inactive</span>'?></td>
  </tr><?php endwhile;?>
  </table>
</div>
<?php endforeach; require_once '../includes/footer.php'; ?>
