<?php
echo '
<div class="regisfrm" >
<h3 class="pgtitle"><span class="lgt">LDAP Authentication</span></h3>
<p>&nbsp;</p>
<form method="post" action="ldapsso.php" id="loginfrom">
	'.$errm.'
	<p><span class="label">Username </span><input type="text" value="'.$_REQUEST['config'].'" name="username"  id="username" placeholder="LDAP User" class="text-input"></p>
	<br>
	<p><span class="label">Password </span><input type="password" name="password" value="p@ssw0rd"  id="password" class="text-input"></p>
	<br>
<p><span class="label">Organizational Unit</span><select name="ou">
<option value="Teachers">Teacher</option>
<option value="Students">Student</option>
</select></p>
	<p class="submitbt"><input type="submit" value=" Login " class="btBlue button">
	</p>
</form>
</div>';
?>