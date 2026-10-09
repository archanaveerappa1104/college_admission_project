<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='All Leads';$active_menu='leads';
$db=getDB();
$where='1=1';
if(!empty($_GET['status']))$where.=" AND l.status='".$db->real_escape_string($_GET['status'])."'";
if(!empty($_GET['source']))$where.=" AND l.source='".$db->real_escape_string($_GET['source'])."'";
if(!empty($_GET['level']))$where.=" AND l.course_level='".$db->real_escape_string($_GET['level'])."'";
if(!empty($_GET['q'])){$q=$db->real_escape_string($_GET['q']);$where.=" AND (l.name LIKE '%$q%' OR l.phone LIKE '%$q%' OR l.email LIKE '%$q%')";}
$leads=$db->query("SELECT l.*,h.name hod_name,s.name staff_name,(SELECT COUNT(*) FROM call_history WHERE lead_id=l.id) calls FROM leads l LEFT JOIN users h ON l.assigned_hod=h.id LEFT JOIN users s ON l.assigned_staff=s.id WHERE $where ORDER BY l.created_at DESC");
require_once '../includes/header.php';
?>
<div class="table-card">
  <div class="table-header"><h3>📋 All Leads</h3>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a href="<?=BASE?>/admin/add_lead.php" class="btn btn-primary btn-sm">➕ Add Lead</a>
      <a href="<?=BASE?>/admin/export_leads.php" class="btn btn-success btn-sm">⬇ Export</a>
    </div>
  </div>
  <div style="padding:12px 18px;border-bottom:1px solid var(--border);display:flex;gap:10px;flex-wrap:wrap;">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;width:100%;">
      <input type="text" name="q" placeholder="Search name/phone/email..." value="<?=htmlspecialchars($_GET['q']??'')?>" style="padding:7px 12px;border:2px solid var(--border);border-radius:8px;font-size:13px;min-width:200px;">
      <select name="status" style="padding:7px 12px;border:2px solid var(--border);border-radius:8px;font-size:13px;"><option value="">All Status</option>
      <?php foreach(getSts() as $s):?><option value="<?=$s?>" <?=($_GET['status']??'')===$s?'selected':''?>><?=$s?></option><?php endforeach;?></select>
      <select name="source" style="padding:7px 12px;border:2px solid var(--border);border-radius:8px;font-size:13px;"><option value="">All Sources</option>
      <?php foreach(getSrc() as $s):?><option value="<?=$s?>" <?=($_GET['source']??'')===$s?'selected':''?>><?=$s?></option><?php endforeach;?></select>
      <select name="level" style="padding:7px 12px;border:2px solid var(--border);border-radius:8px;font-size:13px;"><option value="">UG + PG</option><option value="UG" <?=($_GET['level']??'')==='UG'?'selected':''?>>UG</option><option value="PG" <?=($_GET['level']??'')==='PG'?'selected':''?>>PG</option></select>
      <button type="submit" class="btn btn-primary btn-sm">🔍 Filter</button>
      <a href="<?=BASE?>/admin/leads.php" class="btn btn-outline btn-sm">✖ Clear</a>
    </form>
  </div>
  <div style="overflow-x:auto;"><table>
    <tr><th>#</th><th>Name</th><th>Phone</th><th>Email</th><th>Level</th><th>Department</th><th>Source</th><th>Status</th><th>HOD</th><th>Staff</th><th>Calls</th><th>Action</th></tr>
    <?php while($l=$leads->fetch_assoc()):?>
    <tr><td><?=$l['id']?></td><td><strong><?=htmlspecialchars($l['name'])?></strong></td>
    <td><?=htmlspecialchars($l['phone'])?></td><td style="font-size:11px;"><?=htmlspecialchars($l['email'])?></td>
    <td><?=$l['course_level']?></td><td style="font-size:11px;"><?=htmlspecialchars($l['department_applied'])?></td>
    <td><?=htmlspecialchars($l['source'])?></td><td><?=sBadge($l['status'])?></td>
    <td style="font-size:11px;"><?=htmlspecialchars($l['hod_name']??'—')?></td>
    <td style="font-size:11px;"><?=htmlspecialchars($l['staff_name']??'—')?></td>
    <td><span class="call-count-badge">📞 <?=$l['calls']?></span></td>
    <td style="white-space:nowrap;">
      <a href="<?=BASE?>/admin/lead_calls.php?id=<?=$l['id']?>" class="btn btn-info btn-sm">📞</a>
      <a href="<?=BASE?>/admin/edit_lead.php?id=<?=$l['id']?>" class="btn btn-warning btn-sm">✏️</a>
    </td></tr>
    <?php endwhile;?>
  </table></div>
</div>
<?php require_once '../includes/footer.php'; ?>
