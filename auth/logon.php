<?php
include '../initial.min.php';

	if(isset($_GET['redirect_to']))
	{
		//@session_start();
		$_SESSION['app_id'] = $_GET['app_id'];
		$_SESSION['app_se'] = $_GET['app_se'];
		$_SESSION['redirect_to'] = $_GET['redirect_to'];

		if(isset($_SESSION['loginid']))
		{
			$user_session = UserInfo();
			if($user_session['provider']==$_REQUEST['source'])
			{
				 header('location: '.$_GET['redirect_to'].'?auth='.$_SESSION['loginid']['nickname']);
			}
			else if($user_session['provider']!=$_REQUEST['source'])
			{
				//echo '/dialog?confirm='.$user_session['provider'];
				 header('location: /dialog?confirm='.$user_session['provider'].'&action=change_login&ch='.$_REQUEST['source']);
			}
		}
		else if(!isset($_SESSION['loginid'])&&$_SESSION['redirect_to']!=''&&$_REQUEST['source']=='facebook') header('location: facebook.php');
		else if(!isset($_SESSION['loginid'])&&$_SESSION['redirect_to']!=''&&$_REQUEST['source']=='bll') header('location: /login');
		else echo 'System fail try again later.!';
	}
?>