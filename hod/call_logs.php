<?php
require_once '../includes/config.php';
requireLogin('hod');
$page_title='Call Logs';$active_menu='call_logs';
$db=getDB();$uid=$_SESSION['user_id'];
$sid=isset($_GET['staff_id'])?(int)$_GET['staff_id']:0;
$where="l.assigned_hod=$uid";
if($sid)$where.=" AND c.staff_id=$sid";
$calls=$db->query("SELECT c.*,l.name lname,l.phone,u.name sname,u.staff_level FROM call_history c LEFT JOIN leads l ON c.lead_id=l.id LEFT JOIN users u ON c.staff_id=u.id WHERE $where ORDER BY c.called_at DESC LIMIT 50");
$icons=['Answered'=>'✅','Not Reachable'=>'❌','Switched Off'=>'📵','Wrong Number'=>'⛔','Busy'=>'🔴','Callback Requested'=>'🔁'];
require_once '../includes/header.php';
?>
<div class="table-card">
  <div class="table-header"><h3>📞 All Call Logs <?=$sid?'— Filtered by Staff':''?></h3>
  <?php if($sid):?><a href="<?=BASE?>/hod/call_logs.php" class="btn btn-outline btn-sm">✖ Clear Filter</a><?php endif;?>
  </div>
  <div class="call-timeline">
  <?php while($c=$calls->fetch_assoc()):$icon=$icons[$c['call_status']]??'📞';?>
  <div class="call-item">
    <div class="call-dot"><?=$icon?></div>
    <div class="call-body">
      <div class="call-meta"><?=cBadge($c['call_status'])?> &nbsp; by <strong><?=htmlspecialchars($c['sname'])?></strong> <span class="level-pill">L<?=(int)$c['staff_level']?></span> → Student: <strong><?=htmlspecialchars($c['lname'])?></strong> (<?=htmlspecialchars($c['phone'])?>) &nbsp; <?=date('d M Y, h:i A',strtotime($c['called_at']))?></div>
      <div class="call-remark"><?=nl2br(htmlspecialchars($c['remarks']))?></div>
    </div>
  </div>
  <?php endwhile;?>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
