<?php
include_once('initial.min.php');
include_once('core/class.am.php');
$idm = new IDM2API;

//Check user not create on am
$sql = 'select * from '.conf('table_prefix').'_profile where amid="" limit 0,10';

$q = mysql_query($sql);
while($res = mysql_fetch_array($q))
{
	echo 'runing: '.$res['fullname'].'<br>';
	$scq = mysql_query('select * from '.conf('table_prefix').'_school where sid='.$res['org']);
	$scr = mysql_fetch_array($scq);

	$chkg = $idm->check_group($scr['sid']);

	if((int)$chkg==0){
		$idm->add_group($scr['sid'], $scr['sid']);
	}

		//if(isset($_GET['debug'])) $idm->debug = true;
		$res['password'] = openssl_decrypt($res['password'], 'aes128', '');
		$idm->debug = 1;
		$uapi = $idm->create_user($res['user'], $res['password'], str_replace(' ', '', $res['fullname']), $res['role'], $scr['sid']);
		echo $idm->idm_debug();
		if($uapi==true){
			$uapi = $res['user'].' = success<br>'; 
			$idm->get_userid($scr['sid'], $res['user']);
		}
		else $uapi = $res['user'].' = fail<br>';
		echo $uapi;
}
?>