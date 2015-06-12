<?php
$u = UserInfo();
if($_GET['ft']=='post')
{
	$data = json_decode(htmlspecialchars_decode($_GET['content']));
	$apps_info = get_apps_info($data->app_id,$data->app_secr);
	$token = access_token_generator($data->app_id,$data->app_secr);
	
	
	if($data->token==$token)
	{

		//Start post here.
		//$sql = mysql_query();
		echo '
		<div class="view">
		<h2 class="title" align="center" id="mytitle">คุณต้องการแบ่งปันผลการทดสอบครั้งนี้หรือไม่?</h2>';
		echo '<div  class="cblock">';
		echo '<p style="margin: 10px auto;">
		<span class="app_photo" style="vertical-align: top;">
		<img src="/image?width=45&height=45&croptratio=1:1&image=/user/'.$u['user'].'/'.$u['avatar'].'">
		</span>
		<span class="app_title">
		<textarea name="status-update-input" class="post_input" id="status_update" placeholder="อยากบอกให้รู้ว่า..." ></textarea>
		<input type="hidden" id="app_id" value="'.$apps_info['aid'].'">
		</span>
		</p>';

		if($data->atd=='exam_result')
		{
			$sql = mysql_query('select * from '.conf('table_prefix').'_group where gid='.$data->group);
			$gq = mysql_fetch_array($sql);
			$sql = mysql_query('select * from '.conf('table_prefix').'_level where level_id='.$data->level);
			$lq = mysql_fetch_array($sql);
			
			$sql = mysql_query('select * from '.conf('table_prefix').'_subject where req_id="'.$data->contentid.'"');
			$cinfo = mysql_fetch_array($sql);
			

			
			$caid='test_'.md5(session_id().time());
			echo '<div  id="app_'.$apps_info['aid'].'" style="margin-left: 50px;">
			<div style="display: block;width: 380px;height: 80px;padding: 5px;overflow: hidden;background: white;box-shadow: 2px 2px 0 #CCC;border: 1px solid #AAA;">';
			echo '<div style="display: inline-block;">
			<canvas id="'.$caid.'" width="100" height="80" style="background: #21ACDA;">
			Your browser does not support the HTML5 canvas tag.
			</canvas>
			<script>
				var c=document.getElementById("'.$caid.'");
				var ctx=c.getContext("2d");
			
				ctx.fillStyle="#fff";
				ctx.font="13px Arial";
				ctx.fillText("Your score",28,15);
				ctx.font="30px Arial";
				ctx.fillText("'.$data->score.'",3,45);
				ctx.font="50px Arial";
				ctx.fillText("/",32,62);
				ctx.font="50px Arial";
				ctx.fillText("'.$data->fullscore.'",40,70);
			</script>
			</div>';
			echo '<div class="score" style="width: 270px;display: inline-block;vertical-align: top;margin-left: 10px;padding-top: 6px;font-size: 13px;">
			ฉันทำข้อสอบได้ <b>'.$data->score.'</b> คะแนน จากคะแนนเต็ม <b>'.$data->fullscore.'</b> คะแนน เมื่อ '.$data->date.' 
			<br>จากเนื้อหา <a href="/c/'.$data->contentid.'">'.$cinfo['subject'].'</a>
			</div>';
			echo '</div></div>';
			
			echo '</div>';
			echo '<p style="text-align: center;" id="dibt">
			<input type="button" class="btPost" id="submit_activity" onclick="postme(\'app_'.$apps_info['aid'].'\');" value=" โพสต์ "> 
			&nbsp;หรือ<a href="#" onclick="window.close();">ยกเลิก</a></p>';
			echo '</div>';
		   }
	}
	else
	{
			header('HTTP/1.1 400 Bad Request');
			echo '<div class="view" style="text-align: center;">
			<h3>Access denied: you \'re not have authorized to access this section.  </h3>
			<p>&nbsp;</p>';
			echo '<p><a href="#" class="UpdateBT btPost" onclick="window.close();">ปิดหน้านี้</a></p>';
			echo '</div>';
			exit(0);
	}
	
}
else if($_GET['action']=='change_login')
{
			echo '<div class="view" style="text-align: center;">
			<h3>Continue to login with...</h3>
			<p>&nbsp;</p>';
			echo '<p style="margin: 10px auto;">
			<span class="app_photo" style="vertical-align: top;">
			<img src="/image?width=45&height=45&croptratio=1:1&image=/user/'.$u['user'].'/'.$u['avatar'].'">
			</span>
			<span class="app_title">
<span style="font-weight: bold;vertical-align: top;color: #000;font-size: 16px;">'.str_replace('|',' ', $u['fullname']).'</span>
<br>
<span style="font-weight: bold;vertical-align: top;color: #888;font-size: 13px;">If this is not your account please click login with another account.</span>
			</span>
			</p>';
	echo '<p><a href="'.$_SESSION['redirect_to'].'?auth='.$_SESSION['loginid']['nickname'].'" class="UpdateBT btPost"> Continue </a> <br> <a href="/go/logout?s='.$_GET['ch'].'"> Login with another account </a></p>';
			echo '</div>';
}
else
{
			header('HTTP/1.1 400 Bad Request');
			echo '<div class="view" style="text-align: center;">
			<h3>Access denied: you \'re not have authorized to access this section.  </h3>
			<p>&nbsp;</p>';
			echo '<p><a href="#" class="button btPost" onclick="window.close();">ปิดหน้านี้</a></p>';
			echo '</div>';
			continue;
}

?>