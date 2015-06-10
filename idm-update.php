<?php
include 'initial.min.php';

if($_REQUEST['cmd']=='remove_duo'&&$_REQUEST['sid']!='')
{
		include_once('core/class.am.php');
		$idm = new IDM2API;
		$do = $idm->remove_studio($_REQUEST['sid']);
		if($do)
			echo 1;
		else
			echo 0;
}

?>