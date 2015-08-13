<?php
//including system files.
	require_once 'configure';
	include_once 'core/class.db.php';
	include_once 'core/class.common.php';
	include_once 'core/class.core.php';

	$browser = getBrowser();
	$bs['name'] = $browser['name'];
	$bs['version'] = $browser['version'];
	$bs['platform'] = $browser['platform'];
	$bs['ipaddr'] = $_SERVER['REMOTE_ADDR'];
	$sid = session_id();
	$time =time();

	$p = new common;
	$do = new mysql;

	$ret['result'] = '';

	if(@$_SESSION['loginid']['nickname']!='') header('location: /my');

if(isset($_POST))
{
	$ck_sql = mysql_query('select * from '.conf('table_prefix').'_profile where user="'.$_REQUEST['username'].'" ');
	$chit = mysql_fetch_array($ck_sql);

	$userinfo = $chit;

	if($userinfo['password']==openssl_encrypt($_REQUEST['password'], 'aes128', ''))
	{
		
		if($userinfo['school']=='') $userinfo['school']='0000000001';
			$info = array(
			'fullname'=>$userinfo['fullname'],
			'nickname'=>$userinfo['user'],
			'email'=>$userinfo['email']
			);

		$sql_school = mysql_query('select sname from '.conf('table_prefix').'_school where sid='.$userinfo['org']);
		$school = mysql_fetch_array($sql_school);
		
		if(conf('domain')!='rms.local.io'){
		include_once('core/class.am.php');
		$idm = new IDM2API;
		$chkg = $idm->check_group($userinfo['org']);
		
		if($chkg==false) $add = $idm->add_group($userinfo['org'], $school['sname']);
	
		$idm->create_user($userinfo['user'], openssl_decrypt($userinfo['password'], 'aes128', ''), str_replace(' ','',$userinfo['fullname']), $userinfo['role'], $userinfo['org']);
		}

		setcookie('userid', base64_encode($userinfo['user']),time()+378432000);

		$res['result']='success';
		$res['msg']='';
		$res['url']=conf('url');

		$_SESSION['loginid'] = $info;
		if(isset($_REQUEST['client'])&&($_REQUEST['client']=='android'||$_REQUEST['client']=='pc'))
		{
			if($_POST['callback_to']!=''){
				header('Refresh: 1; '.$_POST['callback_to']);
			}
			else{
				if(isset($_SESSION['redirect_to']))
					header('Refresh: 1; '.$_SESSION['redirect_to'].'?auth='.$userinfo['user']);
				else
					header('Refresh: 1; /my');
					echo 'Redirecting...';
			}
		}
		else
		{
			header("Content-type: application/json; charset=utf-8");
			echo json_encode($res);
		}
	}
	else
	{
		if(isset($_REQUEST['client'])&&($_REQUEST['client']=='android'||$_REQUEST['client']=='pc'))
		{
				if($_REQUEST['client']=='pc'){
						header("location: /login?error=l101");

				}
				else
				{
						header("location: /login?client=android&error=l101");
						exit();
				}
		}
		$res['result']=0;
		$res['msg']='Wrong username or password.';

		echo json_encode($res);
	}
}
?>