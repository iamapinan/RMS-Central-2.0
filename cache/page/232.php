<?php
	$group = PageInfo($_REQUEST['gpid'], $type = 'group');
	if($group['name']=='') {
		header('location: /404');
		exit;
	}
	$admin = UserInfo($group['admin']);
	if($group['banner']=='') { $group['banner'] = '/data/default_image/country-road-11649-1920x1080.jpg';
	}
?>
<div class="group_home">
	<div id="ubn" style="background: #333 url(/image?width=988&amp;height=310&amp;cropratio=4:1&amp;image=<?php echo $group['banner'];?>) no-repeat center center;">
		<a href="javascript:;" class="botton btTransparent" onclick="$('#banneruploader').click();" rel="tooltip" original-title="รูปภาพที่ใช้ต้องเป็นไฟล์ jpg, png และมีความขนาด กว้าง 1024px สูง 220px ขึ้นไป โดยจะตัดส่วนบนและล่างของภาพออก">
			<i class="fa fa-pencil"></i> เปลี่ยนภาพหน้าปก
		</a>
		<form action="" method="post" id="uploadfrm" enctype="multipart/form-data">
			<input type="file" name="banneruploader" id="banneruploader" onchange="ajaxFileUpload();" style="width: 100px;height:20px;opacity: 0;position:relative;z-index: 0;" accept="image/*">
		</form>
		<div class="profile-top-info">
			<span class="title-name"><i class="fa fa-users fa-1x"></i> <?php echo $group['name'];?></span>
			<span class="profile-detail"><i class="fa fa-graduation-cap"></i> <?php echo orgname($group['sid']);?> </span>
		</div>
		<div class="pt_larg">
			<img src="/image?width=220&amp;height=220&amp;cropratio=1:1&amp;image=<?php echo $group['logo'];?>" >
			<a href="/edit_profile/action/edit-picture" class="Chimg"><i class="fa fa-camera"></i> เปลี่ยนรูป</a>
		</div>
		<div class="gbt">
			<button class="button btGreen"><i class="fa fa-sign-in"></i>  เข้าร่วม</button>
			<button class="button btGray"><i class="fa fa-sign-out"></i> ออกจากกลุ่ม</button>
			<button class="button btGray"><i class="fa fa-gear"></i></button>
		</div>
	</div>
	<div class="admin-tools-bar">
		<div class="toolsbox left-tools">
			<div class="section">
				<h2 class="section-header">รายละเอียดของกลุ่ม <a href="#"><i class="fa fa-pencil invisible"></i></a></h2>
				<div class="section-body">
					<p class="info-group"><span class="info-label">ชื่อกลุ่ม</span> <span class="info-data"><?php echo $group['name'];?></span></p>
					<p class="info-group"><span class="info-label">สังกัด</span> <span class="info-data"><?php echo orgname($group['sid']);?></span></p>
					<p class="info-group"><span class="info-label">ผู้ดูแล</span> <span class="info-data">
						<a href="/profile/<?php echo $admin['user'];?>"><?php echo str_replace('|',' ',$admin['fullname']);?></a></span></p>
					<p class="info-group"><span class="info-label">ลิ้งค์</span> <span class="info-data"><a href="#">https://rms.bll.in.th/group/<?php echo $group['url'];?></a></span></p>
	
				</div>
			</div>

		</div>
		<div class="toolsbox right-tools">
			<div class="section postbox">
				<h3 class="section-header post-header"><i class="fa fa-bullhorn"></i> ส่งข่าวให้สมาชิก</h3>
				<div class="section-body">
					<form method="post">
					<textarea class="text-input post-text" name="msg-data" placeholder="ข้อความถึงสมาชิก"></textarea>
					<div class="post-bottom">
						<button class="btBlue button" type="submit"><i class="fa fa-send"></i> ส่งข้อความ</button> 
						<button class="btGray button" type="reset">ยกเลิก</button>
					</div>
					</form>
				</div>
			</div>
			<div class="section">
				<h2 class="section-header">สมาชิก <a href="#" class="invisible"> แสดงทั้งหมด</a></h2>
				<div class="section-body">
					<div class="user_container">
					<a href="/profile/1550600042131">
						<div class="photo"><img data-src="holder.js/100x100/random"></div>
						<div class="user_info_caption">นายอภินันท์ วรตระกูล</div>
					</a>
					</div>
					<div class="user_container">
					<a href="/profile/1550600042131">
						<div class="photo"><img data-src="holder.js/100x100/random"></div>
						<div class="user_info_caption">นายอภินันท์ วรตระกูล</div>
					</a>
					</div>
					<div class="user_container">
					<a href="/profile/1550600042131">
						<div class="photo"><img data-src="holder.js/100x100/random"></div>
						<div class="user_info_caption">นายอภินันท์ วรตระกูล</div>
					</a>
					</div>
					<div class="user_container">
					<a href="/profile/1550600042131">
						<div class="photo"><img data-src="holder.js/100x100/random"></div>
						<div class="user_info_caption">นายอภินันท์ วรตระกูล</div>
					</a>
					</div>
				</div>
			</div>
			<div class="section">
				<h2 class="section-header">ข่าว</h2>
				<div class="section-body">
					<div class="feeds-item">
						<div class="feed-head">
							<p><img data-src="holder.js/30x30"><span class="feedder-name">อภินันท์ วรตระกูล</span>
							<span class="feeder-role"><i class="fa fa-flag"></i> หัวหน้ากลุ่ม</span>
							</p>
						</div>
						<div class="feed-body">
							ขณะนี้กลุ่มของเราได้เริ่มต้นขึ้นแล้ว ขอต้อนรับสมาชิกทุกคนครับ เราจะใช้ที่นี้พูดคุยทำงานร่วมกันต่อไปนะครับ ...
							<div class="feed-tools">
								<i class="fa fa-comments"></i> <span class="comment-count">0</span> ความเห็น &nbsp; 
								<i class="fa fa-clock-o"></i> 08:21 am, 18-5--15
							</div>
						</div>
					</div>
					
					<div class="feeds-item">
						<div class="feed-head">
							<p><img data-src="holder.js/30x30"><span class="feedder-name">อภินันท์ วรตระกูล</span>
							<span class="feeder-role"><i class="fa fa-flag"></i> หัวหน้ากลุ่ม</span>
							</p>
						</div>
						<div class="feed-body">
							ทดลองส่งข่าวในกลุ่ม RMS ให้กับสมาชิก
							<div class="feed-tools">
								<i class="fa fa-comments"></i> <span class="comment-count">0</span> ความเห็น &nbsp; 
								<i class="fa fa-clock-o"></i> 06:01 am, 17-5--15
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>