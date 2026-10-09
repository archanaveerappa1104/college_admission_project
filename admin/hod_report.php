<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='HOD Report';$active_menu='hod_report';
$db=getDB();

$depts_raw = getDepts();
$filter_dept = $_GET['dept'] ?? '';
$filter_level = $_GET['level'] ?? '';

$where = "u.role='hod'";
if($filter_dept) $where .= " AND u.department='".$db->real_escape_string($filter_dept)."'";
if($filter_level === 'UG') $where .= " AND (u.department LIKE 'B.E%' OR u.department LIKE 'B.Tech%' OR u.department LIKE 'B.Arch%')";
elseif($filter_level === 'PG') $where .= " AND u.department IN ('MCA','MBA','M.E')";

$hods=$db->query("SELECT u.id,u.name,u.department,COUNT(l.id) total,SUM(l.status='Admitted') admitted,SUM(l.status='Rejected') rejected,SUM(l.status='In Progress') progress,(SELECT COUNT(*) FROM call_history c2 LEFT JOIN leads l2 ON c2.lead_id=l2.id WHERE l2.assigned_hod=u.id) calls FROM users u LEFT JOIN leads l ON l.assigned_hod=u.id WHERE $where GROUP BY u.id ORDER BY u.department");

require_once '../includes/header.php';
?>
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
      <a href="<?=BASE?>/admin/hod_report.php" class="btn btn-outline" style="margin-top:22px;">✖ Clear</a>
    </form>
  </div>
</div>
<div class="table-card">
  <div class="table-header">
    <h3>👩‍💼 HOD-wise Performance
      <?php if($filter_dept):?><span style="font-size:12px;color:var(--muted);margin-left:8px;">— <?=htmlspecialchars($filter_dept)?></span><?php endif;?>
      <?php if($filter_level):?><span style="font-size:12px;background:var(--navy);color:#fff;padding:2px 8px;border-radius:10px;margin-left:6px;"><?=$filter_level?></span><?php endif;?>
    </h3>
    <span style="font-size:12px;color:var(--muted);"><?=$hods?$hods->num_rows:0?> HOD(s)</span>
  </div>
  <div style="overflow-x:auto;"><table>
    <tr><th>HOD Name</th><th>Department</th><th>Total</th><th>Admitted</th><th>Rejected</th><th>In Progress</th><th>Calls</th><th>Rate%</th><th>Detail</th></tr>
    <?php if($hods && $hods->num_rows > 0): while($h=$hods->fetch_assoc()):$rate=$h['total']>0?round($h['admitted']/$h['total']*100):0;?>
    <tr>
      <td><strong><?=htmlspecialchars($h['name'])?></strong></td>
      <td style="font-size:12px;"><?=htmlspecialchars($h['department'])?></td>
      <td><strong><?=$h['total']?></strong></td>
      <td style="color:var(--success);font-weight:700;"><?=$h['admitted']??0?></td>
      <td style="color:var(--danger);"><?=$h['rejected']??0?></td>
      <td style="color:var(--warning);"><?=$h['progress']??0?></td>
      <td><span class="call-count-badge">📞 <?=$h['calls']?></span></td>
      <td><strong><?=$rate?>%</strong></td>
      <td><a href="<?=BASE?>/admin/hod_detail.php?id=<?=$h['id']?>" class="btn btn-info btn-sm">View Staff</a></td>
    </tr>
    <?php endwhile; else:?>
    <tr><td colspan="9" style="text-align:center;padding:30px;color:var(--muted);">No HODs found.</td></tr>
    <?php endif;?>
  </table></div>
</div>
<?php require_once '../includes/footer.php'; ?>
