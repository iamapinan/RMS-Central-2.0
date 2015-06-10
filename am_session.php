<?php
include_once('initial.min.php');
include_once('core/class.am.php');
$idm = new IDM2API;

if($_GET['cmd']=='addsession')
{

	$idm->debug = 1;
	$add = $idm->add_session($_POST);
	//echo $idm->idm_debug();

	if($add==true)
	{
		header('location: /my?param=conference');
	}else
	{
		echo 'Sorry, Create a new conference fail please try again!. <a href="javascript:history.back(-1);">Back</a>';
	}
}

if($_GET['cmd']=='editsession')
{
	$idm->debug = 1;
	$update = $idm->update_session($_POST, $_GET['sid']);
	//echo $idm->idm_debug();
	if($update==true)
	{
		echo '<p align="center" style="margin-top: 50px;">Successfully please <a href="javascript:window.opener.location.reload();window.close();" style="padding: 3px 8px;border: 1px solid #ccc;border-radius: 3px;background: #efefef;display:inline-block;margin-left: 10px;text-decoration: none;color: #000;"> Close </a></p>';
	}else
	{
		echo 'Sorry, can not update conference please try again!. <a href="javascript:history.back(-1);">Back</a>';
	}

}
?>