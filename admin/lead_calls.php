<?php
require_once '../includes/config.php';
requireLogin('admin');
$id=(int)($_GET['id']??0);
$db=getDB();
$lead=$db->query("SELECT l.*,h.name hod_name,s.name staff_name FROM leads l LEFT JOIN users h ON l.assigned_hod=h.id LEFT JOIN users s ON l.assigned_staff=s.id WHERE l.id=$id")->fetch_assoc();
if(!$lead){header('Location: '.BASE.'/admin/leads.php?err=Not+found');exit;}
$calls=$db->query("SELECT c.*,u.name staff_name,u.staff_level FROM call_history c LEFT JOIN users u ON c.staff_id=u.id WHERE c.lead_id=$id ORDER BY c.called_at DESC");
$call_count=$db->query("SELECT COUNT(*) c FROM call_history WHERE lead_id=$id")->fetch_assoc()['c'];
$page_title='Call History — '.htmlspecialchars($lead['name']);$active_menu='leads';
$icons=['Answered'=>'✅','Not Reachable'=>'❌','Switched Off'=>'📵','Wrong Number'=>'⛔','Busy'=>'🔴','Callback Requested'=>'🔁'];
require_once '../includes/header.php';
?>
<div style="display:grid;grid-template-columns:300px 1fr;gap:22px;">
  <div>
    <div class="form-card">
      <h3>🎓 Student Details <span style="font-size:11px;background:#f8d7da;color:#721c24;padding:2px 7px;border-radius:5px;font-weight:400;">Read Only</span></h3>
      <div style="font-size:13px;display:flex;flex-direction:column;gap:9px;">
        <div><strong>Name:</strong> <?=htmlspecialchars($lead['name'])?></div>
        <div><strong>Phone:</strong> <?=htmlspecialchars($lead['phone'])?></div>
        <div><strong>Email:</strong> <?=htmlspecialchars($lead['email'])?></div>
        <div><strong>Qualification:</strong> <?=htmlspecialchars($lead['qualification'])?></div>
        <div><strong>Level:</strong> <?=$lead['course_level']?></div>
        <div><strong>Department:</strong> <?=htmlspecialchars($lead['department_applied'])?></div>
        <div><strong>Source:</strong> <?=htmlspecialchars($lead['source'])?></div>
        <div><strong>Status:</strong> <?=sBadge($lead['status'])?></div>
        <div><strong>HOD:</strong> <?=htmlspecialchars($lead['hod_name']??'—')?></div>
        <div><strong>Staff:</strong> <?=htmlspecialchars($lead['staff_name']??'—')?></div>
        <?php if($lead['reminder_date']):?><div><strong>Reminder:</strong> <?=date('d M Y',strtotime($lead['reminder_date']))?></div><?php endif;?>
      </div>
      <div style="margin-top:16px;background:var(--light);border-radius:8px;padding:14px;text-align:center;">
        <div style="font-size:11px;color:var(--muted);">TOTAL CALLS</div>
        <div style="font-size:32px;font-weight:700;color:var(--navy);"><?=$call_count?></div>
      </div>
      <div style="margin-top:12px;display:flex;gap:8px;">
        <a href="<?=BASE?>/admin/edit_lead.php?id=<?=$id?>" class="btn btn-warning btn-sm">✏️ Edit Lead</a>
        <a href="<?=BASE?>/admin/leads.php" class="btn btn-outline btn-sm">← Back</a>
      </div>
    </div>
  </div>
  <div class="table-card">
    <div class="table-header"><h3>📞 Full Call History</h3><span class="call-count-badge">Total: <?=$call_count?> calls</span></div>
    <?php if($call_count==0):?>
    <div style="padding:30px;text-align:center;color:var(--muted);">No calls logged yet for this lead.</div>
    <?php else:?>
    <div class="call-timeline">
      <?php while($c=$calls->fetch_assoc()):$icon=$icons[$c['call_status']]??'📞';?>
      <div class="call-item">
        <div class="call-dot"><?=$icon?></div>
        <div class="call-body">
          <div class="call-meta"><?=cBadge($c['call_status'])?> &nbsp; by <strong><?=htmlspecialchars($c['staff_name'])?></strong> <span class="level-pill">L<?=(int)$c['staff_level']?></span> &nbsp; <?=date('d M Y, h:i A',strtotime($c['called_at']))?></div>
          <div class="call-remark"><?=nl2br(htmlspecialchars($c['remarks']))?></div>
        </div>
      </div>
      <?php endwhile;?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
