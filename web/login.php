<?php
session_start();
function userauth($ID, $PWD, $db_conn)
{
    $sqlcmd = "SELECT * FROM User WHERE id='$ID' AND valid='Y'";
    $rs = querydb($sqlcmd, $db_conn);
    $retcode = 0;
    if (count($rs) > 0) {
        $PWD = sha1($PWD);
        if ($PWD == $rs[0]['password']) $retcode = 1;
        $_SESSION['id'] = $rs[0]['id'];
    }
    //echo $Password;
    return $retcode;
}
//session_unset();
require_once("../include/gpsvars.php");

$ErrMsg = "";
if (!isset($ID)) $ID = "";

/*
$_SESSION['VerifyCode']=mt_rand(1000,9999);
if(isset($Submit)&& isset($vCode)){
  $VerifyCode=$_SESSION['VerifyCode'];
  if($vCode<>$VerifyCode){
    $ErrMsg='驗證碼錯誤!\n';
  }
}
*/

if (isset($Submit)) {
    //require ("include/configure.php");

    /*
    if (isset($_SERVER['HTTP_VIA']) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
        $UserIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['REMOTE_ADDR'])) $UserIP = $_SERVER['REMOTE_ADDR'];
    */
    require_once("../include/config.php");
    require_once("../include/db_func.php");
    $db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
    if (strlen($ID) > 0 && strlen($ID) <= 16 && $ID == addslashes($ID)) {
        $Authorized = userauth($ID, $PWD, $db_conn);
        //echo $ID;
        //echo $PWD;
        if ($Authorized) {
            $sqlcmd = "SELECT * FROM User WHERE id='$ID' AND valid='Y'";
            $rs = querydb($sqlcmd, $db_conn);
            $ID = $rs[0]['id'];
            $_SESSION['id'] = $ID;
            header("Location: index.php");
            exit();
        }
        $ErrMsg = '<font color="Red">'
            . '您並非合法使用者或是使用權已被停止</font>';
    } else {
        $ErrMsg = '<font color="Red">'
            . 'ID錯誤，您並非合法使用者或是使用權已被停止</font>';
    }
    if (empty($ErrMsg)) $ErrMsg = '<font color="Red">登入錯誤</font>';
}
/*if(isset($Add))
{
	//require ("include/configure.php");
    $dbhost = "sugiuraayano.synology.me:13307";
    $dbname = "project";
    $dbuser = "NOBUNOBU";
    $dbpwd = "Ultimate8763";
    $uDate = date("Y-m-d H:i:s");
    $ErrMsg = "";
    $UserIP = '';
    if (isset($_SERVER['HTTP_VIA']) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
        $UserIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['REMOTE_ADDR'])) $UserIP = $_SERVER['REMOTE_ADDR'];
    header ("Location: ../WebProgrammingProject-master/web/add.php");
            //exit();
}
*/
?>
<HTML>

<HEAD>
    <meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8">
    <meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <title>登錄系統</title>
	<link rel="stylesheet" href="../assets/css/main.css" />
</HEAD>
<script type="text/javascript">
    function setFocus() {
        <?php if (empty($ID)) { ?>
            document.LoginForm.ID.focus();
        <?php } else { ?>
            document.LoginForm.PWD.focus();
        <?php } ?>
    }
</script>
<center>

    <BODY bgcolor="#FFFFCC" text="#000000" topmargin="0" leftmargin="0" rightmargin="0" onload="setFocus()">
        <div style="margin-top:100px;">
            <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                    <td align="center">
                        請於輸入框中輸入帳號與密碼，然後按「登入」按鈕登入。
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <form method="POST" name="LoginForm" action="">
                            <table width="300" border="1" cellspacing="0" cellpadding="2" align="center" bordercolor="Blue">
                                <tr bgcolor="#FFCC33" height="35">
                                    <td align="center">登入系統
                                    </td>
                                </tr>
                                <tr bgcolor="#FFFFCC" height="35">
                                    <td align="center">帳號：
                                        <input type="text" name="ID" size="16" maxlength="16" value="<?php echo $ID; ?>" class="pwdtext">
                                    </td>
                                </tr>
                                <tr bgcolor="#FFFFCC" height="35">
                                    <td align="center">密碼：
                                        <input type="password" name="PWD" size="16" maxlength="16" class="pwdtext">
                                    </td>
                                </tr>
                                <?php
                                /*
  <tr bgcolor="#FFFFCC" height="35">
    <td align=="center"> 驗證碼:
      <input type="text" name="vCode" size="6" maxlength="4" placeholder="4個數字">&nbsp;
      <img src="../images/chapcha.php" style="vertical-align:text-bottom;">
      <input type="submit" name="ReGen" value="重新產生"/>
    </td>
  </tr>
  */
                                ?>
                                <tr bgcolor="#FFCC33" height="35">
                                    <td align="center">
                                        <input type="submit" name="Submit" value="登入">
                                    </td>
                                </tr>

                                <tr bgcolor="#FFCC33" height="35">
                                    <td align="center">
                                        <input type="button" name="Submit" value="加入會員" onclick="location.href='reg.php'" />
                                    </td>
                                </tr>

                            </table>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        <?php if (!empty($ErrMsg)) echo $ErrMsg; ?>
    </body>

</html>