<?php
include 'initial.min.php';

	if(trim($_POST['captcha']) != $_SESSION['captcha']) {
		$_POST['password'] = base64_encode($_POST['password']);
		header('location: /trial?invalid=captcha&data='.json_encode($_POST));
		exit;
	}

//Acumanager
include_once('core/class.am.php');
$idm = new IDM2API;
$chkg = $idm->check_group('15day-demo');
if($chkg==false) $idm->add_group('15day-demo', '15day-demo');
//$idm->debug=1;
$name = $_POST['firstname'].'%20'.$_POST['lastname'];
$run = $idm->create_user($_POST['username'], $_POST['password'], $name, 15, '15day-demo');
$expiredate = time()+2592000;
//echo $idm->idm_debug();

$sql = 'INSERT INTO am_trial15d VALUES(NULL,"'.$_POST['firstname'].'","'.$_POST['lastname'].'","'.$_POST['username'].'","'.$_POST['password'].'","'.$_POST['company'].'","'.$_POST['email'].'","'.$_POST['country'].'","'.$_POST['mobile'].'","'.time().'","'.$expiredate.'")';
$query = mysql_query($sql);

if($query&&$run)
{
	$mailbody = '
	<b>Welcome to Aculearn Product.</b>
	<br><br>
	Hello '.str_replace('%20',' ',$name).',
	<br>This is aculearn products registration confirmation email. Below is your account data.<br>
	<br>Username : '.$_POST['username'].'
	<br>Password : '.$_POST['password'].'
	<br>Registered products : AcuConference
	<br>AcuConference Server : '.conf('idm-server').'
	<br>Expire date : '.date('d/m/Y', $expiredate).'
	<br><br>
	Aculearn support by Rich Media System Co.,Ltd<br>
		';

		include_once($_SERVER['DOCUMENT_ROOT'].'/module/phpmail/class.phpmailer.php');
		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		//Tell PHPMailer to use SMTP
		$mail->IsSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug  = 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		//$mail->Host       = "106.0.176.36";
		//$mail->Host       = "127.0.0.1";
		$mail->Host       = "mail.bll.in.th";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port       = 25;
		//Whether to use SMTP authentication
		$mail->SMTPAuth   = true;
		//Username to use for SMTP authentication
		$mail->Username   = "mail-noreply@bll.in.th";
		//Password to use for SMTP authentication
		$mail->Password   = "P@ssw0rd";
		//Set who the message is to be sent from
		$mail->SetFrom('mail-noreply@richmediasystem.co.th', 'AcuLearn Products');
		//Set an alternative reply-to address

		$mail->AddReplyTo('mail-noreply@richmediasystem.co.th', 'AcuLearn Products');
		//Set who the message is to be sent to
		$mail->AddAddress($_POST['email'],$name);
		//Set the subject line
		$mail->Subject = 'AcuLearn Product Registration';
		//Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body
		$mail->MsgHTML($mailbody);
		//Replace the plain text body with one created manually
		$mail->AltBody = strip_tags($mailbody);
		//Attach an image file
		//$mail->AddAttachment('images/phpmailer-mini.gif');
		//Send the message, check for errors
		$mail->Send();

	//$sendmail = $i->sendmail($_POST['email'], 'AcuLearn Products', $mailbody);
	sleep(1);
	header('location: /trial?step=final&user='.$_POST['username']);
}
else
	header('location: /trial?step=fail');