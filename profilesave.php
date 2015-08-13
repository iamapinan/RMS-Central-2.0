<?php
include 'initial.min.php';
$RandId = $p->randomstr(15);
$my=UserInfo();


if($_REQUEST['action']=='newp')
{
	$_POST['citizid'] = ($_POST['citizid']!='') ? $_POST['citizid'] : $_POST['user'];
	$user = UserInfo($_POST['citizid']);
	if($user['user']!='') exit();

	$_POST['source'] = (!isset($_POST['source'])) ? 'bll' : $_POST['source'];

	if(trim($_POST['captcha']) != $_SESSION['captcha']&&$_POST['source']=='bll') {
		$_POST['password'] = base64_encode($_POST['password']);
		header('location: /register?invalid=captcha&data='.json_encode($_POST));
		exit;
	}

	$role = $_POST['role'];
	$code = $RandId;
	$sql = mysql_query('select * from '.conf('table_prefix').'_group where gid ='.$_POST['maj']);
	$res = mysql_fetch_array($sql);

	$remark = 'Major='.$res['name'].',';
	$remark .= 'Guardian=';
	for($in=0;$in<count($_POST['std']);$in++) $remark .= $_POST['std'][$in].'|';

	$remark .= ',Grade='.$_POST['level'];
	$name = $_POST['firstname'].'|'.$_POST['lastname'];
	/*
	== AcuManager API Call ==
	For production use do not enable debug mode.
	*/
	include_once('core/class.am.php');
	$idm = new IDM2API;
	$chkg = $idm->check_group($_POST['ogrid']);
	if($chkg==false) $idm->add_group($_POST['ogrid'], $_POST['school']);

	switch($_POST['gender']){
		case 'male':
		$_POST['gender'] = 'm';
		break;
		case 'female':
		$_POST['gender'] = 'f';
		break;
		default:
		break;
	}

	$_POST['bd'] = ($_POST['bd']=='') ? $_POST['dd'].'/'.$_POST['mm'].'/'.$_POST['yy'] : $_POST['bd'];
	//$idm->debug=1;
	$idmcall = $idm->create_user($_POST['citizid'], $_POST['password'], str_replace(' ', '', $name), $_POST['role'], $_POST['ogrid']); //Call to API
	//echo $idm->idm_debug();
	$verify = getConf('auto_approve');
	$status = getConf('auto_verify');

	if($role==3) $class = '"'.$_POST['classroom'].'"'; else $class="NULL";
	if($role<=2) $school_id = '0000000000';
	$qr = 'INSERT INTO '.conf('table_prefix').'_profile (`user`, `provider`, `password`, `avatar`, `fullname`, `email`, `role`, `org`, `gender`, `language`, `bday`, `citizen_id`, `mobile`, `remark`, `grade`, `position`, `verify`, `status`, `class`, `vc`)
	VALUES ("'.$_POST['citizid'].'","'.$_POST['source'].'","'.openssl_encrypt($_POST['password'], 'aes128', '').'","'.$_POST['photo'].'","'.$name.'",
	"'.$_POST['email'].'","'.$role.'","'.$_POST['ogrid'].'","'.$_POST['gender'].'","th",
	"'.$_POST['bd'].'", "'.$_POST['citizid'].'", "'.$_POST['mobile0'].$_POST['mobile'].'", "'.mysql_real_escape_string($remark).'", "'.$_POST['level'].'", "'.$_POST['position'].'", "'.$verify.'", "'.$status.'", '.$class.', "'.$code.'")';

	$qr_moodle = 'INSERT INTO moodle_user
	VALUES ("'.$_POST['citizid'].'","'.md5($_POST['password']).'","'.$_POST['firstname'].'","'.$_POST['lastname'].'",
	"'.$_POST['email'].'","'.$_POST['ogrid'].'","'.$_POST['school'].'",NULL)';

	$query = mysql_query($qr) || die(mysql_error());
	$moodle_query = mysql_query($qr_moodle) || die(mysql_error());
		if($query){
			//============== Send Email Verification =============//
			//Get system email body
					include $_SERVER['DOCUMENT_ROOT'].'/mail_template/std_message.php';

					$link = conf('url').'verify.php?id='.$code;
					$recp['email'] = $_POST['email'];
					$recp['name'] = str_replace('|',' ',$_POST['name']);
					$mail_subject = $std_verification['th']['s'];
					$mail_body = sprintf($std_verification['th']['b'], $recp['name'], $link,$link);

					$mail_template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/mail_template/default/content.html');
					$mail_template = str_replace('%s',$mail_subject,$mail_template);
					$mail_template = str_replace('%c',$mail_body,$mail_template);

					//Send mail
					$sendmail = $i->sendmail($recp, $mail_subject, $mail_template);
					if($_POST['source']!='bll')
					{
						$info = array(
							'fullname'=>$name,
							'nickname'=>$_POST['citizid'],
							'email'=>$_POST['email']
							);
						setcookie('userid', base64_encode($_POST['citizid']),time()+378432000);
						$_SESION['loginid'] = $info;
						header('location: /my');
						exit;
					}
			//============== End Email Verification =============//
			if($idmcall==''){
				header('location: /idm_api?cmd=adduser&uid='.$_POST['citizid'].'&up='.base64_encode($_POST['password']).'&user='.$name.'&gid='.$_POST['ogrid'].'&role='.$role.'&return=/final_registration');
				exit;
			}
			else{
				header('location: /final_registration');
				exit;
			}
		}
		else{
			echo 'การลงทะเบียนล้มเหลว โปรดลองใหม่ภายหลัง หรือติดต่อผู้ดูแล';
			exit;
		}
}

if(@$_POST['action']=='edit-picture-profile')
{

		if(!empty($_FILES))
		{
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$filename = $_FILES['Filedata']['name']; // Get the name of the file (including file extension).
			$fileTypes = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
			$fileTypes = strtolower($fileTypes);

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/user/'.$_REQUEST['u']))
					mkdir($_SERVER['DOCUMENT_ROOT'].'/user/'.$_REQUEST['u'], 0777);

			$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/user/'.$_REQUEST['u'].'/'.$RandId.'_orig'.$fileTypes;

					if(move_uploaded_file($tempFile,$targetPath))
					{
						// Insert Record
						$strSQL = "UPDATE ".conf('table_prefix')."_profile ";
						$strSQL .="SET avatar = '".$RandId.'_orig'.$fileTypes."', `update`=0 WHERE user = '".$_REQUEST['u']."' ";
						$objQuery = mysql_query($strSQL);

						//if success
						if($objQuery)
						{
							$ret['filename'] = '/image?width=83&height=83&cropratio=1:1&image='.str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetPath);
						}
					}
		}
		else
		{
			$ret['error'] = 'Invalid file upload.';
		}

	echo json_encode($ret);
}//End action upload image

if($_REQUEST['action']=='changepassword')
{
    $iam = UserInfo();
	if(openssl_encrypt($_REQUEST['currentpassword'], 'aes128', '')==$iam['password']&&$_REQUEST['currentpassword']!=''&&$_REQUEST['newpassword']==$_REQUEST['confirmpassword'])
	{

				$_REQUEST['newpassword'] = openssl_encrypt($_REQUEST['newpassword'], 'aes128', '');
				$iSQL = mysql_query('UPDATE '.conf('table_prefix').'_profile SET password="'.$_REQUEST['newpassword'].'",  `update`=0 WHERE user="'.$iam['user'].'"') || die(mysql_error());
				//for moodle
				$mdu = MoodleUser();

				if($mdu['username']!='')
				{
					mysql_query('UPDATE moodle_user SET password="'.$_REQUEST['confirmpassword'].'" WHERE username="'.$iam['user'].'"') || die(mysql_error());
				}
				else{
					$na = explode('|',$iam['fullname']);
					mysql_query('INSERT INTO moodle_user VALUES ("'.$iam['citizen_id'].'","'.md5($_POST['confirmpassword']).'","'.$na[0].'","'.$na[1].'","'.$iam['email'].'","'.$iam['citizen_id'].'","'.$iam['org'].'",NULL)') || die(mysql_error());
				}

				include_once('core/class.am.php');
				$idm = new IDM2API;
				$userid = $idm->get_userid($iam['org'], $iam['user']);
				//$idm->debug=1;
				$idm_set = $idm->update_password($userid, $_REQUEST['confirmpassword']);


				if($iSQL&&$idm_set)
					$return['result']='success';
	}
	else
	{
		$return['result']='error';
	}
	echo json_encode($return);
}

if($_REQUEST['action']=='edit-profile')
{
			$name = $_REQUEST['fn'].'|'.$_REQUEST['ln'];
			$birthday = $_REQUEST['dd'].'/'.$_REQUEST['mm'].'/'.$_REQUEST['yy'];
			$language = $_REQUEST['language'];
			$mobile = $_REQUEST['mobile'];
			$gender = $_REQUEST['gender'];

			$iam = UserInfo();
			if($name!='')
			$set = 'fullname="'.$name.'", ';
			if($birthday!='')
			$set .= 'bday="'.$birthday.'", ';
			if($gender!='')
			$set .= 'gender="'.$gender.'", ';
			if($language!='')
			$set .= 'language="'.$language.'",';
			if($language!='')
			$set .= 'mobile="'.$mobile.'", ';
			$set .= 'email="'.$_REQUEST['email'].'"';

			$sql = mysql_query('select * from '.conf('table_prefix').'_group where gid ='.$_REQUEST['grp']);
			$res = mysql_fetch_array($sql);

		if($iam['role']==5){
			$remark = 'Major='.$res['name'].',';
			$remark .= 'Guardian=';
			$remark .= ',Grade=';
			$set .= ',remark = "'.$remark.'"';
		}
			include_once('core/class.am.php');
			$idm = new IDM2API;
			$userid = $idm->get_userid($iam['org'], $iam['user']);
			$idm_set = $idm->update_name($userid, $name);

			$strSQL = "UPDATE ".conf('table_prefix')."_profile ";
			$strSQL .="SET ".$set.", `update`=0 WHERE ssid = ".$_REQUEST['uid'];
			//echo $strSQL;
			$objQuery = mysql_query($strSQL);

				$result['save']=$objQuery? 'success':'error';

			echo json_encode($result);
}

if($_REQUEST['action']=='verify_stat'){
			$strSQL = "UPDATE ".conf('table_prefix')."_profile ";
			$strSQL .="SET verify=1, `update`=0 WHERE ssid = ".$_REQUEST['uid'];
			$objQuery = mysql_query($strSQL);
			$result=($objQuery) ? '1':'0';
			echo $result;
}

if($_REQUEST['action']=='change_status'){
			$strSQL = "UPDATE ".conf('table_prefix')."_profile ";
			$strSQL .="SET status=1, `update`=0 WHERE ssid = ".$_REQUEST['uid'];
			$objQuery = mysql_query($strSQL);
			$result=($objQuery) ? '1':'0';
			echo $result;
}


if($_REQUEST['action']=='verify')
{
	if($_REQUEST['token']==$session_token)
	{
		$user_dir = $_SERVER['DOCUMENT_ROOT'].'/user/'.$_REQUEST['u'];

		if(count($_FILES['file']['name'])==2)
		{
			if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/user/'.$_REQUEST['u']))
					mkdir($_SERVER['DOCUMENT_ROOT'].'/user/'.$_REQUEST['u'], 0777);

			for($x=0;$x<=1;$x++){
				move_uploaded_file($_FILES['file']['tmp_name'][$x], $user_dir.'/verify_photo_'.$x.'.jpg');
			}

			$strSQL = "UPDATE ".conf('table_prefix')."_profile ";
			$strSQL .="SET verify=2, mobile='".$_REQUEST['mobile']."',  `update`=0 WHERE user = '".$_REQUEST['u']."'";
			$query = @mysql_query($strSQL);
			if($query&&isset($_SESSION['redirect_to'])) header('location: '.$_SESSION['redirect_to'].'?auth='.$_REQUEST['u']);
			else if($query&&!isset($_SESSION['redirect_to'])) header('location: '.conf('url').'verify');
		}
		else
			header('location: /verify?erms=ต้องมี 2 ไฟล์เท่านั้น');
	}
else
	{
		header('location: /verify?erms=ที่มาไม่ถูกต้อง');
	}
}
if($_REQUEST['action']=='plicker_signup')
{
    $sql = mysql_query("insert into tc_plicker values(".$my['ssid'].",'".$_REQUEST['id']."')");

    if($sql) echo 1;
    else echo 0;
}
?>