<?php
/*
RES API acception for RMS in-class system is made by Apinan Woratrakun.
This software made for RMS under control of Rich Media System Co.,Ltd.
=======Version========
Name: RMS In-Class
Version: 1.0a
Create date: 14-7-15
======================

Allow connect from any public domain.
The request accept parameter.
- q 							= query requested
- source 						= identify id
- return_type [optional] 		= json or html, leave blank json data will return
- course 						= course id

Method allow are POST or GET* (GET is limit for long url and must encoded sendding data before)

*/

$config = parse_ini_file('./config.ini.php');
include 'autoload.php';

//Disable error report by PHP.
error_reporting(0);

$http = new libs\http_request;
$course = new libs\CourseProc($http->request()->course);
$db = new libs\Storage($course->sqlite);

//Set of allowed return type.
$responseType = array("json","html","xml");
$_REQUEST['return_type'] = (!isset($_REQUEST['return_type'])&&!in_array($_REQUEST['return_type'], $responseType)) ? 'json' : $_REQUEST['return_type'];

switch ($http->request()->q) {
	case 'sessionlist':
			$sessionlist = $db->select("SELECT * FROM session");
			$res['status'] = 'success';
			$res['count'] = count($sessionlist);
			$res['session'] = $sessionlist;
			echo $http->data_response($res, $http->request()->return_type);
		break;
	case 'SingleScore':
			$ScoreList = $db->select("SELECT * FROM score WHERE uid='".$http->request()->source."'");
			$res = $ScoreList[0];
			$res['status'] = 'success';

			$ScoreHis = $db->select("SELECT * FROM score_history WHERE uid='".$http->request()->source."'");
			$res['score_history'] = $ScoreHis;


			echo $http->data_response($res, $http->request()->return_type);
		break;
	default:
		# code...
		break;
}