<?php
include 'initial.min.php';

if($_GET['cmd']=='logout')
			{
				if (isset($_SERVER['HTTP_COOKIE'])) {
				$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
				foreach($cookies as $cookie) {
						$parts = explode('=', $cookie);
						$name = trim($parts[0]);
						setcookie($name, '', time()-1000);
						setcookie($name, '', time()-1000, '/');
					}
				}
				session_destroy();

					if($_SESSION['loginid']['nickname']=='')
						header('location: '.$_GET['callback']);
					else{
						echo '<meta http-equiv="refresh" content="0">Redirecting...';
					}
					exit();
			}

if($_GET['cmd']=='set_status'&&$_GET['id']!='')
{
	$sql = 'update '.conf('table_prefix').'_profile set `update`=1 where ssid='.$_GET['id'];

	$cmd = mysql_query($sql);
	if($cmd) echo 1;
	else echo 0;
}
?>