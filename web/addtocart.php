<?php
require_once("../include/gpsvars.php");
require_once("../include/config.php");
require_once("../include/db_func.php");
require_once("../include/xss.php");
session_start();
$_SESSION['buyer_id'] = $buyerID;

$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$cid = xsspurify($cid);

$sqlcmd = "SELECT seller_id FROM Item WHERE id = '$cid'";
$rs = querydb($sqlcmd, $db_conn);
$seller_id = $rs[0]['seller_id'];
$newPrice = $price*$Rad1;
$sqlcmd = "INSERT INTO Cart (item_id, name, buyamount, price, buyer_id, seller_id) VALUES( "
    . " '$cid', '$Name', '$Rad1', '$newPrice', '$buyerID', '$seller_id')";

updatedb($sqlcmd, $db_conn);

header("Location: cart.php");
exit();

?>