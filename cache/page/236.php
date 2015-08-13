<div id="leftContainer">
	<div class="menu">
		<p align="center"><a href="/my?param=class"  class="button btGray"><i class="fa fa-arrow-left"></i> &nbsp;ย้อนกลับ</a></p>
		<div class="tipbox">
		</div>
	</div>
</div>
<!-- Content -->
<div id="middleContainer">
	<div id="boxUI">
		<div class="header"><i class="fa fa-cube fa-lg"></i> สร้างห้องเรียนพิเศษ</div>
		<div class="boxContent">
			<form method="post" id="data-form" action="/save.php"  enctype="multipart/form-data">
				<div class="frm-group">
					<input type="hidden" name="q" value="classroom_single_insert">
					<p class="title">อนุญาตให้ใครก็ได้เข้าร่วมห้องเรียนนี้</p>
					<p class="frm-obj">
						<input type="radio" name="allow_public" value="1"> อนุญาต  &nbsp;
						<input type="radio" checked name="allow_public" value="0"> ไม่อนุญาต
					</p>
				</div>
				<div class="frm-group">
					<p class="title">ชื่อห้องเรียน  <sup class="text-red">บังคับ</sup></p>
					<p class="frm-obj"><input type="text" class="text-input" name="class_name" id="title" placeholder="ชื่อห้องเรียน" required></p>
				</div>
				<div class="frm-group">
					<p class="title">คำอธิบาย  <sup class="text-red">บังคับ</sup></p>
					<p class="frm-obj"><textarea name="class_detail"  required></textarea></p>
				</div>
				<div class="frm-group">
					<p class="title">กำหนดครูประจำชั้น <sup class="text-red">บังคับ</sup></p>
					<p class="frm-obj menulist">
					<input type="text" class="text-input" name="group_member_check" id="idCheck" placeholder="ป้อนชื่อแล้วเลือกจากรายการ">
					<ul class="uls hidden"></ul></p>
					<div class="member-list">
					<?php
						$img = ($client['avatar']!='') ? '/user/'.$client['user'].'/'.$client['avatar'] : '';
					?>
					<span class="member_selected" id="member-id-<?php echo $client['ssid'];?>">
						<input type="hidden" name="member_group[]" value="<?php echo $client['ssid'];?>">
						<img src="<?php echo image_resize($img, 26, 26);?>" class="inlinepos"> 
						<?php echo str_replace('|',' ',$client['fullname']);?>    
					</span>
					</div>
				</div>
				<div class="frm-group">
					<p class="title">ภาพประกอบ</p>
					<p class="frm-obj"><input type="file" accept="image/*" class="text-input" name="class_image"></p>
				</div>
				<br>
				<div class="frm-group">
					<p class="frm-obj"><button type="submit" class="button btBlue">สร้างห้องเรียน</button></p>
				</div>
			</form>
		</div>
	</div>
</div>
