<?php
require_once '../includes/config.php';
requireLogin('hod');
$id=(int)($_GET['id']??0);
$db=getDB();$uid=$_SESSION['user_id'];
$lead=$db->query("SELECT l.*,s.name staff_name,s.staff_level FROM leads l LEFT JOIN users s ON l.assigned_staff=s.id WHERE l.id=$id AND l.assigned_hod=$uid")->fetch_assoc();
if(!$lead){header('Location: '.BASE.'/hod/leads.php');exit;}
$calls=$db->query("SELECT c.*,u.name sname,u.staff_level FROM call_history c LEFT JOIN users u ON c.staff_id=u.id WHERE c.lead_id=$id ORDER BY c.called_at DESC");
$cnt=$db->query("SELECT COUNT(*) c FROM call_history WHERE lead_id=$id")->fetch_assoc()['c'];
$page_title='Call History — '.htmlspecialchars($lead['name']);$active_menu='leads';
$icons=['Answered'=>'✅','Not Reachable'=>'❌','Switched Off'=>'📵','Wrong Number'=>'⛔','Busy'=>'🔴','Callback Requested'=>'🔁'];
require_once '../includes/header.php';
?>
<div style="display:grid;grid-template-columns:290px 1fr;gap:22px;">
  <div class="form-card">
    <h3>🎓 Student</h3>
    <div style="font-size:13px;display:flex;flex-direction:column;gap:8px;">
      <div><strong>Name:</strong> <?=htmlspecialchars($lead['name'])?></div>
      <div><strong>Phone:</strong> <?=htmlspecialchars($lead['phone'])?></div>
      <div><strong>Dept:</strong> <?=htmlspecialchars($lead['department_applied'])?></div>
      <div><strong>Status:</strong> <?=sBadge($lead['status'])?></div>
      <div><strong>Staff:</strong> <?=htmlspecialchars($lead['staff_name']??'—')?> <?=$lead['staff_level']?'<span class="level-pill">L'.(int)$lead['staff_level'].'</span>':''?></div>
    </div>
    <div style="margin-top:14px;background:var(--light);border-radius:8px;padding:12px;text-align:center;">
      <div style="font-size:11px;color:var(--muted);">TOTAL CALLS</div>
      <div style="font-size:28px;font-weight:700;color:var(--navy);"><?=$cnt?></div>
    </div>
    <a href="<?=BASE?>/hod/leads.php" class="btn btn-outline btn-sm" style="margin-top:12px;">← Back</a>
  </div>
  <div class="table-card">
    <div class="table-header"><h3>📞 Call History (<?=$cnt?>)</h3></div>
    <div class="call-timeline">
    <?php if($cnt==0):?><p style="padding:20px;color:var(--muted);">No calls yet.</p><?php endif;?>
    <?php while($c=$calls->fetch_assoc()):$icon=$icons[$c['call_status']]??'📞';?>
    <div class="call-item">
      <div class="call-dot"><?=$icon?></div>
      <div class="call-body">
        <div class="call-meta"><?=cBadge($c['call_status'])?> &nbsp; by <strong><?=htmlspecialchars($c['sname'])?></strong> <span class="level-pill">L<?=(int)$c['staff_level']?></span> &nbsp; <?=date('d M Y, h:i A',strtotime($c['called_at']))?></div>
        <div class="call-remark"><?=nl2br(htmlspecialchars($c['remarks']))?></div>
      </div>
    </div>
    <?php endwhile;?>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
