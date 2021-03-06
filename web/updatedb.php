<?php
require_once("../include/gpsvars.php");
require_once("../include/config.php");
require_once("../include/db_func.php");
require_once("../include/xss.php");

$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);

if ($_GET['action'] == "delete") {
    $sqlcmd = "DELETE FROM Cart WHERE cid = '$cid'";
    updatedb($sqlcmd, $db_conn);
} else {
    $sqlcmd = "SELECT amount FROM Item WHERE id = '$cid'";
    $rs = querydb($sqlcmd, $db_conn);
    $curAmount = $rs[0]['amount'];
    $newAmount = $curAmount - $buyAmount;
    if ($newAmount <= 0) {
        $sqlcmd = "DELETE FROM Item WHERE id = '$cid'";
        updatedb($sqlcmd, $db_conn);
    } else {
        $sqlcmd = "UPDATE Item SET amount = '$newAmount' WHERE id = '$cid'";
        updatedb($sqlcmd, $db_conn);
        $sqlcmd = "DELETE FROM Cart WHERE buyer_id='$buyer_id'";
        updatedb($sqlcmd, $db_conn);
    }
}

header("Location: buyer.php");
exit();
