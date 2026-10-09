<?php
require_once '../includes/config.php';
requireLogin('admin');
$db=getDB();
$where='1=1';
if(!empty($_GET['status']))$where.=" AND l.status='".$db->real_escape_string($_GET['status'])."'";
if(!empty($_GET['source']))$where.=" AND l.source='".$db->real_escape_string($_GET['source'])."'";
if(!empty($_GET['level']))$where.=" AND l.course_level='".$db->real_escape_string($_GET['level'])."'";
$leads=$db->query("SELECT l.*,h.name hod_name,s.name staff_name,(SELECT COUNT(*) FROM call_history WHERE lead_id=l.id) calls FROM leads l LEFT JOIN users h ON l.assigned_hod=h.id LEFT JOIN users s ON l.assigned_staff=s.id WHERE $where ORDER BY l.id");
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="college_leads_'.date('Y-m-d').'.csv"');
$out=fopen('php://output','w');
fputcsv($out,['ID','Name','Phone','Email','Qualification','Level','Department','Source','Status','HOD','Staff','Calls','Reminder Date','Added On']);
while($l=$leads->fetch_assoc()){
    fputcsv($out,[$l['id'],$l['name'],$l['phone'],$l['email'],$l['qualification'],$l['course_level'],$l['department_applied'],$l['source'],$l['status'],$l['hod_name']??'',$l['staff_name']??'',$l['calls'],$l['reminder_date']??'',$l['created_at']]);
}fclose($out);exit;
