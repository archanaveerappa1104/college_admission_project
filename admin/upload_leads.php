<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='Upload Leads (CSV)';$active_menu='upload';
$db=getDB();
$done=[];$skipped=[];
if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_FILES['csv'])){
    $file=fopen($_FILES['csv']['tmp_name'],'r');
    fgetcsv($file);// skip header
    while(($row=fgetcsv($file))!==false){
        if(count($row)<7)continue;
        [$name,$phone,$email,$qual,$level,$dept,$source]=$row;
        $name=$db->real_escape_string(trim($name));
        $phone=$db->real_escape_string(trim($phone));
        $email=$db->real_escape_string(trim($email));
        $qual=$db->real_escape_string(trim($qual));
        $level=in_array(trim($level),['UG','PG'])?trim($level):'UG';
        $dept=$db->real_escape_string(trim($dept));
        $source=in_array(trim($source),['Webinar','Staff Call','Internet','Social Media','Friend Referral','Other'])?trim($source):'Other';
        $chk=$db->query("SELECT id FROM leads WHERE phone='$phone' OR email='$email'")->fetch_assoc();
        if($chk){$skipped[]=$name;continue;}
        $db->query("INSERT INTO leads(name,phone,email,qualification,course_level,department_applied,source)VALUES('$name','$phone','$email','$qual','$level','$dept','$source')");
        if($db->affected_rows)$done[]=$name;else $skipped[]=$name;
    }fclose($file);
}
require_once '../includes/header.php';
?>
<div class="form-card">
  <h3>📤 Upload Leads via CSV</h3>
  <?php if(!empty($done)):?><div class="alert alert-success">✅ Imported <?=count($done)?> leads: <?=implode(', ',array_map('htmlspecialchars',$done))?></div><?php endif;?>
  <?php if(!empty($skipped)):?><div class="alert alert-danger">⚠️ Skipped <?=count($skipped)?> (duplicate phone/email): <?=implode(', ',array_map('htmlspecialchars',$skipped))?></div><?php endif;?>
  <form method="POST" enctype="multipart/form-data">
    <div class="form-group" style="margin-bottom:16px;"><label>Select CSV File</label>
    <input type="file" name="csv" accept=".csv" required></div>
    <div style="display:flex;gap:10px;">
      <button type="submit" class="btn btn-primary">📤 Upload & Import</button>
      <a href="<?=BASE?>/admin/download_template.php" class="btn btn-outline">⬇ Download Template</a>
    </div>
  </form>
  <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
    <p style="font-size:13px;color:var(--muted);margin-bottom:8px;"><strong>CSV Format:</strong> Name, Phone, Email, Qualification, Level (UG/PG), Department, Source</p>
    <p style="font-size:13px;color:var(--muted);">Duplicate phone/email will be automatically skipped.</p>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
