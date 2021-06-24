<?php
// Authentication 認證
//require_once("../include/auth.php");
// session_start();
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/config.php");

///////////////////////
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
/*
$sqlcmd = "SELECT * FROM user WHERE loginid='$LoginID' AND valid='Y'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die('Unknown or invalid user!');
$UserGroupID = $rs[0]['groupid'];
*/
/*
if (isset($action) && $action == 'recover' && isset($id)) {
	$sqlcmd = "SELECT * FROM Item WHERE id='$id'";
	$rs = querydb($sqlcmd, $db_conn);
	if (count($rs) > 0) {
		$sqlcmd = "UPDATE Item SET valid='Y' WHERE id='$id'";
		$result = updatedb($sqlcmd, $db_conn);
	}
}
if (isset($action) && $action == 'delete' && isset($id)) {
	$sqlcmd = "SELECT * FROM Item WHERE id='$id' AND valid='Y'";
	$rs = querydb($sqlcmd, $db_conn);
	if (count($rs) > 0) {
		$sqlcmd = "UPDATE Item SET valid='N' WHERE id='$id'";
		$result = updatedb($sqlcmd, $db_conn);
	}
}
*/
$ItemPerPage = 5;
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
$sqlcmd = "SELECT * FROM Item LIMIT $StartRec,$ItemPerPage";
$Contacts = querydb($sqlcmd, $db_conn);
?>

<head>
	<title>購物車</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="assets/css/main.css" />
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
</head>

<body id="top">
	
	<!-- Header -->
	<header id="header">

	</header>

	<!-- Main -->
	<div id="main">

		<!-- One -->
		<section id="one">
			<header class="major">
				<h2>您的購物車</h2>
			</header>
			<p></p>
			<ul class="actions">
				<li><a href="index.php" target="_blank" class="button">返回首頁</a></li>
			</ul>
		</section>
		<section>
			<div class="table-wrapper">
				<script Language="JavaScript">
					function confirmation(DspMsg, PassArg) {
						var name = confirm(DspMsg)
						if (name == true) {
							location = PassArg;
						}
					}
				</script>
				<table class="alt">
					<tbody>
						<tr>
							<th width="10%">處理</th>
							<th width="10%">CID</th>
							<th width="10%">商品</th>
							<th width="15%">數量</th>
							<th width="15%">價格</th>
						</tr>
						<?php
						foreach ($Contacts as $item) {
							$id = $item['id'];
							$Name = $item['name'];
							$Amount = $item['amount'];
							$Price = $item['price'];
							$Valid = 'N';
							
							$DspMsg = "'確定刪除項目?'";
							$PassArg = "'contactmgm.php?action=delete&id=$id'";
							echo '<tr align="center"><td>';
							if ($Valid == 'N') {
						?>
								<a href="index,html?action=recover&id=<?php echo $id; ?>">
									<img src="/images/recover.gif" border="0" align="absmiddle">
								</a></td>
								<td><STRIKE><?php echo $Name ?></STRIKE></td>
							<?php } else { ?>
								<a href="javascript:confirmation(<?php echo $DspMsg ?>, <?php echo $PassArg ?>)">
									<img src="/images/cut.gif" border="0" align="absmiddle" alt="按此鈕將本項目作廢"></a>&nbsp;
								<a href="contactmod.php?id=<?php echo $id; ?>">
									<img src="/images/edit.gif" border="0" align="absmiddle" alt="按此鈕修改本項目"></a>&nbsp;
								</td>
								<td><?php echo $id ?></td>
							<?php } ?>
							<td><?php echo $Name ?></td>
							<td><?php echo $Amount ?></td>
							<td><?php echo $Price ?></td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</section>
		<!-- Two -->
		<section id="two">
			<h2>購物須知</h2>
			<div>
				<ol>
					<li>在大同購物，您能夠用最划算的價格買到所有需要、想要的商品。</li>
				</ol>
			</div>
		</section>

		<!-- Three -->
		<section id="three">
			<div class="row">
				<div class="4u$ 12u$(small)">
					<ul class="labeled-icons">
						<li>
							<h3 class="icon fa-home"><span class="label">Address</span></h3>
							大同大學
						</li>
						<li>
							<h3 class="icon fa-phone"><span class="label">Phone</span></h3>
							電話：1922
						</li>
						<li>
							<h3 class="icon fa-envelope-o"><span class="label">Email</span></h3>
							<a href="mailto:tkchou@mail.ncku.edu.tw">test9527@ms.ttu.edu.tw</a>
						</li>
					</ul>
				</div>
			</div>
		</section>

	</div>

	<!-- Footer -->
	<footer id="footer">
		<ul class="icons">
			<li><a href="mailto:tkchou@mail.ncku.edu.tw" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
		</ul>
		<ul class="copyright">
			<li>&copy; leem wlm fht</li>
			<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
		</ul>
	</footer>

</body>