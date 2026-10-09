<?php
require_once '../includes/config.php';
requireLogin('hod');
$page_title='Assign Staff to Leads';$active_menu='assign_staff';
$db=getDB();$uid=$_SESSION['user_id'];$dept=$_SESSION['dept'];
if($_SERVER['REQUEST_METHOD']==='POST'&&!empty($_POST['lead_ids'])&&!empty($_POST['staff_id'])){
    $sid=(int)$_POST['staff_id'];
    foreach($_POST['lead_ids'] as $lid){
        $lid=(int)$lid;
        $db->query("UPDATE leads SET assigned_staff=$sid WHERE id=$lid AND assigned_hod=$uid");
        logActivity($lid,$uid,"Assigned to Staff ID $sid");
    }
    header('Location: '.BASE.'/hod/assign_staff.php?msg='.count($_POST['lead_ids']).' leads assigned');exit;
}
$staff=$db->query("SELECT * FROM users WHERE role='staff' AND department='".$db->real_escape_string($dept)."' AND is_active=1 ORDER BY staff_level");
$leads=$db->query("SELECT * FROM leads WHERE assigned_hod=$uid ORDER BY created_at DESC");
require_once '../includes/header.php';
?>
<div class="table-card">
  <div class="table-header"><h3>🔗 Assign Leads to Staff</h3></div>
  <form method="POST">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
      <select name="staff_id" required style="padding:9px 12px;border:2px solid var(--border);border-radius:8px;font-size:13px;min-width:280px;">
        <option value="">-- Select Staff --</option>
        <?php while($s=$staff->fetch_assoc()):?>
        <option value="<?=$s['id']?>"><?=htmlspecialchars($s['name'])?> — Level <?=(int)$s['staff_level']?></option>
        <?php endwhile;?>
      </select>
      <button type="submit" class="btn btn-primary">🔗 Assign Selected</button>
    </div>
    <div style="overflow-x:auto;"><table>
    <tr><th><input type="checkbox" id="chk-all" onclick="document.querySelectorAll('.lid').forEach(c=>c.checked=this.checked)"></th>
    <th>Name</th><th>Phone</th><th>Level</th><th>Department</th><th>Status</th><th>Current Staff</th></tr>
    <?php $db2=getDB();$leads2=$db2->query("SELECT l.*,s.name staff_name FROM leads l LEFT JOIN users s ON l.assigned_staff=s.id WHERE l.assigned_hod=$uid ORDER BY l.id");
    while($l=$leads2->fetch_assoc()):?>
    <tr><td><input type="checkbox" class="lid" name="lead_ids[]" value="<?=$l['id']?>"></td>
    <td><strong><?=htmlspecialchars($l['name'])?></strong></td>
    <td><?=htmlspecialchars($l['phone'])?></td><td><?=$l['course_level']?></td>
    <td style="font-size:11px;"><?=htmlspecialchars($l['department_applied'])?></td>
    <td><?=sBadge($l['status'])?></td>
    <td style="font-size:12px;"><?=htmlspecialchars($l['staff_name']??'—')?></td></tr>
    <?php endwhile;?>
    </table></div>
  </form>
</div>
<?php require_once '../includes/footer.php'; ?>
