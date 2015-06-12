<?php
//if(isset($_SESSION['loginid']['nickname']))
//	header('location: '.conf('url').'/my');
//if(conf('port')==443) header('location: http://'.conf('domain').':80/register');
$return_var = json_decode($_GET['data']);
if($_GET['invalid']=='captcha') $ccb = 'style="border: 1px solid #ff0000;" onload="this.focus()"';
if($_GET['error']==101) $answck = 'style="display: block;"';
echo '<form action="/profilesave.php?action=newp" method="post" id="regisfrm" name="regisfrm">';
if($_GET['st']=='success')
{
echo '<h2 class="pgtitle">ยินดีต้อนรับ</h2><br>
<div class="fw">';
echo '<p>&nbsp;</p>';
echo '<p align="center">ลงทะเบียนเสร็จเรียบร้อยแล้ว ท่านสามารถใช้อีเมล์และรหัสผ่านที่คุณตั้งเพื่อเข้าสู่ระบบได้เลย</p>';
echo '<p>&nbsp;</p>';
echo '<p align="center"><a href="/login?wp=loader" class="button btGray">Login</a></p>';
echo '</div>';
}
else
{
echo '<h2 align="center"><i class="fa fa-pencil-square-o"></i> สมัครสมาชิกใหม่</h2><br>
<div id="reg_block">';
echo '<p>&nbsp;</p>';
if($_GET['error']==102)
echo '<div class="warning" style="display: block;">'.$return_var->{'msg'}.'</div>';

echo '<p><span class="label">ชื่อ</span><input type="text" name="firstname" id="regfirst" value="'.$return_var->{'firstname'}.'" class="text-input" autofocus required></p>
<p><span class="label">นามสกุล</span><input type="text" name="lastname"  id="reglast" value="'.$return_var->{'lastname'}.'" class="text-input" required></p>
<p><span class="label">อีเมล์</span> <input type="text" name="email" id="email" onchange="validmail(\'email\')"  value="'.$return_var->{'email'}.'" class="text-input" required placeholder="john@domain.com"></p>
<p><span class="label">เพศ</span> <select name="gender" id="gender" required>
<option value="" selected>เลือกเพศ</option>
<option value="m">ชาย</option>
<option value="f">หญิง</option>
</select>
</p>
<p><span class="label">เบอร์มือถือ</span><select name="mobile0"><option value="08">08</option><option value="09">09</option></select>
<input type="text" class="text-input" placeholder="xxxxxxxx" style="width: 80px !important;" maxlength="8" value="" name="mobile"  required></p>
<p><span class="label">วันเกิด</span>
<span class="bdpick">';

echo '<select required name="dd" id="dd" placeholder="วัน">';
echo '<option selected>วันที่</option>';
for($d=1;$d<=31;$d++)
echo '<option value="'.$d.'">'.$d.'</option>';
echo '</select>&nbsp;';

echo '<select required name="mm" id="mm" placeholder="เดือน">';
echo '<option selected>เดือน</option>';
for($m=1;$m<=12;$m++)
echo '<option value="'.$m.'">'.$m.'</option>';
echo '</select>&nbsp;';

echo '<select required name="yy" id="yy" placeholder="ปี ค.ศ.">';
echo '<option selected>ปี ค.ศ.</option>';
for($y=1930;$y<=2003;$y++)
echo '<option value="'.$y.'">'.$y.'</option>';
echo '</select>';

echo '</span></p>
<p style="color: blue;"><span class="label">&nbsp;</span> ลบ พ.ศ. ด้วย 543 เพื่อให้เป็น ค.ศ. </p>
<p>&nbsp;</p>
<p><span class="label">หมายเลขบัตรประชาชน</span><input type="text" name="citizid" maxlength="13" value="'.$return_var->{'citizid'}.'" onchange="checkexists(\'citizid\')" id="citizid"  class="text-input" autocomplete="off" placeholder="เลขบัตรประชาชน 13 หลักเท่านั้น" required></p>
<p><span class="label">รหัสผ่าน</span> <input type="password" name="password" id="password" class="text-input" onchange="chkpin(\'password\');" required> </p>
<p style="color: blue;"><span class="label">&nbsp;</span> ความยาวไม่น้อยกว่า 8 ตัวอักษรและเป็นตัวเลขผสมกับตัวอักษรเท่านั้น</p>
<p>&nbsp;</p>
<p><span class="label">บทบาท</span><select name="role"  id="role'.$lev['rid'].'"  onChange="switch_role($(this).find(\':selected\').val());">';
		$sql_lev = mysql_query('select * from '.conf('table_prefix').'_role order by rid asc limit 0,6');
		while($lev = mysql_fetch_array($sql_lev))
		{
			$options = '';
			//if($lev['rid']==3) $options = 'disabled';
			echo '<option value="'.$lev['rid'].'" '.$options.'> '.$lev['rolename'].'</option>';
		}
echo '</select></p>
<span class="subset"></span>
<div style="position: relative;" class="org">
<p><span class="label">โรงเรียน</span><input type="text" name="school" onchange="validschool(\'schoolsh\')" placeholder="ป้อนชื่อโรงเรียนโดยไม่ต้องมีคำว่า โรงเรียน นำหน้า" id="schoolsh"  class="text-input" required autocomplete="off">
<div id="shoolshpre"><ul class="scview"></ul></div>
<p style="color: blue;"><span class="label">&nbsp;</span> ป้อนชื่อโรงเรียนของท่านโดยไม่ต้องมีคำว่า โรงเรียน นำหน้าแล้วเลือกจากรายการ</p>
<input type="hidden" name="action"  id="action"   value="newp">
<input type="hidden" name="ogrid"  id="schoolid"  class="text-input" ></p>
</div>
<p>&nbsp;</p>
<hr>
<p><span class="label">&nbsp;</span><img src="/module/cool-php-captcha-0.3.1/captcha.php" id="captcha" /></p>
<p><span class="label">&nbsp;</span><a href="#" onclick=" document.getElementById(\'captcha\').src=\'/module/cool-php-captcha-0.3.1/captcha.php?\'+Math.random();
  document.getElementById(\'captcha-form\').focus();" id="change-image">ไม่สามารถอ่านได้ เปลี่ยนใหม่</a></p>
<p><span class="label">ป้อนข้อความในภาพ</span> <input type="text" name="captcha" id="captcha-form" autocomplete="off" '.$ccb.' required/></p>
<p class="submitbt"><span class="label">&nbsp;</span> <input type="submit" value=" ส่งข้อมูล " class="btPost button"> หรือ 
<a href="/login?wp=loader" class="button btGray">ยกเลิก</a></p>
</div>';

echo '</form>';
}
?>