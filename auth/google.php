<?php
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->server = 'Google';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/google.php';

	$client->client_id = '366941162297-dd4tknjjqvhn8mj6219lf1i6s28c36im.apps.googleusercontent.com';
	$application_line = __LINE__;
	$client->client_secret = '2jfZHMFHiBGQaQmE_ajnSmco';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Google APIs console page '.
			'http://code.google.com/apis/console in the API access tab, '.
			'create a new client ID, and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client Secret. '.
			'The callback URL must be '.$client->redirect_uri.' but make sure '.
			'the domain is valid and can be resolved by a public DNS.');

	/* API permissions
	 */
	$client->scope = 'https://www.googleapis.com/auth/userinfo.email '.
		'https://www.googleapis.com/auth/userinfo.profile';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->authorization_error))
			{
				$client->error = $client->authorization_error;
				$success = false;
			}
			elseif(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://www.googleapis.com/oauth2/v1/userinfo',
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OAuth client</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
	$(document).ready(function(){
		$('#predata').submit();
	});
//-->
</script>
</head>
<body>
<?php
		$name = explode(' ', $user->name);

		echo '<form id="predata" action="/final.php?prelogin=google" method="post">';
		echo '<input type="hidden" name="user" value="">';
		echo '<input type="hidden" name="email" value="'.$user->email.'">';
		echo '<input type="hidden" name="locale" value="'.$user->locale.'">';
		echo '<input type="hidden" name="id" value="'.$user->id.'">';
		echo '<input type="hidden" name="firstname" value="'.trim($name[0]).'">';
		echo '<input type="hidden" name="lastname" value="'.trim($name[1]).'">';
		echo '<input type="hidden" name="photo" value="'.$user->picture.'">';
		echo '<input type="hidden" name="gender" value="'.$user->gender.'">';
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