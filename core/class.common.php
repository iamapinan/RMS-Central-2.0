<?php
define("lang","th",true);

	function conf($configname)
	{
		global $conf;
		return $conf[$configname];
	}

	function checkEmail($email)
	{
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;

	}
	function get_images($html_string)
	{
		$post_images = array();
		preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $html_string, $image_matches, PREG_SET_ORDER);
		foreach ($image_matches as $image_match)
		{
		list($width, $height, $type, $attr) = getimagesize($image_match[1]);
		if($width<=250&&$height<=80) continue;
			$post_images[] = $image_match[1];
		}

		return $post_images;
	}
	function ftype($f) {
                    curl_setopt_array(($c = @curl_init((!preg_match("/[a-z]+:\/{2}(?:www\.)?/i",$f) ? sprintf("%s://%s/%s", "http" , $_SERVER['HTTP_HOST'],$f) :  $f))), array(CURLOPT_RETURNTRANSFER => 1, CUROLPT_HEADER => 1));
                        return(preg_match("/Type:\s*(?<mime_type>[^\n]+)/i", @curl_exec($c), $m) && curl_getinfo($c, CURLINFO_HTTP_CODE) != 404)  ? ($m["mime_type"]) : 0;

    }

	function remote_filesize($url, $user = "", $pw = "")
	{
		ob_start();
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);

		if(!empty($user) && !empty($pw))
		{
			$headers = array('Authorization: Basic ' .  base64_encode("$user:$pw"));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		$ok = curl_exec($ch);
		curl_close($ch);
		$head = ob_get_contents();
		ob_end_clean();

		$regex = '/Content-Length:\s([0-9].+?)\s/';
		$count = preg_match($regex, $head, $matches);

		return isset($matches[1]) ? $matches[1] : "unknown";
	}

	function formatBytes($size, $precision = null)
	{
		$base = log($size) / log(1024);
		$suffixes = array('', 'KB', 'M', 'G', 'T');

		$res['size'] = (int)round(pow(1024, $base - floor($base)), $precision);
		$res['format'] = $suffixes[floor($base)];
		return $res;
	}

	function catch_image($post) {
	  ob_start();
	  ob_end_clean();
	  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post, $matches);
	  $first_img = $matches[1][0];
/*
	  $imgs = remote_filesize($first_img);
	  $imgsize = formatBytes($imgs);

	  if(empty($first_img)||$imgsize['size']<=3){ //Defines a default image
		$first_img = "";
	  }
*/
	  return $first_img;
	}

	function UserInfo($g = null)
	{
		if($g==null)
		$u = @$_SESSION['loginid']['nickname'];
		else
		$u = $g;
        
        if(!isset($_SESSION['u'.$u])){
            $db = new mysql;
            $res = $db->query('select * from '.conf('table_prefix').'_profile where user="'.$u.'" ');
            $ret = @$res[0];
            $_SESSION['u'.$res['ssid']] = $ret;
        }else
        {
            $ret = $_SESSION['u'.$u];
        }
        
		return $ret;
	}
    function image_resize($image, $w=100, $h=100, $crop='1:1'){
        return "/image?width=$w&height=$h&cropratio=$crop&image=$image";
    }
	function MoodleUser($g = null)
	{
		if($g==null)
		$u = @$_SESSION['loginid']['nickname'];
		else
		$u = $g;

		$db = new mysql;
		$res = $db->query('select * from moodle_user where username="'.$u.'" ');
		$ret = @$res[0];
		return $ret;
	}

	function GroupInfo($g = null)
	{
		$db = new mysql;
		$res = $db->query('select * from '.conf('table_prefix').'_group where gid="'.$g.'" ');

		return $res[0];
	}
	function classinfo($g = null)
	{
		$db = new mysql;
		$res = $db->query('select * from '.conf('table_prefix').'_classroom where clsid='.$g);
		$res[0] ['text'] = ($res[0]['grade']>=7&&$res[0]['grade']<=12) ? 'มัธยมศึกษาปีที่ '.($res[0]['grade']-6) : 'ประถมศึกษาปีที่ '.$res[0]['grade'];
		return $res[0];
	}

	function CourseInfo($g = null)
	{
		$db = new mysql;
		$res = $db->query('select * from '.conf('table_prefix').'_course where course_id="'.$g.'" ');

		return $res[0];
	}

	function PageInfo($g = null, $type = 'group')
	{
		$db = new mysql;
		if($type=='group')
			$res = $db->query('select * from '.conf('table_prefix').'_social_group where url="'.$g.'" ');

		return $res[0];
	}

	function schoolinfo($g = null, $scop=null)
	{
		$db = new mysql;
        		$scop = ($scop!='') ? $scop : '*';
		$res = $db->query('select '.$scop.' from '.conf('table_prefix').'_school where sid="'.$g.'" ');

		return $res[0];
	}

	function orgname($g = null)
	{
		$db = new mysql;
		$res = $db->query('select sname from '.conf('table_prefix').'_school where sid="'.$g.'" ');
        		if($res[0]['sname']=='') $res[0]['sname']='ไม่มีสังกัด';
		return $res[0]['sname'];
	}

	function Level($lid)
	{
		$db = new mysql;
		$res = $db->query('select * from '.conf('table_prefix').'_level where level_id="'.$lid.'" ');
		return $res[0];
	}
	function uRole($rid)
	{
		$db = new mysql;
		$res = $db->query('select rolename from '.conf('table_prefix').'_role where rid="'.$rid.'" ');
		return trim(str_replace(range(0,9),'', $res[0]['rolename']));
	}

	function UserById($u)
	{
		if(!isset($_SESSION['u'.$u])){
			$db = new mysql;
			$res = $db->query('select * from '.conf('table_prefix').'_profile where ssid='.$u);
			$ret = $res[0];
			$_SESSION['u'.$res['ssid']] = $ret;
		 }else
		 {
		      $ret = $_SESSION['u'.$u];
		 }
		        return $ret;
	}

	function csname($cid){
		$db = new mysql;
		$res = $db->query('select name from '.conf('table_prefix').'_level where level_id = '.$cid);
		$ret = $res[0]['name'];
		return $ret;
	}

	function UserDescript($data=null,$u)
	{
		if($u==null)
		$u = @$_SESSION['loginid']['nickname'];

		if($data=='')
			$sel = '*';
		else
			$sel = $data;

		$db = new mysql;
		$res = $db->query('select '.$sel.' from '.conf('table_prefix').'_profile where ssid='.$u.' ');
		$ret = @$res[0];
		return $ret;
	}

	function GetProtectUser()
	{
		 $protected = GetConf('ProtectUser');
		 $result = explode(',',$protected);
		 return $result;
	}

	if(!function_exists('mime_content_type')) {
		function mime_content_type($filename) {

			$mime_types = array(

				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',

				// images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',

				// archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',

				// audio/video
				'mp3' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',

				// adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',

				// ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',

				// open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			);

			$ext = strtolower(array_pop(explode('.',$filename)));
			if (array_key_exists($ext, $mime_types)) {
				return $mime_types[$ext];
			}
			elseif (function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME);
				$mimetype = finfo_file($finfo, $filename);
				finfo_close($finfo);
				return $mimetype;
			}
			else {
				return 'application/octet-stream';
			}
		}
	}

	function GetConf($items)
	{
		$db = new mysql;
		$res = $db->query('select * from '.conf('table_prefix').'_config where name="'.$items.'" ');
		return $res[0]['value'];
	}



//User profile function
class profile
{
	protected $varname = null;
	public $name = null;

	public function info($mode = 1,$varname=null)
	{
		if($varname!=null)
			$this->varname=$varname;
			if($this->name==null||$mode==0)
			{
				$ret ='<div id="array">';
				$ret .= '<p class="topic-header">User Info</p>'."\n";
				foreach($_SESSION[$this->varname] as $v => $val)
				{
					$ret .= '<span class="list-items">'.$v.'</span> = <span class="list-values">'.$val.'</span><br>'."\n";
				}
				$ret .='</div>'."\n";
			}
			else
			{
				$ret = @$_SESSION[$this->varname][$this->name];
			}
			return $ret;
	}

	//Random key
	function randomstr($length)
	{
		$str = rand(100000,999999).sha1(time()).md5(date('dmYhmis'));
		$str = 	substr($str,0, $length);
		return $str;
	}

	function encodeme($str)
	{
		$date = $this->dateTime();
		$ret = base64_encode($this->randomstr(3).base64_encode($date[0]).base64_encode($str).$this->randomstr(3));
		return $ret;
	}

	//return user fullname 2 mode
	public function cname($mode=null)
	{
		$u = @$_SESSION['loginid']['nickname'];
		$db = new mysql;
		$name = $db->query('select fullname from '.conf('table_prefix').'_profile where user="'.$u.'" ');
		if($mode==null||$mode==0)
		$getit = explode("|",$name[0]['fullname']);
		else if($mode=1)
		$getit = str_replace("|"," &nbsp;",@$name[0]['fullname']);
		else
		$getit = '<p>Please check cname function parametor.</p>'."\n";

		return $getit;
	}
}

class common extends profile
{
	public $privateheader;
	public $privatefooter;
	public $moduleheader;
	public $u = array();
	public $role;
	public $libURL;
	public $module;
	public $title;
	public $PageOption;
	protected static $config = array();
	public $ShareHeader;
	public $PageHeader;
	public $PageTitle;

	public function __construct()
	{
		ob_start();
		session_start();
		header ('Content-type: text/html; charset=utf-8');
		setlocale(LC_ALL, 'th');

		global $conf;
		self::$config = $conf;

		if(isset($_SESSION['loginid']))
			$this->u = $_SESSION['loginid'];

		$this->libURL = '/library/';
	}

	function dateTime()
	{
		$d = conf('d');
		$t = conf('t');
		$ret[0] = $d;
		$ret[1] = $t;
		return $ret;
	}


	//Show Welcome Message
	public function toolbar()
	{
		$uif = UserInfo();
			if($uif['avatar']==null)
				$uimg ='/library/avatar/uAvatar-ss.jpg';
			else
				$uimg = '/user/'.$uif['user'].'/'.$uif['avatar'];
		$ret = '';
				if($_SERVER['SCRIPT_NAME']=='/page.php'&&$this->getRole('int')==('08')&&!isset($_GET['edit'])){
						$pageUrl = explode('?', $_SERVER['REQUEST_URI']);
						$ret .= '
						<div class="page_menu"><a href="'.$pageUrl[0].'?edit=body" rel="left"><span style="color: #fff;">Edit</span></a></div>
						';
					}

					$ret .= '<div id="rb">
					<div class="area_noscoll">'."\n";
					$ret .= '<ul class="rb_menu">'."\n";

					if(isset($_SESSION['loginid'])){
					$ret .= '	<a href="http://rpdcenter.bll.in.th/Pages/SignIn.aspx" style="color: #0B739E;"><li><img src="/library/images/mini-bll-logo.png" class="top-web-logo"> ไร้พรมแดน</li></a>'."\n";
					$ret .= '<a href="'.conf('url').'my"><li><span><img src="/image?width=30&height=30&cropratio=1:1&image=/user/'.$uif['user'].'/'.$uif['avatar'].'" style="    border-radius: 3px;"></span> &nbsp;My Home</li></a>&nbsp;'."\n";
					}
					if(getConf('devmode')==1&&$this->isAdmin()==1) $ret .= '	<a href="'.conf('url').'course"><li><i class="fa fa-book"></i> Course</li></a>'."\n";
					$ret .= '	<a href="'.conf('url').'public"><li><i class="fa fa-quote-right"></i> Blogs</li></a>'."\n";
					$ret .= '	<a href="'.conf('url').'news"><li><i class="fa fa-newspaper-o"></i> News</li></a>'."\n";
					$ret .= '<li class="master-li"><a><i class="fa fa-bars fa-lg"></i></a>
					<a onclick="window.location.href=\''.conf('url').'faq\'" class="child-li">FAQ</a>
					<a onclick="window.location.href=\''.conf('url').'tutorial\'" class="child-li">Tutorial</a>
					<a onclick="window.location.href=\''.conf('url').'download-center\'" class="child-li">Download</a>
					</li>
					';

					$ret .= '</ul>'."\n";
					$ret .= '<div class="msg-alert-popup"></div>';
					$ret .= '<span class="user-menu" style="right:0px;">
					<a><span data="display-name" class="user-text"><i class="fa fa-search"></i></span><input type="text" id="shb" onclick="$(\'#shb\').css(\'width\',\'100px\');" name="kk" placeholder="Keyword"></a>
					';
					if(isset($_SESSION['loginid'])){
						$ret .= '	<a href="'.conf('url').'go/logout"><span class="user-text"><i class="fa fa-sign-out"></i>Logout</span></a>';
					}else{
						$ret .= '	<a href="'.conf('url').'/login"><span class="user-text"><i class="fa fa-sign-in"></i>Login </span></a>';
					}

					$ret .= '</div></div>'."\n";
					$ret .= '<div class="clear"></div>'."\n";

		return $ret;
	}

	function br2p($string) {
	return preg_replace('#<p>[\n\r\s]*?</p>#m', '', '<p>'.preg_replace('#(<br\s*?/?>){2,}#m', '</p><p>', $string).'</p>');
	}


public function page_initial()
	{
		$ret = '<div id="alert_status">
		<span class="text"></span>
		<span class="status_close"><img src="/library/images/cancel.png"></span>
		</div>';
		$ret .= '
		<script type=\'text/javascript\'>
		<!--
			var uSid = "'.$_SESSION['loginid']['nickname'].'";
			var site_url = "'.conf('url').'";
		//-->
		</script>
		';
			return $ret;
	}

public function globalheader()
{
		global $session_token;
		$msgTitle = (!isset(self::$config['msgtitle'])) ? self::$config['title'] : self::$config['msgtitle'];
		$ret =  '<!DOCTYPE html>
<html lang="en" class="no-js" prefix="og: http://ogp.me/ns#">
<head>';
//Page Title
		if(empty($this->PageTitle)){
			$ret .= '<title>'.conf('pagename').'</title>';
		}
		else
		{
			$ret .= '<title>'.$this->PageTitle.'</title>';
		}

$this->privateheader = $this->PageHeader;
$this->privateheader .= '<link rel="apple-touch-icon" sizes="57x57" href="/icon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/icon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/icon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/icon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/icon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/icon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/icon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/icon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/icon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/icon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
<link rel="manifest" href="/icon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/icon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">'."\n";
$ret .= $this->privateheader;
$ret .= '<META NAME="copyright" content="&copy; '.date('Y').' Rich Media System Co.,Ltd">';
$ret .= '<meta name="Robots" content="all">';
$ret .= '<meta name="revisit-after" content="1 days">';
$ret .='<link rel="stylesheet" type="text/css" href="/library/style.css"  media="screen"  charset="utf-8"/>
<link rel="stylesheet" href="/library/plugin/tipsy/tipsy.css" type="text/css">
<link rel="stylesheet" href="/library/plugin/font-awesome/css/font-awesome.min.css" type="text/css"/>
<script src="/library/jquery-1.9.1.min.js" type="text/javascript"></script>
'."\n";
$ret .= $this->ShareHeader;
$ret .= '<script type="text/javascript" src="/library/jsfunction.js"></script>';
$ret .= '<script type="text/javascript" src="/holder.js"></script>';
$ret .= '<script type="text/javascript" src="/library/plugin/tipsy/jquery.tipsy.js"></script>';
$ret .= '</head>
<body dir="ltr">
<div id="page-wrapper">
			'."\n";
$ret .= $this->page_initial();
return $ret;
}

public function getC($req)
	{
		$data = mysql_query('SELECT value FROM '.conf('table_prefix').'_config WHERE name = "'.$req.'"');
		$export = mysql_fetch_array($data);
		return $export[0];
	}

public function pureheader()
	{
		$ret =  '<!DOCTYPE html>
		<html lang="en" class="no-js" prefix="og: http://ogp.me/ns#">
		<head>';
		if(isset($this->PageHeader))
			$this->privateheader = $this->PageHeader;
		else
			$this->privateheader = '';

$this->privateheader .= '
<link rel="apple-touch-icon" sizes="57x57" href="/icon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/icon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/icon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/icon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/icon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/icon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/icon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/icon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/icon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/icon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
<link rel="manifest" href="/icon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/icon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<link rel="stylesheet" href="/library/plugin/font-awesome/css/font-awesome.min.css" type="text/css"/>'."\n";
$ret .= $this->privateheader;
$ret .= '<META NAME="copyright" content="&copy; '.date('Y').' Rich Media System Co.,Ltd">';
$ret .='<link rel="stylesheet" type="text/css" href="/library/style.css"  media="screen"  charset="utf-8"/>
<script src="/library/jquery-1.9.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/library/jquery.autosize-min.js"></script>'."\n";
$ret .= $this->ShareHeader;
$ret .= '<script type="text/javascript" src="/library/jsfunction.js"></script>';
$ret .= '<script type="text/javascript" src="/holder.js"></script>';    
$ret .= '
</head>
<body dir="ltr">
<div id="page-wrapper">'."\n";
$ret .= $this->page_initial();
return $ret;
}

public function minheader($query=null)
	{
global $session_token;
$msgTitle = (!isset(self::$config['msgtitle'])) ? self::$config['title'] : self::$config['msgtitle'];
$db = new mysql();
$data = $db->get(conf('table_prefix').'_page','title',$query);
$inc = $db->get(conf('table_prefix').'_page','include',$query);

if($data!=null)
$this->title = $data;
$ret =  '<!DOCTYPE html>
<html lang="en" class="no-js" prefix="og: http://ogp.me/ns#">
<head>';

		//Page Title
		if(empty($this->PageTitle)){
			$ret .= '<title>'.$msgTitle.'</title>';
		}
		else
		{
			$ret .= '<title>'.$this->PageTitle.'</title>';
		}
$ret .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'."\n";
	$this->privateheader = $this->PageHeader;
	$this->privateheader .= '<link rel="apple-touch-icon" sizes="57x57" href="/icon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/icon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/icon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/icon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/icon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/icon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/icon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/icon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/icon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/icon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
<link rel="manifest" href="/icon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/icon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" href="/library/plugin/font-awesome/css/font-awesome.min.css" type="text/css"/>'."\n";
$ret .= $this->privateheader;
$ret .= '<META NAME="copyright" content="&copy; '.date('Y').' Rich Media System Co.,Ltd">';
$ret .='<link rel="stylesheet" type="text/css" href="/library/style.css"  media="screen"  charset="utf-8"/>
<script src="/library/jquery-1.9.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/library/jquery.autosize-min.js"></script>'."\n";
$ret .= $this->ShareHeader;
$ret .= '<script type="text/javascript" src="/library/jsfunction.js"></script>';
$ret .= '<script type="text/javascript" src="/holder.js"></script>';
		if(@$_GET['edit']!='body'){
		$ret .= $inc;
		}
//google analytic
$ret .= $this->getC('google-analytics');
$ret .= '</head>
<body dir="ltr">
<div id="page-wrapper">'."\n";
$ret .= $this->page_initial();
return $ret;
}

public function bos($js)
	{
		$ret = '<script type="text/javascript" src="'.$this->libURL.$js.'"></script>';
		return $ret;
	}

public function footer()
	{
		$ret = '</div>'."\n";
		$ret .= '<div id="preview"></div>';
		$ret .= $this->privatefooter;
		$ret .= '</body>'."\n";
		$ret .= '</html>'."\n";
		return $ret;
	}


public function getRole($mode='int')
	{
			$u=UserInfo();
			if($u['role']!='')
				return (int)$u['role'];
			else
				return '';
	}

	public function isAdmin()
	{
			$u=UserInfo();
			if($u['admin']==1)
				return 1;
			else
				return 0;
	}


public function contents($display)
	{
	   return $display;
    }

}

function create_user_dir($name){
	$dir = conf('dir');
	if(!file_exists($dir.'user/'.$name))
	{
		mkdir($dir.'user/'.$name);
		chmod($dir.'user/'.$name,0777);
	}
	else if(!file_exists($dir.'user/'.$name.'/picture'))
	{
		mkdir($dir.'user/'.$name.'/picture');
		chmod($dir.'user/'.$name.'/picture',0777);
	}else if(!file_exists($dir.'user/'.$name.'/upload'))
	{
		mkdir($dir.'user/'.$name.'/upload');
		chmod($dir.'user/'.$name.'/upload',0777);
	}else if(!is_writable($dir.'user/'.$name))
	{
		chmod($dir.'user/'.$name,0777);
	}else if(!is_writable($dir.'user/'.$name.'/picture'))
	{
		chmod($dir.'user/'.$name.'/picture',0777);
	}else if(!is_writable($dir.'user/'.$name.'/upload'))
	{
		chmod($dir.'user/'.$name.'/upload',0777);
	}
}

function graph($url)
{
		$api = conf('url').'l.php?ref=';
		$encode_data = base64_encode($_SESSION['loginid']['nickname'].'|'.$url);
		$new_url = $api.$encode_data;
		return $new_url;
}

function checkforroom($date,$stime,$etime)
	{
		$select = 'select bid from '.conf('table_prefix').'_subject s where
		type = "live"
		and s.date = "'.$date.'"
		and s.stime <= '.$etime.'
		and s.etime >= '.$stime;

		$query = mysql_query($select);
		if($query)
		{
			$n = 0;
			while($res=mysql_fetch_array($query))
			{
				$n++;
			}

			if($n!=0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

function get_apps_info($apps_id, $apps_secr)
	{
		//Get Application info
		$SQL = "SELECT * FROM ".conf('table_prefix')."_apps WHERE application_id = '".$apps_id."' AND app_security = '".$apps_secr."' ";
		$exec = mysql_query($SQL);

		if($exec&&mysql_num_rows($exec)!=0)
			$result = mysql_fetch_array($exec);
		return $result;
	}

function permission_access_validation($application_id,$permission_request)
	{
		$app_info = get_apps_info($application_id);
		if($app_info!=null)
		{
			$permission_exp = explode(',',$app_info['permission']);

			foreach($permission_exp as $permission => $value)
			{
				if($value==$permission_request)
				{
					$access = true;
					break;
				}
			}

			if($access==true)
				return true;
			else
				return false;
		}
		else
			return false;
	}

function access_token_generator($apps_id,$security_key)
	{

		if(!isset($_SESSION['token'])||$_SESSION['token']=='')
		{
			$token = sha1($apps_id.$security_key.conf('auth_key').$_SERVER['REMOTE_ADDR']);
			$sql = mysql_query('INSERT INTO '.conf('table_prefix').'_token (id, app_id, token, timestamp) VALUES(NULL, "'.$apps_id.'", "'.$token.'",'.time().')');

			$_SESSION['token'] = $token;
			return $token;
		}
		else
		{
			return $_SESSION['token'];
		}

	}

function access_token_validation($token_id,$application_id,$request_access)
	{
		$apps_info = get_apps_info($application_id);
		//echo access_token_generator($apps_info['application_id'],$apps_info['app_security']);
		if($token_id==access_token_generator($apps_info['application_id'],$apps_info['app_security']))
		{
			if(permission_access_validation($application_id,$request_access)==true)
				return true;
			else
				return false;
		}
		else
				return false;
	}


?>
