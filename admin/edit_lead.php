<?php
require_once '../includes/config.php';
requireLogin('admin');
$id=(int)($_GET['id']??0);
$db=getDB();
$lead=$db->query("SELECT * FROM leads WHERE id=$id")->fetch_assoc();
if(!$lead){header('Location: '.BASE.'/admin/leads.php?err=Lead+not+found');exit;}
$page_title='Edit Lead #'.$id;$active_menu='leads';
$depts=getDepts();$sources=getSrc();$statuses=getSts();
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name=$db->real_escape_string(trim($_POST['name']));
    $phone=$db->real_escape_string(trim($_POST['phone']));
    $email=$db->real_escape_string(trim($_POST['email']));
    $qual=$db->real_escape_string(trim($_POST['qualification']??''));
    $level=$_POST['course_level'];
    $dept=$db->real_escape_string($_POST['department_applied']);
    $src=$_POST['source'];
    $sts=$_POST['status'];
    $rd=!empty($_POST['reminder_date'])?("'".$db->real_escape_string($_POST['reminder_date'])."'"):'NULL';
    $db->query("UPDATE leads SET name='$name',phone='$phone',email='$email',qualification='$qual',course_level='$level',department_applied='$dept',source='$src',status='$sts',reminder_date=$rd WHERE id=$id");
    logActivity($id,$_SESSION['user_id'],"Lead updated by Admin");
    header('Location: '.BASE.'/admin/leads.php?msg=Lead+updated');exit;
}
require_once '../includes/header.php';
?>
<div class="form-card">
  <h3>✏️ Edit Lead — <?=htmlspecialchars($lead['name'])?></h3>
  <form method="POST">
    <div class="form-row">
      <div class="form-group"><label>Full Name *</label><input type="text" name="name" required value="<?=htmlspecialchars($lead['name'])?>"></div>
      <div class="form-group"><label>Phone *</label><input type="text" name="phone" required value="<?=htmlspecialchars($lead['phone'])?>"></div>
      <div class="form-group"><label>Email *</label><input type="email" name="email" required value="<?=htmlspecialchars($lead['email'])?>"></div>
    </div>
    <div class="form-row">
      <div class="form-group"><label>Qualification</label><input type="text" name="qualification" value="<?=htmlspecialchars($lead['qualification'])?>"></div>
      <div class="form-group"><label>Level *</label>
        <select name="course_level" id="course_level" required onchange="updateDepts()">
          <option value="UG" <?=$lead['course_level']==='UG'?'selected':''?>>UG</option>
          <option value="PG" <?=$lead['course_level']==='PG'?'selected':''?>>PG</option>
        </select>
      </div>
      <div class="form-group"><label>Department *</label>
        <select name="department_applied" id="dept_group" required>
          <option value="<?=htmlspecialchars($lead['department_applied'])?>" selected><?=htmlspecialchars($lead['department_applied'])?></option>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group"><label>Source *</label>
        <select name="source"><?php foreach(getSrc() as $s):?><option value="<?=$s?>" <?=$lead['source']===$s?'selected':''?>><?=$s?></option><?php endforeach;?></select>
      </div>
      <div class="form-group"><label>Status</label>
        <select name="status"><?php foreach(getSts() as $s):?><option value="<?=$s?>" <?=$lead['status']===$s?'selected':''?>><?=$s?></option><?php endforeach;?></select>
      </div>
      <div class="form-group"><label>Reminder Date</label><input type="date" name="reminder_date" value="<?=htmlspecialchars($lead['reminder_date']??'')?>"></div>
    </div>
    <div style="display:flex;gap:10px;margin-top:6px;">
      <button type="submit" class="btn btn-primary">💾 Update Lead</button>
      <a href="<?=BASE?>/admin/leads.php" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>
<?php require_once '../includes/footer.php'; ?>
