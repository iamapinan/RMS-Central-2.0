<?php
	include_once 'initial.min.php';
	$gen = $p->randomstr(25);


$ret['result'] = '';

if($_REQUEST['sendaction']=='newpassword'&&$_REQUEST["email"]=='')
{
	header('location: /reset?action=invalidmail&token='.$_REQUEST['token']);
}
else if($_REQUEST['sendaction']=='newpassword')
{
	$check_email = 'SELECT * FROM '.conf('table_prefix').'_profile WHERE email = "'.$_REQUEST['email'].'" ';
	$SQL = mysql_query($check_email);
	$result = mysql_fetch_array($SQL);

	if($_REQUEST['email']==$result['email'])
		{
			//Get system email body
			include $_SERVER['DOCUMENT_ROOT'].'/mail_template/std_message.php';

			$link = conf('url').'request_new_password.php?renew='.base64_encode($gen);
			$recp['email'] = $result['email'];
			$recp['name'] = str_replace('|',' ',$result['fullname']);
			$mail_subject = $std_forgot_password['th']['s'];
			$mail_body = sprintf($std_forgot_password['th']['b'], $recp['name'], $link,$link);
			//$mail_body .= sprintf($std_forgot_password['en']['b'], $recp['name'], $link,$link);

			$mail_template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/mail_template/default/content.html');
			$mail_template = str_replace('%s',$mail_subject,$mail_template);
			$mail_template = str_replace('%c',$mail_body,$mail_template);


			//Update status
			$update = mysql_query('UPDATE '.conf('table_prefix').'_profile SET pass_reset = "'.$gen.'" WHERE user="'.$result['user'].'"') || die(mysql_error());

			//Send mail
			$sendmail = $i->sendmail($recp, $mail_subject, $mail_template);

			if($sendmail&&$update){
				$ret['msg'] =  'ระบบได้ส่งอีเมล์สำหรับตั้งรหัสผ่านใหม่ไปยัง';
			}
			else
				$ret['msg'] =  'ระบบส่งอีเมล์ผิดพลาด กรุณาลองใหม่อีกครั้ง';

			if($sendmail&&$update) header('location: /reset?action=approved&um='.$result['email'].'&data='.$ret['msg'].'&token='.$_REQUEST['token']);
			else header('location: /reset?action=systemfail&token='.$_REQUEST['token']);

		}
		else
		{
			echo 'Email not match!';
			header('location: /reset?action=invalidmail&token='.$_REQUEST['token']);
		}

}

$css = '<style type="text/css">
.resf {
padding: 20px 10px;
width: 790px;
background: #FFF;
margin: 10px auto;
display: block;
height: 300px;
}
.view p {
margin-top: 15px;
margin-bottom: 5px;
text-align: center;
}
#pwf{width: 60%;}
input[type^="text"],input[type^="password"]{width: 230px;}
</style>';

if(isset($_GET['renew'])&&$_GET['renew']!=''&&!isset($_REQUEST["newpass"]))
{
$decode = base64_decode($_GET['renew']);
$checking = 'SELECT * FROM '.conf('table_prefix').'_profile WHERE pass_reset = "'.$decode.'" ';
$SQL = $do->query($checking);
$print = $css;
$print .=  $p->toolbar()."\n";
if(@$SQL[0]['user']!='')
	{
	$print .= '
		<div class="resf">
			<div class="view">
				<h2>การเปลี่ยนรหัสผ่าน</h2>
				<hr>
						<form method="post" action="'.$_SERVER['PHP_SELF'].'" id="pwf">
							<p><span class="label">รหัสผ่านใหม่ </span><input type="password" name="newpass" class="text-input passwrod"></p>
							<input type="hidden" name="id" value="'.$SQL[0]['user'].'">
							<p><span class="label">ยืนยันรหัสผ่านใหม่ </span><input type="password" name="newpasscomfirm" class="text-input passwrod"></p>
							<p align="left">กรุณาใส่รหัสผ่านใหม่ให้ตรงกันทั้งสองช่อง แล้วคลิกปุ่มตกลง<br>รหัสผ่านควรมีความยาวไม่ต่ำกว่า 8 ตัวอักษร โดยเป็นตัวอักษร a-z และ 0-9 เท่านั้น</p>
							<p style="text-align: center;"><button class="btPost button" type="submit"> ดำเนินการต่อ </button></p>
						</form>
			</div>
		</div>';
	}
	else
	{
		$print .= '
	<div class="resf">
			<div class="view">
				<h2>ลิ้งค์สำหรับเปลี่ยนรหัสผ่านนี้ได้ถูกใช้งานไปแล้ว ไม่สามารถใช้ซ้ำได้อีก</h2>
				<p><img src="/library/images/_0005_Delete.png"></p>
				<p><a href="/login" class="button btPost"> เข้าสู่ระะบบ </a></p>
			</div>
	</div>
	';
	}
echo $p->globalheader();
eval('?>' . $p->getC('header'). '<?php ');
echo  '<div id="container">
<div id="ContentBody">
<center>';
echo $print;
echo '</center>
</div>
</div>
</div>';

}

if(isset($_REQUEST)&&isset($_REQUEST["id"])&&isset($_REQUEST["newpass"]))
{
$checking = 'SELECT * FROM '.conf('table_prefix').'_profile WHERE user = "'.$_REQUEST["id"].'" ';
$SQL = $do->query($checking);

echo $p->globalheader();
$print = $css;
$print .=  $p->toolbar()."\n";

if($_REQUEST["newpass"]==$_REQUEST["newpasscomfirm"]&&$_REQUEST["newpass"]!=''&&$_REQUEST["newpasscomfirm"]!=''){
	$print .= '
	<div class="resf">
			<div class="view">
				<h2>การเปลี่ยนรหัสผ่านเสร็จสมบูรณ์</h2>
				<p><img src="/library/images/_0007_Tick.png"></p>
				<p><a href="/login" class="button btPost"> เข้าสู่ระะบบ </a></p>
			</div>
	</div>
	';
				include_once('core/class.am.php');
				$idm = new IDM2API;
				$userid = $idm->get_userid($SQL[0]['org'], $SQL[0]['user']);
				$idm_set = $idm->update_password($userid, $_REQUEST['newpass']);

	$update = mysql_query('UPDATE '.conf('table_prefix').'_profile SET password = "'.openssl_encrypt($_REQUEST['newpass'], 'aes128', '').'", pass_reset=1  WHERE user="'.$SQL[0]['user'].'"');
}
else
{
	$print .= '
	<div class="resf">
			<div class="view">
				<h2>คุณใส่รหัสผ่านไม่ตรงกัน กรุณาใส่รหัสใหม่ทั้ง 2 ช่องให้ตรงกัน</h2>
				<p><img src="/library/images/_0006_Cross.png"></p>
				<p><a href="javascript:history.back(-1);" class="button btPost"> กลับไปแก้ไขใหม่ </a></p>
			</div>
	</div>
	';
}
		eval('?>' . $p->getC('header'). '<?php ');
		echo  '<div id="container">
		<div id="ContentBody">
		<center>';
		echo $print;
		echo '</center>
		</div>
		</div>
		</div>';

}
//display footer
	$ft_wrapper = '<div class="clear"></div>';
	$ft_wrapper .= '<div id="footer-wrapper">'."\n";
	$ft_wrapper .= '<div class="ft_link">'.GetConf('footer_link').'</div>';
	$ft_wrapper .= '<div class="footer-text">'.GetConf('footer_text').'</div>';
	$ft_wrapper .= '</div>'."\n";
	$p->privatefooter = $ft_wrapper;
	eval('?>' . $p->footer(). '<?php ');
?>