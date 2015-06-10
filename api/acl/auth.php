<?php
//including system files.
include '../../initial.min.php';
include_once 'inc_common.php';

	if(!isset($_SESSION['loginid']['nickname']))
	{
		header('location: /login');
		exit();
	}
	
	
	$author 						= $_REQUEST['author'];
	$modulename 				= $_REQUEST['modulename'];
	$cat								= $_REQUEST['cat'];
	$amhost							= $_REQUEST['amhost'];	
	
	if (!isset($author) || !isset($modulename) || !isset($cat) || !isset($amhost))
	{
		echo 'Parameters error';
		exit();		
	}
	
	if (trim($author) == "" || trim($modulename) == "" || trim($cat) == "" || trim($amhost) == "" )
	{
		echo 'Parameters error';
		exit();		
	}
	
	
	$p = new common;
	$do = new mysql;
		
	//RMS Role Auth
	$bRoleOK = false;
	if (isset($_REQUEST['roleid']) && trim($_REQUEST['roleid']) != "")
	{
		$rRoleIDArray = explode("|",$_REQUEST['roleid']);		
		$count = count($rRoleIDArray);
		
		$sql = mysql_query('select * from '.conf('table_prefix').'_profile where user="'.$_SESSION['loginid']['nickname'].'"');
		$userinfo = mysql_fetch_array($sql);
		if ($userinfo)
		{
			$userRole = $userinfo['role'];
			$userRoleArray = explode(",",$userRole);
			
			for($i=0;$i<$count;$i++)
			{
				$rRoleID = $rRoleIDArray[$i];			
				for($j=0;$j<count($userRoleArray);$j++)
				{
					if (trim($rRoleID) != "" && trim($rRoleID) == trim($userRoleArray[$j]))
					{
						$bRoleOK = true;
						break;
					}
				}
				if ($bRoleOK == true)
				{
					break;
				}
			}
		}
	}
	else
	{
		$bRoleOK = true;
	}		
	
	if (!$bRoleOK)
	{
		header('location: /login');
		exit();
	}	
	
	
	//School Group Auth
	$bGroupOK = false;
	if (isset($_REQUEST['groupid']) && trim($_REQUEST['groupid']) != "")
	{
		$rGroupIDArray = explode("|",$_REQUEST['groupid']);
		$count = count($rGroupIDArray);	
		
		//$strSql = 'select b.group_id from '.conf('table_prefix').'_profile a,'.conf('table_prefix').'_school b Where a.org=b.sid and a.user="'.$_SESSION['loginid']['nickname'].'"';
		$strSql = 'select org from '.conf('table_prefix').'_profile Where user="'.$_SESSION['loginid']['nickname'].'"';
		$sql = mysql_query($strSql);
		$userinfo = mysql_fetch_array($sql);
		
		if (isset($userinfo))
		{
			$org = $userinfo['org'];
			
			$strSql = 'select group_id from '.conf('table_prefix').'_school Where sid="'.$org.'"';
			$sql = mysql_query($strSql);
			$schoolinfo = mysql_fetch_array($sql);
			
			if (isset($schoolinfo))
			{
				$userGroup = $schoolinfo['group_id'];
				for($i=0;$i<$count;$i++)
				{
					if (trim($rGroupIDArray[$i]) != '' && trim($rGroupIDArray[$i]) == $userGroup .'')
					{
						$bGroupOK = true;
						break;
					}
				}
			}
		}
	}
	else
	{
		$bGroupOK = true;
	}
		
	if (!$bGroupOK)
	{
		header('location: /login');
		exit();
	}
	
		
	
	$strProtocol = "http://";
	if (isset($_REQUEST["ssl"]))
	{
		if ($_REQUEST["ssl"] == "1")
			$strProtocol = "https://";
	}	
	
	$strAclUrl =  $strProtocol . $amhost . "/aculearn-idm/v4/opr/content_challenge.asp?author=" . $author . "&cat=" . $cat . "&modulename=" . $modulename;
	$strAclOprUrl = $strProtocol . $amhost . "/aculearn-idm/v4/opr/studioclient.asp?author=" . $author . "&cat=" . $cat . "&modulename=" . $modulename;
	
	$key = aculearn_get($strAclUrl);
	$strAclOprUrl .= "&key=" . $key;
		
	//echo $strAclOprUrl;
	header('location: '.$strAclOprUrl);
	
?>