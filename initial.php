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


		echo '<div style="font-size: 18px;
		display: block;
		width: 280px;
		padding: 10px;
		margin: 50px auto;
		background: #CCC;
		text-align: center;
		border: 1px solid #BDBDBD;
		color: white;
		text-shadow: 1px 1px 2px #333;
		box-shadow: 3px 3px 0 #EEE;">Loging out please wait...</center>';
		//echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=http://rpdcenter.bll.in.th/oauth/signout.ashx?redirect='.conf('url').'">';.
		echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL='.conf('url').'">';

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

