<?php
require_once '../includes/config.php';
requireLogin('admin');
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="leads_template.csv"');
$out=fopen('php://output','w');
fputcsv($out,['Name','Phone','Email','Qualification','Level (UG/PG)','Department','Source']);
fputcsv($out,['Karthik M','9600001111','karthik@gmail.com','B.Sc CS','PG','MCA','Webinar']);
fclose($out);exit;
