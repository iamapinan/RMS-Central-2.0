<?php
include_once('/core/class.am.php');

if($_GET['cmd']=='adduser')
{
	if(isset($_GET['uid']))
	{
		$idm = new IDM2API;
		if(isset($_GET['debug'])) $idm->debug = 1;
		
		$chkg = $idm->check_group($_GET['gid']);
		
		if($chkg!=true)
		{
			$qschool = 'select sname from '.conf('table_prefix').'_school where sid='.$_GET['gid'];
			$qsch = mysql_query($qschool);
			$school = mysql_fetch_array($qsch);
			//echo $school['sname'];
			$idm->add_group($_GET['gid'], $school['sname']);
			//echo $idm->idm_debug();
		}

		$api = $idm->create_user($_GET['uid'], base64_decode($_GET['up']), $_GET['user'], $_GET['role'], $_GET['gid']); //Call to API
		if(isset($_GET['debug'])) echo $idm->idm_debug();
		echo '<p>&nbsp;</p>';
		echo '<p>&nbsp;</p>';
		echo '<p>&nbsp;</p>';
		if($api==true){
			echo '<p align="center">ระบบได้ดำเนินการสร้างบัญชีผู้ใช้ของท่านเรียบร้อยแล้ว กรุณาคลิกดำเนินการต่อ</p>';
		}
		else{
			echo '<p align="center">ลงทะเบียนบัญชีสำเร็จแล้ว กรุณาดำเนินการต่อ</p>';
		}
		echo '<p>&nbsp;</p>';
		if(isset($_GET['return']))
			echo '<p align="center"><a href="'.$_GET['return'].'" class="button btPost">ดำเนินการต่อ</a></p>';
		else
			echo '<p align="center"><a href="/my" class="button btPost">ดำเนินการต่อ</a></p>';
	}
  }
if($_GET['cmd']=='addsession')
{
	$idm = new IDM2API;
	$idm->debug = 1;
	$idm->add_session($_POST);
	echo $idm->idm_debug();
}
if($_GET['cmd']=='addgroup')
{
	$idm = new IDM2API;
	$idm->debug = 1;
	$idm->add_group($_GET['gid'], $_GET['gn']);
	echo $idm->idm_debug();
}
if($_GET['cmd']=='remsession')
{
	$idm = new IDM2API;
	$idm->debug = 1;
	$result = $idm->remove_session($_GET['sid']);
	echo $idm->idm_debug();
	echo '<br>Status: '.$result;
}
if($_GET['cmd']=='checkgroup')
{
	$idm = new IDM2API;
	$idm->debug = 1;
	$result = $idm->check_group($_GET['gid']);
	echo $idm->idm_debug();
	echo '<br>Status: '.$result;
}

if($_GET['cmd']=='search')
{
	$idm = new IDM2API;
	$idm->debug = 1;
	$retme = $idm->get_userid($_GET['gid'], $_GET['email']);
	echo $idm->idm_debug();
	echo $retme;
}

if($_GET['cmd']=='update_password')
{
	$idm = new IDM2API;
	$idm->debug = 1;
	$idm->update_password($_GET['userid'], $_GET['password']);
	echo $idm->idm_debug();
}

if($_GET['cmd']=='getStudio')
{
	$idm = new IDM2API;
	$retme = $idm->get_userid($_GET['gid'], $_GET['email']);
	//echo $retme;
	$idm->debug = 1;
	$api = $idm->getStudioList($retme);
	echo '<pre>';
	echo $idm->idm_debug();
	print_r($api);
	echo '</pre>';
	
	echo '<h2>Return : '.count($api->Record).'</h2>';
}

if($_GET['cmd']=='getLive')
{
	$idm = new IDM2API;
	$retme = $idm->get_userid($_GET['gid'], $_GET['email']);

	$idm->debug = 1;
	$api = $idm->getLive($retme);
	echo '<pre>';
	echo $idm->idm_debug();
	echo '</pre>';
	echo '<h2>Return : '.count($api->Record).'</h2>';
	echo '<pre>';
	print_r($api);
	echo '</pre>';
}
if($_GET['cmd']=='getCon')
{
	$idm = new IDM2API;
	$retme = $idm->get_userid($_GET['gid'], $_GET['email']);

	$idm->debug = 1;
	$api = $idm->getConference($retme);
	echo '<pre>';
	echo $idm->idm_debug();
	echo '</pre>';
	echo '<h2>Return : '.count($api->Record).'</h2>';
	echo '<pre>';
	print_r($api);
	echo '</pre>';
}

if($_GET['cmd']=='acminfo')
{
	$idm = new IDM2API;
	$idm->debug = 1;
	$api = $idm -> getACMInfo($_GET['cid'], $_GET['userid']);
	//echo $idm->idm_debug();
	echo '<pre>';
	print_r($api);
	echo '</pre>';
	
	if($_GET['q']=='showimg')
	{
		echo '<img src="'.$api['image'].'">';
	}
}
?>