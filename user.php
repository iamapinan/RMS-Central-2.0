<?php
	include 'initial.min.php';
	$q = $_GET;
	session_start();
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
//Check referer domain name.
	$ip = $_SERVER['REMOTE_ADDR'];
	$domain = parse_url($_SERVER['HTTP_REFERER']);
	$domain = $domain['host'];

	if(isset($_COOKIE['tclogin'])&&$_COOKIE['tclogin']!='')
	{
		$info = UserInfo();
		$ret['status'] = '1';
		$ret['login'] = true;
		$ret['user'] = $info['user'];
		$ret['fullname'] = $info['fullname'];
		$ret['email'] = $info['email'];
		$ret['image'] = conf('url').'user/'.$info['user'].'/'.$info['avatar'];
		echo json_encode($ret);
	}
	else
	{
		$ret['status'] = '0';
		echo json_encode($ret);
	}


?>