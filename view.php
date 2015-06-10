<?php
include 'initial.min.php';
$info = UserInfo($_SESSION['loginid']['nickname']);

if(isset($_GET['view'])&&$_GET['view']!=''){
$data = mysql_query('select * from '.conf('table_prefix').'_subject where req_id = "'.$_GET['view'].'"');
$fetch =mysql_fetch_array($data);
$fetch['like']=(int)$fetch['like'];
$gif = GroupInfo($fetch['group']);
$lif = Level($fetch['level']);
if($fetch['req_id']=='') header('location: /404');

//Page start here.
$cb = UserInfo($fetch['username']);
//Assign Page Title
$p->PageTitle = strip_tags($fetch['subject']).' - '.conf('pagename');

			$image = conf('url').'data/content/'.$fetch['req_id'].'/'.$fetch['image'];
			$type = 'website';
			$source = '';


	$p->ShareHeader = '
	<meta name="keywords" content="'.$fetch['keyword'].'">
	<meta name="description" content="'.strip_tags($fetch['subject']).'">
	<meta name="robots" content="index, follow" />
	<meta name="title" content="'.strip_tags($fetch['subject']).'" />

	<meta property="fb:app_id" content="'.conf('fb_appid').'" />
	<meta property="og:title" content="'.strip_tags($fetch['subject']).'" />
	<meta property="og:description" content="'.strip_tags($fetch['desc']).'" />
	<meta property="og:site_name" content="RMS Enterprise" />
	<meta property="og:type" content="'.$type.'" />
	'.$ogm.$source.'
	<meta property="og:url" content="'.conf('url').'blog/'.$fetch['req_id'].'" />
	<meta property="og:image" content="'.$image.'" />

	<link rel="canonical" href="'.conf('url').'blog/'.$fetch['req_id'].'" />
	<link rel="image_src" href="'.$image.'" />';

$p->ShareHeader .= '
<style type="text/css">
#ContentBody{padding: 5px 5px;width: 980px !important;}
#embedded_doc {
		margin-left:-8px;
        width: 725px;
		height: auto;
        padding: 5px;
		float:left;
		display: block;
		text-align: center;
		border: none;
}
.contents-area {
float: left;
width: 970px;
height: auto;
padding: 10px 5px;
}
#comment-input{padding: 0;}
#content-view{
	margin: 0px;
	width: 100%;
	height: 500px;
	padding: 5px;
	float: left;
	overflow: auto;
	word-wrap: break-word;
	margin-bottom: 15px;
}
.comment_control {
width: 100%;
font-size: 12px;
font-family: "lucida grande",tahoma,verdana,arial,sans-serif;
float: left;
margin: 10px 0px 20px 0px;
background: white;
position: relative;
z-index: 1;
border-radius: 3px;
}
#comment-input {
width: 94%;
font-size: 14px;
float: left;
margin: 2px;
height: 33px;
border: none;
color: #1E1E1E;
padding: 0 5px;
}
.comment_iu {
width: 35px;
height: 35px;
display: inline-block;
margin: 2px;
float: left;
}
.comment_login_text {
text-align: left;
padding: 8px 5px;
width: 248px;
display: block;
}
.comment_control ul {
position: relative;
float: right;
width: 100%;
}
.comment_control > ul li {
width: 99% !important;
min-height: 30px !important;
padding: 5px !important;
position: relative;
list-style: none;
background: #fff;
}
.cmmsg {
width: 94%;
}
p.cdt{
width: 100%;
line-height: 17px;
font-size: 12px;
}

.comment_control .cmm {
border: none;
background-color: #F8F8F8 !important;
margin-bottom: 0px;
height: 17px !important;
width: 715px;
margin-right: 0px;
margin-left: 0px;
box-shadow: inset 0 3px 2px rgba(0, 0, 0, 0.05);
}
#content-view img
	{
		max-width: 500px;
	}
.video-player{
background: #444444;
width: 100%;
height: 402px;
}
.dtr{
padding: 5px;
background: #fff5e1;
}
.toolbar{
width: 100%;
text-align: center;
padding: 5px 0;
}
.toolbar a{
margin: 0 1px;
text-decoration: none;
}
#sharedlg {
	margin-left: 110px;
}
</style>
';

//Show header
echo $p->globalheader();
echo '<div id="container">'."\n";
eval('?>' . $p->getC('header'). '<?php '); //Banner
echo $p->toolbar()."\n"; //toolbar

echo '<div id="ContentBody" class="mainbody">';

		if($cb['avatar']=='')
			$avatar = '/library/avatar/uAvatar-s.jpg';
		else
			$avatar = '/user/'.$fetch['username'].'/'.$cb['avatar'];

		echo '<h2 class="title">
		<img src="'.conf('url').'image?width=45&height=45&cropratio=1:1&image='.$avatar.'">
		<div style="display: inline-block;width: 900px;vertical-align: top;margin-left: 5px;">
		<span  style="font-size: 11px;color: #ababa5;font-weight: normal;">
		'.str_replace('|',' ',$cb['fullname']).'
		</span>
		<p>
		'.iconv_substr(strip_tags($fetch['subject']),0,100,'utf-8').'
		<span style="float:right;font-size: 11px !important;font-weight: normal;border-radius: 6px;padding: 2px 4px;">
		<span style="color: #c0c0c0;">'.relativeTime($fetch['timestamp']).'</span>
		</span>
		</p>
		</div>
		</h2>';
	if($info['role']==8||$info['admin']==1)
	{
		$AdminBt = '<a class="button btGray" href="/actions?del='.$fetch['req_id'].'" onclick="if(confirm(\'ยืนยันการลบบทความนี้!\')==true) window.location.href=$(this).attr(\'href\'); else return false;"> Delete </a>';
	}
	if($info['role']==8||$info['admin']==1||$info['user']==$fetch['username'])
	{
		$toolsbt = '<a class="button btGray" href="/new_blog?edit='.$fetch['req_id'].'&token='.$session_token.'" onclick="window.location.href=$(this).attr(\'href\');"> Edit </a>
		<a class="button btGray" href="javascript:void(0);" onclick="$(\'.urlcontainer\').html(\''.conf('url').'blog/'.$fetch['req_id'].'\');
		$(\'.embedcontainer\').html(\'<textarea cols=97 rows=4 onclick=\&quot;this.focus();this.select()\&quot; readonly=\&quot;readonly\&quot;>&lt;iframe width=\&quot;100%\&quot; height=\&quot;600\&quot; src=\&quot;'.conf('url').'blog/'.$fetch['req_id'].'\&quot; frameborder=\&quot;0\&quot;&gt;&lt;/iframe&gt;</textarea>\');$(\'#sharedlg\').slideDown(200);"> Share </a>
		';
	}


	echo '<div class="contents-area">';
	switch($fetch['filetype'])
	{
		case 'blog';
			echo '<div style="width: 980px;margin: 10px auto;text-align: right;">';
			if($fetch['group']==18||$fetch['group']==19||$fetch['group']==20||$fetch['group']==21){
			echo '<a href="#" onclick="history.back(-1);" class="button btGray">ย้อนกลับ</a>';
			}

			echo '<a href="#" class="button btGray" onclick="$(\'#blogcontents\').attr(\'src\', $(\'#blogcontents\').attr(\'src\'));$(this).hide();" id="backtoblog" style="display:none;">กลับไปที่บทความ</a>
			<a href="/profile/'.$fetch['username'].'?user=blog" class="button btGray">ดูบทความทั้งหมด</a>
			'.$toolsbt.'
			'.$AdminBt.'
			</div>
			<div id="content-view" style="height:auto;"><iframe id="blogcontents" src="/data/content/'.$fetch['req_id'].'/'.$fetch['req_id'].'.html?wmode=transparent" framespacing="no" frameborder="no" width="100%" height="520" scrolling="no" onload="autoResize(\'blogcontents\');"/></iframe></div>';
			break;
		default:
				echo '<div id="embedded_doc"><h3>ไม่พบเนื้อหาที่คุณกำลังเปิด</h3></div>';
				exit;
		break;
	}

		//====================Comments generate here...!=========================//
			$comments = '
			<div id="sharedlg">
			<h2 class="title"><img src="/library/icon/newtab.png"> Share url.</h2>
			<div class="urlcontainer"></div>
			<br>
			<h2 class="title"><img src="/library/icon/embed.png"> Embed code.</h2>
			<div class="embedcontainer" contenteditable="true"></div>
			<br>
			<hr>
			<br>
			<p align="right"><span class="button" onclick="$(\'#sharedlg\').slideUp(200);"><img src="/library/icon/times.png"> Close</span></p>
			</div>
			';
			$comments .= '<div class="comment_control c'.$fetch['bid'].'">';
			if(isset($_SESSION['loginid'])){
			if($info['avatar']=='')
				$cmavatar = '/library/avatar/uAvatar-s.jpg';
			else
				$cmavatar = '/user/'.$info['user'].'/'.$info['avatar'];
			$comments .= '
			<form onsubmit="cmts('.$fetch['bid'].'); return false;" action="" method="post">
			<span class="comment_iu"><img src="/image?width=35&height=35&cropratio=1:1&image='.$cmavatar.'"></span>
			<input type="text" id="comment-input" placeholder="แสดงความคิดเห็นของคุณ...">
			</form>';
			}else
			{
				$comments .= '<span class="comment_login_text"><a href="/login">ลงชื่อเข้าใช้</a> หรือ <a href="/register">สมัคร</a> เพื่อโพสต์ความคิดเห็น...</span>';
			}
			$cmqr = mysql_query("SELECT * FROM ".conf('table_prefix')."_comment WHERE bid = ".$fetch['bid']." ORDER BY cid DESC LIMIT 0,10");
			$cmn = mysql_query("SELECT cid FROM ".conf('table_prefix')."_comment WHERE bid = ".$fetch['bid']);

			$comments .= '<ul>';
			while($cmexec = @mysql_fetch_array($cmqr))
			{
				$ut = UserById($cmexec['uid'],'fullname,user,avatar');
				$comments .= '<li id="cm'.$cmexec['cid'].'">';
				if($cmexec['uid']==$info['ssid']||$fetch['username']==$info['user'])
				$comments .= '<a href="javascript:void(0);" class="icons2 cmdel" onclick="cmdel('.$cmexec['cid'].')" >x</a>';
				$comments .= '<a href="/u/'.$ut['user'].'"><img src="/image?width=30&height=30&cropratio=1:1&image=/user/'.$ut['user'].'/'.$ut['avatar'].'" class="ust" onerror="this.src=\'/image?width=30&height=30&cropratio=1:1&image=/library/avatar/uAvatar-s.jpg\'"></a> <div class="cmmsg">
				<p><a href="/u/'.$ut['user'].'"><b>'.str_replace('|',' ',$ut['fullname']).'</b></a> <span class="cmt">'.relativeTime($cmexec['timestamp']).'</span></p> '.$cmexec['msg'].'
				</div></li>';
			}
			$comments .='</ul>';
			$cmcr = @mysql_num_rows($cmn);
			if($cmcr >=10)
			{
				$comments .= '<div class="cmm profile_btmore"  onClick="more_cm('.$fetch['bid'].')">
				ความคิดเห็นเพิ่มเติม
				</div>';
			}
			$comments .= '</div>';
			//End Generate comment..
			echo '<div id="cmarea">
			'.$comments.'
			</div>';
			//=============================//
		echo '</div>';

	echo '<div class="clear"></div>';
	echo '</div>'."\n";
	echo '</div>'."\n";

	echo $p->bos('bottom-script.js');
	//display footer
	echo $p->footer();

//Page stop here.
	}
	else
	{
		header('location: /404');
	}


?>