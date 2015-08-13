<div id="leftContainer">
	<div class="menu">
		<p>
			<a href="/my?param=course"  class="button btGray"><i class="fa fa-arrow-left"></i> &nbsp;ย้อนกลับ</a> 
		</p>
		<?php if($client['role']>=6):?>
			<!--<p>
				<a href="#"  class="btYellow"><i class="fa fa-cloud-upload"></i> &nbsp;นำเข้ารายวิชา</a>
			</p>-->
		<?php endif;?>		
		<div class="tipbox">
			<div class="tipcontainer">
				<p style="text-align: center;"><i class="fa fa-question-circle fa-4x text-warn"></i></p>			
				<?php if($client['role']>=5):?>
				<span id="standard" class="data-info"><b>ประเภทวิชาพื้นฐาน</b> คือ วิชาที่ถูกกำหนดจากสารการเรียนรู้ในหลักสูตรมาตรฐาน 
						การสร้างวิชาประเภทนี้จำเป็นต้องสร้างห้องเรียนในเมนู My Class ก่อนเนื่องจากต้องกำหนดผู้เรียนด้วย</span>
				<?php endif;?>
				<span id="extra" class="data-info"><b>ประเภทวิชาเพิ่มเติม</b> คือ วิชาหรือหลักสูตรที่โรงเรียนเป็นผู้กำหนดเองเพิ่มเติม 
						การสร้างวิชาประเภทนี้จำเป็นต้องสร้างห้องเรียนในเมนู My Class ก่อนเนื่องจากต้องกำหนดผู้เรียนด้วย</span>
				<span id="public" class="data-info"><b>ประเภทวิชาสาธารณะ</b> คือ วิชาหรือหลักสูตรที่ไม่บังคับ สามารถเปิดให้ใครก็ได้มาสมัครเรียน <span class="text-red">*สามารถกำหนดให้ผู้ที่อยู่ต่างโรงเรียน/องค์กรสามารถสมัครเรียนได้</span> 
						ไม่จำเป็นต้องกำหนดผู้เรียนไว้ล่วงหน้าเนื่องจากใครก็สามารถสมัครเรียนได้</span>
			</div>
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
									if($client['role']<5&&$lev['name']=='standard') continue; 
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
				<div class="frm-group frm-teacher-container">
					<p class="title">กำหนดครูผู้สอนเพิ่มเติม</p>
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
					<p class="title">ภาพประกอบ</p>
					<p class="frm-obj"><input type="file" accept="image/*" class="text-input" name="course_image"></p>
				</div>
				<br>
				<div class="frm-group">
					<p class="frm-obj"><button type="submit" class="button btBlue">สร้างรายวิชา</button></p>
				</div>
			</form>
		</div>
	</div>
</div>