<?php
$u = UserInfo();
$usr = $u['user']; 
$pagemsg = array('default'=>'จัดการโปรไฟล์','edit-password'=>'เปลี่ยนรหัสผ่าน','edit-picture'=>'เปลี่ยนภาพโปรไฟล์','details'=>'แก้ไขข้อมูลส่วนตัว');
if(isset($_GET['u'])) $_GET['action'] = $_GET['u'];
if($_GET['action']=='') $_GET['action'] = 'default';
echo '<h3 class="pgtitle" align="left"><a href="/my">My Home</a> > '.$pagemsg[$_GET['action']].'</h3>';
echo '<br>';
if(isset($_GET['action'])&&$_GET['action']=='edit-picture')
{
$ret .= '<div class="ui-tabbar">
<a href="/verify"  tabIndex="1"><div class="ui-tabb">ยืนยันบัญชีผู้ใช้</div></a>
<a href="/edit_profile/u/details"  tabIndex="1"><div class="ui-tabb">แก้ไขข้อมูลส่วนตัว</div></a>
<a href="/edit_profile/action/edit-picture"  tabIndex="2"><div class="ui-tabb active_tab">เปลี่ยนภาพโปรไฟล์</div></a>';
$ret .= '<a href="/edit_profile/action/edit-password"  tabIndex="3"><div class="ui-tabb">เปลี่ยนรหัสผ่าน</div></a>';
$ret .= '</div>';
$ret .= '
<div id="boxUI">
<span class="boxContent">';
$ret .= '
<br><br>
<div align="center">
<span id="preview_photo" style="display: none"></span>
<input type="file" id="file_upload" name="file_upload"/>
<input type="hidden" name="u" value="'.$usr.'" id="une">
<input type="button" name="success" value=" ตกลง "  class="button btPost ok" style="margin-top: 10px;float: none;display:none;">
<p>&nbsp;</p>
<p>เลือกไฟล์นามสกุล jpg, png, gif, bmp ขนาดไฟล์ไม่เกิน 2 MB เท่านั้น</p>
</div>
';

$ret .='</span>
</div>';
	echo $ret;
}

if(isset($_GET['action'])&&$_GET['action']=='edit-password')
{
$ret .= '<div class="ui-tabbar">
<a href="/verify"  tabIndex="1"><div class="ui-tabb">ยืนยันบัญชีผู้ใช้</div></a>
<a href="/edit_profile/u/details"  tabIndex="1"><div class="ui-tabb">แก้ไขข้อมูลส่วนตัว</div></a>
<a href="/edit_profile/action/edit-picture"  tabIndex="2"><div class="ui-tabb">เปลี่ยนภาพโปรไฟล์</div></a>';
$ret .= '<a href="/edit_profile/action/edit-password"  tabIndex="3"><div class="ui-tabb active_tab">เปลี่ยนรหัสผ่าน</div></a>';
$ret .= '</div>';
$ret .= '<div class="ui-tab-content">
	<div id="boxUI">
<span class="boxContent">';
$ret .= '<form action="'.conf('url').'profilesave.php?action=changepassword" method="post" id="changepasswordform" onSubmit="savepassword();return false;" required>';
$ret .= '<p><span class="label">รหัสผ่านเดิม</span> <input type="password" class="text-input" name="currentpassword" required></p>
<p><span class="label">รหัสผ่านใหม่</span> <input type="password" class="text-input" name="newpassword" id="newpassword" onchange="chkpin(\'newpassword\');" required></p>
<p><span class="label">ยืนยันรหัสผ่านใหม่</span> <input type="password" class="text-input" name="confirmpassword" id="confirmpassword"  onchange="chkpin(\'confirmpassword\');"></p>
<div class="line"></div>
<div id="status-update" class="savestatus"></div>
<p align="center"><input type="submit" class="button btPost submitme" value=" บันทึก "> หรือ <a href="/edit_profile"> ยกเลิก</a></p>
';
	$ret .= '</form>';
	$ret .= '</span>
</div></div>';
echo $ret;
}

if(isset($_GET['u'])&&$_GET['u']=='details')
{
echo '<div class="ui-tabbar">
<a href="/verify"  tabIndex="1"><div class="ui-tabb">ยืนยันบัญชีผู้ใช้</div></a>
<a href="/edit_profile/u/details"  tabIndex="1"><div class="ui-tabb active_tab">แก้ไขข้อมูลส่วนตัว</div></a>
<a href="/edit_profile/action/edit-picture"  tabIndex="2"><div class="ui-tabb">เปลี่ยนภาพโปรไฟล์</div></a>';
echo '<a href="/edit_profile/action/edit-password"  tabIndex="3"><div class="ui-tabb">เปลี่ยนรหัสผ่าน</div></a>';
echo '</div>';

$lang = array('th'=>'ไทย','en'=>'English');
if($u['gender']=='m')
$gm = 'selected';
else
$gf = 'selected';

if($u['language']=='th')
$th = 'selected';
else
$en = 'selected';

$name = explode('|',$u['fullname']);

echo '<div class="ui-tab-content">
	<div id="boxUI">
<span class="boxContent">';
echo '
<form action="'.conf('url').'profilesave.php?action=edit-profile" method="post" id="editmyprofile" onsubmit="profilesave();return false;">
<input type="hidden" name="uid" value="'.$u['ssid'].'">
<p><span class="label">ชื่อ</span> <input type="text" class="text-input" name="fn" value="'.$name[0].'"> </p>
<p><span class="label">นามสกุล</span> <input type="text" class="text-input" name="ln" value="'.$name[1].'" ></p>
<span class="bdpick">';
echo '<p><span class="label">วันเกิด</span>';
echo '<select required name="dd" id="dd" placeholder="วัน">';
echo '<option>วันที่</option>';
	$bd = explode('/', $u['bday']);
	for($d=1;$d<=31;$d++){
		if($bd[0]==$d) $actvated = 'selected'; else $actvated = '';
		echo '<option value="'.$d.'" '.$actvated.'>'.$d.'</option>';
	}echo '</select>&nbsp;';

echo '<select required name="mm" id="mm" placeholder="เดือน">';
echo '<option>เดือน</option>';
	for($m=1;$m<=12;$m++){
		if($bd[1]==$m) $actvated = 'selected'; else $actvated = '';
		echo '<option value="'.$m.'" '.$actvated.'>'.$m.'</option>';
	}
echo '</select>&nbsp;';

echo '<select required name="yy" id="yy" placeholder="ปี ค.ศ.">';
echo '<option>ปี ค.ศ.</option>';
	for($y=1930;$y<=2003;$y++){
		if($bd[2]==$y) $actvated = 'selected'; else $actvated = '';
		echo '<option value="'.$y.'" '.$actvated.'>'.$y.'</option>';
	}echo '</select>';
if($u['gender']=='m') $gender['m']='selected';
else $gender['f'] = 'selected';
		
echo '</span></p>
<p><span class="label">&nbsp;</span> ลบ พ.ศ. ด้วย 543 เพื่อให้เป็น ค.ศ. </p>
<p><span class="label">ภาษา</span> <select name="language">
<option value="th" '.@$th.'>'.$lang['th'].'</option>
<option value="en" '.@$en.'>'.$lang['en'].'</option>
</select>
</p>
<p><span class="label">เพศ</span> <select name="gender">
<option value="m" '.$gender['m'].'>ชาย</option>
<option value="f" '.$gender['f'].'>หญิง</option>
</select>
</p>
<p><span class="label">อีเมล์</span> <input type="text" class="text-input" name="email" value="'.$u['email'].'"></p>
<p><span class="label">เบอร์มือถือ</span> <input type="text" class="text-input" name="mobile" value="'.$u['mobile'].'"></p>
<p align="center" style="margin-top: 25px;"><input type="submit" class="button btPost submitme" value=" บันทึก "> หรือ <a href="/edit_profile">ยกเลิก</a></p>
<p align="center" ><a href="/delete_me">ลบบัญชีผู้ใช้</a></p>
</form>

';

echo '</span>
	</div>
	</div>';

}
else if(!isset($_REQUEST['action']))
{
if($u['bday']=='')
	$u['bday'] = '';

if($u['school']==''||$u['school']=='0')
	$u['school']='';

if($u['gender']=='m')
	$u['gender'] = 'Male';
else
	$u['gender'] = 'Female';

if($u['language']=='th')
	$u['language'] = 'Thai';
else
	$u['language'] = 'English';

if($u['remark']=='')
	$u['remark'] = 'No data';
	
echo '<div class="ui-tabbar">
<a href="/verify"  tabIndex="1"><div class="ui-tabb">ยืนยันบัญชีผู้ใช้</div></a>
<a href="/edit_profile/u/details"  tabIndex="1"><div class="ui-tabb">แก้ไขข้อมูลส่วนตัว</div></a>
<a href="/edit_profile/action/edit-picture"  tabIndex="2"><div class="ui-tabb">เปลี่ยนภาพโปรไฟล์</div></a>';
echo '<a href="/edit_profile/action/edit-password"  tabIndex="3"><div class="ui-tabb">เปลี่ยนรหัสผ่าน</div></a>';
echo '</div>';


	$sql_school_info = mysql_query('select * from '.conf('table_prefix').'_school where sid='.$u['org']);
	$sci = @mysql_fetch_array($sql_school_info);
	
	echo '<div id="boxUI">
				<span class="boxContent" id="class-alert">
<p style="width: 100%;float:left;margin-top: -5px;"><div class="label" style="text-align:left;color: #7e7e7e;width: 80px;margin-left: 45px;">ชื่อ-นามสกุล</div><span style="margin: 2px 4px 6px 4px;">'.str_replace('|','&nbsp;',$u['fullname']).'</span></p>
					<p style="width: 100%;margin-top: -5px;"><div class="label" style="text-align:left;color: #7e7e7e;width: 80px;margin-left: 45px;">วันเกิด</div><span style="margin: 2px 4px 6px 4px;">'.$u['bday'].'</span></p>
					<p style="width: 100%;margin-top: -5px;"><div class="label" style="text-align:left;color: #7e7e7e;width: 80px;margin-left: 45px;">เพศ</div><span style="margin: 2px 4px 6px 4px;">'.$u['gender'].'</span></p>
					<p style="width: 100%;margin-top: -5px;"><div class="label" style="text-align:left;color: #7e7e7e;width: 80px;margin-left: 45px;">ภาษา</div><span style="margin: 2px 4px 6px 4px;">'.$u['language'].'</span></p>
					<p style="width: 100%;margin-top: -5px;"><div class="label" style="text-align:left;color: #7e7e7e;width: 80px;margin-left: 45px;">องค์กร</div><span style="margin: 2px 4px 6px 4px;">'.$sci['sname'].'</span></p>
					<p style="width: 100%;margin-top: -5px;"><div class="label" style="text-align:left;color: #7e7e7e;width: 80px;margin-left: 45px;">อีเมล์</div><span style="margin: 2px 4px 6px 4px;">'.$u['email'].'</span></p>

				</span>
			</div>';
}
?>