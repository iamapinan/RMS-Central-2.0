<?php
//if(isset($_SESSION['loginid']['nickname']))
//  header('location: '.conf('url').'/my');
	
?>
<div class="resf">
<h3 class="pgtitle" style="margin: 0px;">ลืมรหัสผ่าน</h3>
<p>&nbsp;</p>
<div class="containblock">
<?php
if($_GET['action'] != 'approved')
{
?>
	<form action="/request_new_password.php" method="post" id="resetfrm" name="resetfrm">
	<div class="warning" <?php if($_GET['action']=='invalidmail') echo 'style="display: block;"'; ?>>ขออภัย! ไม่พบอีเมล์ที่ท่านกรอก</div>
	<div class="warning" <?php if($_GET['action']=='systemfail') echo 'style="display: block;"'; ?>>ขออภัย! ระบบทำงานผิดพลาดกรุณาลองใหม่ภายหลัง</div>
	<p>ใส่อีเมล์ที่ท่านใช้เข้าสู่ระบบ</p>
	<p><input type="text" name="email" placeholder="email@domain.com" class="text-input"><span class="icons2 res-email reset_info">
	<p><input type="submit" class="button btPost" value=" ตกลง "> <a href="/login" class="button btn-link">ยกเลิก</a></p>
	<input type="hidden" value="newpassword" name="sendaction">
	<p>&nbsp;</p>
	</form>
<?php
}
else
{
	echo '<div class="success">'.$_GET['data'].'<br>'.$_GET['um'].'<br>
กรุณาตรวจสอบอีเมล์ของท่าน<br> หากไม่พบกรุณาตรวจสอบใน Junk Mail</div>';
}
?>
</div>
<div class="descblock">
	<h3 class="title">ขั้นตอนการเปลี่ยนรหัสผ่าน</h3>
	<ol class="Usetip">
		<li>ใส่อีเมล์ที่ท่านใช้เข้าสู่ระบบ แล้วคลิกปุ่มตกลง</li>
		<li>ระบบจะส่งลิ้งค์สำหรับเปลี่ยนรหัสผ่านไปยังอีเมล์ที่ท่านระบุ</li>
		<li>เมื่อเปิดอีเมล์ที่ท่านได้รับ ให้ท่านคลิกลิงค์นั้น เพื่อไปยังหน้าเปลี่ยนรหัสผ่าน</li>
		<li>ใส่รหัสผ่านใหม่ที่ต้องการลงไปในช่องว่างทั้งสองช่องให้เหมือนกัน แล้วคลิกตกลง</li>
	</ol>
</div>
</div>
