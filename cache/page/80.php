<?php
//if(isset($_SESSION['loginid']['nickname']))
//	header('location: '.conf('url').'/my');
if(isset($_GET['error'])&&$_GET['error']=='l101')
	$errm = '<p align="center" style="color: red">รหัสบัตรประชาชนหรือรหัสผ่านที่คุณป้อนไม่ถูกต้อง</p><p>&nbsp;</p>';
	
if(isset($_GET['error'])&&$_GET['error']=='l102')
	$errm = '<p align="center" style="color: red">ท่านต้องยืนยันบัญชีของท่านก่อนเข้าสู่ระบบ โดยตรวจสอบที่อีเมล์</p><p>&nbsp;</p>';
	
if(isset($_GET['ref'])) 
	$_SESSION['redirect_to'] = $_GET['ref'];
	
if(isset($_GET['lt']))
{
	switch($_GET['lt'])
	{
		case 'bll': break;
		break;
		default: break;
	}
}

$sqlSh = mysql_query('SELECT * FROM '.conf('table_prefix').'_subject WHERE `group`=18 ORDER BY bid DESC LIMIT 0,4');
echo '<div class="display_0">
<h2 class="title"><i class="fa fa-newspaper-o fa-lg"></i> ข่าวสาร</h2>
<ul class="news-blck">';
while($news = mysql_fetch_array($sqlSh)){

		if($news['image']!='') $imgsrc = '/image?width=100&height=100&cropratio=1:1&image=/data/content/'.$news['req_id'].'/'.$news['image'];
		else $imgsrc = '/image?width=100&height=100&cropratio=1:1&image=/library/images/thumbnail.png';
		echo '<a href="/blog/'.$news['req_id'].'"><li class="news-items"><img src="'.$imgsrc.'" class="bgpic"/> <span class="restext">'.$news['subject'].'<br><span style="color:#aaa;"> @'.date('d/m/Y, H:i',$news['timestamp']).'</span></span></li></a>';
}
echo '</ul></div>';
echo '
<div class="display-1">
<h2 class="title"><i class="fa fa-sign-in fa-lg"></i> เข้าสู่ระบบ</h2>
<div class="regisfrm" >
<p>&nbsp;</p>
	<form method="post" action="gologin.php" id="loginfrom">
	<input type="hidden" name="timestamp" value="'.time().'">
	<input type="hidden" name="callback_to" value="'.$_GET['return'].'">
	<input type="hidden" name="client" value="pc">
	'.$errm.'
<p><span class="label">รหัสบัตรประชาชน </span><input type="text" value="'.$_REQUEST['config'].'" name="username"  id="username" placeholder="รหัสบัตรประชาชน 13 หลัก" class="text-input"></p>
<p><span class="label">รหัสผ่าน </span><input type="password" name="password" value="p@ssw0rd"  id="password" class="text-input"></p>
<p><input type="checkbox" name="logintime" value="forever"> จดจำการเข้าสู่ระบบนี่ไว้.</p>
<p class="submitbt"><button type="submit" class="btn btn-block btn-social btn-vimeo"><i class="fa fa-sign-in"></i> เข้าสู่ระบบ</button>
<a href="/sso-ldap" class="btn btn-block btn-social btn-adn"><i class="fa fa-key"></i> Sign in with LDAP</a>
</p>
<hr><br>
<p>
<a href="/auth/facebook.php" class="btn btn-block btn-social btn-facebook max-width">
  <i class="fa fa-facebook"></i> Sign in with Facebook
</a>
</p>
<p>
<a  href="/auth/google.php" class="btn btn-block btn-social btn-google max-width">
  <i class="fa fa-google-plus"></i> Sign in with Google
</a>
</p><br>
<hr><br>
<p> <a href="/register"><span class="btn btn-block btn-social btn-github"><i class="fa fa-pencil-square-o"></i> สมัครสมาชิกใหม่</span>
<a href="/reset" class="btn btn-link">ลืมรหัสผ่าน</a>
</a>
</p>
</form>
</div>
</div>
';
?>