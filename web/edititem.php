<?php
// 使用者點選放棄修改按鈕
if (isset($_POST['Abort'])) header("Location: seller.php");
// Authentication 認證
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/db_func.php");
require_once("../include/config.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
// 確認參數是否正確
if (!isset($cid)) die("Parameter error!");
// 找出此用戶的群組
/* $sqlcmd = "SELECT * FROM user WHERE loginid='$LoginID' AND valid='Y'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');
$UserGroupID = $rs[0]['groupid'];
 */
// Authorization 授權
// =====================================================
// 先取得這筆資料的群組，再檢查這個帳號是否有權限
/* $sqlcmd = "SELECT * FROM namelist WHERE cid='$cid'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die("找不到編號為 $cid 之資料");
$GID = $rs[0]['groupid'];
if ($GID<>$UserGroupID) {   // 非本單位人員，看看是否有額外權限
    $sqlcmd = "SELECT privilege FROM userpriv WHERE loginid='$LoginID' and groupid='$GID'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) <= 0) die("您對編號 $cid 之資料無修改權限");
} */
// =====================================================

// 處理使用者異動之資料
if (isset($Confirm)) {   // 確認按鈕
    if (!isset($Name) || empty($Name)) $ErrMsg = '姓名不可為空白\n';
    if (!isset($Amount) || empty($Amount)) $ErrMsg = '數量不可為空白\n';
    if (!isset($Price) || empty($Price)) $ErrMsg = '價錢不可為空白\n';
    if (!isset($Itemimage) || empty($Itemimage)) $ErrMsg = '圖片不可為空白\n';
    if (!isset($Description) || empty($Description)) $ErrMsg = '描述不可為空白\n';
    if (!isset($Status) || empty($Status)) $ErrMsg = '商品狀態不可為空白\n';
    if (empty($ErrMsg)) {   // 資料經初步檢核沒問題
        // Demo for XSS
        //    $Name = xssfix($Name);
        //    $Phone = xssfix($Phone);
        // Demo for the reason to use addslashes
        if (!get_magic_quotes_gpc()) {
            $Name = addslashes($Name);
            $Amount = addslashes($Amount);
            $Price = addslashes($Price);
            $Itemimage = addslashes($Itemimage);
            $Description = addslashes($Description);
            $Status = addslashes($Status);
        }
        $sqlcmd = "UPDATE Item SET name='$Name',amount='$Amount',price='$Price', "
            . "itemimage='$Itemimage',description='$Description',status='$Status' WHERE id='$cid'";
        $result = updatedb($sqlcmd, $db_conn);
        header("Location: seller.php");
        exit();
    }
}
if (!isset($Name)) {
    // 此處是在contactlist.php點選後進到這支程式，因此要由資料表將欲編輯的資料列調出
    $sqlcmd = "SELECT * FROM Item WHERE id='$cid'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) <= 0) die('No data found');      // 找不到資料，正常應該不會發生
    $Name = $rs[0]['name'];
    $Name = $rs[0]['name'];
    $Price = $rs[0]['price'];
    $Amount = $rs[0]['amount'];
    $Valid = $rs[0]['valid'];
    $Description = $rs[0]['description'];
    $Status = $rs[0]['status'];
} else {    // 點選送出後，程式發現有錯誤
    // Demo for stripslashes
    if (get_magic_quotes_gpc()) {
        $Name = stripslashes($Name);
        $Name = stripslashes($Name);
        $Amount = stripslashes($Amount);
        $Price = stripslashes($Price);
        $Itemimage = stripslashes($Itemimage);
        $Description = stripslashes($Description);
        $Status = stripslashes($Status);
    }
}
// 取出群組資料
/* $sqlcmd = "SELECT * FROM groups WHERE valid='Y' AND (groupid='$UserGroupID' "
    . "OR groupid IN (SELECT groupid FROM userpriv "
    . "WHERE loginid='$LoginID' AND privilege > 1 AND valid='Y'))";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs)<=0) die('No group could be found!');  
$GroupNames = array();
foreach ($rs as $item) {
    $ID = $item['groupid'];
    $GroupNames[$ID] = $item['groupname'];
} */
$PageTitle = '示範修改人員資料';
?>

<head>
	<link rel="stylesheet" href="../assets/css/main.css" />
</head>

<div align="center">
    <form enctype="multipart/form-data" action="" method="post" name="ULFile">
        <b>修改商品資料</b>
        <table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
            <tr height="30">
                <th width="40%">姓名</th>
                <td><input type="text" name="Name" value="<?php echo $Name ?>" size="20"></td>
            </tr>
            <tr height="30">
                <th width="40%">數量</th>
                <td><input type="text" name="Amount" value="<?php echo $Amount ?>" size="20"></td>
            </tr>
            <tr height="30">
                <th width="40%">價錢</th>
                <td><input type="text" name="Price" value="<?php echo $Price ?>" size="20"></td>
            </tr>
            <tr height="30">
                <th width="40%">簡介</th>
                <td><input type="text" name="Description" value="<?php echo $Description ?>" size="500"></td>
            </tr>
            <tr height="30">
                <th width="40%">狀態</th>
                <td><input type="radio" name="Status" value="brandnew" size="1">brandnew<br>
                    <input type="radio" name="Status" value="secondhand" size="1">secondhand<br>
                    <input type="radio" name="Status" value="others" size="1">others<br>
                </td>
            </tr>
            <input type="hidden" name="MAX_FILE_SIZE" value="102497152">
            <input type="hidden" name="GoUpload" value="1">
            <input type="hidden" name="fname">
            <input type="hidden" name="orgfn">
            <br />
            <table width="420" border="0" cellspacing="0" cellpadding="3" align="center">
                <tr>
                    <td align="center"> 上傳圖片：<input name="userfile" type="file"> </td>
                </tr>
                <tr>
                    <th>
            </table>
            <input type="button" name="upload" value="存檔送出" onclick="startload()">&nbsp;&nbsp;
            <input type="submit" name="Abort" value="放棄新增">
    </form>
</div>