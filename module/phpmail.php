<?php

class phpmail extends common
{
public $path;
public $display;
function __construct()
	{
		$this->module("name","Mailer");
		$this->module("version","5.0.2");
		$this->path = 'module/phpmail/';
		$this->display = null;
	}

function sendEmail($body,$subject,$sendTo)
	{
	  $mail = new PHPMailer(); // สร้าง object class ครับ
	  $mail->IsHTML(true);
      $mail->IsSMTP(); // กำหนดว่าเป็น SMTP นะ
      $mail->Host = 'ssl://smtp.gmail.com'; // กำหนดค่าเป็นที่ gmail ได้เลยครับ
      $mail->Port = 465; // กำหนด port เป็น 465 ตามที่ google บอกครับ
      $mail->SMTPAuth = true; // กำหนดให้มีการตรวจสอบสิทธิ์การใช้งาน
      $mail->Username = conf('mailAuthen'); // ต้องมีเมล์ของ gmail ที่สมัครไว้ด้วยนะครับ
      $mail->Password = conf('mailPass'); // ใส่ password ที่เราจะใช้เข้าไปเช็คเมล์ที่ gmail ล่ะครับ
      $mail->From = 'service@noeplaza.com'; // ใครเป็นผู้ส่ง
      $mail->FromName = 'Service infomation'; // ชื่อผู้ส่งสักนิดครับ
      $mail->Subject  = $subject; // กำหนด subject ครับ
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
      $mail->MsgHTML =  $body; // ใส่ข้อความเข้าไปครับ
	  $mail->Body = $body;
      $mail->AddAddress($sendTo); // ส่งไปที่ใครดีครับ
  

		if(!$mail->Send()) {
			return false;
		} else {
			return true;
		}
	}

}
?>