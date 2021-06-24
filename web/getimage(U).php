<?php
$SystemName = '網頁程式設計與安全實務';
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
// 本程式因為目前只用一個參數即可取得照片，因此有安全疑慮，請思考如何改進！
// 變數及函式處理，請注意其順序
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