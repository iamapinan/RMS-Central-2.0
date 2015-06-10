<?php
class social
{
	function __construct()
	{

	}

	function friendList($s='html',$limit = null)
	{
		$myid=UserInfo();
		if($limit==null) $lm = 'ORDER BY rand() LIMIT 0, 18';
		else $lm = '';
		$check_sql = 'SELECT * FROM '.conf('table_prefix').'_friend WHERE (uid='.$myid['ssid'].' OR fid='.$myid['ssid'].') AND status=1 '.$lm;
		$check_exec = mysql_query($check_sql);

		$i=0;
		$x=0;
		$ret = '<div class="fls">';
		$ret .= '<span class="title"><span class="sprites ico_ui_customers "></span> เพื่อน</span><ul>';
		$f =array();
			while(@$check_data = mysql_fetch_array($check_exec))
			{
				if($check_data['status']==1){
					//Create check condition
					if($check_data['uid']==$myid['ssid'])
						$chid = $check_data['fid'];
					else if($check_data['fid']==$myid['ssid'])
						$chid = $check_data['uid'];

					//echo $check_data['uid'].'='.$check_data['fid'].' : '.$myid['ssid'].'<br>';
					$req = 'SELECT ssid,user,avatar,fullname FROM '.conf('table_prefix').'_profile WHERE ssid='.$chid;
					$q = mysql_query($req);
					$retVal = mysql_fetch_array($q);
					if($retVal['user']=='') continue;
					//echo $req;
					if($s=='array')
					{
						$f[$x]['uid'] = $retVal['ssid'];
						$f[$x]['username'] = $retVal['user'];
						$f[$x]['avatar'] = $retVal['avatar'];
						$f[$x]['fullname'] = str_replace("|","&nbsp; ",$retVal['fullname']);
						$x++;
					}
					if($retVal['ssid']==$myid['ssid']) continue;
					if($retVal['avatar']=='')
						$avatar = 'library/avatar/uAvatar-l.jpg';
					else
						$avatar = 'user/'.$retVal['user'].'/'.$retVal['avatar'];
					$usts = (useronline('check', $retVal['ssid'])!='offline') ? '#33cc00' : '#cccccc';
					$u = urlencode($retVal['user']);
					$i++;
					$ret .= '<li><a href="'.conf('url').'@'.$u.'"  rel="tooltip" original-title="'.str_replace("|","&nbsp; ",$retVal['fullname']).'" style="float:left;"><img src="'.conf('url').'image?width=30&height=30&cropratio=1:1&image=/'.$avatar.'" alt="'.str_replace("|","&nbsp; ",$retVal['fullname']).'" style="border-bottom: 2px solid '.$usts.';"></a></li>';
				}
			}

		if($s=='array')
			return json_encode($f);
		else
		{
			if($i==0)
				$ret .= "<span style='margin-left: 10px;font-size: 11px;'>คุณยังไม่ได้เพิ่มใครเป็นเพื่อน<br> <a href='".conf('url')."searching?q=' rel='tooltip' original-title='ค้นหาและเพิ่มเพื่อนใหม่' style='margin-left: 10px;font-size: 11px;'><span class='icons2'>></span> เพิ่มเพื่อนใหม่</a></span>";
			$ret .= '</ul></div>';
			return $ret;
		}
	}

	function myfriend($user)
	{
		$myid=UserInfo($user);
		$check_sql = 'SELECT * FROM '.conf('table_prefix').'_friend WHERE uid='.$myid['ssid'].' AND status=1 ORDER BY rand() LIMIT 0,24';
		$check_exec = mysql_query($check_sql);
		$i=0;
		$ret = '';

			while($check_data = mysql_fetch_array($check_exec))
			{
				if($check_data['status']==1){
					//Create check condition
					if($check_data['uid']==$myid['ssid'])
						$chid = $check_data['fid'];
					else if($check_data['fid']==$myid['ssid'])
						$chid = $check_data['uid'];

					//echo $check_data['uid'].'='.$check_data['fid'].' : '.$myid['ssid'].'<br>';
					$req = 'SELECT ssid,user,avatar,fullname FROM '.conf('table_prefix').'_profile WHERE ssid='.$chid;
					$q = mysql_query($req);
					$retVal = mysql_fetch_array($q);
					if($retVal['user']=='') continue;
					//echo $req;
					if($retVal['ssid']==$myid['ssid']) continue;
					if($retVal['avatar']=='')
						$avatar = 'library/avatar/uAvatar-l.jpg';
					else
						$avatar = 'user/'.$retVal['user'].'/'.$retVal['avatar'];

					$usts = (useronline('check', $retVal['ssid'])!='offline') ? '#33cc00' : '#cccccc';
					$u = urlencode($retVal['user']);
					$i++;
					$ret .= '<li><a href="'.conf('url').'@'.$u.'"><img src="'.conf('url').'image?width=16&height=16&cropratio=1:1&image=/'.$avatar.'" style="border-bottom: 2px solid '.$usts.';"> <div style="vertical-align: top;display:inline;line-height: 15px;margin-bottom: 3px;">'.str_replace("|","&nbsp; ",$retVal['fullname']).'</div></a></li>';
				}
			}

		if($i==0)
			$ret .= '<li class="erasein">ยังไม่ได้ติดตามใคร</li>';
		return $ret;
	}

	function uAvatar($get,$m=null)
	{
		$req = UserInfo($get);
		$i = UserInfo();
		if($m!='home'){
			$size = '_s';
			$css = 'id="avatarImage"';
		}else{
			$size = '_l';
			$css = 'class="pt_larg"';
		}

			if($req['avatar']=='')
				$req['avatar'] = 'holder.js/220x220/random';
			else
				$req['avatar'] = conf('url').'image?width=220&height=220&cropratio=1:1&image=/user/'.$req['user'].'/'.$req['avatar'];

		$ret = '<div '.$css.'><img src="'.$req['avatar'].'" alt="'.str_replace('|',' &nbsp;',@$req['fullname']).'">';
		if(@$m!='home'){
		$ret = '<div '.$css.'><a href="/edit_profile"><img src="'.conf('url').'image?width=60&height=60&cropratio=1:1&image=/'.$req['avatar'].'" alt="'.str_replace('|',' &nbsp;',@$req['fullname']).'"></a>';
		$ret .= '<span class="DisplayName">'.str_replace('|',' &nbsp;',@$req['fullname']).'<br>';
		if(@$req['user']==$i['user'])
			{
				$ret .= '<a href="/edit_profile">ข้อมูลส่วนตัว</a>';
			}
		$ret .= '</span>';
		}

		if($get==$i['user']&&$m=='home')
			$ret .= '<a href="'.conf('url').'edit_profile/action/edit-picture" class="Chimg"><i class="fa fa-camera"></i>  เปลี่ยนรูป</a>';
		$ret .= '</div>';

		return $ret;
	}
}
	function friendCheck($friendID)
	{
		$myid=UserInfo();
		if($friendID==$myid['ssid']){
			return 'me';
			exit();
		}

		$check_sql = 'SELECT status FROM '.conf('table_prefix').'_friend WHERE (uid='.$myid['ssid'].' OR uid='.$friendID.') AND (fid='.$friendID.' OR fid='.$myid['ssid'].')';

		$check_exec = mysql_query($check_sql);
		@$check_data = mysql_fetch_array($check_exec);
		if($check_data['status']==1&&$check_data['status']!='')
		{
			return 'true';
		}
		else if($check_data['status']==0&&$check_data['status']!='')
		{
			return 'waiting';
		}
		else
		{
			return 'false';
		}
	}


?>