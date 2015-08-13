<?php
$config = parse_ini_file('./config.ini.php');
include 'autoload.php';

$refer = @$_SERVER['HTTP_REFERER'];
$parts = parse_url($refer);
parse_str(@$parts['query'], $query);


//Only allow self connect.
$domain = explode('//', $config['address']);
if(@$parts['host']!=$domain[1]){
	header("HTTP/1.1 401 Unauthorized");
	exit('Access denied.!');
}

$http = new libs\http_request;
$course = new libs\CourseProc(base64_decode($query['crs']));
$db = new libs\Storage($course->sqlite);

header('Content-Type: application/json');
switch($http->request()->q){
	case 'getsessionurl':
		//session?crs=NzA=&q=session_media&sessionid=17d090388f8fa22a2b2033489854696d7cfb0bea&return_type=return_media
		$res['title'] = 'URL สำหรับไฟล์ทั้งหมดนี้';
		$res['body'] = '<textarea class="form-control" rows="2" onclick="$(this).select()">'.$config['address'].$config['install_path'].'session?crs='.$query['crs'].'&q=session_media&sessionid='.$http->request()->id.'&return_type=return_media</textarea>';
		echo json_encode($res);
	break;
	case 'getfileurl':
		$file = $db->select("SELECT * FROM `file` WHERE `id`='".$http->request()->id."'");
		$res['title'] = 'URL สำหรับไฟล์ที่ท่านเลือก';
		if($file[0]['ftype']!='link'){
			$res['body'] = '<textarea class="form-control" rows="2" onclick="$(this).select()">'.$config['address'].$config['install_path'].'data/'.base64_decode($query['crs']).'/files/'.$file[0]['filename'].'</textarea>';
		}else{
			$res['body'] = '<textarea class="form-control" rows="2" onclick="$(this).select()">'.$file[0]['filename'].'</textarea>';
		}
		echo json_encode($res);
	break;
	case 'toggle':
		if($http->post()->data==0) $access = 1; else $access = 0;
		$toggle = $db->query("UPDATE file SET accessible=".$access." WHERE id=".$http->post()->id);
		if($toggle) $res['status'] = 1;
		else $res['status'] = 0;
	break;
	case 'score':
		$set = $http->request()->type.'=\''.$http->request()->point.'\'';

		$update = $db->query("UPDATE score SET $set WHERE uid='".$http->request()->user."'");
		$insert = $db->query("INSERT INTO score_history VALUES(NULL,'".$query['sessionid']."','".$http->request()->user."',".$http->request()->point.",".time().");");
		
		if($update)
		{
			$res['score'] = $http->request()->point;
			$res['type'] = $http->request()->type;
			$res['status'] = 'success';
		}
		echo json_encode($res);
	break;
	case 'config':
		//Set random speed
		$set = 'value=\''.$http->request()->confSpeed.'\'';
		$update = $db->query("UPDATE configs SET $set WHERE title='random_speed'");

		//Set random count
		$set = 'value=\''.$http->request()->randCount.'\'';
		$update = $db->query("UPDATE configs SET $set WHERE title='randomcount'");

		//BMG
		$fd = pathinfo($http->file('Musicfile')->name);
		$nf = md5($fd['filename']).'.'.$fd['extension'];
		$savedata = str_replace("\\", '/', __DIR__.'/data/'.$http->get()->cid.'/files/'.$nf);

		$upload = move_uploaded_file($http->file('mediafile')->tmp_name, $savedata);

		$set = 'value=\''.$http->file('Musicfile')->name.'\'';
		$update = $db->query("UPDATE configs SET $set WHERE title='music'");

		if($update)
		{
			$res['status'] = 'success';
		}
		echo json_encode($res);
	break;
	case 'mediasort':
		$data = json_decode($http->post()->sorted, true);
		foreach($data as $ms){
			$movetoSession = $ms[0]['session'];
			$fcount = count($ms)-1;
			for($x=1;$x<=$fcount;$x++){
				$db->query("UPDATE file SET session='".$movetoSession."' WHERE id=".$ms[$x]['id']);
			}
		}
		
	break;
}