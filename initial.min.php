<?php
//including system files.
	require_once 'configure';
	include_once 'core/class.db.php';
	include_once 'core/class.common.php';
	include_once 'core/class.core.php';
	include_once 'core/UStream.php';
	include_once 'core/social.php';
	include_once 'core/3des.class.php';

//Create class objects
	$p = new common;
	$do = new mysql;
	$i = new core;
	$show = new UStream;
	$social = new social();

//Private token
	$utoken = sha1($_SESSION['loginid']['nickname']);
	$utoken = $utoken.'_'.session_id();
	$session_token = sha1($_SERVER['REMOTE_ADDR'].session_id().$conf['auth_key']);
	$client_ip = $_SERVER['REMOTE_ADDR'];

//Browser Infomation
	$browser = getBrowser();
	$bs['name'] = $browser['name'];
	$bs['version'] = $browser['version'];
	$bs['platform'] = $browser['platform'];
	if($bs['name'] == 'Internet Explorer'||isset($_REQUEST['flashdv'])||$bs['platform']=='linux') $_SESSION['flash'] = 1;

//Check login
		if(!isset($_SESSION['loginid']))
			{
			if(isset($_COOKIE['userid']))
				{
					$me = UserInfo(base64_decode($_COOKIE['userid']));
						//Set values.

					$info = array(
						'fullname'=>$me['fullname'],
						'nickname'=>$me['user'],
						'email'=>$me['private_mail']
					);
					   $_SESSION['loginid'] = $info;
				}
			}

if(isset($_SESSION['loginid']))
{
	$client = UserInfo($_SESSION['loginid']['nickname']);
}

?>