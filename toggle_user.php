<?php
require_once 'includes/config.php';
requireLogin('admin');
$id=(int)($_GET['id']??0);
if($id){getDB()->query("UPDATE users SET is_active=NOT is_active WHERE id=$id");}
header('Location: '.BASE.'/admin/users.php?msg=User+status+updated');exit;
