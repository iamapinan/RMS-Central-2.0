<?php
	$me = UserInfo();
	$sql_school_info = mysql_query('select * from '.conf('table_prefix').'_school where sid='.$me['org']);
	$sci = @mysql_fetch_array($sql_school_info);

echo '<h3 class="pgtitle" align="left"><a href="/my">My Home</a> > การยืนยันความถูกต้องของบัญชี</h3><br>';
echo  '<div class="ui-tabbar" style="text-align: center;">
<a href="/verify"  tabIndex="1"><div class="ui-tabb active_tab">ยืนยันบัญชีผู้ใช้</div></a>
<a href="/edit_profile/u/details"  tabIndex="1"><div class="ui-tabb">แก้ไขข้อมูลส่วนตัว</div></a>
<a href="/edit_profile/action/edit-picture"  tabIndex="2"><div class="ui-tabb">เปลี่ยนภาพโปรไฟล์</div></a>
<a href="/edit_profile/action/edit-password"  tabIndex="3"><div class="ui-tabb">เปลี่ยนรหัสผ่าน</div></a>';
echo  '</div>';
echo '<div class="view">';
echo '<form action="profilesave.php?action=verify" method="post" enctype="multipart/form-data" id="verifyme">';

echo "<p>&nbsp;</p>";
if(isset($_GET['erms']))
{
	echo "<p style='color: red;font-weight: bold;background: #FFF4C7;text-align: center;width: 560px;border-radius: 3px;border: 1px solid #CECBC3;'>
	".$_GET['erms']."</p>";
	echo "<p>&nbsp;</p>";
	
}
if($me['verify']==2&&!isset($_GET['erms']))
{
	echo "<p style='color: #3A3A3A;font-weight: bold;background: #FFF4C7;text-align: center;width: 560px;border-radius: 3px;border: 1px solid #CECBC3;'>
	บัญชีผู้ใช้ของท่านอยู่ในระหว่างการพิจารณา</p>";
	echo "<p>&nbsp;</p>";
}
if($me['verify']==1&&!isset($_GET['erms']))
{
	echo "<p style='color: black;
font-weight: bold;
background: #EBFFD5;
text-align: center;
width: 560px;
border-radius: 3px;
border: 1px solid #96C550;'>
	ท่านได้รับการยืนยันบัญชีแล้ว</p>";
	echo "<p>&nbsp;</p>";
	exit;
}
echo "<p><span class='label'>ชื่อ-นามสกุล</span> ".str_replace('|',' ', $me['fullname'])."</p>";
echo "<p><span class='label'>อีเมล์</span> ".$me['email']."</p>";
echo "<p><span class='label'>องค์กร</span> ".$sci['sname']."</p>";
echo "<input type='hidden' name='token' value='".$session_token."'>";
echo "<input type='hidden' name='u' value='".$me['user']."'>";
echo "<p>&nbsp;</p>";
echo "<p><span class='label'>หมายเลขบัตรประชาชน</span> <input type='text' name='ctid' id='ctid' value='".$me['user']."'> 
<a href='#' rel='tooltip' original-title='กรอกเลขที่บัตรประชาชน 13 หลักของท่าน'>*</a></p>";
echo "<p><span class='label'>เบอร์มือถือ</span> <input type='text' name='mobile' id='mobile' value='".$me['mobile']."'> *</p>";
echo "<p>&nbsp;</p>";
echo "<p style='color: #000;'><span class='label'>&nbsp;</span> ท่านต้องแนบสำเนาบัตรประชาชนและบัตรพนักงานหรือบัตรนักเรียนด้วย</p>
<p style='color: #000;'><span class='label'>&nbsp;</span> ท่านต้องอัพโหลดไฟล์นามสกุล jpg หรือ png เท่านั้น
</p>";
echo "<p><span class='label'>สำเนาบัตรประชาชน</span> <input type='file' name='file[]' multiple='true' id='ctzc' accept='image/*'> *</p>";
echo "<p>&nbsp;</p>";

echo "<p><span class='label'>&nbsp;</span> <input type='button' id='verify_ac' value=' ตกลง ' class='button btPost'></p>";
echo '</form>';
echo '</div>';
?>