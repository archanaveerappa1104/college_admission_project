<?php
require_once '../includes/config.php';
requireLogin('admin');
$page_title='Reports & Analytics';$active_menu='reports';
$db=getDB();
$src=$db->query("SELECT source,COUNT(*) total,SUM(status='Admitted') admitted,SUM(status='Rejected') rejected,SUM(status='In Progress') progress FROM leads GROUP BY source ORDER BY total DESC");
$sts=$db->query("SELECT status,COUNT(*) cnt FROM leads GROUP BY status");
require_once '../includes/header.php';
?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;">
  <div class="table-card">
    <div class="table-header"><h3>📡 Admissions by Source</h3><a href="<?=BASE?>/admin/export_leads.php" class="btn btn-success btn-sm">⬇ Export</a></div>
    <table><tr><th>Source</th><th>Total</th><th>Admitted</th><th>Rejected</th><th>In Progress</th><th>Rate%</th></tr>
    <?php while($r=$src->fetch_assoc()):$rate=$r['total']>0?round($r['admitted']/$r['total']*100):0;?>
    <tr><td><strong><?=htmlspecialchars($r['source'])?></strong></td><td><?=$r['total']?></td>
    <td style="color:var(--success);font-weight:700;"><?=$r['admitted']?></td>
    <td style="color:var(--danger);"><?=$r['rejected']?></td>
    <td style="color:var(--warning);"><?=$r['progress']?></td>
    <td><strong><?=$rate?>%</strong></td></tr>
    <?php endwhile;?>
    </table>
  </div>
  <div class="table-card">
    <div class="table-header"><h3>📊 Status Summary</h3></div>
    <table><tr><th>Status</th><th>Count</th></tr>
    <?php while($s=$sts->fetch_assoc()):?>
    <tr><td><?=sBadge($s['status'])?></td><td><strong><?=$s['cnt']?></strong></td></tr>
    <?php endwhile;?>
    </table>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
