<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='Assign Leads to HOD';$active_menu='assign_hod';
$db=getDB();

// Handle per-row assignment
if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['assignments'])){
    $count=0;
    foreach($_POST['assignments'] as $lid => $hod_id){
        $lid=(int)$lid; $hod_id=(int)$hod_id;
        if($lid>0 && $hod_id>0){
            $db->query("UPDATE leads SET assigned_hod=$hod_id,assigned_staff=NULL WHERE id=$lid");
            logActivity($lid,$_SESSION['user_id'],"Assigned to HOD ID $hod_id");
            $count++;
        }
    }
    header('Location: '.BASE.'/admin/assign_hod.php?msg='.$count.' leads assigned to their HODs');exit;
}

$depts_raw=getDepts();
$filter_dept = $_GET['dept'] ?? '';
$filter_level = $_GET['level'] ?? '';
$where = '1=1';
if($filter_dept) $where .= " AND l.department_applied='".$db->real_escape_string($filter_dept)."'";
if($filter_level) $where .= " AND l.course_level='".$db->real_escape_string($filter_level)."'";

$unassigned=$db->query("SELECT l.* FROM leads l WHERE l.assigned_hod IS NULL AND $where ORDER BY l.department_applied,l.created_at DESC");

// Build HOD list for JS auto-match
$hods_result=$db->query("SELECT id,name,department FROM users WHERE role='hod' AND is_active=1 ORDER BY department");
$hods_list=[];
while($h=$hods_result->fetch_assoc()) $hods_list[]=$h;

$assigned=$db->query("SELECT l.*,h.name hod_name FROM leads l LEFT JOIN users h ON l.assigned_hod=h.id WHERE l.assigned_hod IS NOT NULL ORDER BY l.created_at DESC LIMIT 15");

require_once '../includes/header.php';
?>

<!-- MODAL -->
<div id="detail-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:16px;padding:32px;max-width:520px;width:90%;position:relative;box-shadow:0 20px 60px rgba(0,0,0,.3);">
    <button onclick="closeModal()" style="position:absolute;top:14px;right:16px;background:none;border:none;font-size:22px;cursor:pointer;color:#718096;">✕</button>
    <h3 style="color:var(--navy);margin-bottom:18px;font-size:17px;">🎓 Student Details</h3>
    <div id="modal-content" style="font-size:14px;line-height:2;color:#2d3748;"></div>
    <div style="margin-top:20px;display:flex;gap:10px;flex-wrap:wrap;">
      <button onclick="copyDetails()" class="btn btn-primary">📋 Copy Details</button>
      <a id="wa-link" href="#" target="_blank" class="btn btn-success">📲 WhatsApp</a>
      <a id="mail-link" href="#" class="btn btn-outline">📧 Email</a>
    </div>
    <div id="copy-msg" style="display:none;margin-top:10px;background:#d4edda;color:#155724;padding:8px 12px;border-radius:8px;font-size:13px;">✅ Copied! Paste in WhatsApp or Email.</div>
  </div>
</div>

<!-- FILTER -->
<div class="table-card" style="margin-bottom:22px;">
  <div class="table-header"><h3>🔍 Filter by Department</h3></div>
  <div style="padding:14px 18px;">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
      <div style="display:flex;flex-direction:column;gap:5px;">
        <label style="font-size:11px;font-weight:700;color:#4a5568;text-transform:uppercase;letter-spacing:.4px;">Level</label>
        <select name="level" style="padding:9px 14px;border:2px solid var(--border);border-radius:8px;font-size:13px;min-width:150px;">
          <option value="">All (UG + PG)</option>
          <option value="UG" <?=($filter_level==='UG')?'selected':''?>>UG Only</option>
          <option value="PG" <?=($filter_level==='PG')?'selected':''?>>PG Only</option>
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;">
        <label style="font-size:11px;font-weight:700;color:#4a5568;text-transform:uppercase;letter-spacing:.4px;">Department</label>
        <select name="dept" style="padding:9px 14px;border:2px solid var(--border);border-radius:8px;font-size:13px;min-width:300px;">
          <option value="">All Departments</option>
          <optgroup label="── B.E ──">
            <?php foreach($depts_raw['UG']['B.E'] as $d):?>
            <option value="<?=$d?>" <?=$filter_dept===$d?'selected':''?>><?=$d?></option>
            <?php endforeach;?>
          </optgroup>
          <optgroup label="── B.Tech ──">
            <?php foreach($depts_raw['UG']['B.Tech'] as $d):?>
            <option value="<?=$d?>" <?=$filter_dept===$d?'selected':''?>><?=$d?></option>
            <?php endforeach;?>
          </optgroup>
          <optgroup label="── B.Arch ──">
            <?php foreach($depts_raw['UG']['B.Arch'] as $d):?>
            <option value="<?=$d?>" <?=$filter_dept===$d?'selected':''?>><?=$d?></option>
            <?php endforeach;?>
          </optgroup>
          <optgroup label="── PG ──">
            <?php foreach($depts_raw['PG']['PG'] as $d):?>
            <option value="<?=$d?>" <?=$filter_dept===$d?'selected':''?>><?=$d?></option>
            <?php endforeach;?>
          </optgroup>
        </select>
      </div>
      <button type="submit" class="btn btn-primary" style="margin-top:22px;">🔍 Filter</button>
      <a href="<?=BASE?>/admin/assign_hod.php" class="btn btn-outline" style="margin-top:22px;">✖ Clear</a>
    </form>
  </div>
</div>

<!-- ASSIGN TABLE — each row has own HOD dropdown -->
<div class="table-card" style="margin-bottom:22px;">
  <div class="table-header">
    <h3>🔗 Assign Each Student to HOD
      <?php if($filter_dept):?><span style="font-size:12px;background:#e8f4fd;color:var(--navy);padding:3px 10px;border-radius:10px;margin-left:8px;"><?=htmlspecialchars($filter_dept)?></span><?php endif;?>
    </h3>
    <span style="font-size:12px;color:var(--muted);"><?=$unassigned?$unassigned->num_rows:0?> students</span>
  </div>

  <?php if($unassigned && $unassigned->num_rows > 0):?>

  <!-- Info box -->
  <div style="margin:12px 18px;background:#e8f4fd;border-left:4px solid var(--navy);padding:12px 16px;border-radius:0 8px 8px 0;font-size:13px;color:var(--navy);">
    💡 <strong>Each student has their own HOD dropdown.</strong> Select the correct HOD for each student based on their department, then click <strong>"Assign All"</strong> at the bottom.
    <br>⚡ <strong>Auto-match</strong> button will automatically suggest the correct HOD based on the student's department.
  </div>

  <form method="POST">
    <div style="padding:12px 18px;border-bottom:1px solid var(--border);display:flex;gap:12px;flex-wrap:wrap;align-items:center;background:#f8fafb;">
      <button type="button" class="btn btn-warning" onclick="autoMatch()">⚡ Auto-Match HODs by Department</button>
      <button type="submit" class="btn btn-primary">🔗 Assign All to Selected HODs</button>
    </div>

    <div style="overflow-x:auto;"><table>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Level</th>
        <th>Department</th>
        <th>Source</th>
        <th style="min-width:280px;">Assign to HOD</th>
        <th>Share</th>
      </tr>
      <?php
      $hods_json = json_encode($hods_list);
      $row=0;
      while($l=$unassigned->fetch_assoc()):
        $row++;
        $details = "🎓 *STUDENT ENQUIRY DETAILS*\n";
        $details .= "━━━━━━━━━━━━━━━━━━━━\n";
        $details .= "👤 Name       : ".$l['name']."\n";
        $details .= "📞 Phone      : ".$l['phone']."\n";
        $details .= "📧 Email      : ".$l['email']."\n";
        $details .= "🎓 Qual.      : ".($l['qualification']??'—')."\n";
        $details .= "📚 Level      : ".$l['course_level']."\n";
        $details .= "🏫 Department : ".$l['department_applied']."\n";
        $details .= "📡 Source     : ".$l['source']."\n";
        $details .= "📅 Date       : ".date('d M Y',strtotime($l['created_at']))."\n";
        $details .= "━━━━━━━━━━━━━━━━━━━━\n";
        $details .= "Sent from College Admission CRM";
        $wa = urlencode($details);
      ?>
      <tr id="row-<?=$l['id']?>">
        <td style="color:var(--muted);font-size:12px;"><?=$row?></td>
        <td><strong><?=htmlspecialchars($l['name'])?></strong></td>
        <td><?=htmlspecialchars($l['phone'])?></td>
        <td>
          <span style="background:<?=$l['course_level']==='PG'?'#e8f4fd':'#e8f5e9'?>;color:<?=$l['course_level']==='PG'?'#1a3c5e':'#155724'?>;padding:2px 8px;border-radius:8px;font-size:11px;font-weight:700;"><?=$l['course_level']?></span>
        </td>
        <td>
          <strong style="color:var(--navy);font-size:13px;"><?=htmlspecialchars($l['department_applied'])?></strong>
        </td>
        <td style="font-size:12px;"><?=htmlspecialchars($l['source'])?></td>
        <td>
          <select name="assignments[<?=$l['id']?>]"
                  id="hod-select-<?=$l['id']?>"
                  data-dept="<?=htmlspecialchars($l['department_applied'])?>"
                  style="padding:7px 10px;border:2px solid var(--border);border-radius:8px;font-size:12px;width:100%;min-width:260px;">
            <option value="">-- Select HOD for this student --</option>
            <?php foreach($hods_list as $h):?>
            <option value="<?=$h['id']?>"><?=htmlspecialchars($h['name'])?> — <?=htmlspecialchars($h['department'])?></option>
            <?php endforeach;?>
          </select>
        </td>
        <td>
          <button type="button" class="btn btn-info btn-sm" onclick='showModal(<?=json_encode($details)?>, <?=json_encode($wa)?>)'>👁 Share</button>
        </td>
      </tr>
      <?php endwhile;?>
    </table></div>

    <div style="padding:14px 18px;border-top:1px solid var(--border);background:#f8fafb;display:flex;gap:10px;">
      <button type="button" class="btn btn-warning" onclick="autoMatch()">⚡ Auto-Match HODs</button>
      <button type="submit" class="btn btn-primary">🔗 Assign All to Selected HODs</button>
    </div>
  </form>

  <?php else:?>
  <div style="padding:30px;text-align:center;color:var(--muted);">✅ All leads are assigned to HODs.</div>
  <?php endif;?>
</div>

<!-- RECENTLY ASSIGNED -->
<div class="table-card">
  <div class="table-header"><h3>✅ Recently Assigned Leads</h3></div>
  <div style="overflow-x:auto;"><table>
    <tr><th>Name</th><th>Phone</th><th>Level</th><th>Department</th><th>Assigned HOD</th><th>Status</th><th>Share</th></tr>
    <?php while($l=$assigned->fetch_assoc()):
      $d2 = "🎓 *STUDENT ENQUIRY*\n👤 ".$l['name']."\n📞 ".$l['phone']."\n📧 ".$l['email']."\n🏫 ".$l['department_applied']."\n📚 ".$l['course_level']."\n👩‍💼 HOD: ".$l['hod_name']."\nSent from College CRM";
      $w2 = urlencode($d2);
    ?>
    <tr>
      <td><strong><?=htmlspecialchars($l['name'])?></strong></td>
      <td><?=htmlspecialchars($l['phone'])?></td>
      <td><?=$l['course_level']?></td>
      <td><strong style="font-size:12px;"><?=htmlspecialchars($l['department_applied'])?></strong></td>
      <td style="font-size:12px;color:var(--teal);font-weight:600;"><?=htmlspecialchars($l['hod_name']??'—')?></td>
      <td><?=sBadge($l['status'])?></td>
      <td><button type="button" class="btn btn-info btn-sm" onclick='showModal(<?=json_encode($d2)?>,<?=json_encode($w2)?>)'>👁 Share</button></td>
    </tr>
    <?php endwhile;?>
  </table></div>
</div>

<script>
var currentDetails='';
var hodsList=<?=json_encode($hods_list)?>;

// Auto-match HOD based on department name
function autoMatch(){
    var selects=document.querySelectorAll('select[data-dept]');
    var matched=0;
    selects.forEach(function(sel){
        var dept=sel.getAttribute('data-dept').toLowerCase().trim();
        var bestId='', bestScore=0;
        hodsList.forEach(function(h){
            var hDept=h.department.toLowerCase().trim();
            // exact match
            if(hDept===dept){bestId=h.id;bestScore=100;return;}
            // partial match
            var score=0;
            dept.split(' ').forEach(function(word){
                if(word.length>2 && hDept.includes(word)) score+=10;
            });
            if(score>bestScore){bestScore=score;bestId=h.id;}
        });
        if(bestId && bestScore>0){
            sel.value=bestId;
            sel.style.borderColor='#28a745';
            sel.style.background='#f0fff4';
            matched++;
        }
    });
    alert('⚡ Auto-matched '+matched+' students to their HODs!\nPlease review before clicking Assign.');
}

function showModal(details, waText){
    currentDetails=details;
    document.getElementById('detail-modal').style.display='flex';
    var lines=details.split('\n');
    var html='';
    lines.forEach(function(line){
        if(line.startsWith('━')){html+='<hr style="border:1px dashed #e2e8f0;margin:6px 0;">';}
        else if(line.trim()){html+='<div>'+line.replace(/\*(.*?)\*/g,'<strong>$1</strong>')+'</div>';}
    });
    document.getElementById('modal-content').innerHTML=html;
    document.getElementById('copy-msg').style.display='none';
    document.getElementById('wa-link').href='https://wa.me/?text='+waText;
    document.getElementById('mail-link').href='mailto:?subject=Student Enquiry&body='+encodeURIComponent(details);
}
function closeModal(){document.getElementById('detail-modal').style.display='none';}
function copyDetails(){
    if(navigator.clipboard){
        navigator.clipboard.writeText(currentDetails).then(function(){
            document.getElementById('copy-msg').style.display='block';
            setTimeout(function(){document.getElementById('copy-msg').style.display='none';},3000);
        });
    }
}
document.getElementById('detail-modal').addEventListener('click',function(e){if(e.target===this)closeModal();});
</script>

<?php require_once '../includes/footer.php'; ?>
