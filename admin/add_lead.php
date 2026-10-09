<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='Add New Lead';$active_menu='add_lead';
$depts=getDepts();$sources=getSrc();$statuses=getSts();
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $db=getDB();
    $name=$db->real_escape_string(trim($_POST['name']));
    $phone=$db->real_escape_string(trim($_POST['phone']));
    $email=$db->real_escape_string(trim($_POST['email']));
    $qual=$db->real_escape_string(trim($_POST['qualification']??''));
    $level=$_POST['course_level'];
    $dept=$db->real_escape_string($_POST['department_applied']);
    $src=$_POST['source'];
    $sts=$_POST['status'];
    $rem=!empty($_POST['reminder_date'])?$db->real_escape_string($_POST['reminder_date']):'NULL';
    $st=$db->prepare("INSERT INTO leads(name,phone,email,qualification,course_level,department_applied,source,status,reminder_date)VALUES(?,?,?,?,?,?,?,?,?)");
    if($st){
        $rd=!empty($_POST['reminder_date'])?$_POST['reminder_date']:null;
        $st->bind_param("sssssssss",$name,$phone,$email,$qual,$level,$dept,$src,$sts,$rd);
        if($st->execute()){logActivity($db->insert_id,$_SESSION['user_id'],"Lead added by Admin");header('Location: '.BASE.'/admin/leads.php?msg=Lead+added');exit;}
        else{$err='Error: '.$st->error;}
    }else{$err='DB error: '.$db->error;}
}
require_once '../includes/header.php';
?>
<div class="form-card">
  <h3>➕ Add New Student Lead</h3>
  <?php if($err):?><div class="alert alert-danger">❌ <?=htmlspecialchars($err)?></div><?php endif;?>
  <form method="POST">
    <div class="form-row">
      <div class="form-group"><label>Full Name *</label><input type="text" name="name" required value="<?=htmlspecialchars($_POST['name']??'')?>"></div>
      <div class="form-group"><label>Phone Number *</label><input type="text" name="phone" required pattern="\d{10}" value="<?=htmlspecialchars($_POST['phone']??'')?>"></div>
      <div class="form-group"><label>Email ID *</label><input type="email" name="email" required value="<?=htmlspecialchars($_POST['email']??'')?>"></div>
    </div>
    <div class="form-row">
      <div class="form-group"><label>Qualification</label><input type="text" name="qualification" placeholder="e.g. B.Sc Computer Science" value="<?=htmlspecialchars($_POST['qualification']??'')?>"></div>
      <div class="form-group"><label>Applying For (Level) *</label>
        <select name="course_level" id="course_level" required onchange="updateDepts()">
          <option value="">-- Select --</option>
          <option value="UG" <?=($_POST['course_level']??'')==='UG'?'selected':''?>>UG (Under Graduate)</option>
          <option value="PG" <?=($_POST['course_level']??'')==='PG'?'selected':''?>>PG (Post Graduate)</option>
        </select>
      </div>
      <div class="form-group"><label>Department Applied *</label>
        <select name="department_applied" id="dept_group" required>
          <option value="">-- Select level first --</option>
          <?php if(!empty($_POST['department_applied'])):?><option value="<?=htmlspecialchars($_POST['department_applied'])?>" selected><?=htmlspecialchars($_POST['department_applied'])?></option><?php endif;?>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group"><label>Source *</label>
        <select name="source" required>
          <?php foreach($sources as $s):?><option value="<?=$s?>" <?=($_POST['source']??'')===$s?'selected':''?>><?=$s?></option><?php endforeach;?>
        </select>
      </div>
      <div class="form-group"><label>Status</label>
        <select name="status">
          <?php foreach($statuses as $s):?><option value="<?=$s?>" <?=($_POST['status']??'In Progress')===$s?'selected':''?>><?=$s?></option><?php endforeach;?>
        </select>
      </div>
      <div class="form-group"><label>Follow-up Reminder Date</label><input type="date" name="reminder_date" value="<?=htmlspecialchars($_POST['reminder_date']??'')?>"></div>
    </div>
    <div style="display:flex;gap:10px;margin-top:6px;">
      <button type="submit" class="btn btn-primary">💾 Save Lead</button>
      <a href="<?=BASE?>/admin/leads.php" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>
<?php require_once '../includes/footer.php'; ?>
