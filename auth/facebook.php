<?php
/*
 * login_with_facebook.php
 *
 * @(#) $Id: login_with_facebook.php,v 1.2 2012/10/05 09:22:40 mlemos Exp $
 *
 */
	require('http.php');
	require('oauth_client.php');


	$client = new oauth_client_class;
	$client->server = 'Facebook';
	$client->redirect_uri = 'https://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/facebook.php';

	$client->client_id = '1711219642349675';
	$application_line = __LINE__;
	$client->client_secret = '0876c670c49d2a2f9163823b30ef74a3';

	if(strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
		die('Please go to Facebook Apps page https://developers.facebook.com/apps , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to App ID/API Key and client_secret with App Secret');

	/* API permissions
	 */
	$client->scope = 'email, user_birthday, user_about_me, user_photos';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://graph.facebook.com/me',
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{

		//Get user profile photo
		$con = file_get_contents('https://graph.facebook.com/me?redirect=false&fields=picture.height(300).type(square)&access_token='.$client->access_token.'&app_id='.$client->client_id);
		$pb = json_decode($con);

		//Get user friends list
		//$flc = file_get_contents('https://graph.facebook.com/'.$user->username.'/friends?access_token='.$client->access_token.'&app_id='.$client->client_id);
		//$freader = json_decode($flc);
		//echo $freader;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OAuth client</title>
<script src="/library/jquery-1.9.1.min.js" type="text/javascript"></script>
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
	switch($user->locale)
		{
			case 'th_TH':
				$user->locale = 'th';
			break;
			default:
				$user->locale = 'us';
			break;
		}
		//echo $client->access_token;
		echo '<form id="predata" action="/final.php?prelogin=facebook" method="post">';
		echo '<input type="hidden" name="user" value="'.@$user->username.'">';
		echo '<input type="hidden" name="email" value="'.@$user->email.'">';
		echo '<input type="hidden" name="locale" value="'.@$user->locale.'">';
		echo '<input type="hidden" name="id" value="'.@$user->id.'">';
		echo '<input type="hidden" name="firstname" value="'.@$user->first_name.'">';
		echo '<input type="hidden" name="lastname" value="'.@$user->last_name.'">';
		echo '<input type="hidden" name="photo" value="'.$pb->picture->data->url.'">';
		echo '<input type="hidden" name="gender" value="'.@$user->gender.'">';
		echo '<input type="hidden" name="birthday" value="'.@$user->birthday.'">';
		echo '</form>';

/*
		echo '<h2>Friends List</h2>';

	echo '<ul>';
        for ($x=0;$x<50;$x++)
		{
            echo '<li>';
            echo '<div class="pic">';
            echo '<img src="https://graph.facebook.com/' . $freader->data[$x]->id . '/picture"/>';
            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
*/
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
<!-- <pre>Error: <?php echo HtmlSpecialChars($client->error); ?></pre> -->
</body>
</html>
<?php
	}

?>