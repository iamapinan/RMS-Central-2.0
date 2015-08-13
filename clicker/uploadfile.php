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


//Copy
if(!empty($http->file('mediafile')->tmp_name))
{
	$fd = pathinfo($http->file('mediafile')->name);
	$nf = md5($fd['filename']).'.'.$fd['extension'];
	$savedata = str_replace("\\", '/', __DIR__.'/data/'.$http->get()->cid.'/files/'.$nf);

	$upload = move_uploaded_file($http->file('mediafile')->tmp_name, $savedata);
	if($upload){

		$db->query("INSERT INTO file VALUES(NULL, '$nf', '".$query['sessionid']."','".$fd['extension']."','".$fd['basename']."',1);");
		$image = $course->imageSetup($config['install_path'].$config['data_path'].$http->get()->cid.'/files/'.$nf,'100x100', $fd['extension']);
		$selectid = $db->select("SELECT id FROM file WHERE filename='$nf'");
		$res['status'] = 'success';
		$res['file'] = $fd['basename'];
		$res['id'] = $selectid[0]['id'];
		$res['title'] = $fd['basename'];
		$res['type'] = $fd['extension'];
		$res['image'] = $image;
		$res['simplePath'] = $config['install_path'].$config['data_path'].$http->get()->cid.'/files/'.$nf;

		echo json_encode($res);
	}

}

if(@$http->request()->uploadtype=='link')
{
	$insert = $db->query("INSERT INTO file VALUES(NULL, '".$http->request()->link."', '".$query['sessionid']."','link','".$http->request()->ftitle."',1);");
	$selectid = $db->select("SELECT id FROM file WHERE filename='".$http->request()->link."'");
		$res['status'] = 'success';
		$res['id'] = $selectid[0]['id'];
		$res['file'] = $http->request()->link;
		$res['title'] = $http->request()->ftitle;
		$res['type'] = "link";
		$res['image'] = $course->imageSetup("","100x100","URL");


		echo json_encode($res);	
}