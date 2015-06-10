<?php
include 'initial.min.php';

	$q = $_GET;

//Generate token id.
	$apps_info = get_apps_info($q['app_id'],$q['app_secr']);
	$token = access_token_generator($apps_info['application_id'],$apps_info['app_security']);

//Check allowed domain.

	if($apps_info['app_name']!=''&&$token==$q['token']){
			header('Access-Control-Allow-Origin: *');
			$ud = UserInfo($q['pf']);
			$udir = conf('root').'/user/'.$q['pf'];
			if($q['pf']=='system'){ echo 1; exit; }
			if($ud['user']=='')
			{
					$dataxp = explode("|x|", $q['data']);
					$flxp = explode(",", $q['f']);

					for($i=0;$i<count($flxp);$i++)
					{
						if($dataxp[$i]=='')
							$update_set .= $dataxp[$i]='""';
						else
							$update_set .= '"'.$dataxp[$i].'"';

						if($i!=(count($flxp)-1))
							$update_set .= ', ';
					}

					$sql = 'INSERT INTO '.conf('table_prefix').'_profile ('.$q['f'].', `update`) VALUES('.$update_set.',1)';
					if(!is_dir($udir)) mkdir($udir, 0777);
					if(mysql_query($sql)){
						echo 'insert';
					}
					else
						echo 'update';
			}
				else
			{

					$dataxp = explode("|x|", $q['data']);
					$flxp = explode(",", $q['f']);

					for($i=0;$i<count($flxp);$i++)
					{
						if($i==0) continue;
						if($dataxp[$i]=='')
							$update_set .= '`'.$flxp[$i].'`=""';
						else
							$update_set .= '`'.$flxp[$i].'`="'.$dataxp[$i].'"';
						if($i!=(count($flxp)-1))
							$update_set .= ', ';
					}
					$update_set = str_replace(',,', ',', $update_set);
					$sql = 'UPDATE '.conf('table_prefix').'_profile SET '.$update_set.', `update`=1 WHERE user="'.$q['pf'].'" ';
/*
					include_once('core/class.am.php');
					$idm = new IDM2API;
					$chkg = $idm->check_group($_POST['ogrid']);
					if($chkg==false) $idm->add_group($_POST['ogrid'], $_POST['ogrid']);

					$_POST['bd'] = $_POST['dd'].'/'.$_POST['mm'].'/'.$_POST['yy'];
					//$idm->debug=1;
					$idmcall = $idm->create_user($q['pf'], $_POST['password'], str_replace(' ', '', $name), $_POST['role'], $_POST['ogrid']); //Call to API
*/
					if(mysql_query($sql))
						echo 1;
					else
						echo 0;
			}



			if($q['photo']!=null){
				$pif = pathinfo($q['photo']);
				copy($q['photo'], $udir.'/'.$pif['basename']);
			}


	}
	else
	{
				header('HTTP/1.1 400 Bad Request');
				exit();
	}

?>