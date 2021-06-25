<?php
// Authentication 認證

require_once("../include/auth.php");
// session_start();
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
//require_once("../include/configure.php");
require_once("../include/config.php");
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);

if (isset($action) && $action == 'delete' && isset($cid)) {
	$sqlcmd = "SELECT * FROM Cart WHERE cid='$cid'";
	$rs = querydb($sqlcmd, $db_conn);
	if (count($rs) > 0) {
		$sqlcmd = "DELETE FROM Cart WHERE cid='$cid'";
		$result = updatedb($sqlcmd, $db_conn);
	}
}
$sqlcmd = "SELECT count(*) AS reccount FROM Cart ";
$rs = querydb($sqlcmd, $db_conn);
$RecCount = $rs[0]['reccount'];
$TotalPage = (int) ceil($RecCount / $CartPerPage);

if (!isset($Page)) {
	if (isset($_SESSION['CurPage'])) $Page = $_SESSION['CurPage'];
	else $Page = 1;
}
if ($Page > $TotalPage) $Page = $TotalPage;
if (!isset($Page) || $Page < 1) $Page = 1;
$_SESSION['CurPage'] = $Page;
$curUser = $_SESSION['buyer_id'];
$StartRec = ($Page - 1) * $CartPerPage;
$sqlcmd = "SELECT * FROM Cart WHERE buyer_id='$curUser' LIMIT $StartRec,$CartPerPage";

$Contacts = querydb($sqlcmd, $db_conn);
?>

<head>
	<title>購物車</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="../assets/css/main.css" />

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
				</Script>

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
						foreach ($Contacts as $Item) {
							$cid = $Item['cid'];
							$Name = $Item['name'];
							$Amount = $Item['buyamount'];
							$Price = $Item['price'];

							$DspMsg = "'確定刪除項目?'";
							$PassArg = "'updatedb.php?action=delete&cid=$cid'";
							echo '<tr align="center"><td>';
						?>
							<a href="javascript:confirmation(<?php echo $DspMsg ?>, <?php echo $PassArg ?>)">
								<img src="/images/cut.gif" border="0" align="absmiddle" alt="按此鈕將本項目作廢"></a>&nbsp;
							<a href="contactmod.php?cid=<?php echo $cid; ?>">
								<img src="/images/edit.gif" border="0" align="absmiddle" alt="按此鈕修改本項目"></a>&nbsp;
							</td>
							<td><?php echo $cid ?></td>
							<td><?php echo $Name ?></td>
							<td><?php echo $Amount ?></td>
							<td><?php echo $Price ?>元</td>

							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<form action="updatedb.php" method="POST">
				<input type="hidden" name='cid'value="<?php echo $cid?>">
				<input type="hidden" name='buyAmount'value="<?php echo $Amount?>">
				<input type="hidden" name='buyer_id'value="<?php echo $curUser?>">
				<input type="submit" value="送出">
			</form>
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
							104台北市中山區中山北路三段40號1樓
						</li>
						<li>
							<h3 class="icon fa-phone"><span class="label">Phone</span></h3>
							電話：02 2182 2928
						</li>
						<li>
							<h3 class="icon fa-envelope-o"><span class="label">Email</span></h3>
							<a href="mailto:test@ms.ttu.edu.tw">test9527@ms.ttu.edu.tw</a> <br>
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
		<ul class="copyright">

			<ul class="icons">
				<li><a href="mailto:tkchou@mail.ncku.edu.tw" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
			</ul>
			<ul class="copyright">
				<li>&copy; leem wlm fht</li>

				<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
			</ul>
	</footer>

</body>