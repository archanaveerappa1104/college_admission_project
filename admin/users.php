<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='Manage Users';$active_menu='users';
$db=getDB();
$users=$db->query("SELECT * FROM users ORDER BY role,name");
require_once '../includes/header.php';
?>
<div class="table-card">
  <div class="table-header"><h3>👥 All Users</h3></div>
  <div style="overflow-x:auto;"><table>
  <tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Department</th><th>Level</th><th>Status</th><th>Action</th></tr>
  <?php while($u=$users->fetch_assoc()):?>
  <tr><td><strong><?=htmlspecialchars($u['name'])?></strong></td>
  <td style="font-size:12px;"><?=htmlspecialchars($u['email'])?></td>
  <td><?=htmlspecialchars($u['phone'])?></td>
  <td><span class="badge badge-progress" style="text-transform:uppercase;"><?=$u['role']?></span></td>
  <td><?=htmlspecialchars($u['department']??'—')?></td>
  <td><?=$u['staff_level']?'<span class="level-pill">L'.(int)$u['staff_level'].'</span>':'—'?></td>
  <td><?=$u['is_active']?'<span class="badge badge-admitted">Active</span>':'<span class="badge badge-rejected">Inactive</span>'?></td>
  <td><a href="<?=BASE?>/toggle_user.php?id=<?=$u['id']?>" class="btn btn-<?=$u['is_active']?'danger':'success'?> btn-sm"><?=$u['is_active']?'Deactivate':'Activate'?></a></td>
  </tr><?php endwhile;?>
  </table></div>
</div>
<?php require_once '../includes/footer.php'; ?>
