<div id="leftContainer">
	<div class="menu">
		<p align="center">
			<a href="/my?param=group"  class="button btGray"><i class="fa fa-arrow-left"></i> &nbsp;ย้อนกลับ</a>
			<a href="#" onClick="window.location.reload();"  class="button btRed"><i class="fa fa-refresh"></i> &nbsp;เริ่มใหม่</a>
		</p>
		<div class="tipbox">
		</div>
	</div>
</div>
<!-- Content -->
<div id="middleContainer">
	<div id="boxUI">
		<div class="header"><i class="fa fa-users fa-lg"></i> สร้างกลุ่ม</div>
		<div class="boxContent">
			<form method="post" id="data-form" action="/save.php"  enctype="multipart/form-data">
				<input type="hidden" name="q" value="group_single_insert">
				<div class="step1">
					<div class="frm-group">
						<p class="title">ประเภทกลุ่ม</p>
						<br>
						<ul class="frm-info">
							<a  href="#" onclick="$('.step2').fadeIn(); $('.step1').hide();$('#gt').val(1);$('#alw1').attr('checked',true);">
								<li class="text-green"><i class="fa fa-check-circle-o fa-3x"></i>
								<br> กลุ่มแบบเปิด โรงเรียน/องค์กรอื่นสามารถค้นหาและเข้าร่วมกลุ่มได้</li>
							</a>
							<a  href="#" onclick="$('.step2').fadeIn(); $('.step1').hide();$('#gt').val(2);$('#alw2').attr('checked',true);">
								<li class="text-warn"><i class="fa fa-times-circle-o fa-3x"></i>
								<br> กลุ่มแบบปิด โรงเรียน/องค์กรอื่นไม่สามารถค้นหาและเข้าร่วมกลุ่มได้</li>
							</a>
						</ul>
						<br>
						<input type="hidden" name="group_type" id="gt">
					</div>
				</div>
				<div class="step2 hidden">
					<div class="frm-group">
						<p class="title">สถานะเมื่อมีใครก็ตามขอเข้าร่วมกลุ่ม</p>
						<p class="frm-obj">
							<input type="radio" id="alw1" name="allow_public" value="1"> อนุมัติโดยทันที  &nbsp;
							<input type="radio" id="alw2" name="allow_public" value="0"> รอให้ยืนยัน
						</p>
					</div>
					<div class="frm-group">
						<p class="title">ชื่อกลุ่ม  <sup class="text-red">บังคับ</sup></p>
						<p class="frm-obj"><input type="text" class="text-input" name="group_name" id="title" placeholder="ชื่อกลุ่ม" required></p>
					</div>
					<div class="frm-group">
						<p class="title">สมาชิกในกลุ่ม </p>
						<p class="frm-obj menulist"><input type="text" class="text-input" name="group_member_check" id="idCheck" placeholder="ป้อนชื่อแล้วเลือกจากรายการ">
						<ul class="uls hidden"></ul></p>
						<div class="member-list"></div>
					</div>
					<div class="frm-group">
						<p class="title">ภาพประกอบ <sup class="text-red">บังคับ</sup></p>
						<p class="frm-obj"><input type="file" accept="image/*" class="text-input" name="group_image" required></p>
					</div>
					<br>
					<div class="frm-group">
						<p class="frm-obj"><button type="submit" class="button btBlue">สร้างกลุ่ม</button></p>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
