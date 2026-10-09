<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='Staff Report';$active_menu='staff_report';
$db=getDB();

$depts_raw = getDepts();
$filter_dept = $_GET['dept'] ?? '';
$filter_level = $_GET['level'] ?? '';

$where = "u.role='staff'";
if($filter_dept) $where .= " AND u.department='".$db->real_escape_string($filter_dept)."'";
if($filter_level === 'UG') $where .= " AND (u.department LIKE 'B.E%' OR u.department LIKE 'B.Tech%' OR u.department LIKE 'B.Arch%')";
elseif($filter_level === 'PG') $where .= " AND u.department IN ('MCA','MBA','M.E')";

$staff=$db->query("SELECT u.*,COUNT(l.id) leads,SUM(l.status='Admitted') admitted,SUM(l.status='Rejected') rejected,SUM(l.status='In Progress') progress,(SELECT COUNT(*) FROM call_history WHERE staff_id=u.id) calls FROM users u LEFT JOIN leads l ON l.assigned_staff=u.id WHERE $where GROUP BY u.id ORDER BY u.department,u.staff_level");

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
      <a href="<?=BASE?>/admin/staff_report.php" class="btn btn-outline" style="margin-top:22px;">✖ Clear</a>
    </form>
  </div>
</div>
<div class="table-card">
  <div class="table-header">
    <h3>👤 Staff Performance Report
      <?php if($filter_dept):?><span style="font-size:12px;color:var(--muted);margin-left:8px;">— <?=htmlspecialchars($filter_dept)?></span><?php endif;?>
      <?php if($filter_level):?><span style="font-size:12px;background:var(--navy);color:#fff;padding:2px 8px;border-radius:10px;margin-left:6px;"><?=$filter_level?></span><?php endif;?>
    </h3>
    <span style="font-size:12px;color:var(--muted);"><?=$staff?$staff->num_rows:0?> staff found</span>
  </div>
  <div style="overflow-x:auto;"><table>
    <tr><th>Staff Name</th><th>Email</th><th>Department</th><th>Level</th><th>Leads</th><th>Admitted</th><th>In Progress</th><th>Rejected</th><th>Calls</th><th>Rate%</th></tr>
    <?php if($staff && $staff->num_rows > 0): while($s=$staff->fetch_assoc()):$rate=$s['leads']>0?round($s['admitted']/$s['leads']*100):0;?>
    <tr>
      <td><strong><?=htmlspecialchars($s['name'])?></strong></td>
      <td style="font-size:11px;color:var(--muted);"><?=htmlspecialchars($s['email'])?></td>
      <td style="font-size:12px;"><?=htmlspecialchars($s['department'])?></td>
      <td><span class="level-pill">L<?=(int)$s['staff_level']?></span></td>
      <td><?=$s['leads']?></td>
      <td style="color:var(--success);font-weight:700;"><?=$s['admitted']??0?></td>
      <td style="color:var(--warning);"><?=$s['progress']??0?></td>
      <td style="color:var(--danger);"><?=$s['rejected']??0?></td>
      <td><span class="call-count-badge">📞 <?=$s['calls']?></span></td>
      <td><strong><?=$rate?>%</strong></td>
    </tr>
    <?php endwhile; else:?>
    <tr><td colspan="10" style="text-align:center;padding:30px;color:var(--muted);">No staff found.</td></tr>
    <?php endif;?>
  </table></div>
</div>
<?php require_once '../includes/footer.php'; ?>
