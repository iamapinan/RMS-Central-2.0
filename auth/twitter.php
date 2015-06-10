<?php
/*
 * login_with_twitter.php
 *
 * @(#) $Id: login_with_twitter.php,v 1.2 2012/10/05 09:22:40 mlemos Exp $
 *
 */

	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = 1;
	$client->server = 'Twitter';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/twitter.php';

	$client->client_id = 'lfI884txO5MY1grYUPycA'; $application_line = __LINE__;
	$client->client_secret = 'QKpeXgTW5VzwLXGT6v0iV21CjTjA8LOEyp0KpBqDa0';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Twitter Apps page https://dev.twitter.com/apps/new , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Callback URL must be '.$client->redirect_uri);

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://api.twitter.com/1.1/account/verify_credentials.json',
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
		print_r($user);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OAuth client</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
	$(document).ready(function(){
		//$('#predata').submit();
	});
//-->
</script>
</head>
<body>
<?php
		$user->profile_image_url_https = str_replace('_normal','', $user->profile_image_url_https);
		switch($user->location)
		{
			case 'Thailand':
				$user->location = 'th';
			break;
			default:
				$user->location = 'us';
			break;
		}

		echo '<form id="predata" action="/final.php?prelogin=twitter" method="post">';
		echo '<input type="hidden" name="user" value="'.$user->screen_name.'">';
		echo '<input type="hidden" name="email" value="">';
		echo '<input type="hidden" name="locale" value="'.$user->location.'">';
		echo '<input type="hidden" name="id" value="'.$user->id_str.'">';
		echo '<input type="hidden" name="firstname" value="'.$user->name.'">';
		echo '<input type="hidden" name="lastname" value="">';
		echo '<input type="hidden" name="photo" value="'.$user->profile_image_url_https.'">';
		echo '<input type="hidden" name="gender" value="">';
		echo '<input type="hidden" name="birthday" value="">';
		echo '</form>';

?>
</body>
</html>
<?php
	}
	else
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OAuth client error</title>
</head>
<body>
<h1>OAuth client error</h1>

</body>
</html>
<?php
	}

?>