<?php
//if(isset($_SESSION['loginid']['nickname']))
//	header('location: '.conf('url').'/my');
if($_GET['step'] == 'fail') 
{
	echo '<div class="warning" style="display: block;">Registration fail please try again.</div>';
}

if($_GET['step'] == 'final') 
{
	$sql = mysql_query('SELECT * FROM am_trial15d WHERE user="'.$_GET['user'].'"');
	$data = mysql_fetch_array($sql);
	echo '<style type="text/css">div#ContentBody{border:1px solid #eee !important;border-radius:5px;}</style>';
	echo '<div class="dtail">
		<h3 class="pgtitle">Registration is success</h3>
		<p align="center">Your username and password was sent to your email.</p>
		<br>
		<ul class="data-respon">
			<li><span class="text-label">Expire date</span><span class="resp-text">'.date('d/m/Y', $data['ExpireDate']).'</span></li>		
			<li><span class="text-label">Software</span><span class="resp-text">AcuConference</span></li>
<li><span class="text-label">Download AcuConference</span><span class="resp-text"><a href="'.conf('idm_server').'aculearn-idm/setup/acuconference7_setup.exe">Download</a></span></li>
		</ul>
	</div>';
exit(200);
}


$return_var = json_decode($_GET['data']);
if($_GET['invalid']=='captcha') $ccb = 'style="border: 1px solid #ff0000;" onload="this.focus()"';
if($_GET['error']==101) $answck = 'style="display: block;"';
echo '<form action="/trial-process.php" method="post" id="regisfrm" name="regisfrm">';

echo '<h3 class="pgtitle">Registration form</h3>
<div id="reg_block">';
echo '<p>&nbsp;</p>';


if($_GET['error']==102)
echo '<div class="warning" style="display: block;">'.$return_var->{'msg'}.'</div>';

echo '
<p><span class="label">Organization</span><input type="text" name="company" id="company" value="'.$return_var->{'company'}.'" class="text-input" autofocus required></p>
<p><span class="label">Firstname</span><input type="text" name="firstname" id="regfirst" value="'.$return_var->{'firstname'}.'" class="text-input" autofocus required></p>
<p><span class="label">Lastname</span><input type="text" name="lastname"  id="reglast" value="'.$return_var->{'lastname'}.'" class="text-input" required></p>
<p><span class="label">Email</span> <input type="text" name="email" id="email" onchange="validmail(\'email\')"  value="'.$return_var->{'email'}.'" class="text-input" required placeholder="john@domain.com"></p>
<p><span class="label">Tel.</span>
<input type="text" class="text-input"  style="width: 100px !important;" maxlength="16" value="" name="mobile"  required></p>
<p><span class="label">Country</span> <input type="text" name="country" id="country"  value="'.$return_var->{'country'}.'" class="text-input" required></p>
<p>&nbsp;</p>
<p><span class="label">Username</span><input type="text" name="username"  value="'.$return_var->{'username'}.'" onchange="checkexists(\'username\')" id="username"  class="text-input" autocomplete="off" required></p>
<p><span class="label">Password</span> <input type="password" name="password" id="password" class="text-input" onchange="chkpin(\'password\');" required> </p>

<p>&nbsp;</p>

<hr>
<p><span class="label">&nbsp;</span><img src="/module/cool-php-captcha-0.3.1/captcha.php" id="captcha" /></p>
<p><span class="label">&nbsp;</span><a href="#" onclick=" document.getElementById(\'captcha\').src=\'/module/cool-php-captcha-0.3.1/captcha.php?\'+Math.random();
  document.getElementById(\'captcha-form\').focus();" id="change-image">Can\'t read refresh picture.</a></p>
<p><span class="label">Type word from the picture.</span> <input type="text" name="captcha" id="captcha-form" autocomplete="off" '.$ccb.' required/></p>
<p class="submitbt"><span class="label">&nbsp;</span> <input type="submit" value=" Register " class="btPost button"></p>
</div>';

echo '</form>';

?>