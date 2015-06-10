<?php
//including system files.
	require_once '../../configure';
	include_once '../../core/class.db.php';
	include_once '../../core/class.common.php';
	include_once '../../core/class.core.php';
	include_once 'inc_common.php';

	/*
	Request Parameters:
	startpos						start position(0 is first)
	recordcount					record count for each time(0 or emtpy is all)
	keyword							for search
	*/
	
	$do = new mysql;

	$ret['code'] = '1';
	$ret['desc'] = 'Succeed';
	
	$startpos = "0";
	$recordcount = "0";
	
	if (isset($_REQUEST['startpos']) && is_numeric($_REQUEST['startpos']) && isset($_REQUEST['recordcount']) && is_numeric($_REQUEST['recordcount']))
	{
		$startpos = $_REQUEST['startpos'];
		$recordcount = $_REQUEST['recordcount'];
	}
	
	
		
header ("Content-Type:text/xml");
echo '<?xml version="1.0" encoding="utf-8" ?>
<Aculearn version="7.0">
	<RetCode>'.$ret['code'].'</RetCode>
	<Desc>'.$ret['desc'].'</Desc>';
if ($ret['code'] == '1')
{	
	$strSql = "SELECT SQL_CALC_FOUND_ROWS * FROM ".conf('table_prefix')."_school_group";
	
	if (isset($_REQUEST['keyword']))
	{
		$strSql = $strSql." Where group_name like '%".$_REQUEST['keyword']."%'";
	}
	$strSql = $strSql." order by group_name";
	if ($recordcount != "0")
	{
		$strSql = $strSql." limit ".$startpos.",".$recordcount;
	}
	
	$cmqr = mysql_query($strSql);
	$ccqr = mysql_query("SELECT FOUND_ROWS() as cc");
	
	$cc = "0";
	if($ccexec = &mysql_fetch_array($ccqr))
		$cc = $ccexec['cc'];
	
	echo '<SchoolGroups count="';
	if ($recordcount != "0")
	{
		echo $recordcount;
	}
	else
	{
		echo $cc;
	}	
	echo '" total="'. $cc .'">';
	
	while($cmexec = @mysql_fetch_array($cmqr))
	{		
		echo '<SchoolGroup ID="'.$cmexec['group_id'].'" Name="'.$cmexec['group_name'].'"></SchoolGroup>';
	}
	
	
	echo '</SchoolGroups>';
	
}
echo '</Aculearn>';
?>