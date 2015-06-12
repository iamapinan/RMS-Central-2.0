<?php
$name = explode('|', $client['fullname']);
?>

<form method="post" id="signup_frm">
	<div class="form-row">
		<label>Email</label> <input type="text" name="email" id="email" value="<?php echo $client['email'];?>">
	</div>
	<div class="form-row">
		<label>Password</label> <input type="text" name="password" id="password" value="123456a">
	</div>
	
	<div class="form-row">
		<label>Firstname</label> <input type="text" name="firstName" value="<?php echo $name[0];?>">
	</div>
	<div class="form-row">
		<label>Lastname</label> <input type="text" name="lastName" value="<?php echo $name[1];?>">
	</div>
	<p class="divider"></p>
	<div class="form-row">
		<label></label> 
		<input type="button" onclick="register();return false;" class="btBlue" value=" SignUp to Plicker">
	</div>
	<p class="divider"></p>
	<iframe src="" id="send-login" style="display:block;width:100%;height:400px;border:none;"></iframe>
</form>
