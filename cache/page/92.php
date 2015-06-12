<?php

$ret = '<div id="boxUI">
<h1 class="header"><i class="fa fa-file"></i> สร้างหน้าใหม่</h1>
<span class="boxContent">
<div id="pagec">
<div id="stat"></div>
<form action="/BodySave.php" method="post" id="newpage">';
$ret .= '<p><h3 class="title">Page Title</h3> <input type="text" class="text-input" name="title" id="title" placeholder="ข้อความแสดงใน Title" required></p>
<p><h3 class="title">URL</h3> <input type="text" class="text-input" name="url" id="url" placeholder="ห้ามเว้นวรรค ตัวอักษร a-z หรือภาษาไทยเท่านั้น" required></p>
<input type="hidden"  name="action" value="newpage" id="action">
<p><h3 class="title">Role</h3>
<select name="role">
<option value="10" selected>Guest</option>
<option value="0">All Member</option>
<option value="1">User</option>
<option value="2">Tutor</option>
<option value="3">Modulator</option>
<option value="5">Admin</option>
</select>
</p>
<h2 class="title">Settings</h2> 
<h3 class="title">ภาพพื้นหลังพื้นหลัง</h3> <input type="text" name="setting_bg" class="text-input" placeholder="URL ของภาพพื้นหลัง"> 
<h3 class="title"> <input type="checkbox" name="setting_banner" value="1" checked>  แสดงป้ายแบนเนอร์</h3>
<h3 class="title"> <input type="checkbox" name="setting_ribbon" value="1" checked>  แสดงเมนูด้านบน</h3>
<h3 class="title"><input type="checkbox" name="setting_footer" value="1" checked>  แสดง footer</h3> 
<h2 class="title">Code</h2>
<p><span class="edit-inc"><h3 class="title"><a href="#" onClick="$(\'#include\').slideToggle();"><i class="fa fa-code"></i> Header</a></a></span> 
<textarea class="edit-body edit-header" name="include" id="include"></textarea></p>
<p><h3 class="title"><i class="fa fa-code"></i> Body *</h3> <textarea class="edit-body edit-header" name="body" id="body" required></textarea></p>
<p><input type="submit" class="button btBlue" value =" Save "></p>
</form>
</div>
</span>
</div>';

echo '<div id="leftContainer">
					<a href="/my?param=page" class="button btGray"><i class="fa fa-arrow-left"></i> &nbsp;ย้อนกลับ
					</a>
		</div>';
echo '<div id="middleContainer">';
echo $ret;
echo '</div>';
?>