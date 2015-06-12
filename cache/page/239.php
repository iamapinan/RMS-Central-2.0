<?php
if(!isset($_GET['id'])||!isset($_GET['qt'])||$client['role']<=2)
{
	header('location: /403');
	exit;
}
?>
<div id="leftContainer">
	<div class="menu">
		<p>
			<a href="#"  class="button btGray"><i class="fa fa-arrow-left"></i> &nbsp;ย้อนกลับ</a> 
		</p>
		</div>
	</div>
</div>
<!-- Content -->
<div id="middleContainer">
	<div id="boxUI">
		<div class="header"><i class="fa fa-book fa-lg"></i> สร้างรายวิชา</div>
		<div class="boxContent">
			<form method="post" action="save.php" id="data-form"  enctype="multipart/form-data">
				<div class="frm-group">
					<input type="hidden" value="course_save" name="q">
					<p class="title">ประเภทวิชา  <sup class="text-red">บังคับ</sup></p>
					<p class="frm-obj">
						<select name="course_type" required id="select_course_type">
							<option selected  value="">เลือกประเภทวิชา</option>
							<?php
								$sql_lev = mysql_query('select * from '.conf('table_prefix').'_course_type order by ctid asc');
								while($lev = mysql_fetch_array($sql_lev))
								{
									if($client['role']<6&&$lev['name']=='standard') continue; 
									echo '<option value="'.$lev['name'].'"> '.$lev['thText'].'</option>';
								}
							?>
						</select>
					</p>
				</div>
				<div class="frm-group hidden" id="alwpb">
					<p class="title">อนุญาตให้ผู้ที่อยู่ต่างโรงเรียน/สังกัด สมัครเรียนวิชานี้ได้หรือไม่</p>
					<p class="frm-obj">
						<input type="radio" checked name="allow_public" value="1"> อนุญาต &nbsp;
						<input type="radio" name="allow_public" value="0"> ไม่อนุญาต
					</p>
				</div>
				<div class="frm-group">
					<p class="title">ชื่อวิชา  <sup class="text-red">บังคับ</sup></p>
					<p class="frm-obj"><input type="text" class="text-input" name="course_name" id="title" placeholder="กำหนดชื่อวิชา" required></p>
				</div>
				<div class="frm-group hidden" id="cgx">
					<p class="title">กลุ่มสาระวิชา</p>
					<p class="frm-obj">
						<select name="group">
							<option selected value="">เลือกกลุ่มสาระวิชา</option>
						<?php
							$sql_lev = mysql_query('select * from '.conf('table_prefix').'_group where type=1 order by gid asc');
							while($lev = mysql_fetch_array($sql_lev))
							{
								echo '<option value="'.$lev['gid'].'"> '.$lev['name'].'</option>';
							}
						?>
						</select>
					</p>
				</div>
				<div class="frm-group">
					<p class="title">คำอธิบายรายวิชา/ ตัวชี้วัดต่างๆ  <sup class="text-red">บังคับ</sup></p>
					<p class="frm-obj"><textarea name="course_detail" required></textarea></p>
				</div>
				<div class="frm-group">
					<p class="title">กำหนดครูผู้สอน <sup class="text-red">บังคับ</sup></p>
					<p class="frm-obj menulist">
					<input type="text" class="text-input" name="group_member_check" id="idCheck" placeholder="ป้อนชื่อแล้วเลือกจากรายการ">
					<ul class="uls hidden"></ul></p>
					<div class="member-list"></div>
				</div>
				<div class="frm-group hidden" id="cgd">
					<p class="title">ระดับชั้น</p>
					<p class="frm-obj">
						<select name="grade">
							<option selected  value="">เลือกห้องเรียน</option>
						<?php
							$sql_lev = mysql_query('select * from '.conf('table_prefix').'_classroom where sid='.$client['org'].' or uid='.$client['ssid'].' order by clsid asc');
							while($lev = mysql_fetch_array($sql_lev))
							{
								echo '<option value="'.$lev['clsid'].'"> '.$lev['title'].'</option>';
							}
						?>
						</select>
					</p>
				</div>
				<div class="frm-group">
					<p class="title">ภาพประกอบ <sup class="text-red">บังคับ</sup></p>
					<p class="frm-obj"><input type="file" accept="image/*" class="text-input" name="course_image" required></p>
				</div>
				<br>
				<div class="frm-group">
					<p class="frm-obj"><button type="submit" class="button btBlue">สร้างรายวิชา</button></p>
				</div>
			</form>
		</div>
	</div>
</div>