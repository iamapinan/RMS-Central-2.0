<?php

class UStream extends common
{
	var $post_action = '';
	var $ret = '';
	public function __construct()
	{
		$this->post_action = '
		<div id="chd">
			<a href="#" class="select update-status" onclick="ToggleMenu(\'update-status\',\'status-update-frm\');"><span>
			<span class="sp-balloon"></span>Post message</span></a>
			<!-- <a href="/new_blog" class="createContent"><span><span class="sp-blog"></span>เขียนบทความ</span></a>
			<a href="/upload_content" class="uploadcontent"><span><span class="sp-upload"></span>อัพโหลดเนื้อหา</span></a> -->
		</div>

		<div class="status-update-frm showme">
		<div style="position: relative;">
		<textarea class="status-update-input" id="status-update-input" name="content"  placeholder="What do you want to talk?" style="resize: none; overflow-y: hidden; " disabled></textarea>
		</div>
		</div>

		<div class="search_form">
		<input type="text" class="input-class-quick-search" placeholder="ค้นหาเนื้อหาที่คุณต้องการ" name="ClassSearch" id="ClassSearch" onkeydown="clicksearch()" x-webkit-speech>
		</div>

		<script type="text/javascript">
		<!--
			$(document).ready(function(){
				setInterval("loadCheck()", 600000);
			});
		//-->
		</script>
		';
	}

	public function check_support_type($filetype)
	{
		$av_type = explode(",",GetConf('available_type'));
		if(in_array($filetype,$av_type))
			return true;
		else
			return false;
	}

	public function getStream($m,$q=null,$rang=null)
	{
		$this->ret = '';
		$i = UserInfo();
		$my = new mysql;

		if($m==0)
		{
			if($rang=='') $rang='0,10';
			$cond = $q.' and ';
		//Get lastest post from database
			$qr = 'SELECT s.* FROM `'.conf('table_prefix').'_subject` s
					  WHERE approve="yes"
					ORDER BY bid DESC LIMIT '.$rang;
			$res = $my->query($qr);

			$this->ret .= $this->post_action;
			$this->ret .= '<div class="middle-wrapper">';
			$this->ret .= '<ul class="slist" id="slist">';
		}

		if($m==1) //Search Mode
		{
			//Search with keyword
			$rang='0,50';
			$qr = 'SELECT * FROM `'.conf('table_prefix').'_subject` s
			WHERE '.$q.' approve="yes"
			ORDER BY bid DESC LIMIT '.$rang;
			$res = $my->query($qr);

		}

		if($m==2)
		{
			//Mode User profile page.
			if($rang=='') $rang='0,10';
			$cond = $q;

			$exp = explode('=',$q);
			$exp[1] = str_replace('"','',$exp[1]);
			$getinfo = UserInfo($exp[1]);
			$query = 'select * from '.conf('table_prefix').'_subject where ('.$cond.' OR post_to='.$getinfo['ssid'].') AND approve="yes" ORDER BY  `'.conf('table_prefix').'_subject`.`bid` DESC limit 0,10';

			$res = $my->query($query);
			if($i['user']==$exp[1]){
				$this->ret .= $this->post_action;
			}else
			{
				if(isset($_SESSION['loginid'])){
					$uc = UserInfo($exp[1]);
					if(friendCheck($uc['ssid'])=='true'){
						$this->ret .= '
						<div class="status-update-frm showme">
						<textarea class="status-update-input" id="status-update-input" name="content" placeholder="เขียนข้อความของคุณ" style="resize: none; overflow-y: hidden; " ></textarea>
						<input type="hidden" name="post_on" id="post_as" value="'.$uc['ssid'].'">
						<span class="UpdateBT  btPost">'.trs('lg_10166').'</span>
						</div>';
					}
				}
			}
			$this->ret .= '<div class="middle-wrapper">';
			$this->ret .= '<ul class="slist-profile" id="slist-profile">';
	}

	if($m==3)
	{
		//For show more post
		if($rang=='') $rang='0,10';
		if($q=='')
		{
			$qr = 'SELECT * FROM '.conf('table_prefix').'_subject s WHERE s.approve="yes" ORDER BY  s.bid DESC LIMIT '.$rang;
		}
		else
		{
			$qr = 'SELECT s.* FROM `'.conf('table_prefix').'_subject` s
			WHERE (s.username="'.$q.'" OR s.post_to="'.$q.'") AND approve="yes"
			ORDER BY bid DESC LIMIT '.$rang;
		}
		$res = $my->query($qr);
	}

//End Mode
	$c = count($res);

	for($x=0;$x<$c;$x++)
	{
		$aut = @$my->query('select * from '.conf('table_prefix').'_profile where user="'.$res[$x]['username'].'" ');
		$g = $my->query('select name from '.conf('table_prefix').'_group where gid='.$res[$x]['group'].' ');

	//Convert view count to integer.
		$res[$x]['viewed'] = (int)$res[$x]['viewed'];
	//Reset default values
		$title = '';
		$type = '';
		$PostTypeIcon = '';
		$pdt = '';
		$statistice = '';
		$thumbnail = '';

		//Check public contents or private.
		if(friendCheck($aut['ssid'])==false&&$res[$x]['status']=='private') continue;
		//Weather filetype are support or not.
		if($this->check_support_type($res[$x]['filetype'])==true)
		{
			//Make defualt title message
			$title = '<span class="stat" style="margin-bottom: 15px;margin-top: 10px;font-size: 14px;">'.$res[$x]['subject'].'</span>';
			//Switch post type
			switch($res[$x]['filetype'])
			{
				case 'status':
					$type='Status';
					$title = '';
					$PostTypeIcon = '<span class="sp-balloon"></span>';
					$pdt = '<span class="stat-g v'.$res[$x]['bid'].'" style="font-size: 14px;">'.iconv_substr($res[$x]['desc'],0,500,'utf-8').'</span>';
				break;
				case 'via':
					$via = UserById($res[$x]['post_to']);
					$type='Via';
					$title = '';
					$PostTypeIcon = '<span class="sp-balloon"></span> <b><a href="/u/'.$via['user'].'">'.str_replace("|"," &nbsp;",$via['fullname']).'</a></b></span>';
					$pdt = '<span class="stat-g v'.$res[$x]['bid'].'" style="font-size: 14px;">'.iconv_substr($res[$x]['desc'],0,500,'utf-8').'</span>';
				break;
				case 'blog':
					$type='Article';
					$PostTypeIcon = '<span class="sp-blog"></span>';
					$res[$x]['image'] = (is_file($_SERVER['DOCUMENT_ROOT'].'/data/content/'.$res[$x]['image'])) ? $res[$x]['image'] : '';
					$thumbnail = ($res[$x]['image']!='') ? '<a href="/c/'.$res[$x]['req_id'].'" style="width: 410px;max-height: 300px;display: block;overflow: hidden;background: #333;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;margin-left: -10px;text-align: center;">
					<img src="/image?width=410&height=400&image=/data/content/'.$res[$x]['image'].'&cropratio=2:1" class="postthumb" >
					</a>' : '<a href="/c/'.$res[$x]['req_id'].'" class="button btGray">คลิกเพื่ออ่านบทความนี้</a>';
					$pdt = '<span class="stat">'.iconv_substr(strip_tags($res[$x]['desc']),0,300,'utf-8').'</span>';
					$statistice = '<span class="view_'.$res[$x]['bid'].' linkoption"> <span class="sp-chart-up"></span> '.$res[$x]['viewed'].' คนดู</span>';
				break;
				case 'pdf':
					$type = 'Document';
					$PostTypeIcon = '<span class="sp-pdf"></span>';
					$thumbnail = ($res[$x]['image']!='') ? '<a href="/c/'.$res[$x]['req_id'].'" style="width: 410px;max-height: 300px;display: block;overflow: hidden;background: #333;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;margin-left: -10px;text-align: center;">
					<img src="'.$res[$x]['cdn'].'/img/'.conf('cdn_user').'_'.$res[$x]['username'].'-'.$res[$x]['req_id'].'_'.$res[$x]['image'].'?width=410&height=360&cropratio=2:1" class="postthumb"  >
					</a>' : '';
					$pdt = '<span class="stat-g v'.$res[$x]['bid'].'" style="float:left;width: 100%;word-wrap:break-word;margin: 5px 0;">'.iconv_substr($res[$x]['desc'],0,500,'utf-8').'</span>';
					$statistice = '<span class="view_'.$res[$x]['bid'].' linkoption"> <span class="sp-chart-up"></span> '.$res[$x]['viewed'].' คนดู</span>';
				break;
				case 'apps_post':
					$sql = mysql_query('select * from '.conf('table_prefix').'_apps where aid='.$res[$x]['app_id']);
					$apps = mysql_fetch_array($sql);
					$type = $apps['app_name'];
					$PostTypeIcon = '<span class="sp-ico"><img src="/image?width=16&height=16&cropratio=1:1&image=/apps/'.$apps['application_id'].'/'.$apps['photo'].'"></span>';
					$pdt = '<span class="stat-g v'.$res[$x]['bid'].'">'.htmlspecialchars_decode($res[$x]['desc']).'</span>';
				break;
				case 'video':
					$duration = ($res[$x]['duration']!='') ? date('H:i:s',$res[$x]['duration']) : date('H:i:s',videoduration($res[$x]['file']));
					$type = 'Video';
					$PostTypeIcon = '<span class="sp-video"></span>';
					$thumbnail = ($res[$x]['image']!='') ? '<a href="/c/'.$res[$x]['req_id'].'"  class="video-play"><div class="play-indicator"><img src="/library/icon/playicon_b.png"></div><img src="'.$res[$x]['cdn'].'/img/'.conf('cdn_user').'_'.$res[$x]['username'].'-'.$res[$x]['req_id'].'_'.$res[$x]['image'].'?width=410&height=300&cropratio=2:1" class="postthumb"  ><span class="video-duration">'.$duration.'</span></a>' : '';
					$pdt = '<span class="stat-g v'.$res[$x]['bid'].'" style="float:left;width: 100%;word-wrap:break-word;margin: 5px 0;">'.iconv_substr($res[$x]['desc'],0,500,'utf-8').'</span>';
					$statistice = '<span class="view_'.$res[$x]['bid'].' linkoption"> <span class="sp-chart-up"></span> '.$res[$x]['viewed'].' View</span>';
				break;
				case 'flash/zip':
					$type = 'Flash';
					$PostTypeIcon = '<span class="sp-flash"></span>';
					$thumbnail = ($res[$x]['image']!='') ? '<a href="/c/'.$res[$x]['req_id'].'" style="width: 410px;max-height: 300px;display: block;overflow: hidden;background: #333;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;margin-left: -10px;text-align: center;">
					<img src="'.$res[$x]['cdn'].'/img/'.conf('cdn_user').'_'.$res[$x]['username'].'-'.$res[$x]['req_id'].'_'.$res[$x]['image'].'?width=410&height=360&cropratio=2:1" class="postthumb"  >
					</a>' : '';
					$pdt = '<span class="stat-g v'.$res[$x]['bid'].'" style="float:left;width: 100%;word-wrap:break-word;margin: 5px 0;">'.iconv_substr($res[$x]['desc'],0,500,'utf-8').'</span>';
					$statistice = '<span class="view_'.$res[$x]['bid'].' linkoption"> <span class="sp-chart-up"></span> '.$res[$x]['viewed'].' View</span>';
				break;
			}
		}
		else
			continue; //Skip if not support

		//Check user avatar.
		$avatar = ($aut[0]['avatar']=='') ? '/library/avatar/uAvatar-s.jpg' : '/user/'.$aut[0]['user'].'/'.$aut[0]['avatar'];
		//End preparing data.

		//Stream ouput start here.
		//Stream own
			$this->ret .= '
			<li  class="post-'.$res[$x]['bid'].' content_line"  position-id="'.$res[$x]['bid'].'">
				<div class="thumb-wrap">
				<a href="/u/'.@$aut[0]['user'].'" class="uthumb" style="background: url(/image?width=50&height=50&cropratio=1:1&image='.$avatar.');box-shadow: 1px 1px 0 0 #eee;"></a>
				</div>';
		//Stream details
				$this->ret .= '<div class="desc-wrap"><span class="stb-ar-l"></span>';
		//Stream header
				$this->ret .= '<span class="stat">
				<b><a href="/u/'.@$aut[0]['user'].'">'.str_replace("|"," &nbsp;",@$aut[0]['fullname']).'</a></b>
				'.$PostTypeIcon.'</span>';
		//Owner post message
				$this->ret .= $title;
		//Thumbnail if exists.
				$this->ret .= $thumbnail;
		//Post description here.
				$this->ret .= $pdt;

		//End Details
				$comment_cmd = mysql_query("select * from ".conf('table_prefix')."_comment where bid=".$res[$x]['bid']);
				$cmc =mysql_num_rows($comment_cmd);

				$this->ret .= '
				<div id="post-fnc-'.$res[$x]['bid'].'">
				<div class="menu-fnc">
				<span class="linkoption">
					<a href="#" rel="tooltip" original-title="Like this post" onclick="liked('.$res[$x]['bid'].')">
					<span class="sp-thumb-up"></span>
					<span class="liked_'.$res[$x]['bid'].'"> '.$res[$x]['like'].'</span> Like  ·
					</a>
				</span>
				<span class="linkoption commentcount">
					<a href="javascript:void(0);" class="doComment" ><span class="sp-comments"></span> '.$cmc.' Comments</a>
				</span>';
				//Show viewed count
				$this->ret .= $statistice;
				$this->ret .= '</div>';
				//Delete button
				if($res[$x]['username']==$i['user']||($i['role']=='05'&&$res[$x]['username']!='system'))
				$this->ret .= '<a href="javascript:void(0);" class="post-del p'.$res[$x]['bid'].' icons2"  rel="tooltip" original-title="Delete" onClick="delete_post('.$res[$x]['bid'].')">]</a>';

				$this->ret .= '</div>
				 <span class="trans-control"></span>
				 <span class="stat-ft">'.relativeTime($res[$x]['timestamp']).'</span>';
				$this->ret .= '</div>'; //Close desc
				$this->ret .= '</li>';
		//End stream

		//Comments generate here...!
			$this->ret .= '<div class="comment_control c'.$res[$x]['bid'].'">';
			//Create comment input
			$this->ret .= '<form onsubmit="cmts('.$res[$x]['bid'].'); return false;" action="" method="post">
			<input type="text" id="comment-input" placeholder="Write your comment" x-webkit-speech autocomplete="off">
			</form>';
			//Get comment list
			$cmqr = mysql_query("SELECT * FROM ".conf('table_prefix')."_comment WHERE bid = ".$res[$x]['bid']." ORDER BY cid DESC LIMIT 0,3");
			$cmn = mysql_query("SELECT * FROM ".conf('table_prefix')."_comment WHERE bid = ".$res[$x]['bid']);

			//Start comments
			$this->ret .= '<ul>';
			while($cmexec = @mysql_fetch_array($cmqr))
			{
				$ut = UserById($cmexec['uid'],'fullname,user,avatar');
				$this->ret .= '<li id="cm'.$cmexec['cid'].'">';
				if($cmexec['uid']==$i['ssid']||$res[$x]['username']==$i['user']||$i['role']=='05')
				$this->ret .= '<a href="javascript:void(0);" class="icons2 cmdel" onclick="cmdel('.$cmexec['cid'].')" >]</a>';
				$this->ret .= '<img src="/image?width=30&height=30&cropratio=1:1&image=/user/'.$ut['user'].'/'.$ut['avatar'].'" class="ust" onerror="this.src=\'/image?width=30&height=30&cropratio=1:1&image=/library/avatar/uAvatar-s.jpg\'"> <div class="cmmsg"> <a href="/u/'.$ut['user'].'"><b>'.str_replace('|',' ',$ut['fullname']).'</b> <small class="cmt">'.relativeTime($cmexec['timestamp']).'</small></a>
				<div id="message_inf">'.$cmexec['msg'].'</div>
				</div></li>';
			}
			$this->ret .='</ul>';
			$pcmc = mysql_num_rows($cmn);
			if($pcmc >=4)
			{
				$this->ret .= '<div class="cmm profile_btmore"  onClick="more_cm('.$res[$x]['bid'].')">
				 Show more comments
				</div>';
			}
			$this->ret .= '</div>';
			//End Generate comment..


	}//End for loops.
	$this->ret .= '</ul>';

		if(($m==1||$m==0)&&$c>=10){

			$this->ret .= '<div class="btmore" style="margin-left: 30px;"> Show more contents </div>';
		}

		if($m==2&&$c>10){
			$this->ret .= '<div class="profile_btmore" onClick="MoreProfilePage(\''.$_REQUEST['indentity'].'\');" style="margin-left: 30px;"> Show more contents </div>';
		}
		else if($c>10)
		{
			$this->ret .= '<div class="btmore" style="margin-left: 30px;"> Show more contents </div>';
		}

		$this->ret .= '</div>';

	return $this->ret;
	}

}
?>
