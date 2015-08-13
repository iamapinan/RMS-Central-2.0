<?php
	include_once 'configure';
	include_once 'core/class.db.php';
	include_once 'core/class.common.php';
	include_once 'core/class.core.php';
	include_once 'core/UStream.php';
	include_once 'core/social.php';


	header ('Content-type: text/html; charset=utf-8');

//Create class objects
	$p = new common;
	$do = new mysql;
	$i = new core;
	$show = new UStream;
	$social = new social();

	$utoken = sha1($_SESSION['loginid']['nickname']);
	$utoken = $utoken.'_'.session_id();
	$session_token = sha1($_SERVER['REMOTE_ADDR'].session_id().$conf['auth_key']);
	$client_ip = $_SERVER['REMOTE_ADDR'];

//Logout..
if($_GET['go']=='logout'){
		$ref = $_SESSION['redirect_to'];
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
	
 if(isset($_GET['s']))
	$addition = '?ref='.$ref.'&lt='.$_GET['s'];

		echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL='.conf('url').'">';
		echo '
		<body style="margin:0px;">
		<link rel="stylesheet" href="/library/plugin/font-awesome/css/font-awesome.min.css" type="text/css"/>
		<div style="font-size: 38px;
		display: block;
		width: 100%;
		padding: 100px 0;
		margin: 50px auto;
		background: rgba(59, 199, 255, 1);
		text-align: center;
		color: #fff;">
		<p align="center"><i class="fa  fa-sign-out fa-5x"></i></p>
		<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp; กำลังออกจากระบบ</center>';
		//echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=http://rpdcenter.bll.in.th/oauth/signout.ashx?redirect='.conf('url').'">';.
		echo '</body>';

		exit();
}

//Browser Infomation
$browser = getBrowser();
$bs['name'] = $browser['name'];
$bs['version'] = $browser['version'];
$bs['platform'] = $browser['platform'];
	 	//Check login
		if(!isset($_SESSION['loginid']))
			{
			if(isset($_COOKIE['userid']))
				{
					$me = UserInfo(base64_decode($_COOKIE['userid']));
						//Set values.
					if($me['status']==1){
					$info = array(
						'fullname'=>$me['fullname'],
						'nickname'=>$me['user'],
						'email'=>$me['private_mail']
					);

					   $_SESSION['loginid'] = $info;
					}
				}
				else
				{
					header("location: /login");
					exit;
				}
			}

if(isset($_SESSION['loginid']))
{
	$client = UserInfo($_SESSION['loginid']['nickname']);
}

//Go to pages
	$ActiveMN = array();
	if(!isset($_REQUEST['go'])){
				$display = $show->getStream(0);
				$ActiveMN['home'] = 'active';
	}

//User online
if(!isset($_COOKIE['tclogin'])&&isset($_SESSION['loginid']['nickname']))
{
	useronline('init',$client['ssid'],session_id(),$client_ip);
}
else if(isset($_SESSION['loginid']['nickname']))
{
	useronline('reset',$client['ssid'],session_id(),$client_ip);
}

?>

