<?php
// 使用者點選放棄新增按鈕
if (isset($_POST['Abort'])) header("Location: login.php");
// Authentication 認證
//require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
//require_once("../include/configure.php");
session_start();
/* 
if (isset($_SERVER['HTTP_VIA']) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    $UserIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if (isset($_SERVER['REMOTE_ADDR'])) $UserIP = $_SERVER['REMOTE_ADDR']; 
*/
/*
$sqlcmd = "SELECT * FROM User WHERE id='$ID' AND valid='Y'";
print_r($rs);
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');
*/

//$UserGroupID = $rs[0]['groupid'];
//if (!isset($GroupID))  $GroupID = $rs[0]['groupid'];
if (!isset($Name)) $Name = '';
if (!isset($Phone)) $Phone = '';
if (!isset($Address)) $Address = '';
if (!isset($id)) $id = '';
if (!isset($password)) $password = '';
// 取出群組資料

//echo $LoginID;
//echo $UserGroupID;
/*
$sqlcmd = "SELECT * FROM groups WHERE valid='Y' AND (groupid='$UserGroupID' "
    . "OR groupid IN (SELECT groupid FROM privileges "
    . "WHERE id='$LoginID' AND privilege > 1 AND valid='Y'))";
$rs = querydb($sqlcmd, $db_conn);
//print_r ($rs);
if (count($rs)<=0) die('No group could be found!');  
$GroupNames = array();
foreach ($rs as $item) {
    $ID = $item['groupid'];
    $GroupNames[$ID] = $item['groupname'];
}
$GroupIDs = '';
foreach ($GroupNames as $ID => $GroupName) $GroupIDs .= "','" . $ID;
$GroupIDs = "(" . substr($GroupIDs,2) . "')";
*/
//$checkB = "/\d{4}-\d{2}-\d{2}/";
$checkP = "/02|09[0-9]{8}/";
$checkGender = array("M", "F", "X");
if (isset($Confirm)) {   // 確認按鈕
	if (empty($Name)) $ErrMsg = '姓名不可為空白\n';
	if (empty($Phone)) $ErrMsg = '電話不可為空白\n';
	//if (empty($GroupID) || $GroupID<>addslashes($GroupID)) $ErrMsg = '群組資料錯誤\n';
	if (!filter_var($eMail, FILTER_VALIDATE_EMAIL)) $ErrMsg = 'Email 格式錯誤\n';

	$flag = 0;
	for ($i = 0; $i < 3; $i++) {
		if ($gender = $checkGender[$i]) {
			$flag = 1;
			break;
		}
	}
	if ($flag == 0) $ErrMsg = '性別格式錯誤\n';

	//if(!preg_match($checkB, $Birthday)) $ErrMsg='生日格式錯誤\n';
	if (!preg_match($checkP, $Phone)) $ErrMsg = '電話格式錯誤\n';
	if (empty($ErrMsg)) {
		// 確定此用戶可設定所選定群組的聯絡人資料
		/* $sqlcmd = "SELECT privilege FROM privileges "
            . "WHERE loginid='$LoginID' AND groupid='$GroupID' AND privilege>0";
        $rs = querydb($sqlcmd, $db_conn);*/
		// 若權限表未設定權限，則設為用戶的群組
		//if (count($rs) <= 0) $GroupID = $UserGroupID;

		$Name = htmlpurifier($Name);
		$Address = htmlpurifier($Address);
		$id = htmlpurifier($id);
		$password = htmlpurifier($password);

		require_once("../include/db_func.php");
		$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
		$sqlcmd = 'INSERT INTO User (id,name,gender,phone,email,address,password) VALUES ('
			. ":id,:Name,:gender,:Phone,:eMail,:Address,:password)";
		$statment = $db_conn->prepare($sqlcmd);
		$statment->execute(array(
			':id' => $id, ':Name' => $Name, ':gender' => $gender, ':Phone' => $Phone, ':eMail' => $eMail, ':Address' => $Address, ':password' => $password
		));
		$result = $statment->fetchAll(PDO::FETCH_ASSOC);
		/*
        $sqlcmd = "SELECT count(*) AS reccount FROM namelist WHERE groupid IN $GroupIDs ";
        $rs = querydb($sqlcmd, $db_conn);
        $RecCount = $rs[0]['reccount'];
        $TotalPage = (int) ceil($RecCount/$ItemPerPage);
        $_SESSION['CurPage'] = $TotalPage; 
        */
		$_SESSION['id'] = $id;
		header("Location: upload.php");
	}
}
$PageTitle = '示範新增人員資料';
require_once("../include/header.php");
?>
<div align="center">
	<form action="" method="post" name="inputform">
		<b>新增人員資料</b>
		<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
			<tr height="30">
				<th width="40%">姓名</th>
				<td><input type="text" name="Name" value="<?php echo $Name ?>" maxlength="20" size="20"></td>
			</tr>


			<?php
			/*
<tr height="30">
  <th>單位</th>
  <td><select name="GroupID">
<?php
    foreach ($GroupNames as $ID => $GroupName) {
        echo '    <option value="' . $ID . '"';
        if ($ID == $GroupID) echo ' selected';
        echo ">$GroupName</option>\n";
    } 
?>
    </select>
  </td>
</tr>
*/
			?>

			<tr height="30">
				<th width="40%">帳號</th>
				<td><input type="text" name="id" value="<?php echo $id ?>" maxlength="20" size="20"></td>
			</tr>
			<tr height="30">
				<th width="40%">密碼</th>
				<td><input type="text" name="password" value="<?php echo $password ?>" maxlength="20" size="20"></td>
			</tr>

			<?php
			/*
<tr height="30">
  <th width="40%">生日</th>
  <td><input type="date" name="Birthday" value="<?php echo $Birthday ?>" size="50"></td>
</tr>
*/
			?>

			<tr height="30">
				<th width="40%">電話</th>
				<td><input type="text" name="Phone" value="<?php echo $Phone ?>" maxlength="20" size="20"></td>
			</tr>
			<tr height="30">
				<th width="40%">地址</th>
				<td><input type="text" name="Address" value="<?php echo $Address ?>" maxlength="100" size="50"></td>
			</tr>
			<tr height="30">
				<th width="40%">性別</th>
				<td><input type="radio" name="gender" value="M">
					<label for="M">M</label>
					<input type="radio" name="gender" value="F">
					<label for="F">F</label>
					<input type="radio" name="gender" value="X">
					<label for="X">X</label>
				</td>
			</tr>
			<tr height="30">
				<th width="40%">電子信箱</th>
				<td><input type="text" name="eMail" value="<?php echo $eMail ?>" maxlength="100" size="50"></td>
			</tr>
		</table>
		<input type="submit" name="Confirm" value="存檔送出">&nbsp;
		<input type="submit" name="Abort" value="放棄新增">
	</form>
</div>
<?php
require_once('../include/footer.php');
?>