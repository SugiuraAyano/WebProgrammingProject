<?php
// Authentication 認證
require_once("../include/auth.php");
// session_start();
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);

$sqlcmd = "SELECT * FROM user WHERE loginid='$LoginID' AND valid='Y'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');
$UserGroupID = $rs[0]['groupid'];
// var_dump($_SESSION);
// 取出群組資料
// $sqlcmd = "SELECT * FROM groups WHERE valid='Y' AND (groupid='$UserGroupID' "
    // . "OR groupid IN (SELECT groupid FROM userpriv "
    // . "WHERE loginid='$LoginID' AND privilege > 1 AND valid='Y'))";
// $rs = querydb($sqlcmd, $db_conn);
// if (count($rs)<=0) die('No group could be found!');  
// $GroupNames = array();
// foreach ($rs as $item) {
    // $ID = $item['groupid'];
    // $GroupNames[$ID] = $item['groupname'];
// } 

if (isset($action) && $action=='recover' && isset($id)) {
    // Recover this item
    // Check whether this user have the right to modify this contact info
    $sqlcmd = "SELECT * FROM item WHERE id='$id'";
    $rs = querydb($sqlcmd, $db_conn);
//        
    }
}
if (isset($action) && $action=='delete' && isset($id)) {
    // Invalid this item
    // Check whether this user have the right to modify this contact info
    $sqlcmd = "SELECT * FROM item WHERE id='$id' AND valid='Y'";
    $rs = querydb($sqlcmd, $db_conn);
 
}
$ItemPerPage = 3;
$PageTitle = '單位人員資訊系統示範';
require_once("../include/header.php");
/*
$GroupIDs = '';
foreach ($GroupNames as $ID => $GroupName) $GroupIDs .= "','" . $ID;
$GroupIDs = "(" . substr($GroupIDs,2) . "')";
$sqlcmd = "SELECT count(*) AS reccount FROM namelist WHERE groupid IN $GroupIDs ";
*/
$sqlcmd = "SELECT count(*) AS reccount FROM item ";
$rs = querydb($sqlcmd, $db_conn);
$RecCount = $rs[0]['reccount'];
$TotalPage = (int) ceil($RecCount/$ItemPerPage);
if (!isset($Page)) {
    if (isset($_SESSION['CurPage'])) $Page = $_SESSION['CurPage'];
    else $Page = 1;
}
if ($Page > $TotalPage) $Page = $TotalPage;
if(!isset($Page) || $Page<1) $Page = 1;
$_SESSION['CurPage'] = $Page;
$StartRec = ($Page-1) * $ItemPerPage;
$sqlcmd = "SELECT * FROM item "
    . "LIMIT $StartRec,$ItemPerPage";
$Contacts = querydb($sqlcmd, $db_conn);
?>
<body>
<div>
<Script Language="JavaScript">
<!--
function confirmation(DspMsg, PassArg) {
var name = confirm(DspMsg)
    if (name == true) {
      location=PassArg;
    }
}
-->
</SCRIPT>
<div id="logo">單位人員名冊</div>
<table border="0" width="90%" align="center" cellspacing="0"
  cellpadding="2">
<tr>
  <td width="50%" align="left">
<?php if ($TotalPage > 1) { ?>
<form name="SelPage" method="POST" action="">
  第<select name="Page" onchange="submit();">
<?php 
for ($p=1; $p<=$TotalPage; $p++) { 
    echo '  <option value="' . $p . '"';
    if ($p == $Page) echo ' selected';
    echo ">$p</option>\n";
}
?>
  </select>頁 共<?php echo $TotalPage ?>頁
</form>
<?php } ?>
  <td>
  <td align="right" width="30%">
    <a href="contactadd.php">新增</a>&nbsp;
    <a href="../logout.php">登出</a>
  </td>
</tr>
</table>
<table class="mistab" width="90%" align="center">
<tr>
  <th width="15%">處理</th>
  <th width="10%">序號</a></th>
  <th width="15%">商品名</th>
  <th width="20%">價錢</th>
  <th width="10%">數量</a></th>
  <th width="40%">圖片</a></th>
  <th width="10%">簡介</a></th>
</tr>
<?php
foreach ($Contacts AS $item) {
  $cid = $item['id'];
  $Name = $item['name'];
  $Price = $item['price'];
  $Amount = $item['amount'];
  $ITimage = $item['itemimage'];
//  $GroupName = '&nbsp;';
//  if (isset($GroupNames[$GroupID])) $GroupName = $GroupNames[$GroupID];
  $DspMsg = "'確定刪除項目?'";
  $PassArg = "'contactmgm.php?action=delete&id=$id'";
  echo '<tr align="center"><td>';
  if ($Valid=='N') {
?>
  <a href="contactmgm.php?action=recover&cid=<?php echo $id; ?>">
    <img src="../images/recover.gif" border="0" align="absmiddle">
    </a></td>
  <td><STRIKE><?php echo $Name ?></STRIKE></td>
<?php } else { ?>
  <a href="javascript:confirmation(<?php echo $DspMsg ?>, <?php echo $PassArg ?>)">
  <img src="../images/cut.gif" border="0" align="absmiddle"
    alt="按此鈕將本項目作廢"></a>&nbsp;
  <a href="contactmod.php?cid=<?php echo $cid; ?>">
  <img src="../images/edit.gif" border="0" align="absmiddle"
    alt="按此鈕修改本項目"></a>&nbsp;
  </td>
  <td><?php echo $Name ?></td>   
<?php } ?>
  <td><?php echo $Price ?></td>  
  <td><?php echo $Amount ?></td>
  <td><?php echo $ITimage ?></td>  
  <td><?php echo $Name ?></td>  
  </tr>
<?php
}
?>
</div>
</body>
</html>