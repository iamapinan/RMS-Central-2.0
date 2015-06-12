<?php
if($_GET['do']=='neworg')
{
	echo '<div class="view">';
	echo '
	<h2 class="title"> &nbsp;&nbsp;เพิ่มองค์กรใหม่</h2>
	<p align="center" class="errmsg"></p>
	<form actioin="" method="post" id="neworg" onsubmit="create_new_org();return false;">
	<p>&nbsp;</p>
		<p>
			<span class="label">ชื่อองค์กร</span> <input type="text" name="orgname" id="orgname" value="'.$_GET['org'].'">
			?
		</p>
		<p>
			<span class="label">รหัส 10 หลัก</span> <input type="text" name="sid" value="">
			*
		</p>
		<p>
			<span class="label">สังกัด</span> <input type="text" name="division" value="">
			?
		</p>
		<p>
			<span class="label">ตำบล</span> <input type="text" name="district"  value="">
			*
		</p>
		<p>
			<span class="label">อำเภอ</span> <input type="text" name="area" value="">
			*
		</p>
		<p>
			<span class="label">จังหวัด</span> <input type="text" name="province" value="">
			*
		</p>
		<p>&nbsp;</p>
		<p>
		<span class="label">&nbsp;</span><input type="submit" class="button btPost" id="cssave" value=" ตกลง ">
		</p>
	</form>
	';
	echo '</div>';
}
?>