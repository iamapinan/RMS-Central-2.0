<?php
//ini_set('display_errors', '1');
include_once __DIR__.'/initial.min.php';

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}
spl_autoload_register('autoload');

use core\rmsldap;

$SSO = new rmsldap;
$SSOConnect = $SSO->rms_ldap_start();

echo '<pre>';
if(getConf('ldap-authentication')==1){
	if($SSOConnect)
	{
		//echo "Connect to ".getConf('ldap-host')." <font color=green>successfully.</font>\n";

		$SSO->USR = $_POST['username'];
		$SSO->PS = $_POST['password'];
		$SSO->OU = $_POST['ou'];

		$searchentry = $SSO->rms_ldap_search();
		if($searchentry['count']!=0)
		{
			for($x=0;$x<$searchentry['count'];$x++){
				$SSO->DN = $searchentry[$x]['distinguishedname'][0];
				$bind = $SSO->rms_ldap_authen();

				if($bind){
					$info = array(
						'fullname'=>$searchentry[$x]['cn'][0],
						'nickname'=>$_POST['username'],
						'email'=>$searchentry[$x]['mail'][0]
					);
					$_SESSION['loginid'] = $info;

					$checkExists = UserInfo($_POST['username']);
					if($checkExists['user']=='')
					{
						$regis = array('username'=>$_POST['username'],
							'fullname'=>str_replace(' ','|',$searchentry[$x]['cn'][0]),
							'mail'=>$searchentry[$x]['mail'][0],
							 'ps'=>openssl_encrypt($SSO->PS, 'aes128', ''));

						$am = array('username'=>$_POST['username'],
							'fullname'=>$searchentry[$x]['cn'][0],
							'password'=>$SSO->PS);

						$rms_register = $SSO->rms_member_register($regis);
						$am_register = $SSO->rms_am_register($am);

						if($rms_register)
							header("location: /idm_api?cmd=adduser&uid=".$_POST['username']."&up=".base64_encode($_POST['password'])."&user=".str_replace(' ','|',$searchentry[$x]['cn'][0])."&gid=".getConf('ldap-org')."&role=".$SSO->rms_role_gen()."&return=/final_registration");
						else
							echo "Unable to login please try again...!\n";

					}
					else
					{

						header('location: /my');
					}
				}
				else{
					echo "Invalid username or password\n";
				}
			}

		}
	}
}
else
{
	exit('This module was disabled by Administrator');
}
echo '</pre>';

exit(200);

?>