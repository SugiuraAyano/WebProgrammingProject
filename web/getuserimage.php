<?php
require_once("../include/config.php");
require_once("../include/db_func.php");
require_once("../include/gpsvars.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);

if (!isset($ID)) 
    exit();
$sqlcmd = "SELECT * FROM User WHERE id='$ID'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs)>0) {
    $Image = $rs[0]['image'];
    $filename = $ID . '.png';
    $filetype = 'application/x-png';
    header("Content-type: $filetype \n");
    header("Content-Disposition: filename=$filename \n");
    echo $Image;
} else die ('File Not Exist!');
