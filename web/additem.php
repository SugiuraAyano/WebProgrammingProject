<?php
// 使用者點選放棄新增按鈕
if (isset($_POST['Abort'])) header("Location: seller.php");
// Authentication 認證
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");

require_once("../include/db_func.php");
require_once("../include/config.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
?>
<script Language="JavaScript">
	function startload() {
		var Ary = document.ULFile.userfile.value.split('\\');
		document.ULFile.fname.value = Ary[Ary.length - 1];
		document.ULFile.orgfn.value = document.ULFile.userfile.value
		document.forms['ULFile'].submit();
		return true;
	}
</script>
<?php
/* $sqlcmd = "SELECT * FROM user WHERE loginid='$LoginID' AND valid='Y'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');
$UserGroupID = $rs[0]['groupid']; */

if (!isset($Name)) $Name = '';
if (!isset($Amount)) $Amount = '';
if (!isset($Price)) $Price = '';
if (!isset($Itemimage)) $Itemimage = '';
if (!isset($Description)) $Description = '';
if (!isset($Status)) $Status = '';
// 取出群組資料
/* /$sqlcmd = "SELECT * FROM groups WHERE valid='Y' AND (groupid='$UserGroupID' "
    . "OR groupid IN (SELECT groupid FROM userpriv "
    . "WHERE loginid='$LoginID' AND privilege > 1 AND valid='Y'))";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs)<=0) die('No group could be found!');  
$GroupNames = array(); 
foreach ($rs as $item) {
    $ID = $item['groupid'];
    $GroupNames[$ID] = $item['groupname'];
}
$GroupIDs = '';
foreach ($GroupNames as $ID => $GroupName) $GroupIDs .= "','" . $ID;
$GroupIDs = "(" . substr($GroupIDs,2) . "')"; */

if (isset($GoUpload) && $GoUpload == '1') {   // 確認按鈕
	$fname = $_FILES["userfile"]['name'];
	$ftype = $_FILES["userfile"]['type'];
	if ($_POST["fname"] <> $_POST["orgfn"]) $fname = $_POST["fname"];
	$fsize = $_FILES['userfile']['size'];
	if (!empty($fname) && addslashes($fname) == $fname && $fsize > 0) {
		$FName = $_FILES['userfile']['tmp_name'];
		$handle = fopen($FName, "r");
		$Itemimage = fread($handle, $fsize);
		fclose($handle);
		$Itemimage = addslashes($Itemimage);
	} else {
		$ErrMsg = '<font color="Red">'
			. '檔案不存在、大小為0或超過上限(100MBytes)</font>';
	}
	if (empty($Name)) $ErrMsg = '姓名不可為空白\n';
	if (empty($Amount)) $ErrMsg = '數量不可為空白\n';
	if (empty($Price)) $ErrMsg = '價錢不可為空白\n';
	if (empty($Itemimage)) $ErrMsg = '圖片不可為空白\n';
	if (empty($Description)) $ErrMsg = '描述不可為空白\n';
	if (empty($Status)) $ErrMsg = '商品狀態不可為空白\n';


	if (empty($ErrMsg)) {
		// 確定此用戶可設定所選定群組的聯絡人資料
		/*  $sqlcmd = "SELECT privilege FROM privileges "
            . "WHERE loginid='$LoginID' AND groupid='$GroupID' AND privilege>0";
        $rs = querydb($sqlcmd, $db_conn);
        // 若權限表未設定權限，則設為用戶的群組
        if (count($rs) <= 0) $GroupID = $UserGroupID; */
		$Seller_id = $_SESSION['id'];
		$sqlcmd='INSERT INTO Item (name,seller_id, amount,price,itemimage,description,status) VALUES ('
            . "'$Name', '$Seller_id', '$Amount','$Price','$Itemimage','$Description','$Status')";
        $result = updatedb($sqlcmd, $db_conn);
		
		$sqlcmd = "SELECT count(*) AS reccount FROM Item  ";
		$rs = querydb($sqlcmd, $db_conn);
		$RecCount = $rs[0]['reccount'];
		$TotalPage = (int) ceil($RecCount / $ItemPerPage);
		$_SESSION['CurPage'] = $TotalPage;

		header("Location: seller.php");
		exit();
	}
	else echo $ErrMsg;
}
$PageTitle = '示範新增人員資料';
?>

<div align="center">
	<form enctype="multipart/form-data" action="" method="post" name="ULFile">
		<b>新增商品資料</b>
		<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
			<tr height="30">
				<th width="40%">商品名</th>
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
