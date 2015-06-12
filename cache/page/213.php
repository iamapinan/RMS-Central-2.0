<?php
if($_GET['action']!='confirm'&&$_GET['token']!=$session_token)
{
?>
<div class="view">
	<p>&nbsp;</p>
	<h2 align="center">ท่านต้องการลบบัญชีผู้ใช้ของท่านหรือไม่?</h2>
	<p align="center">เมื่อคลิก ยืนยันการลบ ระบบจะลบข้อมูลบัญชีผู้ใช้ทั้งหมดและข้อมูลในการใช้งานระบบ Aculearn ด้วย</p>
	<p>&nbsp;</p>
	<p align="center"><a href="?action=confirm&token=<?php echo $session_token;?>" class="button btPost">ยืนยันการลบ</a> <a href="/my" class="button btGray">ยกเลิก</a></p>
</div>
<?php
}
else
{
	$iam = UserInfo();
	include_once('core/class.am.php');
	$idm = new IDM2API;
	$call_idm = $idm->DeleteUser('',$iam['user']);
	
	echo '
		<div class="view">
			<p>&nbsp;</p>
			<h2 align="center">ดำเนินการเสร็จแล้ว</h2>
			<p align="center"><a href="/go/logout" class="button btPost">กลับ</a></p>
		</div>';

		$sql = 'DELETE FROM '.conf('table_prefix').'_profile WHERE `user` = "'.$_SESSION['loginid']['nickname'].'" ';
		$q = mysql_query($sql);

		
		unset($_SESSION['loginid']);
		$past = time() - 3600;

		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		session_destroy();
	
	
}
?>