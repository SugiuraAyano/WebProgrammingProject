<?php
$SystemName = '�����{���]�p�P�w�����';
$dbhost = "sugiuraayano.synology.me:13307";
$dbname = "project";
$dbuser = "ui3a19";
$dbpwd = "qaq890204";
$uDate = date("Y-m-d H:i:s");
$ErrMsg = "";
$UserIP = '';
if (isset($_SERVER['HTTP_VIA']) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    $UserIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if (isset($_SERVER['REMOTE_ADDR'])) $UserIP = $_SERVER['REMOTE_ADDR'];
?>
<?php
// ���{���]���ثe�u�Τ@�ӰѼƧY�i���o�Ӥ��A�]�����w���ü{�A�Ы�Ҧp���i�I
// �ܼƤΨ禡�B�z�A�Ъ`�N�䶶��
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$ID = $_GET['ID'];
if (!isset($ID)) exit;
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

?>