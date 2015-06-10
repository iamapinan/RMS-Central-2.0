<?php
	$q = $_GET;
	session_start();
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Content-Type: text/html; charset=utf-8');
	include 'initial.min.php';
	if($q['cmd']=='list')
	{
		$sql = 'select * from '.conf('table_prefix').'_profile where `org`="'.$q['org'].'" and `update`=0 ';

		$query = mysql_query($sql);
		$n = 0;
		while($info = mysql_fetch_array($query)){
			$info['banner'] = ($info['banner']=='') ? '' : conf('url').'user/'.$info['user'].'/gallery/banner/'.$info['banner'];
			$info['avatar'] = ($info['avatar']=='') ? '' : conf('url').'user/'.$info['user'].'/'.$info['avatar'];

			$ret[$n]['ssid'] = $info['ssid'];
			$ret[$n]['user'] = $info['user'];
			$ret[$n]['password'] = $info['password'];
			$ret[$n]['fullname'] = $info['fullname'];
			$ret[$n]['email'] = $info['email'];
			$ret[$n]['photo'] = $info['avatar'];
			$ret[$n]['gender'] = $info['gender'];
			$ret[$n]['local'] = $info['local'];
			$ret[$n]['bday'] = $info['bday'];
			$ret[$n]['mobile'] = $info['mobile'];
			$ret[$n]['role'] = $info['role'];
			$ret[$n]['org'] = $info['org'];
			$ret[$n]['citizen_id'] = $info['citizen_id'];
			$ret[$n]['remark'] = $info['remark'];
			$ret[$n]['grade'] = $info['grade'];
			$ret[$n]['position'] = $info['position'];
			$ret[$n]['verify'] = $info['verify'];
			$ret[$n]['status'] = $info['status'];
			$ret[$n]['admin'] = $info['admin'];
			$ret[$n]['banner'] = $info['banner'];
			$n++;
		}

		echo json_encode($ret, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}
	else
	{
		$ret['user'] = '0';
		echo json_encode($ret);
	}


?>