<?php
require_once '../includes/config.php';
requireLogin('hod');
$page_title='HOD Dashboard';$active_menu='dashboard';
$db=getDB();$uid=$_SESSION['user_id'];
$stats=['total'=>0,'admitted'=>0,'rejected'=>0,'progress'=>0,'calls'=>0];
$r=$db->query("SELECT status,COUNT(*) c FROM leads WHERE assigned_hod=$uid GROUP BY status");
while($row=$r->fetch_assoc()){
    $stats['total']+=$row['c'];
    if($row['status']==='Admitted')$stats['admitted']+=$row['c'];
    elseif($row['status']==='Rejected')$stats['rejected']+=$row['c'];
    else $stats['progress']+=$row['c'];
}
$cr=$db->query("SELECT COUNT(*) c FROM call_history c LEFT JOIN leads l ON c.lead_id=l.id WHERE l.assigned_hod=$uid");
$stats['calls']=$cr->fetch_assoc()['c']??0;
$staff=$db->query("SELECT u.*,COUNT(l.id) leads,SUM(l.status='Admitted') admitted,(SELECT COUNT(*) FROM call_history WHERE staff_id=u.id) calls FROM users u LEFT JOIN leads l ON l.assigned_staff=u.id WHERE u.role='staff' AND u.department='".$db->real_escape_string($_SESSION['dept'])."' GROUP BY u.id ORDER BY u.staff_level");
$recent=$db->query("SELECT l.*,s.name staff_name FROM leads l LEFT JOIN users s ON l.assigned_staff=s.id WHERE l.assigned_hod=$uid ORDER BY l.created_at DESC LIMIT 5");
require_once '../includes/header.php';
?>
<div class="stats-grid">
  <div class="stat-card"><div class="stat-label">My Total Leads</div><div class="stat-value"><?=$stats['total']?></div></div>
  <div class="stat-card green"><div class="stat-label">Admitted</div><div class="stat-value"><?=$stats['admitted']?></div></div>
  <div class="stat-card yellow"><div class="stat-label">In Progress</div><div class="stat-value"><?=$stats['progress']?></div></div>
  <div class="stat-card red"><div class="stat-label">Rejected</div><div class="stat-value"><?=$stats['rejected']?></div></div>
  <div class="stat-card blue"><div class="stat-label">Total Calls</div><div class="stat-value"><?=$stats['calls']?></div></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-bottom:22px;">
  <div class="table-card">
    <div class="table-header"><h3>👤 Staff Performance</h3><a href="<?=BASE?>/hod/assign_staff.php" class="btn btn-primary btn-sm">🔗 Assign</a></div>
    <table><tr><th>Staff</th><th>Level</th><th>Leads</th><th>Admitted</th><th>Calls</th><th>Rate%</th></tr>
    <?php while($s=$staff->fetch_assoc()):$rate=$s['leads']>0?round($s['admitted']/$s['leads']*100):0;?>
    <tr><td><strong><?=htmlspecialchars($s['name'])?></strong></td>
    <td><span class="level-pill">L<?=(int)$s['staff_level']?></span></td>
    <td><?=$s['leads']?></td><td style="color:var(--success);font-weight:700;"><?=$s['admitted']?></td>
    <td><span class="call-count-badge">📞 <?=$s['calls']?></span></td>
    <td><?=$rate?>%</td></tr>
    <?php endwhile;?>
    </table>
  </div>
  <div class="table-card">
    <div class="table-header"><h3>📋 Recent Leads</h3><a href="<?=BASE?>/hod/leads.php" class="btn btn-outline btn-sm">All</a></div>
    <table><tr><th>Name</th><th>Dept</th><th>Status</th><th>Staff</th></tr>
    <?php while($l=$recent->fetch_assoc()):?>
    <tr><td><strong><?=htmlspecialchars($l['name'])?></strong></td>
    <td style="font-size:11px;"><?=htmlspecialchars($l['department_applied'])?></td>
    <td><?=sBadge($l['status'])?></td>
    <td style="font-size:12px;"><?=htmlspecialchars($l['staff_name']??'—')?></td></tr>
    <?php endwhile;?>
    </table>
  </div>
</div>
<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/footer.php'; ?>
