<?php
include 'initial.min.php';
$stime = time();
$userid = UserInfo();
$uid = $userid['ssid'];

if($_GET['qr']=='post')
{

	$get = mysql_query("select `req_id`,`desc`,`image`,`group`,`subject`,`username`,`filetype`,`open` from ".conf('table_prefix')."_subject where `bid`=".$_REQUEST['pid']);

	$data = mysql_fetch_array($get, MYSQL_ASSOC);
	echo json_encode($data);
}

if(isset($_GET['get_items'])&&$_GET['get_items']!=''){

//===================================
//Save Comment Item
if(isset($_GET['comment_save'])&&$_GET['comment_save']!=''){
$encode_msg = mysql_real_escape_string(nl2br(htmlspecialchars($_GET['comment_save'])));

$cmd = mysql_query("INSERT INTO ".conf('table_prefix')."_comment (cid,uid,bid,timestamp,msg)
VALUES(NULL,".$uid.",".$_GET['get_items'].",'".$stime."','".$encode_msg."')");

//Getting comment
$cmid=mysql_insert_id();
$getcm = mysql_query("select * from ".conf('table_prefix')."_comment where cid=".$cmid);
$ms = mysql_fetch_array($getcm);
$cmt = UserById($ms['uid'],'fullname,user,avatar'); //User info.

//Avatar check
		if($cmt['avatar']=='')
			$cmt['avatar'] = '/library/avatar/uAvatar-ss.jpg';
		else
			$cmt['avatar'] = '/user/'.$cmt['user'].'/'.$cmt['avatar'];

	$result['comments'] .= '
	<li id="cm'.$ms['cid'].'">';
	if($ms['uid']==$userid['ssid'])
				$result['comments'] .= '<a href="javascript:void(0);" class="icons2 cmdel" onclick="cmdel('.$ms['cid'].')" >]</a>';

	$result['comments'] .= '<img src="/image?width=30&height=30&cropratio=1:1&image='.$cmt['avatar'].'" class="ust">
	<div class="cmmsg">
		<a href="/u/'.$cmt['user'].'"><b>'.str_replace('|',' ',$cmt['fullname']).'</b> <small class="cmt">'.relativeTime($ms['timestamp']).'</small></a>
		<div id="message_inf">'.$ms['msg'].'</div>
	</div>
	</li>';
	echo json_encode($result);
	exit();
}//End save session.
//=======================================

if(isset($_GET['load']))
{
	$load = $_GET['load'];
}
else
{
	$load = '0,10';
}

//Get Comment
	$u['avatar'] = conf('user').$u['user'].'/'.$u['avatar'];
	$getcm = mysql_query("select * from ".conf('table_prefix')."_comment where bid=".$_GET['get_items']." order by cid desc limit ".$load);
	$getcn = mysql_query("select * from ".conf('table_prefix')."_comment where bid=".$_GET['get_items']);
	$getn = mysql_num_rows($getcn);
	$x = explode(',',$load);
	$result['c'] = ($getn<=($x[0]+20)) ? 0 : 1;
	while($ms = mysql_fetch_array($getcm))
	{
		$cmt = UserById($ms['uid']);
		//Avatar check

		if($cmt['avatar']=='')
			$cmt['avatar'] = '/library/avatar/uAvatar-ss.jpg';
		else
			$cmt['avatar'] = '/user/'.$cmt['user'].'/'.$cmt['avatar'];

		$result['comments'] .= '
		<li id="cm'.$ms['cid'].'">';
		if($ms['uid']==$userid['ssid'])
				$result['comments'] .= '<a href="javascript:void(0);" class="icons2 cmdel" onclick="cmdel('.$ms['cid'].')" >]</a>';

		$result['comments'] .= '<img src="/image?width=30&height=30&cropratio=1:1&image='.$cmt['avatar'].'" class="ust">
		<div class="cmmsg">
			<a href="/u/'.$cmt['user'].'"><b>'.str_replace('|',' ',$cmt['fullname']).'</b> <small class="cmt">'.relativeTime($ms['timestamp']).'</small></a>
			<div id="message_inf">'.$ms['msg'].'</div>
		</div>
		</li>';
	}

	echo json_encode($result);

	exit();
}

if(isset($_GET['rmcm'])&&$_GET['rmcm']!=''&&isset($_SESSION['loginid'])){
	$sql = mysql_query('DELETE FROM '.conf('table_prefix').'_comment WHERE cid = '.$_GET['rmcm']) || die(mysql_error());
	if($sql)
	{
		$result['s'] = 1;
	}
	else
	{
		$result['s'] = 0;
	}

	echo json_encode($result);
}
?>