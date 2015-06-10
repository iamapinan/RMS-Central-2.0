<?php
ob_start();
session_start();
	require_once 'configure';
	include_once 'core/class.db.php';
	include_once 'core/class.common.php';
	include_once 'core/class.core.php';
	include_once 'core/UStream.php';
	include_once 'core/social.php';
$sid = session_id();
//Create class objects
	$p = new common;
	$do = new mysql;
$ret['result'] = '';
if(isset($_POST))
{
    // Your code here to handle a successful verification

$userinfo = mysql_query('select private_mail from '.conf('table_prefix').'_profile where private_mail = "'.$_POST['regmail'].'" ');
$uinf = mysql_fetch_array($userinfo);

$nidmatch = '/^[0-9]{1,}$/';
$passmatch = "/^[A-Za-z0-9]{6,16}$/i";

$ret['result'] .= ($_POST['regfirst']=='') ? 'regfirst|' : '';
$ret['result'] .= ($_POST['reglast']=='') ? 'reglast|' : '';
$ret['result'] .= ($_POST['posex']=='') ? 'posex|' : '';
$ret['result'] .= ($_POST['regmail']==''||checkEmail($_POST['regmail'])==false) ? 'regmail|' : '';
$ret['result'] .= ($_POST['regmailconfirm']==''||checkEmail($_POST['regmail'])==false||$uinf['private_mail']!=''||$_POST['regmailconfirm']!=$_POST['regmail']) ? 'regmailconfirm|' : '';

$ret['result'] .= ($_POST['regpass']==''||strlen($_POST['regpass'])<6||preg_match($passmatch,$_POST['regpass'])==false) ? 'regpass|' : '';

$precheck = str_replace('|',' ',$ret['result']);
$check = trim($precheck);
$user_codename = time();
if($check==''){
	$u = $_POST;
	$ret['status'] = '';
	$name = $u['regfirst'].'|'.$u['reglast'];
	$password = md5($u['regpass']);
	$qr = 'INSERT INTO '.conf('table_prefix').'_profile (session,user,provider,password,fullname,email,private_mail,role,school,gender,language,nation_id,tel) VALUES("'.$sid.'","'.$user_codename.'","local","'.$password.'","'.$name.'","'.$u['regmail'].'","'.$u['regmail'].'","02","","'.$_POST['posex'].'","th","'.$_POST['regnatid'].'","'.$_POST['regtel'].'")';
	mysql_query($qr);
	mkdir($_SERVER['DOCUMENT_ROOT'].'/user/'.$user_codename);

	$ret['msg'] = 'สมัครสมาชิกเรียบร้อยแล้ว ขณะนี้ระบบได้ส่งอีเมล์การสมัครสมาชิกไปยังอีเมล์ของคุณ '.$u['regmail'].'กรุณาตรวจสอบอีเมล์ในกล่องจดหมายเข้า หากไม่พบกรุณาตรวจสอบในกล่องอีเมล์ขยะ';

		echo 'Loading...';

		sleep(1);
		header('location: /register/st/success');
}
else
{
$ret['status'] = 'error';
$ret['msg'] = 'กรุณาแก้ไขข้อมูลของท่านให้ถูกต้อง';
$req = json_encode($ret);
echo $check;
header('location: /register?error=102&data='.$req);
}

}


?>