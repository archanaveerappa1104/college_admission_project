<?php
require_once '../includes/config.php';
requireLogin('staff');
$id=(int)($_GET['id']??0);
$db=getDB();$uid=$_SESSION['user_id'];
$lead=$db->query("SELECT * FROM leads WHERE id=$id AND assigned_staff=$uid")->fetch_assoc();
if(!$lead){header('Location: '.BASE.'/staff/leads.php?err=Not+found');exit;}
$page_title='Log Call — '.htmlspecialchars($lead['name']);$active_menu='leads';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $csts=$_POST['call_status'];
    $rem=$db->real_escape_string($_POST['remarks']??'');
    $newsts=$_POST['new_status'];
    $rd=!empty($_POST['reminder_date'])?$_POST['reminder_date']:null;
    $st=$db->prepare("INSERT INTO call_history(lead_id,staff_id,call_status,remarks)VALUES(?,?,?,?)");
    $st->bind_param("iiss",$id,$uid,$csts,$rem);$st->execute();
    $rd_sql=$rd?("'".$db->real_escape_string($rd)."'"):'NULL';
    $db->query("UPDATE leads SET status='".$db->real_escape_string($newsts)."',reminder_date=$rd_sql WHERE id=$id");
    logActivity($id,$uid,"Call logged: $csts — Status changed to $newsts");
    header('Location: '.BASE.'/staff/log_call.php?id='.$id.'&msg=Call+logged+successfully');exit;
}
$calls=$db->query("SELECT c.*,u.name sname,u.staff_level FROM call_history c LEFT JOIN users u ON c.staff_id=u.id WHERE c.lead_id=$id ORDER BY c.called_at DESC");
$cnt=$db->query("SELECT COUNT(*) c FROM call_history WHERE lead_id=$id")->fetch_assoc()['c'];
$icons=['Answered'=>'✅','Not Reachable'=>'❌','Switched Off'=>'📵','Wrong Number'=>'⛔','Busy'=>'🔴','Callback Requested'=>'🔁'];
require_once '../includes/header.php';
?>
<div style="display:grid;grid-template-columns:340px 1fr;gap:22px;">
  <div>
    <div class="form-card" style="margin-bottom:16px;">
      <h3>🎓 Student Info <span style="font-size:11px;background:#f8d7da;color:#721c24;padding:2px 7px;border-radius:5px;font-weight:400;">Read Only</span></h3>
      <div style="font-size:13px;display:grid;grid-template-columns:1fr 1fr;gap:8px;">
        <div><span style="color:var(--muted);font-size:10px;display:block;text-transform:uppercase;">Name</span><strong><?=htmlspecialchars($lead['name'])?></strong></div>
        <div><span style="color:var(--muted);font-size:10px;display:block;text-transform:uppercase;">Phone</span><?=htmlspecialchars($lead['phone'])?></div>
        <div style="grid-column:span 2;"><span style="color:var(--muted);font-size:10px;display:block;text-transform:uppercase;">Email</span><?=htmlspecialchars($lead['email'])?></div>
        <div><span style="color:var(--muted);font-size:10px;display:block;text-transform:uppercase;">Level</span><?=$lead['course_level']?></div>
        <div><span style="color:var(--muted);font-size:10px;display:block;text-transform:uppercase;">Dept</span><?=htmlspecialchars($lead['department_applied'])?></div>
        <div style="grid-column:span 2;"><span style="color:var(--muted);font-size:10px;display:block;text-transform:uppercase;">Source</span><?=htmlspecialchars($lead['source'])?></div>
      </div>
    </div>
    <div class="form-card">
      <h3>📞 Log This Call</h3>
      <form method="POST">
        <div class="form-group" style="margin-bottom:14px;"><label>Call Result *</label>
          <select name="call_status" required>
            <option value="">-- Select --</option>
            <?php foreach(getCallSts() as $cs):?><option value="<?=$cs?>"><?=$cs?></option><?php endforeach;?>
          </select>
        </div>
        <div class="form-group" style="margin-bottom:14px;"><label>Update Lead Status *</label>
          <select name="new_status" required>
            <?php foreach(getSts() as $s):?><option value="<?=$s?>" <?=$lead['status']===$s?'selected':''?>><?=$s?></option><?php endforeach;?>
          </select>
        </div>
        <div class="form-group" style="margin-bottom:14px;"><label>Remarks *</label>
          <textarea name="remarks" rows="3" required placeholder="What did the student say..."></textarea>
        </div>
        <div class="form-group" style="margin-bottom:18px;"><label>Next Follow-up Reminder Date</label>
          <input type="date" name="reminder_date" value="<?=htmlspecialchars($lead['reminder_date']??'')?>">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">💾 Save Call Log</button>
      </form>
    </div>
  </div>
  <div class="table-card">
    <div class="table-header"><h3>📞 Full Call History</h3><span class="call-count-badge">Total: <?=$cnt?> calls</span></div>
    <?php if($cnt==0):?><div style="padding:30px;text-align:center;color:var(--muted);">No calls logged yet. Log your first call above.</div>
    <?php else:?>
    <div class="call-timeline">
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
    <?php endif;?>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
