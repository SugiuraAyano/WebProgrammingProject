<?php
// 使用者點選放棄修改按鈕
if (isset($_POST['Abort'])) header("Location: index.php");
// Authentication 認證
//require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
//require_once("../include/configure.php");
$dbhost = "sugiuraayano.synology.me:13307";
$dbname = "project";
$dbuser = "ui3a22";
$dbpwd = "ui3a22";
$uDate = date("Y-m-d H:i:s");
$ErrMsg = "";
$UserIP = '';
if (isset($_SERVER['HTTP_VIA']) && isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $UserIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if (isset($_SERVER['REMOTE_ADDR'])) $UserIP = $_SERVER['REMOTE_ADDR'];
///////////////////////
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
// 確認參數是否正確
if (!isset($cid)) die ("Parameter error!");

// Authorization 授權
// =====================================================
// 先取得這筆資料的群組，再檢查這個帳號是否有權限
$sqlcmd = "SELECT * FROM Cart WHERE cid='$cid'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die("找不到編號為 $cid 之資料");
/*
$GID = $rs[0]['groupid'];
if ($GID<>$UserGroupID) {   // 非本單位人員，看看是否有額外權限
    $sqlcmd = "SELECT privilege FROM userpriv WHERE loginid='$LoginID' and groupid='$GID'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) <= 0) die("您對編號 $cid 之資料無修改權限");
}
*/
// =====================================================

// 處理使用者異動之資料
if (isset($Confirm)) {   // 確認按鈕
    if (!isset($Amount) || empty($Amount)) $ErrMsg = '數量不可為空白\n';
	if (!isset($preAmount) || empty($preAmount)) $preAmount = $rs[0]['buyamount'];
	if (!isset($Price) || empty($Price)) $Price = $rs[0]['price'] / $preAmount * $Amount;
	
    if (empty($ErrMsg)) {   // 資料經初步檢核沒問題
    // Demo for XSS
    //    $Name = xssfix($Name);
    //    $Phone = xssfix($Phone);
    // Demo for the reason to use addslashes
        if (!get_magic_quotes_gpc()) {
			$Name = stripslashes($Name);
            $Amount = addslashes($Amount);
			$Price = stripslashes($Price);
        }
        $sqlcmd="UPDATE Cart SET buyamount = '$Amount' , price = '$Price' WHERE cid='$cid'";
        $result = updatedb($sqlcmd, $db_conn);
        header("Location: index.php");
        exit();
    }
}

if (!isset($Name)) {    
    $sqlcmd = "SELECT * FROM Cart WHERE cid='$cid'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) <= 0) die('No data found');      // 找不到資料，正常應該不會發生
    $Name = $rs[0]['name'];
    $Amout = $rs[0]['buyamount'];
    $Price = $rs[0]['price'];
} else {
    if (get_magic_quotes_gpc()) {
        $Name = stripslashes($Name);
        $Amount = addslashes($Amount);
        $Price = stripslashes($Price);
    }
}

require_once("../include/header.php");
?>
<div align="center">
<form action="" method="post" name="inputform">
<input type="hidden" name="cid" value="<?php echo $cid ?>">
<b>修改商品資料</b>
<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
<tr height="30">
  <th width="40%">商品</th>
  <td><?php echo $Name ?></td>
</tr>
<tr height="30">
  <th>數量</th>
  <td><input type="text" name="Amount" value="<?php echo $Amount ?>" size="20"></td>
</tr>
<tr height="30">
  <th>價格</th>
  <td><?php echo $Price ?></td>
</tr>
</table>
<input type="submit" name="Confirm" value="存檔送出">&nbsp;
<input type="submit" name="Abort" value="放棄修改">
</form>
</div>