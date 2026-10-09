<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','college_crm');
define('BASE','/college_crm');

function getDB(){
    static $c=null;
    if($c===null){
        $c=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if($c->connect_error)die("<h3 style='color:red;padding:20px;font-family:sans-serif;'>DB Error: ".$c->connect_error."<br><br>Please import database.sql into phpMyAdmin first.</h3>");
        $c->set_charset("utf8mb4");
    }
    return $c;
}
if(session_status()===PHP_SESSION_NONE)session_start();

function requireLogin($role=null){
    if(!isset($_SESSION['user_id'])){header('Location: '.BASE.'/login_'.($role??'admin').'.php');exit;}
    if($role&&$_SESSION['role']!==$role){header('Location: '.BASE.'/login_'.$role.'.php?err=Unauthorized');exit;}
}
function logActivity($lid,$uid,$action){
    $db=getDB();
    $s=$db->prepare("INSERT INTO lead_activity(lead_id,user_id,action)VALUES(?,?,?)");
    if($s){$s->bind_param("iis",$lid,$uid,$action);$s->execute();}
}
function getDepts(){
    return['UG'=>['B.E'=>['B.E Aeronautical Engineering','B.E Computer Science and Engineering','B.E Electronics and Communication Engineering','B.E Bio Medical Engineering','B.E CSE (Cyber Security)','B.E Electrical and Electronics Engineering','B.E Civil Engineering','B.E CSE (AI and Machine Learning)','B.E Mechanical Engineering'],
    'B.Tech'=>['B.Tech Bio Technology','B.Tech AI and Data Science','B.Tech Chemical Engineering','B.Tech Computer Science and Business Systems','B.Tech Information Technology'],
    'B.Arch'=>['B.Arch Architecture']],
    'PG'=>['PG'=>['MCA','MBA','M.E']]];
}
function getSrc(){return['Webinar','Staff Call','Internet','Social Media','Friend Referral','Other'];}
function getSts(){return['In Progress','Admitted','Rejected','Wrong Number','Not Reachable'];}
function getCallSts(){return['Answered','Not Reachable','Switched Off','Wrong Number','Busy','Callback Requested'];}
function sBadge($s){
    $m=['Admitted'=>'admitted','In Progress'=>'progress','Rejected'=>'rejected','Wrong Number'=>'wrong','Not Reachable'=>'notreach'];
    return "<span class='badge badge-".($m[$s]??'progress')."'>".htmlspecialchars($s)."</span>";
}
function cBadge($s){
    $m=['Answered'=>'admitted','Not Reachable'=>'notreach','Switched Off'=>'wrong','Wrong Number'=>'wrong','Busy'=>'progress','Callback Requested'=>'progress'];
    return "<span class='badge badge-".($m[$s]??'progress')."'>".htmlspecialchars($s)."</span>";
}
?>
