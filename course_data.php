<?php
include 'initial.min.php';

$course = CourseInfo($_GET['getid']);
$teacher_json = json_decode($course['alternet_teacher_id']);
unset($course['uid']);
$course['img'] = ($course['img']!='') ? conf('url').$course['img'] : '';

$class = classinfo($course['class_id']);
$course['class']['grade'] = $class['grade'];
$course['class']['room_number'] = $class['cls_number'];
$course['class']['title'] = $class['title'];

foreach($teacher_json as $teacher_id){
	$teacher_data = UserById($teacher_id);
	$teachername = explode('|', $teacher_data['fullname']);
	$course['teacher'][$teacher_id]['id'] = $teacher_data['ssid'];
	$course['teacher'][$teacher_id]['user'] = $teacher_data['user'];
	$course['teacher'][$teacher_id]['firstname'] = $teachername[0];
	$course['teacher'][$teacher_id]['lastname'] = $teachername[1];
	$course['teacher'][$teacher_id]['role'] = $teacher_data['role'];
	$course['teacher'][$teacher_id]['orgid'] = $teacher_data['org'];
	$course['teacher'][$teacher_id]['photo'] = ($teacher_data['avatar']!='') ? conf('url').'user/'.$teacher_data['user'].'/'.$teacher_data['avatar'] : '';
	$course['teacher'][$teacher_id]['email'] = $teacher_data['email'];
	$course['teacher'][$teacher_id]['homepage'] = conf('url').'profile/'.$teacher_data['user'];
	$course['teacher'][$teacher_id]['gender'] = $teacher_data['gender'];
	$course['teacher'][$teacher_id]['status'] = ($teacher_data['user']==$_SESSION['loginid']['nickname']) ? 1 : 0;
}

//get student data.
$strSQL = mysql_query("SELECT uid FROM tc_course_register WHERE course_id=".$course['course_id']." AND status=1 AND role=1");

while($std = mysql_fetch_assoc($strSQL)){

	$student_data = UserById($std['uid']);
	$stdname = explode('|', $student_data['fullname']);
	$course['student'][$std['uid']]['id'] = $student_data['ssid'];
	$course['student'][$std['uid']]['firstname'] = $stdname[0];
	$course['student'][$std['uid']]['lastname'] = $stdname[1];
	$course['student'][$std['uid']]['photo'] = ($student_data['avatar']!=''&&is_file(conf('dir').'user/'.$student_data['user'])) ? conf('url').'user/'.$student_data['user'].'/'.$student_data['avatar'] : '';
	$course['student'][$std['uid']]['gender'] = $student_data['gender'];
	$course['student'][$std['uid']]['grade'] = $class['grade'];
	$course['student'][$std['uid']]['class'] = $class['cls_number'];
	$course['student'][$std['uid']]['citizid'] = ($student_data['citizen_id']=='') ? $student_data['user'] : $student_data['citizen_id'];
	$course['student'][$std['uid']]['email'] = $student_data['email'];
}

header('Content-Type: application/json');
echo json_encode($course);
