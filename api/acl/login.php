<?php
//including system files.
	require_once '../../configure';
	include_once '../../core/class.db.php';
	include_once '../../core/class.common.php';
	include_once '../../core/class.core.php';
	include_once 'inc_common.php';

	$browser = getBrowser();
	$bs['name'] = $browser['name'];
	$bs['version'] = $browser['version'];
	$bs['platform'] = $browser['platform'];
	$bs['ipaddr'] = $_SERVER['REMOTE_ADDR'];
	$sid = session_id();
	$time =time();

	$p = new common;
	$do = new mysql;

	$ret['code'] = '';
	$ret['desc'] = '';

	
	
	if (!isset($_REQUEST['username']))
	{
		$ret['code'] = '0';
		$ret['desc'] = 'username is empty';
	}
	
	if (!isset($_REQUEST['password']))
	{
		$ret['code'] = '0';
		$ret['desc'] = 'password is empty';
	}
	
	if (!isset($_REQUEST['key']))
	{
		$ret['code'] = '0';
		$ret['desc'] = 'challenge code is empty';
	}
	else
	{	
		if(!checkAmKey($_REQUEST['key'])){
			$ret['code'] = '2';
			$ret['desc'] = 'challenge code is error';
		}
	}
	
	if ($ret['code'] != '0')
	{
		$ck_sql = mysql_query('select * from '.conf('table_prefix').'_profile where email="'.$_REQUEST['username'].'" ');
		$userinfo = mysql_fetch_array($ck_sql);
			
		if(strtolower($userinfo['password'])==strtolower($_REQUEST['password']))
		{
			$ret['code'] = '1';
			$ret['desc'] = 'Succeed';
		}
		else
		{
			$ret['code'] = '0';
			$ret['desc'] = 'Failed to login';
		}
	}

header ("Content-Type:text/xml");
echo '<?xml version="1.0" encoding="utf-8" ?>
<Aculearn version="7.0">
	<RetCode>'.$ret['code'].'</RetCode>
	<Desc>'.$ret['desc'].'</Desc>';
if ($ret['code'] == '1')
{
	echo '<UserInfo>
		<DisplayName>'.str_replace('|',' ',$userinfo['fullname']).'</DisplayName>
		<Email>'.$userinfo['email'].'</Email>	
	</UserInfo>';
	
	echo '<Roles>';
	$cmqr = mysql_query("SELECT * FROM ".conf('table_prefix')."_role order by rid");
	while($cmexec = @mysql_fetch_array($cmqr))
	{		
		echo '<Role ID="'.$cmexec['rid'].'" Name="'.$cmexec['rolename'].'"></Role>';
	}
	echo '</Roles>';
	echo '<HaveSchoolGroup>';
	$cmqr = mysql_query("SELECT * FROM ".conf('table_prefix')."_school_group");
	if ($cmexec = @mysql_fetch_array($cmqr))
		echo '1';
	else
		echo '0';
	echo '</HaveSchoolGroup>';
	
	echo '<IsAdmin>'.$userinfo['admin'].'</IsAdmin>';
	
	
	
}
echo '</Aculearn>';
?>