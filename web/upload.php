<?php
if (isset($_POST['Abort'])) {
    header("Location: login.php");
    exit();
}
// Authentication 認證
// require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/config.php");

require_once("../include/db_func.php");
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
if (isset($GoUpload) && $GoUpload == '1') {
    $fname = $_FILES["userfile"]['name'];
    $ftype = $_FILES["userfile"]['type'];
    if ($_POST["fname"] <> $_POST["orgfn"]) $fname = $_POST["fname"];
    $fsize = $_FILES['userfile']['size'];
    if (!empty($fname) && addslashes($fname) == $fname && $fsize > 0) {
        $FName = $_FILES['userfile']['tmp_name'];
        $handle = fopen($FName, "r");
        $Images = fread($handle, $fsize);
        fclose($handle);
        $Images = addslashes($Images);
        $sqlcmd = "UPDATE User SET image='$Images' WHERE id='$id'";
        $result = updatedb($sqlcmd, $db_conn);
        //header("Location: login.php");
        //exit();
    } else {
        $ErrMsg = '<font color="Red">'
            . '檔案不存在、大小為0或超過上限(100MBytes)</font>';
    }
}
require_once("../include/header.php");
?>
<br />
<form enctype="multipart/form-data" method="post" action="" name="ULFile">
    <table width="420" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <span style="font:12pt">
                    <b>會員<?php echo $id ?>照片上傳(上傳完或不上傳者請按結束來離開畫面)</b></span>
            </td>
        </tr>
    </table>

    <input type="hidden" name="MAX_FILE_SIZE" value="102497152">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="GoUpload" value="1">
    <input type="hidden" name="fname">
    <input type="hidden" name="orgfn">
    <br />
    <table width="420" border="0" cellspacing="0" cellpadding="3" align="center">
        <tr>
            <td align="center"> 上傳檔名：<input name="userfile" type="file"> </td>
        </tr>
        <tr>
            <th>
                <input type="button" name="upload" value="新增會員照片" onclick="startload()">&nbsp;&nbsp;
                <input type="submit" name="Abort" value="結束">
            </th>
        </tr>
    </table>
</form>
<?php
?>
<br /> <br />
<div align="center">原存影像<br /><br />
    <img src="getimage.php?ID=<?php echo $id ?>" border="0" width="320">
</div>
<?php
require_once("../include/footer.php");
?>