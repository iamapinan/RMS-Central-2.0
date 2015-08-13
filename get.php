<?php
include 'initial.min.php';

switch($_GET['role'])
{
	case 2:
	//Remark
			$prn .= '<div id="std">
			<p>
			<span class="label">นักเรียน</span> <input type="text" name="std[]" placeholder="ชื่อ, ชั้น, โรงเรียน" style="width: 300px;" required>';
			$prn .= ' <a href="#" rel="tooltip" id="newchild" original-title="เพิ่มนักเรียน">เพิ่ม</a>';
			$prn .= '</p></div>';
			$prn .= '<script type="text/javascript">
			<!--
				$(document).ready(function()
				{
					$("#newchild").click(function(){
							$(".subset").append("<p><span class=\"label\">นักเรียน</span> <input type=\"text\" name=\"std[]\"  placeholder=\"ชื่อ, ชั้น, โรงเรียน\"  style=\"width: 300px;\"></p>");
					});
				});

			//-->
			</script>';
	break;
	case 3:
	//Start grade
			$prn .= '<p>
			<span class="label">ระดับชั้น</span>
			<select name="level" required>
			<option></option>';

			$sql_lev = mysql_query('select * from '.conf('table_prefix').'_level where level_id<=12 order by level_id asc');
			while($lev = mysql_fetch_array($sql_lev))
			{
				if($lev['level_id']==0) continue;
				$prn .= '<option value="'.$lev['level_id'].'">'.$lev['name'].'</option>';
			}
			$prn .= '</select></p>
			<p>
			<span class="label" required>ห้อง</span> <input type="text" list="classroom_attn" name="classroom" required placeholder="1" oninvalid="setCustomValidity(\'กรุณาป้อนห้องเรียน\')" size="2" maxlength="2" > ดับเบิลคลิกแล้วเลือกจากรายการ</p>';
			$prn .= '<datalist id="classroom_text">';
			for($i=1;$i<=6;$i++) $prn .= '<option value="'.$i.'">';
			$prn .= '</datalist>';
			$prn .= '<datalist id="classroom_attn">';
			for($i=1;$i<=16;$i++) $prn .= '<option value="'.$i.'">';
			$prn .= '</datalist>';
	//End grade
	break;
	case 4:
			$prn .= '<p id="pos"><span class="label">ตำแหน่ง</span> <input type="text" name="position" id="position" required></p>';
	break;
	case 5:
	//Major
			$prn .= '
			<p>
			<span class="label">กลุ่มสาระ</span>
			<select name="maj" id="maj" required>
			<option></option>';
			$sql_lev = mysql_query('select * from '.conf('table_prefix').'_group where gid<=9 order by gid asc');
			while($lev = mysql_fetch_array($sql_lev))
			{
				$prn .= '<option value="'.$lev['gid'].'">'.$lev['name'].'</option>';
			}
			$prn .= '</select></p>';
	break;
	case 6:
			$prn .= '<p id="pos"><span class="label">ตำแหน่ง</span> <input type="text" name="position" id="position" required></p>';
	break;
	case 7:
	//Remark
			$prn .= '<p><span class="label">เบอร์มือถือ</span> <input type="text" name="mobile" id="remk"> ';
			$prn .= '<a href="#" rel="tooltip" original-title="ท่านต้องกรอกเบอร์โทรศัพท์มือถือเพื่อยืนยันบัญชีของคุณ">?</a></p>';
			$prn .= '<p id="pos"><span class="label">ตำแหน่ง</span> <input type="text" name="position" id="position" required> </p>';
	break;
}

switch($_GET['t']){
	case 'courseInfo':
		$course =CourseInfo($_GET['get']);
		$cimage = '/holder.png/60x60/Thumbnail';
		if($course['lastuse']=='') $course['lastuse'] = 'ยังไม่เคย'; else $course['lastuse'] = date('d-n-Y, H:i', $course['lastuse']);
		if($course['img']=='') $img = $cimage; else $img = image_resize($course['img'],100,100);
		$class = classinfo($course['class_id']);
		$standardgroup = GroupInfo($course['main_group']);
		if($standardgroup['name']=='') $standardgroup['name'] = 'ไม่มี';
		$teacher_json = json_decode($course['alternet_teacher_id']);

		$io = array('title','body');
		//Section info
		if(mb_strlen($course['cdetail'])>100) $more_txt = ' <a href="javascript:void(0);" data-stat=0 class="morelimit"><i class="fa fa-plus-square-o"></i> เพิ่มเติม</a>';
		$io['body'] .= '<div class="section-lb">';
		$io['body'] .= '<div class="leftzone"><p><img src="'.$img.'" style="border-radius: 4px"></p></div>';
		$io['body'] .= '<div class="rightzone">
			<h3>'.$course['cname'].'</h3>
			<div class="detail limitHeight">'.nl2br($course['cdetail']).$more_txt.'</div>
			<p>ประเภทวิชา '.ucfirst($course['type']).'</p>
			<p>ระดับชั้น '.$class['text'].'/'.$class['cls_number'].'</p>
			<p>กลุ่มสาระ '.$standardgroup['name'].'</p>
			<p class="detail">เริ่มครั้งล่าสุด '.$course['lastuse'].'</p>
		</div>';
		$io['body'] .= '</div><br>';

		//Section teacher
		foreach($teacher_json as $teacherOnce)
		{
			$udimg = '/holder.png/60x60/Thumbnail';
			$tear = UserById($teacherOnce);
			$isyou = ($tear['user']==$_SESSION['loginid']['nickname']) ? '<sup style="color: #080;"><i class="fa fa-check-circle-o"></i> คือคุณ</sup>' : '';
			$uimg = ($tear['avatar']=='') ? $cimage  : image_resize('/user/'.$tear['user'].'/'.$tear['avatar'], 60,60);
			$io['body'] .= '<div class="section-lb">';
			$io['body'] .= '<div class="leftzone"><p><img src="'.$uimg.'" class="img-circle"></p></div>';
			$io['body'] .= '<div class="rightzone"><h3>ผู้สอน '.$isyou.'</h3><p><a href="/profile/'.$tear['user'].'" target="_blank">'.str_replace('|',' ',$tear['fullname']).'</a></p></div>';
			$io['body'] .= '</div>';
		}
		$io['body'] .= '<div class="section-lb">
			<p align="center">
			<button class="button btGreen" onclick="lightbox('.$course['course_id'].', \'registerbt\')" >สมัครเข้าร่วม</button>
			</p><br>
		</div>';


		$prn = json_encode($io);
		header('Content-Type: application/json');
	break;
	case 'ClassroomStudentList':

		$io = array('title','body');

		$class_member = mysql_query("SELECT uid FROM tc_class_register WHERE role=1 AND status=1 AND class_id=".$_GET['get']);
		$io['body'] .= '<h2 class="title"><i class="fa fa-users"></i> สมาชิกในห้อง</h2>';
		while($result = mysql_fetch_object($class_member))
		{
			$u = UserById($result->uid);
			$img = ($u['avatar']!='') ? '/user/'.$u['user'].'/'.$u['avatar'] : '';
			$io['body'] .= '<a href="/profile/'.$u['user'].'" class="classroomList" target="_blank"><img src="'.image_resize($img, 65, 65).'" class="img-circle">
			<span class="name-bottom">'.str_replace('|',' ',$u['fullname']).'</span></a>';
		}
		$io['body'] .= '<br>';

		$prn = json_encode($io);
		header('Content-Type: application/json');
	break;
	case 'ClassStd':
		$io = array('title','body');

		$class_member = mysql_query("SELECT uid FROM tc_class_register WHERE role=1 AND status=1 AND class_id=".$_GET['get']);
		while($result = mysql_fetch_object($class_member))
		{
			$u = UserById($result->uid);
			$img = ($u['avatar']!='') ? '/user/'.$u['user'].'/'.$u['avatar'] : '';
			$classStd .= '<span class="member_selected" id="member-id-'.$u['ssid'].'">
			<input type="hidden" name="member_group[]" value="'.$u['ssid'].'"><img src="'.image_resize($img, 26, 26).'" class="inlinepos"> '.str_replace('|',' ',$u['fullname']).'
			<a href="javascript:void(0);" onclick="$(\'#member-id-'.$u['ssid'].'\').remove();"><i class="fa fa-times"></i></a></span>';
		}
		$io['body'] = '<form method="post" action="save.php" id="data-form"  enctype="multipart/form-data">';
		$io['body'] .= '<div class="frm-group">
						<p class="title">สมาชิก</p>
						<p class="frm-obj menulist"><input type="text" class="text-input" id="idCheck" placeholder="ป้อนชื่อแล้วเลือกจากรายการ">
						<ul class="uls hidden"></ul></p>
						<p><button class="button btGreen" onclick="stds(\'#data-form\')" role="button" type="button">
						<i class="fa fa-check"></i> บันทึก</button>
						<i class="hidden fa success-check fa-2x" style="display:none;"></i></p><br>
						<div class="frm-group">
								<h2>นักเรียน</h2>
						</div>
						<div class="member-list">'.$classStd.'</div>
						<input type="hidden" name="clsid" value="'.$_GET['get'].'">
					</div>';
		$io['body'] .= '</form>';
		$io['body'] .= '<script type="text/javascript">
							$(document).ready(function() {
							  $(\'#idCheck\').keypress(function(){
							       if($(this).val()!=\'\'){
							           $.get( "/search.php?search_type=user&mod=2&q="+$(this).val(), function( data ) {
							                $(\'.uls\').slideDown().html(data);
							           });
							       }
							  })
							});
						</script>';

		$prn = json_encode($io);
		header('Content-Type: application/json');
	break;
	case 'stdSetup':
		$io = array('title','body');
		$io['body'] = '

		<h1>กรุณากำหนดค่าระดับชั้นและห้องเรียนใหม่</h1>
		<form id="stdSetupFrm" onSubmit="stdSetupSave();return false;" action="save.php">
		<input type="hidden" name="uid" value="'.$_GET['get'].'">
		<div class="frm-group">
			<p class="title">ระดับ</p>
			<p class="frm-obj">
			<select name="type">
				<option value="primary">ประถมศึกษา</option>
				<option value="secondary">มัธยมศึกษา</option>
			</select>
			</p>
		</div>
		<div class="frm-group">
			<p class="title">ชั้น</p>
			<p class="frm-obj">
				<select name="grade">
				<option value="1">ปีที่ 1</option>
				<option value="2">ปีที่ 2</option>
				<option value="3">ปีที่ 3</option>
				<option value="4">ปีที่ 4</option>
				<option value="5">ปีที่ 5</option>
				<option value="6">ปีที่ 6</option>
				</select>
			</p>
		</div>
		<div class="frm-group">
			<p class="title">ห้อง</p>
			<p class="frm-obj">
				<select name="class">
				<option value="1">ห้อง 1</option>
				<option value="2">ห้อง 2</option>
				<option value="3">ห้อง 3</option>
				<option value="4">ห้อง 4</option>
				<option value="5">ห้อง 5</option>
				<option value="6">ห้อง 6</option>
				<option value="7">ห้อง 7</option>
				<option value="8">ห้อง 8</option>
				<option value="9">ห้อง 9</option>
				<option value="10">ห้อง 10</option>
				<option value="11">ห้อง 11</option>
				<option value="12">ห้อง 12</option>
				<option value="13">ห้อง 13</option>
				<option value="14">ห้อง 14</option>
				</select>
			</p>
		</div>
		<button class="button btBlue" type="submit">บันทึก</button></form>';
		$prn = json_encode($io);
		header('Content-Type: application/json');
		break;
	case 'courselb':
		$course =CourseInfo($_GET['get']);
		$cimage = '/holder.png/60x60/Thumbnail';
		//if($course['lastuse']=='') $course['lastuse'] = 'ยังไม่เคย'; else $course['lastuse'] = date('d-n-Y, H:i', $course['lastuse']);
		if($course['img']=='') $img = $cimage; else $img = image_resize($course['img'],100,100);
		
		$standardgroup = GroupInfo($course['main_group']);
		if($standardgroup['name']=='') $standardgroup['name'] = 'ไม่มี';
		$teacher_json = json_decode($course['alternet_teacher_id']);

		$io = array('title','body');
		//Section info
		$classroom  = explode(',', $course['class_id']);
		foreach($classroom as $cls){
			$classdata = classinfo($cls);
			
			$clstext .= '<option value="'.$classdata['clsid'].'">'.$classdata['title'].'</option>';
		}
		if(mb_strlen($course['cdetail'])>100) $more_txt = ' <a href="javascript:void(0);" data-stat=0 class="morelimit"><i class="fa fa-plus-square-o"></i> เพิ่มเติม</a>';
		$io['body'] .= '<div class="section-lb">';
		$io['body'] .= '<div class="leftzone"><p><img src="'.$img.'" style="border-radius: 4px"></p></div>';
		$io['body'] .= '<div class="rightzone">
			<h3>'.$course['cname'].'</h3>
			<div class="detail limitHeight">'.nl2br($course['cdetail']).$more_txt.'</div>
		</div>';
		$io['body'] .= '</div><br>';
		
		$io['body'] .= '<div class="section-lb margintop5">
		<div class="leftzone"><b>ประเภทวิชา</b></div>
		<div class="rightzone"> '.formatCourseType($course['type']).'</div>
		</div>';
		
		$io['body'] .= '<div class="section-lb margintop5">
		<div class="leftzone"><b>กลุ่มสาระ</b></div>
		<div class="rightzone">'.$standardgroup['name'].'</div>
		</div>';
		
		
		/*
		if($client['role']==3){
		$io['body'] .= '<div class="section-lb">';
			//?q=SingleScore&course=70&source=1550600042131
			$callScore = json_decode(file_get_contents(conf('inclassRES').'?q=SingleScore&course='.$course['course_id'].'&source='.$client['user']), true);
			$io['body'] .= '<div class="leftzone"><b>คะแนน</b></div>';
			$io['body'] .= '<div class="rightzone">
			<span class="badge"><img src="/image?width=16&height=16&image=/library/images/Crystal.png"> '.$callScore['crystal'].'</span>
			<span class="badge"><img src="/image?width=16&height=16&image=/library/images/Gold.png"> '.$callScore['gold'].'</span>
			<span class="badge"><img src="/image?width=16&height=16&image=/library/images/Coin.png"> '.$callScore['coin'].'</span>
			<a href="#" onclick="lightbox('.$course['course_id'].',\'stdScore\')"><span class="badge"><i class="fa fa-star"></i> ดูเพิ่มเติม</span></a></div>';
		$io['body'] .= '</div><br>';
		}
		
		//Session list
		$callSessionList = json_decode(file_get_contents(conf('inclassRES').'?q=sessionlist&course='.$course['course_id']), true);
		$number = 1;
		foreach($callSessionList['session'] as $slist){
			@$options .= "<option value='".$slist['session_id']."'>$number เมื่อ".relativeTime($slist['timestamp'])."</option>";
		}
		*/

		if($client['role']>=5):
		$io['body'] .= '<div class="section-lb">';
		$io['body'] .= '<div class="leftzone"><b>เลือกห้องเรียน</b></div>';
		$io['body'] .= '<div class="rightzone"><select id="classselect">'.$clstext.'</select></div>';
		endif;
		
		//Section teacher
		foreach($teacher_json as $teacherOnce)
		{
			$udimg = '/holder.png/60x60/Thumbnail';
			$tear = UserById($teacherOnce);
			$isyou = ($tear['user']==$_SESSION['loginid']['nickname']) ? '<sup style="color: #080;"><i class="fa fa-check-circle-o"></i> คือคุณ</sup>' : '';
			$uimg = ($tear['avatar']=='') ? $cimage  : image_resize('/user/'.$tear['user'].'/'.$tear['avatar'], 60,60);
			$io['body'] .= '<div class="section-lb">';
			$io['body'] .= '<div class="leftzone"><p><img src="'.$uimg.'" class="img-circle"></p></div>';
			$io['body'] .= '<div class="rightzone"><h3>ผู้สอน '.$isyou.'</h3><p><a href="/profile/'.$tear['user'].'" target="_blank">'.str_replace('|',' ',$tear['fullname']).'</a></p></div>';

			$io['body'] .= '</div>';
		}

	/*
		if($client['role']==3):
			$io['body'] .= '<div class="section-lb">
			<p align="center">
			<button class="button btBlue" onclick="window.open(\'/clicker/session?q=session_media&return_type=return_media&tid='.$_SESSION['loginid']['nickname'].'&crs='.base64_encode($course['course_id']).'&sessionid=\'+$(\'#classtime option:selected\').val(),\'clicker\')"><i class="fa fa-simplybuilt"></i> ดูเอกสารตามคาบ</button>
			</div>';
		endif;
	*/
		if($client['role']>=5):
		$io['body'] .= '<div class="section-lb">
			<p align="center">
			<button class="button btBlue" onclick="lightbox(\''.$_GET['get'].'+\'+$(\'#classselect option:selected\').val(),\'manageCourse\')"><i class="fa fa-tasks"></i> บริหารจัดการ</button>
			<button class="button btGreen" onclick="lightbox(\''.$_GET['get'].'+\'+$(\'#classselect option:selected\').val(),\'ShareToCC\')"><i class="fa fa-edit"></i> โพสต์</button>
			</p><br>
		</div>';
		endif;


		$prn = json_encode($io);
		header('Content-Type: application/json');
	break;
	case 'stdScore':
		$course =CourseInfo($_GET['get']);
		$io = array('title','body');
		$io['body'] .= '<div class="section-lb">';
			//?q=SingleScore&course=70&source=1550600042131
			$callScore = json_decode(file_get_contents(conf('inclassRES').'?q=SingleScore&course='.$course['course_id'].'&source='.$client['user']), true);
			$io['body'] .= '<div class="section-lb">';
			$io['body'] .= '<div class="leftzone"><h3>คะแนนรวม</h3></div>';
			$io['body'] .= '<div class="rightzone">
			<span class="badge"><img src="/image?width=16&height=16&image=/library/images/Crystal.png"> '.$callScore['crystal'].'</span>
			<span class="badge"><img src="/image?width=16&height=16&image=/library/images/Gold.png"> '.$callScore['gold'].'</span>
			<span class="badge"><img src="/image?width=16&height=16&image=/library/images/Coin.png"> '.$callScore['coin'].'</span>
			<span class="badge"><i class="fa fa-certificate"></i> คะแนนเก็บ '.$callScore['main_score'].'</span></div>';
			$io['body'] .= '</div><br><br>';
			foreach($callScore['score_history'] as $scl):
			$io['body'] .= '<div class="section-lb">';
			$io['body'] .= '<div class="leftzone"><b>'.date('D d F Y - H:i', $scl['timestamp']).'</b></div>';
			$io['body'] .= '<div class="rightzone">
			<span class="badge"><img src="/image?width=16&height=16&image=/library/images/'.ucfirst($scl['score_type']).'.png"> '.$scl['score'].'</span></div>';
			$io['body'] .= '</div></div>';

			endforeach;
		if($client['role']==3):
			$io['body'] .= '<div class="section-lb">
			<p align="center">
			<button class="button btBlue" onclick="lightbox('.$course['course_id'].',\'courselb\')"><i class="fa fa-caret-left"></i> ย้อนกลับ</button>
			</div>';
		endif;

		$prn = json_encode($io);
		header('Content-Type: application/json');
	break;
	case 'CourseStd':
		$io = array('title','body');
		$courseInfo = CourseInfo($_GET['get']);
		$io['body'] = '<form method="post" action="save.php" id="data-form"  enctype="multipart/form-data">';
		$io['body'] .= '<input type="hidden" name="courseid" value="'.$courseInfo['course_id'].'">';
		$io['body'] .= '<div class="frm-group">
							<p class="title">วิชา</p>
							<p class="frm-obj"><input type="text" value="'.$courseInfo['cname'].'" class="text-input" readonly=true></p>
						</div>';
		$classroomlist = explode(',', $courseInfo['class_id']);
		$classroom = mysql_query("SELECT clsid,title FROM tc_classroom WHERE sid=".$client['org']);
		while($result = mysql_fetch_object($classroom)){
			if(in_array($result->clsid, $classroomlist)){
				continue;
			}
			$classoption .= '<option value="'.$result->clsid.'">'.$result->title.'</option>';
		}

		$io['body'] .= '<div class="frm-group">
							<p class="title">กำหนดห้องเรียน</p>
							<p class="frm-obj">
							<select name="courseCls" id="courseCls">
								'.$classoption.'
							</select>&nbsp;
							<button class="button btGreen" onclick="ccs(\'#data-form\','.$courseInfo['course_id'].')" role="button" type="button"><i class="fa fa-plus"></i> เพิ่มห้องที่เลือก</button>
							</p>
						</div>';

		
		foreach($classroomlist as $rooms){
			$classif = classinfo($rooms);
			$clslist .= '<li>
			<input type="hidden" name="classlist[]" value="'.$classif['clsid'].'">
			<img src="'.image_resize($classif['image'],30,30).'" class="img-radius inlinepos">'.$classif['title'].'&nbsp; <a href="javascript:;" onclick="unregisterccr('.$classif['clsid'].','.$courseInfo['course_id'].')"><i class="fa fa-trash"></i></a></li>';
		}
		$io['body'] .= '<div class="stdLists">
							<div class="frm-group">
								<p class="title">ห้องที่เรียนวิชานี้ <i class="hidden fa success-check" style="display:none;"></i> </p>
							</div>
							<ul class="list">'.$clslist.'</ul></div>';

		$io['body'] .= '</form>';
		$prn = json_encode($io);
		header('Content-Type: application/json');
	break;
	case 'GetStdList':

		$io = array('title','body');
		$class_member = mysql_query("SELECT uid FROM tc_class_register WHERE role=1 AND status=1 AND class_id=".$_GET['get']);
		while($result = mysql_fetch_object($class_member)){
			$u = UserById($result->uid);
			$io['body'] .= '<li><i class="fa fa-user"></i> &nbsp;'.str_replace('|',' ',$u['fullname']).'</li>';
		}

		$prn = json_encode($io);
		header('Content-Type: application/json');
	break;
	case 'editCourse':
		$io = array('title','body');
		$courseInfo = CourseInfo($_GET['get']);
		$tj = json_decode($courseInfo['alternet_teacher_id']);
		foreach($tj as $t){
			$ta = UserById($t);
			$img = ($ta['avatar']!='') ? '/user/'.$ta['user'].'/'.$ta['avatar'] : '';
			$teacher .= '<span class="member_selected" id="member-id-'.$t.'">
			<input type="hidden" name="member_group[]" value="'.$t.'"><img src="'.image_resize($img, 26, 26).'" class="inlinepos"> '.str_replace('|',' ',$ta['fullname']).'
			<a href="javascript:void(0);" onclick="$(\'#member-id-'.$t.'\').remove();"><i class="fa fa-times"></i></a></span>';
		}
		
		$io['body'] = '<form method="post" action="/save.php?q=ces" id="data-form" enctype="multipart/form-data">';
		$io['body'] .= '<input type="hidden" name="courseid" value="'.$courseInfo['course_id'].'">';
		$io['body'] .= '<div class="frm-group">
							<p class="title">วิชา</p>
							<p class="frm-obj"><input type="text" value="'.$courseInfo['cname'].'" name="cn" class="text-input" required></p>
						</div>';
		$io['body'] .= '<div class="frm-group">
							<p class="title">คำอธิบายรายวิชา</p>
							<p class="frm-obj"><textarea class="text-input text-area" name="dtail" required>'.$courseInfo['cdetail'].'</textarea></p>
						</div>';

		$io['body'] .= '<div class="frm-group">
							<p class="title">กลุ่มสาระวิชา</p>
							<p class="frm-obj"><select name="group"><option></option>';

		$sql_lev = mysql_query('select * from '.conf('table_prefix').'_group where type=1 order by gid asc');
							while($lev = mysql_fetch_array($sql_lev))
							{
								$sel = ($lev['gid']==$courseInfo['main_group']) ? 'selected' : '';
								$io['body'] .= '<option value="'.$lev['gid'].'" '.$sel.'> '.$lev['name'].'</option>';
							}
		$io['body'] .= '</select></p></div>';

		$io['body'] .= '<div class="frm-group">
							<p class="title">ประเภทวิชา</p>
							<p class="frm-obj">'.$courseInfo['type'].'</p>
						</div>';
		$io['body'] .= '<div class="frm-group">
							<p class="title">กำหนดครูผู้สอน</p>
							<p class="frm-obj menulist">
							<input type="text" class="text-input" id="idCheck" placeholder="ป้อนชื่อแล้วเลือกจากรายการ">
							<ul class="uls hidden"></ul></p>
							<div class="member-list">'.$teacher.'</div>
						</div>';
		$io['body'] .= '<div class="frm-group">
							<p class="title">เปลี่ยนภาพประกอบ</p>
							<p class="frm-obj"><input type="file" accept="image/*" class="text-input" name="course_image" id="courseImgChg" placeholder="คลิกเลือกไฟล์รูปภาพ"></p>
						</div>';
		$io['body'] .= '<div class="frm-group"><br><p align="center"><i class="hidden fa success-check fa-2x" style="display:none;"></i></p><br><p align="center">
		<button onclick="ces(\'#data-form\');return false;" class="button btGreen" role="button" type="button"><i class="fa fa-check"></i> บันทึก</button>
		<button type="button" onclick="if(confirm(\'ยืนยันการลบ\')==true) delCourse('.$courseInfo['course_id'].'); else return false;" class="button btGray"><i class="fa fa-trash"></i> ลบวิชานี้</button>
		</p></div>';
		$io['body'] .= '</form>';
		$io['body'] .= '
			<script type="text/javascript">
				function ces(fid){
				
						var dataset = form_to_json(fid);
						$.ajaxFileUpload
								(
									{
										url:"/save.php?q=ces",
										secureuri:true,
										data: dataset,
										fileElementId: "courseImgChg",
										dataType: "json",
										success: function (res, status)
										{
										  if(res==1){
											  $(\'.success-check\').addClass(\'fa-check-circle-o\');
											  $(\'.success-check\').text(\' บันทึกเรียบร้อยแล้ว\')
											  $(\'.success-check\').removeClass(\'fa-times\');
											  $(\'.success-check\').show();
										  }
										  else{
											  $(\'.success-check\').removeClass(\'fa-check-circle-o\');
											  $(\'.success-check\').addClass(\'fa-times\');
											  $(\'.success-check\').show();
										  }
										}
									}
								)
								return false;
												
				}
							$(document).ready(function() {
							    $(\'#idCheck\').keypress(function(){
							       if($(this).val()!=\'\'){
							           $.get( "/search.php?search_type=user&mod=4&q="+$(this).val(), function( data ) {
							                $(\'.uls\').slideDown().html(data);
							           });
							       }
							  })
							});
						 </script>
						';

		$prn = json_encode($io);
		header('Content-Type: application/json');
	break;
	case 'registerbt':
		$io = array('title','body');

		//Check
			$sqlCheck = mysql_query("SELECT * FROM tc_course_register WHERE course_id=".$_GET['get']." and uid=".$client['ssid']);
			$result = mysql_fetch_array($sqlCheck);
			if(empty($result)){
				$sqlSetup = mysql_query("INSERT INTO tc_course_register VALUES(".$_GET['get'].",".$client['ssid'].",0,".time().",1,NULL)");
				if($sqlSetup)
					$io['body'] .= "<center><br><br><i class='fa fa-check-square-o fa-5x'></i><h3>ยินดีด้วย การสมัครเข้าร่วมรายวิชาเสร็จเรียบร้อยแล้ว</h3></center>";
			}
			else{
				$io['body'] .= "<center><br><br><i class='fa fa-check-circle-o fa-5x'></i><h3>คุณได้สมัครรายวิชานี้ไปแล้ว</h3></center>";
			}

		header('Content-Type: application/json');
		$prn = json_encode($io);
	break;
	case 'activate_class_teacher':

		$selectClassroom = mysql_query("SELECT teacher FROM tc_classroom WHERE clsid=".$_GET['get']);
		$result = mysql_fetch_array($selectClassroom);
		$teacherResult = json_decode($result['teacher']);

		if(!in_array($client['ssid'], $teacherResult)) {
			$teacherResult[] = $client['ssid'];
			$newdata = json_encode($teacherResult);
			$update = mysql_query("UPDATE tc_classroom SET teacher='".$newdata."' WHERE clsid=".$_GET['get']);
			if($update){
				$res['body'] = "<center><br><br><i class='fa fa-check fa-3x'></i> </center><h2 align=center>ตั้งเป็นครูประจำชั้นเรียบร้อยแล้ว</h2>";
			}
		}
		else{
			for($x=0;$x<count($teacherResult);$x++){
				if($teacherResult[$x]==$client['ssid'])
					unset($teacherResult[$x]);
				else
					continue;
			}
			$newdata = json_encode($teacherResult);

			$update = mysql_query("UPDATE tc_classroom SET teacher='".$newdata."' WHERE clsid=".$_GET['get']);
			if($update){
				$res['body'] = "<center><br><br><i class='fa fa-check fa-3x'></i> </center><h2 align=center>ยกเลิกการเป็นครูประจำชั้นเรียบร้อยแล้ว</h2>";
			}
		}
		header('Content-Type: application/json');
		$prn = json_encode($res);
	break;
	case 'ShareToCC':
		$getinfo = explode(' ', $_REQUEST['get']);
		$class = classinfo($getinfo[1]);
		$course = CourseInfo($getinfo[0]);
		$res = array('title','body');
		$res['body'] .= '<script type="text/javascript">
		<!--
		function savereply(postid){
			var remsg = $(\'#replymsg-\'+postid).val();
			$.ajaxFileUpload
						(
							{
								url:"/save.php?q=replysave",
								secureuri:true,
								data: {"pid":postid, "msg":remsg},
								fileElementId: "repf-"+postid,
								dataType: "json",
								success: function (data, status)
								{
									lightbox(\''.$course['course_id'].' '.$class['clsid'].'\',\'ShareToCC\');
								}
							}
						)
		}
		$(document).ready(function()
		{
			$("#sharedata").click(function(){
				var postboxmsg = $(\'.postmsgbox\').val()
				var resp = $(\'#resp:checked\').val();
				if(resp!=1) resp=0;
				$.ajaxFileUpload
				(
					{
						url:"/save.php?q=userpost",
						secureuri:true,
						data: {\'msg\': postboxmsg,\'ref\': \''.$course['course_id'].'|'.$class['clsid'].'\', \'section\':\'cc\',\'response\': resp},
						fileElementId: "postfile",
						dataType: "json",
						success: function (data, status)
						{
							lightbox(\''.$course['course_id'].' '.$class['clsid'].'\',\'ShareToCC\');
						}
					}
				)
		});
	});

		//-->
		</script>';
		$res['body'] .= '<div class="postcontainer">';
		$res['body'] .= '<p class="title"><i class="fa fa-pencil-square-o"></i> '.$course['cname'].' ·  '.$class['title'].'</p>';
		$res['body'] .= '<textarea id="postmsgbox" class="postmsgbox"></textarea>';
		$res['body'] .= '<div class="postbuttoncontainer">
			<input type="file" id="postfile" name="postfile" class="postfilebox" onchange="$(\'.postfiletext\').html($(this).val())">
			<p class="postfiletext"></p>
			<button class="button"><input type="checkbox" name="response" id="resp" value="1"> ให้ตอบกลับ</button>
			<button class="button btGray inlinepos" onclick="addPostFile()"><i class="fa fa-paperclip"></i> แนบไฟล์</button>
			<button class="button btGreen inlinepos" id="sharedata"><i class="fa fa-share-alt"></i> แชร์</button>
		</div>';
		$res['body'] .= '<br>';
		$query = "section='cc' AND ref='".$course['course_id']."|".$class['clsid']."'";
		$res['body'] .= $show->getStream($query);
		$res['body'] .= '</div>';
		header('Content-Type: application/json');
		$prn = json_encode($res);
	break;
	case 'manageCourse':
		$res = array('title','body');
		$arres = explode(" ", $_REQUEST['get']);
		$coursedata = CourseInfo($arres[0]);
		$classdata = classinfo($arres[1]);
		$sessionlist = ClassSession($arres[0]);
		$io['body'] .= '<div class="section-lb">';
		$io['body'] .= '
		<h1>การจัดการการเรียน '.$coursedata['cname'].' ชั้น '.$classdata['title'].'</h1>
		<div class="leftzone" style="width:20%">
			<div class="menu">
			<h3 style="padding-right: 15px;"><a href="#" onclick="lightbox(\''.$_REQUEST['get'].'\',\'manageCourse\')"><i class="fa fa-navicon"></i></a></h3>
			<ul>
				<a href="javascript:;" onClick="$(\'.hidden\').hide();$(\'#SessionList\').show();"><li  style="font-size: 16px;">คาบ</li></a>
				<a href="javascript:;" onClick="$(\'.hidden\').hide();$(\'#StdList\').show();$(\'#SessionList\').hide();"><li  style="font-size: 16px;">รายชื่อนักเรียน</li></a>
				<a href="javascript:;" onClick="$(\'.hidden\').hide();$(\'#Score\').show();$(\'#SessionList\').hide();"><li  style="font-size: 16px;">คะแนนเก็บ</li></a>
			</ul>
			</div>
		</div>';
		$io['body'] .= '<div class="rightzone" style="width: 670px;">
			
			<script>
			var minus = "<i class=\"fa fa-minus-circle delit\" onclick=\"$(\'p:has(>i:hover)\').remove();\"></i>";
			$("document").ready(function(){
				$(".addSb").click(function(){
					$(".sessioninfobox").append("<p class=frm-obj>"+$(".orig").html()+minus+"</p>");
				})
				$(".addAtv").click(function(){
					$(".activitbox").append("<p class=frm-obj>"+$(".atv-orig").html()+minus+"</p>");
				})
				$("#newSessionfrm").submit(function(){
					$.post("/save.php?q=createClassSession", $("#newSessionfrm").serialize(), function(data)
					{

						$("#newSession").html(data.text);
					});
					return false;
				})
			})
			</script>
			<div id="SessionList">
				<h2>คาบเรียนทั้งหมด <a href="javascript:;" onClick="$(\'.hidden\').hide();$(\'#newSession\').show();$(\'#SessionList\').hide();"><i class="fa fa-plus-circle"></i> คาบใหม่</a></h2>
				<div class="datagrid">
				<table style="width: 100%;border:none;">
					<thead><tr><th>#</th><th>วันที่/เวลา</th><th>จำนวนหัวข้อ</th><th>จำนวนกิจกรรม</th><th><i class="fa fa-reorder"></i></th></tr></thead>
						<tbody>';
		$loop = 1;
		foreach($sessionlist as $sesd){
			$sesd['subject_count'] = count(unserialize($sesd['subject']));
			$sesd['activity_count'] = count(unserialize($sesd['activity']));
			$io['body'] .= '<tr>
						        <td>'.$loop.'</td>
	                			<td>'.thai_date(strtotime($sesd['datetime'])).'</td>
	                			<td>'.$sesd['subject_count'].'</td>
	                			<td>'.$sesd['activity_count'].'</td>
	                			<td>
									<button class="button btGreen" onclick="window.open(\''.conf('inclassURL').'/session?crs='.base64_encode($arres[0]).'\',\'sessionwin\')"><i class="fa fa-check-circle"></i> เริ่ม</button>
									<button class="button" onclick="window.open(\''.conf('inclassURL').'/session?crs='.base64_encode($arres[0]).'&q=session_media&sessionid='.$sesd['sessionid'].'&return_type=return_media\',\'sessionwin\')">ไฟล์</button>
								</td>
	                		</tr>';
			$loop++;
		}
		$io['body'] .= '</tbody>
					</table>
				</div>
			</div>
			
			<div id="newSession" class="hidden">
				<h2>สร้างคาบใหม่</h2>
				<form id="newSessionfrm">
				<input type="hidden" name="courseid" value="'.$coursedata['course_id'].'">
				<input type="hidden" name="classid" value="'.$classdata['clsid'].'">
				<div class="frm-group">
					<p class="title">หัวข้อ  <a href="#" class="addSb text-blue"><i class="fa fa-plus-square"></i></a></p>
					<div class="sessioninfobox">
						<p class="frm-obj orig"><input type="text" class="text-input" required name="sessioninfo[]" placeholder="กำหนดชื่อเรื่อง"></p>
					</div>
				</div>
				<div class="frm-group">
					<p class="title">กิจกรรม  <a href="#" class="addAtv text-blue"><i class="fa fa-plus-square"></i></a></p>
					<div class="activitbox">
						<p class="frm-obj atv-orig"><input type="text" class="text-input" required name="sessionactivite[]" placeholder="กำหนดกิจกรรม"></p>
					</div>
				</div>
				<div class="frm-group">
					<p class="title">วัน-เวลา</p>
					<p class="frm-obj atv-orig"><input type="date" class="text-input" required name="sessiondate"></p>
					<p class="frm-obj atv-orig"><input type="time" class="text-input" required name="sessiontime"></p>
				</div>
				<div class="frm-group">
					<p><button type="submit" class="button btGreen">บันทึก</button></p>
				</div>
				</form>
			</div>
			
			<div id="StdList" class="hidden">
			<div class="datagrid">
				<table style="width: 100%;border:none;">
					<thead><tr><th>#</th><th>ชื่อ สกุล</th></tr></thead>
						<tbody>';
						$loop = 1;
						$class_member = mysql_query("SELECT uid FROM tc_class_register WHERE class_id=".$arres[1]);
						
						while($stdRes = mysql_fetch_array($class_member))
						{
							$userx[$loop] = UserById($stdRes['uid']);
							$io['body'] .= '<tr>
												<td>'.$loop.'</td>
												<td><a href="/profile/'.$userx[$loop]['user'].'" target="_blank">'.str_replace('|',' ',$userx[$loop]['fullname']).'</a></td>
											</tr>';
							$loop++;
						}
						$io['body'] .= '</tbody>
					</table>
				</div>
			</div>
			
			<div id="Score" class="hidden">
			<div class="datagrid">
				<table style="width: 100%;border:none;">
					<thead><tr><th>#</th><th>ชื่อ สกุล</th><th>Gold</th><th>Diamond</th><th>Crystal</th></tr></thead>
						<tbody>';
						$ucs = count($userx);

						for($lx=1;$lx<=$ucs;$lx++){
							$io['body'] .= '<tr>
												<td>'.$lx.'</td>
												<td><a href="/profile/'.$userx[$lx]['user'].'" target="_blank">'.str_replace('|',' ',$userx[$lx]['fullname']).'</a></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>';
							$loop++;
						}
						$io['body'] .= '</tbody>
					</table>
				</div>
			</div>
			
		</div>';
		$io['body'] .= '</div>';
		header('Content-Type: application/json');
		$prn = json_encode($io);
	break;
	case 'ShareToClass':
		$result = classinfo($_REQUEST['get']);

		$res = array('title','body');
		$res['body'] .= '<script type="text/javascript">
		<!--
		function savereply(postid){
			var remsg = $(\'#replymsg-\'+postid).val();
			$.ajaxFileUpload
						(
							{
								url:"/save.php?q=replysave",
								secureuri:true,
								data: {"pid":postid, "msg":remsg},
								fileElementId: "repf-"+postid,
								dataType: "json",
								success: function (data, status)
								{
									lightbox('.$result['clsid'].',\'ShareToClass\');
								}
							}
						)
		}
		$(document).ready(function()
		{
			$("#sharedata").click(function(){
				var postboxmsg = $(\'.postmsgbox\').val()
				var resp = $(\'#resp:checked\').val();
				if(resp!=1) resp=0;
				$.ajaxFileUpload
				(
					{
						url:"/save.php?q=userpost",
						secureuri:true,
						data: {\'msg\': postboxmsg,\'ref\': '.$result['clsid'].', \'section\':\'classroom\',\'response\': resp},
						fileElementId: "postfile",
						dataType: "json",
						success: function (data, status)
						{

							lightbox('.$result['clsid'].',\'ShareToClass\');
						
						}
					}
				)
		});
	});

		//-->
		</script>';
		$res['body'] .= '<div class="postcontainer">';
		$res['body'] .= '<p class="title"><i class="fa fa-pencil-square-o"></i> โพสต์ไปที่ '.$result['title'].'</p>';
		$res['body'] .= '<textarea id="postmsgbox" class="postmsgbox"></textarea>';
		$res['body'] .= '<div class="postbuttoncontainer">
			<input type="file" id="postfile" name="postfile" class="postfilebox" onchange="$(\'.postfiletext\').html($(this).val())">
			<p class="postfiletext"></p>
			<button class="button"><input type="checkbox" name="response" id="resp" value="1"> ให้ตอบกลับ</button>
			<button class="button btGray inlinepos" onclick="addPostFile()"><i class="fa fa-paperclip"></i> แนบไฟล์</button>
			<button class="button btGreen inlinepos" id="sharedata"><i class="fa fa-share-alt"></i> แชร์</button>
		</div>';
		$res['body'] .= '<br>';
		$query = "section='classroom' AND ref=".$result['clsid'];
		$res['body'] .= $show->getStream($query);
		$res['body'] .= '</div>';
		header('Content-Type: application/json');
		$prn = json_encode($res);
	break;
	case 'ShareToCourse':
		$result = CourseInfo($_REQUEST['get']);

		$res = array('title','body');
		$res['body'] .= '<script type="text/javascript">
		<!--
		function savereply(postid){
			var remsg = $(\'#replymsg-\'+postid).val();
			$.ajaxFileUpload
						(
							{
								url:"/save.php?q=replysave",
								secureuri:true,
								data: {"pid":postid, "msg":remsg},
								fileElementId: "repf-"+postid,
								dataType: "json",
								success: function (data, status)
								{
									$(\'.rep-\'+postid).hide();
									lightbox('.$result['course_id'].',\'ShareToCourse\');
								}
							}
						)
		}
		
		$(document).ready(function()
		{
			$("#sharedata").click(function(){
				var postboxmsg = $(\'.postmsgbox\').val()
				var resp = $(\'#resp:checked\').val();
				if(resp!=1) resp=0;
				$.ajaxFileUpload
				(
					{
						url:"/save.php?q=userpost",
						secureuri:true,
						data: {\'msg\': postboxmsg,\'ref\': '.$result['course_id'].', \'section\':\'course\',\'response\': resp},
						fileElementId: "postfile",
						dataType: "json",
						success: function (data, status)
						{
							lightbox('.$result['course_id'].',\'ShareToCourse\');
						}
					}
				)
		});
	});

		//-->
		</script>';
		$res['body'] .= '<div class="postcontainer">';
		$res['body'] .= '<p class="title"><i class="fa fa-pencil-square-o"></i> โพสต์ไปที่ '.$result['cname'].'</p>';
		$res['body'] .= '<textarea id="postmsgbox" class="postmsgbox"></textarea>';
		$res['body'] .= '<div class="postbuttoncontainer">
			<input type="file" id="postfile" name="postfile" class="postfilebox" onchange="$(\'.postfiletext\').html($(this).val())">
			<p class="postfiletext"></p>
			<button class="button"><input type="checkbox" name="response" id="resp" value="1"> ให้ตอบกลับ</button>
			<button class="button btGray inlinepos" onclick="addPostFile()"><i class="fa fa-paperclip"></i> แนบไฟล์</button>
			<button class="button btGreen inlinepos" id="sharedata"><i class="fa fa-share-alt"></i> แชร์</button>
		</div>';
		$res['body'] .= '<br>';
		$query = "section='course' AND ref=".$result['course_id'];
		$res['body'] .= $show->getStream($query);
		$res['body'] .= '</div>';
		header('Content-Type: application/json');
		$prn = json_encode($res);
	break;
	case 'classfiles':
		$res = array('title','body');
		$allowtype = array('pdf'=>'pdf-o','mp3'=>'audio-o','ppt'=>'powerpoint-o','pptx'=>'powerpoint-o','wav'=>'audio-o','mp4'=>'video-o','zip'=>'zip-o','rar'=>'zip-o','txt'=>'text-o','jpg'=>'image-o','png'=>'image-o','bmp'=>'image-o','doc'=>'word-o','docx'=>'word-o','xls'=>'excel-o','xlsx'=>'excel-o');

		$selectClassroom = mysql_query("SELECT clsid FROM tc_classroom WHERE cls_number=".$client['class']." AND grade=".$client['grade']);
		$result = mysql_fetch_array($selectClassroom);

		$selectFile = mysql_query("SELECT * FROM tc_files WHERE `typeid` = ".$result['clsid']." AND `type`='class' ORDER BY id DESC");
		$res['body'] .= '<h2><i class="fa fa-file"></i> ไฟล์ของห้องนี้</h2>';
		$res['body'] .= '<div class="lb-menu"><button class="button btBlue" onclick="lightbox('.$result['clsid'].', \'ShareToClass\')"><i class="fa fa-pencil-square"></i> เพิ่ม</button></div>';
		$res['body'] .= '<div class="datagrid"><table style="width: 100%;border:none;">
		<thead><tr><th>ไฟล์</th><th>โดย</th><th>เมื่อ</th></tr></thead><tbody>';
		while($fileres = mysql_fetch_array($selectFile)){
			$fown = UserById($fileres['by']);
			$res['body'] .= '<tr><td><a href="'.$fileres['fileurl'].'"><i class="fa fa-file-'.$allowtype[$fileres['filetype']].'"></i> '.$fileres['filename'].'</a></td><td>'.str_replace('|',' ', $fown['fullname']).'</td><td>'.$fileres['timestamp'].'</td></tr>';
		}
		$res['body'] .= '<tbody></table></div>';

		header('Content-Type: application/json');
		$prn = json_encode($res);
	break;
	default:
		$io = array('title','body');
		header('Content-Type: application/json');
		$prn = json_encode($io);
	break;
}

echo $prn;
?>