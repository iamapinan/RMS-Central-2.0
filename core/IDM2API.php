<?php namespace core;
class IDM2API
{
	var $server;
	var $groupid;
	var $debug;
	var $debug_msg;
	var $debug_action;
	var $debug_url;
	var $debug_args;

	function __construct()
	{
		$this->server = conf('idm_server').'aculearn-idm/api/';
		$this->debug = 0;
		$this->debug_msg = '';
		$this->debug_action = '';
		$this->debug_url = '';
	}

	private function get_config($role)
	{

		$sql = mysql_query('SELECT * FROM '.conf('table_prefix').'_am_permission WHERE rid = '.$role);
		$exec = mysql_fetch_array($sql);
		return $exec;
	}

	private function set_request($uri)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $uri);
		$respone = curl_exec($ch);

		return $respone;
	}

	public function add_group($gid, $gn)
	{
		$is_permission = GetConf('am_group_permission');
		$uri = $this->server.'add_group.asp?group='.$gid.'&name='.urlencode($gn).'&isactive=1&'.$is_permission;
		$res = $this->set_request($uri);

		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'create group '.$gn;
			$this->debug_url = $uri;
		}

		if((int)$res==1) return true;
		else return false;
	}

	public function check_group($gid)
	{

		$uri = $this->server.'get_group_info.asp?groupid='.$gid;
		$res = $this->set_request($uri);
		$rescode = simplexml_load_string($res);
		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'get group info';
			$this->debug_url = $uri;
		}

		if((int)$rescode->RetCode==1) return true;
		else return false;
	}


	public function remove_session($sid)
	{

		$uri = $this->server.'delete_session.asp?sid='.$sid;
		$res = $this->set_request($uri);
		$return = json_decode($res);
		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'remove session';
			$this->debug_url = $uri;
		}

		if((int)$return->response==1) return 1;
		else return 0;
	}

	public function get_userid($gid=null, $email)
	{
			$u = UserInfo($email);
			$uri = $this->server.'list_user.asp?key='.$email.'&groupid='.$u['org'];
			$res = $this->set_request($uri);
			$userde = simplexml_load_string($res);
				if($this->debug==1)
				{
					$this->debug_msg = $res;
					$this->debug_action = 'get user info';
					$this->debug_url = $uri;
				}
			mysql_query('update '.conf('table_prefix').'_profile set amid="'.$userde->Records->Record->UserID.'" where user="'.$email.'"');
			if($userde->Records->Record->UserID!='')
				return $userde->Records->Record->UserID;
			else
				return false;
	}

	public function create_user($uid, $up, $user, $role, $gid, $server = null)
	{
		$permission = $this->get_config($role);

		$is_permission = 'isadmin='.$permission['isadmin'].'&maxparticipant='.$permission['maxparticipant'].'&maxspeaker='.$permission['maxspeaker'].'&maxspeed='.$permission['maxspeed'].'&videocontrol='.$permission['videocontrol'].'&startmode='.$permission['startmode'].'&avmode='.$permission['avmode'].'&iscallout='.$permission['iscallout'].'&iscallyou='.$permission['iscallyou'].'&iscallme='.$permission['iscallme'].'&isacustudio='.$permission['isacustudio'].'&isaculive='.$permission['isaculive'].'&isacuconference='.$permission['isacuconference'].'&isacupodcast='.$permission['isacupodcast'].'&ConfQuality='.$permission['confquality'].'&FPublishQuota='.$permission['FPublishQuota'].'&FQuotaType='.$permission['FQuotaType'];
		//$password = base64_encode(TripleDES::encryptText($up, conf('amkey'), conf('amvi')));
		$uri = $this->server.'add_user.asp?userid='.$uid.'&password='.$up.'&isactive=1&group='.$gid.'&nickname='.$user.'&'.$is_permission;

		$res = str_replace('-','', $this->set_request($uri));
		IDM2API::get_userid('', $uid);
		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'create new user.';
			$this->debug_url = $uri;
		}

		if((int)$res==1) return true;
		else return false;
	}

	public function add_session($param=array()){

		$request = ($param['userid']!='') ? 'userid='.$param['userid'] : '';
		$request .= ($param['title']!='') ? '&title='.str_replace(' ','%20', $param['title']) : '&title=standard-conference';
		$request .= ($param['desc']!='') ? '&desc='.str_replace(' ','%20', $param['desc']) : '&desc=standard-conference';
		$request .= ($param['accesscode']!='') ? '&authtype=2' : '&authtype=1';
		$request .= ($param['accesscode']!='') ? '&authvalue='.$param['accesscode'] : '';
		$request .= ($param['act']!='') ? '&act='.$param['act'] : '&act=1';
		$request .= ($param['maxparticipant']!='') ? '&maxparticipant='.$param['maxparticipant'] : '&maxparticipant=50';
		$request .= ($param['maxspeaker']!='') ? '&maxspeaker='.$param['maxspeaker'] : '&maxspeaker=5';
		$request .= ($param['maxspeed']!='') ? '&maxspeed='.$param['maxspeed'] : '&maxspeed=256';
		$request .= ($param['videocontrol']!='') ? '&videocontrol='.$param['videocontrol'] : '&videocontrol=0';
		$request .= ($param['startmode']!='') ? '&startmode='.$param['startmode'] : '&startmode=1';
		$request .= '&avmode=1';
		$request .= ($param['confmode']!='') ? '&confmode='.$param['confmode'] : '';
		$request .= ($param['iscallout']!='') ? '&iscallout='.$param['iscallout'] : '&iscallout=1';
		$request .= ($param['iscallyou']!='') ? '&iscallyou='.$param['iscallyou'] : '&iscallyou=1';
		$request .= ($param['iscallme']!='') ? '&iscallme='.$param['iscallme'] : '&iscallme=1';
		$request .= ($param['isPresenterMode']!='') ? '&isPresenterMode='.$param['isPresenterMode'] : '';
		$request .= ($param['freerecording']!='') ? '&freerecording=1' : '&freerecording=0';
		$request .= ($param['ConfQuality']!='') ? '&ConfQuality='.$param['ConfQuality'] : '';

		$uri = $this->server.'add_session.asp?'.$request;
		$res = trim($this->set_request($uri));

		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'add session.';
			$this->debug_url = $uri;
		}
		$exr = explode('|', $res);
		if((int)$exr[0]==1) return true;
		else return false;
	}
	public function start_session($sid, $name)
	{
		//$passwordenc = base64_encode(TripleDES::encryptText($password, conf('amkey'), conf('amvi')));
		$uri = $this->server.'start_session.asp?sid='.$sid.'&Name1='.$name;
		$res = trim($this->set_request($uri));
		$startdata = simplexml_load_string($res);
		return $startdata;
	}
	public function update_password($user, $password)
	{
		//$passwordenc = base64_encode(TripleDES::encryptText($password, conf('amkey'), conf('amvi')));
		$uri = $this->server.'update_user_info.asp?userid='.$user.'&isactive=1&password='.$password.'&FPublishQuota=0&FQuotaType=0';
		$res = trim($this->set_request($uri));

		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'update user password.';
			$this->debug_url = $uri;
		}

		if((int)$res==1) return true;
		else return false;
	}

	public function update_session($param=array(), $sid)
	{

		$request = ($param['title']!='') ? 'title='.str_replace(' ','%20', $param['title']) : '';
		$request .= ($param['desc']!='') ? '&desc='.str_replace(' ','%20', $param['desc']) : '';
		$request .= ($param['accesscode']!='') ? '&authtype=2&authvalue='.$param['accesscode'] : '&authtype=1';
		$request .= ($param['act']!='') ? '&act='.$param['act'] : '';
		$request .= ($param['maxparticipant']!='') ? '&maxparticipant='.$param['maxparticipant'] : '';
		$request .= ($param['maxspeaker']!='') ? '&maxspeaker='.$param['maxspeaker'] : '';
		$request .= ($param['videocontrol']!='') ? '&videocontrol='.$param['videocontrol'] : '';
		$request .= ($param['startmode']!='') ? '&startmode='.$param['startmode'] : '';
		$request .= ($param['confmode']!='') ? '&confmode='.$param['confmode'] : '';
		//$request .= ($param['isPresenterMode']!='') ? '&isPresenterMode='.$param['isPresenterMode'] : '';
		$request .= ($param['ConfQuality']!='') ? '&ConfQuality='.$param['ConfQuality'] : '';

		$uri = $this->server.'update_session_info.asp?sid='.$sid.'&'.$request;
		$res = trim($this->set_request($uri));

		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'update session info.';
			$this->debug_url = $uri;
		}

		if((int)$res==1) return true;
		else return false;
	}

	public function update_name($user, $name)
	{
		//$passwordenc = base64_encode(TripleDES::encryptText($password, conf('amkey'), conf('amvi')));
		$uri = $this->server.'update_user_info.asp?userid='.$user.'&isactive=1&nickname='.$name.'&FPublishQuota=0&FQuotaType=0';
		$res = trim($this->set_request($uri));

		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'update user fullname.';
			$this->debug_url = $uri;
		}

		if((int)$res==1) return true;
		else return false;
	}

	public function getStudioList($userid)
	{
		$uri = $this->server.'list_studio_duo.asp?userid='.$userid;
		$res = $this->set_request($uri);
		$stdo = simplexml_load_string($res);
		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'get user contents list.';
			$this->debug_url = $uri;
		}

		if($stdo->RetCode==1) return $stdo->Records;
		else return false;
	}

	public function getACMInfo($contentid, $userid, $version = '7')
	{
		$args = func_get_args();
		switch($version)
		{
			case 7:
				$uri = conf('idm_server').'aculearn-idm/contentbackup/channel1/users/'.$userid.'/'.$contentid.'/info.xml';
				$loadXML = file_get_contents($uri);
				$resXML = simplexml_load_string($loadXML);

				//Assign value
				$node['server'] = $resXML->ServerSetting->AM;
				$node['title'] = $resXML->Course->Title;
				$node['description'] = $resXML->Course->Description;
				$node['image'] = 'http://'.$node['server'].'/aculearn-idm/contentbackup/channel1/users/'.$userid.'/'.$contentid.'/'.$resXML->Recording->Chapter->Scenes->Scene->File;
				$node['date'] = $resXML->Course->Date->CreateDate;
				$node['url'] = 'http://'.$node['server'].'/aculearn-idm/v4/opr/studioclient.asp?author='.$userid.'&modulename='.$contentid.'&cat=acustudio-av';
			break;
			case 6:
				$uri = conf('idm_server').'aculearn-idm/contentbackup/channel1/users/'.$userid.'/'.$contentid.'/AcuStudio.xml';
				$loadXML = file_get_contents($uri);
				$resXML = simplexml_load_string($loadXML);

				//Assign value
				$node['server'] = conf('idm_server');
				$node['title'] = $resXML->Slides->Slide->SlideInfo->SlideLabel;
				$node['description'] = $resXML->Slides->SlideInfo->SlideDetails;
				$node['image'] = 'http://'.$node['server'].'/aculearn-idm/contentbackup/channel1/users/'.$userid.'/'.$contentid.'/'.$resXML->Slides->Slide['Index'];
				$node['date'] = $resXML->SourceGroup->Source['CreateDate'];
				$node['url'] = 'http://'.$node['server'].'/aculearn-idm/v4/opr/studioclient.asp?author='.$userid.'&modulename='.$contentid.'&cat=acustudio-av';
			break;
		}


		if($this->debug==1)
		{
			$this->debug_msg = 'ARRAY DATA FORMAT';
			$this->debug_args = $args;
			$this->debug_action = 'get studio duo info.';
			$this->debug_url = $uri;
		}

		return $node;
	}

	public function idm_debug()
	{
		//Debug option
		$dbg = '<li style="color: red;font-weight: bold;">Debug mode is on</li>';
		$dbg .= '<li>Connect to: '.conf('idm_server').'</li>';
		$dbg .= '<li>AcuManager Version: '.strip_tags(file_get_contents(conf('idm_server').'login/version.asp')).'</li>';
		$dbg .= '<li>Request URL: '.$this->debug_url.'</li>';

		if($this->debug_args!=''){
			$dbg .= '<li>Function arguments</li>';
			for ($i = 0; $i < count($this->debug_args); $i++) {
				$dbg .= "<li>Argument[$i] : " . $this->debug_args[$i] . "</li>";
			}
		}

		$dbg .= '<li>Action: '.$this->debug_action.'</li>';
		$dbg .= '<li>Result : '.$this->debug_msg.'</li>';
		return $dbg;
	}
	public function getConference($userid)
	{
		$uri = $this->server.'list_session.asp?userid='.$userid;
		$res = $this->set_request($uri);
		//Check if debug mode is on.
		$confr = simplexml_load_string($res);
		if($this->debug==1)
		{
			$this->debug_msg = $confr->RetCode;
			$this->debug_args = $args;
			$this->debug_action = 'get conference list.';
			$this->debug_url = $uri;
		}

		if($confr->RetCode==1) return $confr->Records;
		else return false;
	}
	public function DeleteUser($userid, $username=null)
	{
		if($username!=null) $userid=IDM2API::get_userid('', $username);
		$uri = $this->server.'delete_user.asp?userid='.$userid;
		$res = $this->set_request($uri);
		//Check if debug mode is on.
		if($this->debug==1)
		{
			$this->debug_msg = $res;
			$this->debug_action = 'Delete User.';
			$this->debug_url = $uri;
		}

		if($res==1) return $res;
		else return false;
	}

	public function getLive($userid)
	{
		$uri = $this->server.'list_live_duo.asp?userid='.$userid;
		$res = $this->set_request($uri);
		$livelist = simplexml_load_string($res);
		//Check if debug mode is on.
		if($this->debug==1)
		{
			$this->debug_msg = $livelist->RetCode;
			$this->debug_args = $args;
			$this->debug_action = 'get live info.';
			$this->debug_url = $uri;
		}


		if($livelist->RetCode==1) return $livelist->Records;
		else return false;
	}

	public function SessionInfo($sid)
	{
		$uri = $this->server.'get_session_info.asp?sid='.$sid;
		$res = $this->set_request($uri);
		$session = simplexml_load_string($res);

		//Check if debug mode is on.
		if($this->debug==1)
		{
			$this->debug_msg = $session->RetCode;
			$this->debug_args = $args;
			$this->debug_action = 'get session info.';
			$this->debug_url = $uri;
		}


		if($session->RetCode==1) return $session;
		else return false;
	}
	/*
	public function example()
	{
		//Check if debug mode is on.
		if($this->debug==1)
		{
			$this->debug_msg = 'ARRAY DATA FORMAT';
			$this->debug_args = $args;
			$this->debug_action = 'get studio duo info.';
			$this->debug_url = $uri;
		}
	}
	*/
}
?>