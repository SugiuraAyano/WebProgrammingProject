<?php
session_start();
// Authentication 認證
require_once("../include/auth.php");
// session_start();
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$pid = $_SESSION['id'];
$uid = $_SESSION['id'];
$sqlcmd = "SELECT itemimage FROM Item WHERE id = '$uid'";
$rs = querydb($sqlcmd, $db_conn);
if ($rs <= 0)
    $pid = "default";
if (isset($action) && $action == 'recover' && isset($cid)) {
    // Recover this item
    // Check whether this user have the right to modify this contact info
    $sqlcmd = "SELECT * FROM Item WHERE id='$cid'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        //        $GID = $rs[0]['groupid'];
        //        if (isset($GroupNames[$GID])) {     // Yes, the  user has the right. Perform update
        $sqlcmd = "UPDATE Item SET valid='Y' WHERE id='$cid'";
        $result = updatedb($sqlcmd, $db_conn);
        //        }
    }
}
if (isset($action) && $action == 'delete' && isset($cid)) {
    // Invalid this item
    // Check whether this user have the right to modify this contact info
    $sqlcmd = "SELECT * FROM Item WHERE id='$cid'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        //        $GID = $rs[0]['groupid'];
        //        if (isset($GroupNames[$GID])) {     // Yes, the user has the right. Perform update
        $sqlcmd = "DELETE FROM Item WHERE id='$cid'";
        $result = updatedb($sqlcmd, $db_conn);
        //        }
    }
}
$ItemPerPage = 3;
$PageTitle = '賣家頁面';
require_once("../include/header.php");
/*
$GroupIDs = '';
foreach ($GroupNames as $ID => $GroupName) $GroupIDs .= "','" . $ID;
$GroupIDs = "(" . substr($GroupIDs,2) . "')";
$sqlcmd = "SELECT count(*) AS reccount FROM item WHERE groupid IN $GroupIDs ";
*/
$sqlcmd = "SELECT count(*) AS reccount FROM Item ";
$rs = querydb($sqlcmd, $db_conn);
$RecCount = $rs[0]['reccount'];
$TotalPage = (int) ceil($RecCount / $ItemPerPage);
if (!isset($Page)) {
    if (isset($_SESSION['CurPage'])) $Page = $_SESSION['CurPage'];
    else $Page = 1;
}
if ($Page > $TotalPage) $Page = $TotalPage;
if (!isset($Page) || $Page < 1) $Page = 1;
$_SESSION['CurPage'] = $Page;
$StartRec = ($Page - 1) * $ItemPerPage;
$sqlcmd = "SELECT * FROM Item "
    . "LIMIT $StartRec,$ItemPerPage";
$Contacts = querydb($sqlcmd, $db_conn);
?>

<body>
    <div>
        <script Language="JavaScript">
            function confirmation(DspMsg, PassArg) {
                var name = confirm(DspMsg)
                if (name == true) {
                    location = PassArg;
                }
            }
        </script>
        <div id="logo">商品</div>
        <table class="mistab" width="20%" align="left">
            <?php
            $id = 1;
            $sqlcmd = "SELECT name FROM User WHERE id = '$uid'";
            $rs = querydb($sqlcmd, $db_conn);
            foreach ($rs as $it) {
                $Uname = $it['name'];
            }
            ?>
            <tr>
                <td><img src="getimage(U).php?ID=<?php echo $pid ?>" border="0" width="300"></td>
            </tr>
            <td><?php echo $Uname ?></td>
        </table>
        <table class="mistab" width="60%" align="center">
            <tr>
                <th width="10%">處理</th>
                <th width="10%">序號</a></th>
                <th width="10%">圖片</a></th>
                <th width="15%">商品名</th>
                <th width="20%">簡介</th>
                <th width="5%">狀態</a></th>
                <th width="20%">數量</a></th>
                <th width="10%">價錢</a></th>
                <th width="10%">購買數量</a></th>

            </tr>

            <?php
            foreach ($Contacts as $item) {
                $cid = $item['id'];
                $Name = $item['name'];
                $Price = $item['price'];
                $Amount = $item['amount'];
                //$Valid = $item['valid'];
                $Description = $item['description'];
                $Status = $item['status'];
                //  $GroupName = '&nbsp;';
                //  if (isset($GroupNames[$GroupID])) $GroupName = $GroupNames[$GroupID];
                $DspMsg = "'確定刪除項目?'";
                $PassArg = "'seller.php?action=delete&cid=$cid'";
                echo '<tr align="center"><td>';
                if ($Valid == 'N') {
            ?>
                    <a href="seller.php?action=recover&id=<?php echo $cid; ?>">
                        <img src="../images/recover.gif" border="0" align="absmiddle">
                    </a></td>
                    <td><STRIKE><?php echo $Name ?></STRIKE></td>
                <?php } else { ?>
                    <a href="javascript:confirmation(<?php echo $DspMsg ?>, <?php echo $PassArg ?>)">
                        <img src="../images/cut.gif" border="0" align="absmiddle" alt="按此鈕將本項目作廢"></a>&nbsp;
                    <a href="contactmod.php?cid=<?php echo $cid; ?>">
                        <img src="../images/edit.gif" border="0" align="absmiddle" alt="按此鈕修改本項目"></a>&nbsp;
                    </td>
                    <td><?php echo $cid ?></td>
                <?php } ?>
                <td><img src="getimage.php?ID=<?php echo $cid ?>" border="0" width="320"></td>
                <td><?php echo $Name ?></td>
                <td><?php echo $Description ?></td>
                <td><?php echo $Status ?></td>
                <td><?php echo $Amount ?></td>
                <td><?php echo $Price ?></td>
                <td>
                    <form method="POST">
                        <input type="text" name="Rad1" value="<?php echo $Rad1; ?>">
                        <a href="Cart.php">加入購物車</a>

                    </form>
                </td>
                </tr>

            <?php
            }
            ?>
            <table border="0" width="80%" align="center" cellspacing="0" cellpadding="2">
                <tr>
                    <td width="50%" align="left">
                        <?php if ($TotalPage > 1) { ?>
                            <form name="SelPage" method="POST" action="">
                                第<select name="Page" onchange="submit();">
                                    <?php
                                    for ($p = 1; $p <= $TotalPage; $p++) {
                                        echo '  <option value="' . $p . '"';
                                        if ($p == $Page) echo ' selected';
                                        echo ">$p</option>\n";
                                    }
                                    ?>
                                </select>頁 共<?php echo $TotalPage ?>頁
                            </form>
                        <?php } ?>
                    <td>

                </tr>
            </table>
    </div>
</body>
