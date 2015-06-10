<?php
	include 'initial.min.php';
	$q = $_GET;

//Generate token id.
	$apps_info = get_apps_info($q['app_id'],$q['app_secr']);
	$token = access_token_generator($apps_info['application_id'],$apps_info['app_security']);

//Check allowed domain.

	if($apps_info['app_name']!=''){
			header('Access-Control-Allow-Origin: *');
	}
	else
	{
				header('HTTP/1.1 400 Bad Request');

				exit();
	}

//Prepare result data.
	//$return['generate'] = $token;
	$return['token'] = $token;
	$return['status'] = 1;
	$return['app_name'] = $apps_info['app_name'];

//Generate json result.
	if($q['format']=='json')
	{
		header("Content-type: application/json; charset=utf-8");
		echo json_encode($return);
	}

//Generate simple result.
	if($q['format']=='simple'){
		header("Content-type: text/plain; charset=utf-8");
		echo $token;
	}

?>