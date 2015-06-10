<?php
include_once('initial.min.php');
include_once('core/class.am.php');
$idm = new IDM2API;
if($_GET['dbug']==1) $idm->debug = true;
else $idm->debug = false;

if($_GET['cmd']=='CU')
{		echo '<li style="color: #ffcc00;"><b>Format layout Creating > username/password > status.</b></li>';
		for($x=1;$x<=$_GET['c'];$x++)
		{
			$usr = rand(100001,999999).$x;
			$api = $idm->create_user('test_'.$usr, $usr, $usr, 2, $_GET['g']);
			if($api==true) echo '<li>Creating > test_'.$usr.'/'.$usr.' > <font color="green">Success</font></li>';
			else echo '<li>Creating > test_'.$usr.' > <font color="red">Fail</font></li>';
			if($_GET['dbug']==1){
			echo '<li>======= Debug =======</li>';
			echo $idm->idm_debug();
			echo '<li>------------------------------------------------------</li><br>';
			}
		}
}
 
 if($_GET['cmd']=='CG')
{	
	echo '<li style="color: #ffcc00;"><b>Format layout Creating > group name > status.</b></li>';
	for($x=1;$x<=$_GET['c'];$x++)
		{
			$gn = 'group_'.rand(100001,999999).$x;
			$api = $idm->add_group($gn, $gn);
			if($api==true) echo '<li>Creating > '.$gn.' > <font color="green">Success</font></li>';
			else echo '<li>Creating > '.$gn.' > <font color="red">Fail</font></li>';
			if($_GET['dbug']==1){
				echo '<li>======= Debug =======</li>';
				echo $idm->idm_debug();
				echo '<li>------------------------------------------------------</li><br>';
			}
		}
}

if($_GET['cmd']=='CA')
{
	echo '<li style="color: #ffcc00;"><b>Format layout Creating > groupname > status.</b></li>';
	echo '<li style="color: #ffcc00;"><b>Format layout Creating > username > status.</b></li>';
	for($x=1;$x<=$_GET['c'];$x++)
		{
			$gn = 'group_'.rand(100001,999999).$x;
			$un = rand(100001,999999).$x;
			$api = $idm->add_group($gn, $gn);
			if($api==true){
				echo '<li>Creating group > '.$gn.' > <font color="green">Success</font></li>';
				$uapi = $idm->create_user('test_'.$un, $un, $un, 2, $gn);
				if($uapi==true) echo '<li>Creating user > '.'test_'.$un.' > <font color="green">Success</font></li>';
				else echo '<li>Creating user > '.'test_'.$un.' > <font color="green">Fail</font></li>';
			}
			else{
				echo '<li>Creating > '.$gn.' > <font color="red">Fail</font></li>';
			}

			if($_GET['dbug']==1){
				echo '<li>======= Debug =======</li>';
				echo $idm->idm_debug();
				echo '<li>------------------------------------------------------</li><br>';
			}
		}
}

if($_GET['cmd']=='Bash')
{
	//Check user not create on am
	$sql = 'select * from '.conf('table_prefix').'_profile where amid="" or amid IS NULL limit 0,'.$_GET['c'];

	$q = mysql_query($sql);
	while($res = mysql_fetch_array($q))
	{
		echo '<li>Runing: '.$res['fullname'].' user: ';
		$scq = mysql_query('select * from '.conf('table_prefix').'_school where sid='.$res['org']);
		$scr = mysql_fetch_array($scq);

		$chkg = $idm->check_group($scr['sid']);
		if((int)$chkg==0) $idm->add_group($scr['sid'], $scr['sid']);
			$amid = $idm->get_userid($res['org'], $res['user']);
			if($amid==''){
			$res['password'] = openssl_decrypt($res['password'], 'aes128', '');
			$uapi = $idm->create_user($res['user'], $res['password'], str_replace(' ', '', $res['fullname']), $res['role'], $scr['sid']);
			}
			if($uapi==true){
				$uapi = $res['user'].' ,school: '.$scr['sid'].' = <font color="green">Success</font></li>'; 
			}
			else $uapi = $res['user'].' ,school: '.$scr['sid'].' = <font color="red">Fail</font></li>';		
			echo $uapi;

			if($_GET['dbug']==1){
				echo '<li>======= Debug =======</li>';
				echo $idm->idm_debug();
				echo '<li>------------------------------------------------------</li>';
			}
			$amid = $idm->get_userid($res['org'], $res['user']);
			$amid = $idm->get_userid($res['org'], $res['user']);
			$amid = $idm->get_userid($res['org'], $res['user']);
			$amid = $idm->get_userid($res['org'], $res['user']);
			echo '<li>AMID: '.$amid.'</li><br>';
			
			//clear data.
			$scr = array('');
			$uapi = '';
			$amid='';
			$res = array('');
	}
}

?>