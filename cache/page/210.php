<?php
if($client['admin']!=1&&$client['role']!=8) header('location: /403');
?>
<div id="uploadzone">
<input type="file" id="file_upload" accept="*"/>
<input type="hidden" id="tk" value="<?php echo $session_token;?>"/>
	<p>&nbsp;</p>
	<p>โปรดเลือกไฟล์ฐานข้อมูลชื่อผู้ใช้ที่เป็นไฟล์ Excel จากเครื่องของคุณเพื่อนำรายชื่อในตารางเข้าสู่ระบบ</p>
	<p>หากท่านไม่มีไฟล์ฐานข้อมูล Excel กรุณา<a href="/data/file/user.xls">ดาวน์โหลดตัวอย่างไฟล์ที่นี่</a>เพื่อกรอกข้อมูลผู้ใช้ในองค์กร</p>
</div>
<div id="result"></div>