<?php
require_once("../include/config.php");
require_once("../include/db_func.php");
// require_once("../include/gpsvars.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);

$ID = $_GET['ID'];

if (!isset($ID)) 
    exit();

$sqlcmd = "SELECT * FROM Item WHERE id='$ID'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs)>0) {
    $Image = $rs[0]['itemimage'];
    $filename = $ID . '.png';
    $filetype = 'image/png';
    header("Content-type: $filetype \n");
    header("Content-Disposition: filename=$filename \n");
    echo $Image;
} else die ('File Not Exist!');

?>