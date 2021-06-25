<?php
require_once("../include/gpsvars.php");
require_once("../include/config.php");
require_once("../include/db_func.php");
require_once("../include/xss.php");


$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$sqlcmd = "SELECT amount FROM Item WHERE id = '$cid'";
$rs = querydb($sqlcmd, $db_conn);
$curAmount = $rs[0]['amount'];
$newAmount = $curAmount - $buyAmount;
$sqlcmd = "UPDATE Item SET amount = '$newAmount' WHERE id = '$cid'";

updatedb($sqlcmd, $db_conn);

header("Location: buyer.php");
exit();

?>