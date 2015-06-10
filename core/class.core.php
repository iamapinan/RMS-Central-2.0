<?php
class core extends common{

	function get_lang($input)
	{
		$sql = mysql_query('SELECT * FROM '.conf('table_prefix').'_languages WHERE name = "'.$input.'" OR short = "'.$input.'"');
		$exec = mysql_fetch_array($sql);
		return $exec;
	}

	function iplocation($ip){
	$ch = curl_init();
	$data = array('query' => $ip);
	curl_setopt($ch, CURLOPT_URL, 'http://www.iplocation.net/tools/ip-locator.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$x = curl_exec($ch);
	$replace = str_replace("src='/tools/ip2location/flags","@CUT@",$x);
	$string = explode("@CUT@",$replace);
	$getCity = explode("</td><td>",$string[1]);
		//print_r($getCity);
	$return['city'] = $getCity[2];
	$return['country'] = $getCity[count($getCity)-1];

	return $return;
	}

	public function sendmail($t,$s,$b)
	{
		include_once($_SERVER['DOCUMENT_ROOT'].'/module/phpmail/class.phpmailer.php');

		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		//Tell PHPMailer to use SMTP
		$mail->IsSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug  = 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mail->Host       = "127.0.0.1";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port       = 25;
		//Whether to use SMTP authentication
		$mail->SMTPAuth   = true;
		//Username to use for SMTP authentication
		$mail->Username   = "service@bll.in.th";
		//Password to use for SMTP authentication
		$mail->Password   = "P@ssw0rd";
		//Set who the message is to be sent from
		$mail->SetFrom('service@bll.in.th', 'Boundless Learning');
		//Set an alternative reply-to address
		$mail->AddReplyTo('service@bll.in.th', 'Boundless Learning');
		//Set who the message is to be sent to
		$mail->AddAddress($t['email'], $t['name']);
		//Set the subject line
		$mail->Subject = $s;
		//Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body
		$mail->MsgHTML($b);
		//Replace the plain text body with one created manually
		$mail->AltBody = strip_tags($b);
		//Attach an image file
		//$mail->AddAttachment('images/phpmailer-mini.gif');
		//Send the message, check for errors
			if($mail->Send()) {
			  return true;
			}
			else
			  return false;

	}

}//end class

function newCodeeditor($name)
{
		$ret = '<link rel="stylesheet" href="'.conf('url').'library/syntax/lib/codemirror.css">
		<script src="'.conf('url').'library/syntax/lib/codemirror.js"></script>
		<script src="'.conf('url').'library/syntax/mode/xml/xml.js"></script>
		<script src="'.conf('url').'library/syntax/mode/javascript/javascript.js"></script>
		<script src="'.conf('url').'library/syntax/mode/css/css.js"></script>
		<script src="'.conf('url').'library/syntax/mode/clike/clike.js"></script>
		<script src="'.conf('url').'library/syntax/mode/markdown/markdown.js"></script>
		<script src="'.conf('url').'library/syntax/mode/php/php.js"></script>
		<script src="'.conf('url').'library/syntax/mode/markdown/markdown.css"></script>
		<link rel="stylesheet" href="'.conf('url').'library/syntax/theme/rubyblue.css">';
		$ret .= '<textarea class="text-area edit-body" id="code" name="'.$name.'"></textarea>';
		$ret .= '<script src="'.conf('url').'library/syntax/complete.js"></script>';
		return $ret;
}


function checkrole_access($access)
{
	$role = UserInfo();
	$n = count($access);

	if($role['role'] == '') $role['role'] = 10;
	for($i=0;$i<$n;$i++)
	{
		if((int)$role['role']==$access[$i]||(int)$role['role']==5||$access[$i]==10)
		{
			return true;
			break;
		}

	}

}

function relativeTime($timestamp){
    if( !is_numeric( $timestamp ) ){
        $timestamp = strtotime( $timestamp );
        if( !is_numeric( $timestamp ) ){
            return "";
        }
    }

    $difference = time() - $timestamp;
        // Customize in your own language.
    $periods = array( "Sec", "Min", "Hr", "Day", "Week", "Month", "Year", "Decade" );
    $lengths = array( "60","60","24","7","4.35","12","10");

    if ($difference > 0) { // this was in the past
        $ending = "";
    }else { // this was in the future
        $difference = -$difference;
        $ending = "";
    }
    for( $j=0; $difference>=$lengths[$j] and $j < 7; $j++ )
        $difference /= $lengths[$j];
    $difference = round($difference);
    if( $difference != 1 ){
                // Also change this if needed for an other language
        $periods[$j].= "";
    }
    $text = 'On '.$difference.' '.$periods[$j].$ending;

    return $text;
}

function getsubject($subjectID)
{
	$mycmd = mysql_query("SELECT * FROM ".conf('table_prefix')."_subject WHERE bid=".$subjectID);
	$ret = mysql_fetch_array($mycmd);
	return $ret;
}

function useronline($q,$uid=null,$session=null,$ip=null)
{
		//Define
		$time = time();
		$t10 = time()-600; // 10 minute
		$t5 = time()-300; // 5 minute
		$t3 = time()-180;

	if($q=='init') //Preparing data and set status to active.
	{
		$setck = setcookie('tclogin', $uid,time()+3600);
		$sql_qr = mysql_query('SELECT * FROM '.conf('table_prefix').'_online WHERE uid='.$uid);
		$result = @mysql_fetch_array($sql_qr);
		if($result=='')
		{
			$sql_ins = mysql_query('INSERT INTO '.conf('table_prefix').'_online (id,uid,session_id,timestamp,ipaddr,status) VALUES(NULL,'.$uid.',"'.$session.'",'.$time.',"'.$ip.'","active")');
			if($sql_ins&&$setck) return true;
			else return false;
		}
	}

	if($q=='check') //Checking status
	{
		$sql_qr = mysql_query('SELECT status FROM '.conf('table_prefix').'_online WHERE uid='.$uid);
		$sql_fetch = @mysql_fetch_array($sql_qr);
		if($sql_fetch['status']!='')
			return $sql_fetch['status'];
		else
			return 'offline';
	}

	if($q=='reset') //Set status to active
	{
		$sql_qr = mysql_query('SELECT * FROM '.conf('table_prefix').'_online WHERE uid='.$uid);
		$result = @mysql_fetch_array($sql_qr);
		if($result=='')
		{
			$setck = setcookie('tclogin', $uid,time()+3600);
			$sql_ins = mysql_query('INSERT INTO '.conf('table_prefix').'_online (id,uid,session_id,timestamp,ipaddr,status) VALUES(NULL,'.$uid.',"'.$session.'",'.$time.',"'.$ip.'","active")');
			if($sql_ins&&$setck) return true;
			else return false;
			exit();
		}
		$sql_qr = mysql_query('UPDATE '.conf('table_prefix').'_online SET status = "active", timestamp = '.$time.', session_id="'.$session.'", ipaddr="'.$ip.'" WHERE uid='.$uid);
		if($sql_qr) return true;
		else return false;
	}
	//m/lo1/index.php
	if($q=='update') //Time check and update status
	{
			//Clean
			mysql_query('DELETE FROM '.conf('table_prefix').'_online WHERE timestamp<'.(time()-600));
			//Check login time
			$sql_qr = mysql_query('SELECT * FROM '.conf('table_prefix').'_online WHERE uid='.$uid);
			$sql_fetch = @mysql_fetch_array($sql_qr);

		if(isset($sql_fetch['timestamp']))
		{
			switch ($sql_fetch['timestamp'])
			{
				case ($sql_fetch['timestamp']>=(time()-600)):
					$status = 'active';
					break;
				case ($sql_fetch['timestamp']>=(time()-300)):
					$status = 'away';
					break;
				case ($sql_fetch['timestamp']>=(time()-180)):
					$status = 'away';
					break;
				default:
					$status = 'busy';
					break;
			}


			//Update status.
			mysql_query('UPDATE '.conf('table_prefix').'_online SET status = "'.$status.'" WHERE uid='.$uid);
			return $status;
		}
		else
		{
			return 'offline';
		}
	}

	if($q=='count')
	{
		$sql_qr = mysql_query('SELECT count(*) FROM '.conf('table_prefix').'_online');
			$result = mysql_result($sql_qr,0);
			return (int)$result;
	}

	if($q=='active')
	{
		$sql_qr = mysql_query('SELECT count(*) FROM '.conf('table_prefix').'_online WHERE status="active"');
			$result = mysql_result($sql_qr,0);
			return (int)$result;
	}

	if($q=='away')
	{
		$sql_qr = mysql_query('SELECT count(*) FROM '.conf('table_prefix').'_online WHERE status="away"');
			$result = mysql_result($sql_qr,0);
			return (int)$result;
	}

	if($q=='busy')
	{
		$sql_qr = mysql_query('SELECT count(*) FROM '.conf('table_prefix').'_online WHERE status="busy"');
			$result = mysql_result($sql_qr,0);
			return (int)$result;
	}

	if($q=='clean')
	{
		$sql_qr = mysql_query('DELETE FROM '.conf('table_prefix').'_online WHERE timestamp<'.$time-3600);
			if($sql_qr) return true;
			else return false;
	}
}


function user_relate_contents($check,$number = null,$sh = null)
{
	if($number==null)
		$size=3;
	else
		$size=$number;

	if($sh!=null) $sh = $sh.' AND ';


	if(empty($check))
		$cmd = 'SELECT * FROM '.conf('table_prefix').'_subject WHERE '.$sh.' approve="yes" AND status="public" AND (filetype="video" OR filetype="pdf" OR filetype="flash/zip"  OR filetype="blog") ORDER BY bid DESC LIMIT 0,'.$size;
	else
		$cmd = 'SELECT * FROM '.conf('table_prefix').'_subject WHERE username="'.$check.'" AND approve="yes" AND status="public" AND (filetype="video" OR filetype="pdf") ORDER BY viewed DESC LIMIT 0,'.$size;

	$exec = mysql_query($cmd);
	$ret = '<div id="relate_list">';
	$ret .= '<ul class="relate-content-list">';

	while($result = @mysql_fetch_array($exec))
	{
		if(is_file($_SERVER['DOCUMENT_ROOT'].'/data/content/'.$result['image']))
			$result['image'] = $result['image'];
		else
			continue;

		if($result['filetype']=='blog'&&$result['image']!='')
			$result['image'] = '<span style="display:block;"><a href="/c/'.$result['req_id'].'"><img src="'.conf('url').'image?width=240&height=165&cropratio=2:1.3&image=/data/content/'.trim($result['image']).'" width="240" height="auto"></a></span>';
		else
		if($result['filetype']=='video'&&$result['image']!='')
			$result['image'] = '<span style="display:block;"><a href="/c/'.$result['req_id'].'"><img src="'.$result['cdn'].'/img/'.conf('cdn_user').'_'.$result['username'].'-'.$result['req_id'].'_'.$result['image'].'?width=240&height=auto&cropratio=1.53:1" width="240" height="auto"></a></span>';
		else
		if($result['filetype']=='pdf'&&$result['image']!='')
			$result['image'] = '<span style="display:block;"><a href="/c/'.$result['req_id'].'"><img src="'.$result['cdn'].'/img/'.conf('cdn_user').'_'.$result['username'].'-'.$result['req_id'].'_'.$result['image'].'?width=240&height=auto&cropratio=1.53:1" width="240" height="auto"></a></span>';
		else
		if($result['filetype']=='flash/zip'&&$result['image']!='')
			$result['image'] = '<span style="display:block;"><a href="/c/'.$result['req_id'].'"><img src="'.$result['cdn'].'/img/'.conf('cdn_user').'_'.$result['username'].'-'.$result['req_id'].'_'.$result['image'].'" width="240" height="auto"></a></span>';

		$ret .= '<li id="'.$result['bid'].'" style="height: 156px;overflow:hidden;">
		'.$result['image'].'
		<div class="relate-title"><a href="/c/'.$result['req_id'].'" style="font-size: 16px;color: #fff;">'.str_replace('_',' ',$result['subject']).'</a>';
		if(empty($check))
		{
			$owner = UserInfo($result['username']);
			$ret .= '<span style="font-size: 11px !important;display:block;">
			โดย <a href="'.conf('url').'@'.$result['username'].'">'.str_replace('|',' ',$owner['fullname']).'</a>
		·	'.(int)$result['viewed'].' View
		·  '.(int)$result['like'].' Like';
		if($result['filetype']=='video')
			$ret .= '<br>Duration '.date('H:i:s', $result['duration']);
			$ret .= '</span>';
		}
		$ret .= '
		</div>
		</li>';
	}
	$ret .= '</ul>';
	$ret .= '</div>';
    return $ret;
}

function videoduration($videofile)
{
ob_start();
passthru("ffmpeg -i \"{$videofile}\" 2>&1");
$duration = ob_get_contents();
ob_end_clean();

$search='/Duration: (.*?),/';
$duration=preg_match($search, $duration, $matches, PREG_OFFSET_CAPTURE, 3);

$video_time = strtotime($matches[1][0]);
return $video_time;
}


function create_more_msg($msg,$id)
{
	$msg = $msg;
	if(iconv_strlen($msg,'utf-8')>=600)
	{
		$ret = '<span class="first_msg">
		'.iconv_substr($msg,0,500,'utf-8').'
		<span  class="show_me_more_'.$id.'">...
		<a href="#" onclick="$(\'.hide_msg_'.$id.'\').show();$(\'.show_me_more_'.$id.'\').hide();">แสดงเพิ่มเติม &rsaquo;&rsaquo;</a>
		</span>
		</span>';
		$ret .= '<span class="hide_msg_'.$id.'">'.iconv_substr($msg,501,iconv_strlen($msg,'utf-8'),'utf-8').'</span>';
	}
	else
	{
		$ret = $msg;
	}
	return $ret;
}

function trs($text_id)
{
	if(isset($_COOKIE['lang']))
	{
		include ($_SERVER['DOCUMENT_ROOT'].'/language/'.$_COOKIE['lang'].'.php');
	}
	else
	{
		include ($_SERVER['DOCUMENT_ROOT'].'/language/'.lang.'.php');
	}

	return $lang[$text_id];
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

function get_vid_dim($file)
{
    $command = conf('ffmpeg').' -i ' . escapeshellarg($file) . ' 2>&1';
    $dimensions = array();
    exec($command,$output,$status);
    if (!preg_match('/Stream #(?:[0-9\.]+)(?:.*)\: Video: (?P<videocodec>.*) (?P<width>[0-9]*)x(?P<height>[0-9]*)/',implode('\n',$output),$matches))
    {
        preg_match('/Could not find codec parameters \(Video: (?P<videocodec>.*) (?P<width>[0-9]*)x(?P<height>[0-9]*)\)/',implode('\n',$output),$matches);
    }
    if(!empty($matches['width']) && !empty($matches['height']))
    {
        $dimensions['width'] = $matches['width'];
        $dimensions['height'] = $matches['height'];
    }
    return $dimensions;
}
function sheetData($sheet) {
	  $x = 1;
	  while($x <= $sheet['numRows']) {
		$y = 1;
		while($y <= $sheet['numCols']) {
		 $cell = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
		  if($x==1)
		  {
			  $data['title'][$y] = $cell;
		  }
		  else
		  {
			  $data['body'][$x-1][$y] = $cell;
		  }

		  $y++;
		}
		$x++;
	  }

	if(count($data['title'])==12)
	  return $data;
	else
	  return '';
	}
function excel_reader($file)
	{
			include conf('dir').'excel_reader.php';
			$excel = new PhpExcelReader;      // creates object instance of the class
			$excel->read(conf('dir').'temp/'.$file);   // reads and stores the excel file data
			$nr_sheets = count($excel->sheets);       // gets the number of worksheets
			$excel_data = '';              // to store the the html tables with data of each sheet
			// traverses the number of sheets and sets html table with each sheet data in $excel_data
			$exr = array();
			for($i=0; $i<$nr_sheets; $i++) {
				$exr = sheetData($excel->sheets[$i]);
			}

		return $exr;
	}
?>