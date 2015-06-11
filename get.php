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
	case 'courselb':
		$course =CourseInfo($_GET['get']);
		$cimage = 'holder.js/100x100/random';
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
			$udimg = 'holder.js/60x60/random';
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
			<button class="btGreen" onclick="window.open(\'/clicker/session?crs='.base64_encode($course['course_id']).'\',\'clicker\')">เริ่มทันที</button>
			</p><br>
		</div>';
		

		$prn = json_encode($io);
		header('Content-Type: application/json');
	break;

}

echo $prn;
?>