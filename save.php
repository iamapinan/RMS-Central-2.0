<?php

include 'initial.min.php';
include 'crXml.php';

$req = $_REQUEST;
$user = $_SESSION['loginid']['nickname'];
$u = UserInfo();
$uid = $u['ssid'];
$status = ($req['status']==1) ? 'public' : 'private';

//Check is login.
if(!isset($_SESSION['loginid'])) header('location: /404.php');

//Delete post
if(@$req['q']=='delete')
{
	if(!empty($req['id']))
	{
		$SQL = 'SELECT req_id FROM '.conf('table_prefix').'_subject  WHERE bid='.$req['id'];
		$EXEC = mysql_query($SQL);
		$res = mysql_fetch_array($EXEC);
		$reqid = $res['req_id'];

		if($SQL){
			$SQL = 'DELETE FROM '.conf('table_prefix').'_subject  WHERE bid='.$req['id'];
			$EXEC =mysql_query($SQL);

			$dir = conf('root').'/data/content/'.$reqid;

			if(is_dir($dir)){
			//Remove contents
				foreach (@scandir($dir) as $item) {
					if ($item == '.' || $item == '..') continue;
							@unlink($dir.DIRECTORY_SEPARATOR.$item);
				}
				@rmdir($dir);
			}

			//Assign result.
			if($EXEC)
				$ret['result'] = 'success';
			else
				$ret['result'] = 'error';
		}
	}
	echo json_encode($ret); //Return
}

if($_POST['q']=='settings')
{
	if($_POST['tp'] == 'verify') $chg = 'auto_verify';
	if($_POST['tp'] == 'prove') $chg = 'auto_approve';
	if($_POST['tp'] == 'auto-update') $chg = 'auto_update';
	if($_POST['tp'] == 'auto-studio') $chg = 'studio';
	if($_POST['tp'] == 'auto-conference') $chg = 'conference';
	if($_POST['tp'] == 'auto-live') $chg = 'live';
	if($_POST['tp'] == 'auto-blog') $chg = 'blog';
	if($_POST['tp'] == 'devmode') $chg = 'devmode';

	$sql  =mysql_query('UPDATE '.conf('table_prefix').'_config SET `value`='.$_POST['set'].' WHERE `name`="'.$chg.'"') || die(mysql_error());
}

//status_update
if(@$req['q']=='status_update')
{
	$timestamp = time();
	$newSubject = null;
	$newDesc = mysql_real_escape_string(nl2br(htmlspecialchars($req['content'])));
	$newDesc = preg_replace("/(http:\/\/[^\s]+)/", "<a href=\"$1\">$1</a>", $newDesc);

	if(@$req['post_on']!='')
	{
		$post = $req['post_on'];
		$type= 'via';
	}
	else
	{
		$post = 'NULL';
		$type = 'status';
	}

	$insert_update_post = "INSERT INTO ".conf('table_prefix')."_subject (`bid`,`post_to`, `username`, `subject`, `desc`,  `image`, `group`, `timestamp`, `approve`, `filetype`, `open`) VALUES(NULL,".$post.",'".$user."','".$newSubject."','".$newDesc."','".$save_img."',9,".$timestamp.",'yes','".$type."',1)";

	//echo $insert_update_post;
	$insert = mysql_query($insert_update_post);
	$q = 'bid='.mysql_insert_id().' AND ';

	//Get stream
	$return['update'] = ($insert) ? $show->getStream(1,$q) : 'false';

   echo json_encode($return);
}

//Update Like
if(@$req['q']=='like')
{
	/* =======Parameter=======
	* $req['set'] = bid
	* $req['q']=='like'
	*
	*/
	//Checking if you are like this post
	$chliked = mysql_query("SELECT * FROM ".conf('table_prefix')."_like l WHERE l.uid=$uid AND l.sid=".$req['set']);
	$chval = mysql_fetch_array($chliked);
	//echo $chval['id'];
	if($chval['id']!=NULL)
		{
			$return['value'] = 'false';
			echo json_encode($return);
			exit();
		}

	//Check last liked.
	$chCMD = mysql_query("SELECT id FROM ".conf('table_prefix')."_like l WHERE l.sid=".$req['set']);
	$last = mysql_num_rows($chCMD);

	$last = $last+1;

	$command = mysql_query("UPDATE  ".conf('table_prefix')."_subject s SET  s.like =  ".$last." ,s.open = 1  WHERE  s.bid =".$req['set']);

	$timestamp = time();
	$insert_update_post = "INSERT INTO ".conf('table_prefix')."_like (`id`, `uid`, `sid`, `timestamp`) VALUES(NULL,".$uid.",".$req['set'].",".$timestamp.")";
	$insert = mysql_query($insert_update_post);

	if($insert)
		$return['value'] = $last;
	else
		$return['value'] = 'false';

	echo json_encode($return);
}

if($req['action']=='upload_user_list')
{
	$ret['success'] = 1;
	$key = $p->randomstr(15);
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$fin = pathinfo($_FILES['Filedata']['name']);
	$save_path = conf('dir').'temp/'.$key.'.'.$fin['extension'];
	move_uploaded_file($tempFile,$save_path);
	$user_data = excel_reader($key.'.'.$fin['extension']);

	if($user_data!=false&&$user_data['title'][1]=='fullname')
	{

		$qr = 'INSERT INTO '.conf('table_prefix').'_profile (`user`, `provider`, `password`,  `fullname`, `email`, `role`, `org`, `gender`, `language`, `bday`, `mobile`, `grade`, `class`, `position`, `status`, `verify`, `update`, `admin`, `citizen_id`, `amid`) VALUES';
		if(count($user_data['body'])>=1)
		{
			for($m==1;$m<=count($user_data['body'])-1;$m++)
			{
				include_once('core/class.am.php');
				$idm = new IDM2API;

				if($_REQUEST['debug']==1)
				{
					$idm->debug = 1;
				}
				$callam = $idm->create_user($user_data['body'][$m][11], $user_data['body'][$m][3], str_replace(' ', '|', $user_data['body'][$m][1]), $user_data['body'][$m][4], conf('schoolid')); //Call to API

				//$arr[$m]['degug'] = $idm->idm_debug();
				$arr[$m]['name'] = $user_data['body'][$m][1];
				$arr[$m]['user'] = $user_data['body'][$m][11];
				$arr[$m]['email'] = $user_data['body'][$m][2];
				$arr[$m]['password'] = $user_data['body'][$m][3];
				$arr[$m]['status'] = 1;
				$user_data['body'][$m][1] = str_replace(' ', '|', $user_data['body'][$m][1]);
				$chkdb = UserInfo($user_data['body'][$m][11]);
				if($chkdb['user']!='') continue;
				if($user_data['body'][$m][2]=='') continue;

				$qr .= '
				("'.$user_data['body'][$m][11].'","bll","'.openssl_encrypt($user_data['body'][$m][3], 'aes128', '').'","'.$user_data['body'][$m][1].'","'.$user_data['body'][$m][2].'",'.$user_data['body'][$m][4].',"'.conf('orgid').'","'.$user_data['body'][$m][10].'","th","'.$user_data['body'][$m][7].'","'.$user_data['body'][$m][9].'",'.$user_data['body'][$m][5].','.$user_data['body'][$m][6].',"'.$user_data['body'][$m][8].'",1,1,0,'.$user_data['body'][$m][12].',"'.$user_data['body'][$m][11].'","'.$amid.'")';
				if($m<count($user_data['body'])-1) $qr .= ',';
			}
		}
		$query = mysql_query($qr);

		if($query){
			$ret['success'] = 1;
			$ret['maxuser'] = count($user_data['body'])-1;
			$ret['user'] = $arr;
		}
	}
	echo json_encode($ret);
}

if($req['action']=='userbanner')
{
	$key = $p->randomstr(15);
	$tempFile = $_FILES['banneruploader']['tmp_name'];
	$filename = $_FILES['banneruploader']['name']; // Get the name of the file (including file extension).
	$ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
	$ext = strtolower($ext);
	$extentsion = str_replace('.','',$ext);
	$ret['error'] = 0;

	if($filename=='') {
		$ret['error'] = 1;
		$ret['msg'] = 'ประสบปัญหาในการอัพโหลด กรุณาลองใหม่';
		echo json_encode($ret);
		exit(0);
	}
	else
	{
		$ims = getimagesize($tempFile);
		if($ims[0]>=1024&&$ims[1]>=220)
		{
			if($extentsion=='jpg'||$extentsion=='png'||$extentsion=='bmp')
			{
				$contents_path = conf('root') . '/user/'.$u['user'].'/gallery';
				if(!is_dir($contents_path)) mkdir($contents_path, 0777);

				$contents_path = conf('root') . '/user/'.$u['user'].'/gallery/banner';
				if(!is_dir($contents_path)) mkdir($contents_path, 0777);


				$save_path = $contents_path.'/'.$key.$ext;

				@move_uploaded_file($tempFile,$save_path);
				if(file_exists($save_path))
				{
					$cmd = 'UPDATE '.conf('table_prefix').'_profile SET `banner`=\''.$key.$ext.'\', `update`=0 WHERE `user` = "'.$u['user'].'"';
					$exec = mysql_query($cmd);

					if($exec==true) {
						$ret['error'] = 0;
						$ret['img'] = $key.$ext;
						echo json_encode($ret);
					}
					else
					{
						$ret['error'] = 5;
						$ret['msg'] = 'ไม่สามารถบันทึกข้อมูลได้โปรดลองใหม่ภายหลัง';
						unlink($save_path);
						 echo json_encode($ret);
						exit(0);
					}

				}else
					 {
						$ret['error'] = 4;
						$ret['msg'] = 'ไม่สามารถอัพโหลดไฟล์ได้ โปรดลองใหม่';
						echo json_encode($ret);
						exit(0);
					}
			}else
				{
					$ret['error'] = 3;
					$ret['msg'] = 'ไฟล์ที่อัพโหลดไม่ถูกต้อง ซึ่งจะต้องเป็นไฟล์ jpg, png หรือ bmp เท่านั้น';
					echo json_encode($ret);
					exit(0);
				}
		}else
		{
			$ret['error'] = 2;
			$ret['msg'] = 'ขนาดไฟล์เล็กเกินไป ขนาดไฟล์ควรมีความกว้าง 1024px สูง 220px ขึ้นไป';
			echo json_encode($ret);
			exit(0);
		}
	}
	//Return output.

}

if($req['action']=='neworg')
{
	$rnid = '8888'.rand('111111','999999');
	$ret['redirect'] = $_SESSION['redirect_to'].'?auth='.$_SESSION['loginid']['nickname'];

	if($req['orgname']==''||$req['province']==''||$req['cname']==''||$req['mobile']==''||$req['email']=='')
	{
		$ret['res']=0;
	}
	else
	{
		$sql = "INSERT INTO `".conf('table_prefix')."_school` (`sid`, `sname`, `tambon`, `ampur`, `province`, `divisionname`, `c_name`, `c_mob`, `c_phone`, `c_email`, `status`) VALUES ($rnid, '".$req['orgname']."', '".$req['district']."', '".$req['area']."', '".$req['province']."', '".$req['division']."', '".$req['cname']."', '".$req['mobile']."', '".$req['phone']."', '".$req['email']."', '0')";
		$query = mysql_query($sql);

		if($query)
		{
			$strSQL = "UPDATE ".conf('table_prefix')."_profile ";
			$strSQL .="SET school = '".$rnid."' WHERE user = '".$_SESSION['loginid']['nickname']."' ";
			$objQuery = mysql_query($strSQL);

			$ret['res']=1;
		}

	}
	echo json_encode($ret);
}

if(@$req['q']=='blog')
{

	$key = $req['key'];
	$contents_path = conf('root').'/data/content/'.$key;
	if(!is_dir($contents_path)) mkdir($contents_path, 0777);
 //Update to db.


			$level = (int)$req['lev'];
			$group = (int)$req['gp'];
			$req['title'] = mysql_real_escape_string($req['title']);
			$catcount = count($_POST['category']);
			for($c=0;$c<$catcount;$c++){
				if(($catcount-1)==($c)) $comma = ''; else $comma = ',';
				$category .= $_POST['category'][$c].$comma;
			}

			//Find Image
			$image = get_images($req['content']);
			$image_count = count($image);

			if($image_count>=1)
			{
				$ImageInfo = pathinfo($image[0]);
				$imagex = 'image.'.$ImageInfo['extension'];
				if(!copy($image[0], $contents_path.'/'.$imagex)) $imagex = '';
			}

			$styling = '<link rel="stylesheet" type="text/css" href="/library/blogstyle.css" charset="utf-8"/>';
			$req['content'] = '
			<!DOCTYPE html>
			<html lang="en" class="no-js">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			'.$styling.'
			</head>
			<body>
			'.$req['content'].'
			</body>
			</html>';
  $req['allwpb'] = ($req['allwpb']!=1) ? '' : 'public';

  if($req['edit']!='')
	{
		//echo 'UPDATE '.conf('table_prefix').'_subject SET `subject`="'.$req['title'].'", `tag`="'.$req['keyword'].'", `group`='.$group.', `level`='.$level.' WHERE `req_id`="'.$key.'"';
		file_put_contents($contents_path.'/'.$key.'.html', $req['content']);
		$sql = mysql_query('UPDATE '.conf('table_prefix').'_subject SET `subject`="'.$req['title'].'", `desc`="'.mysql_real_escape_string($category).'", `image`="'.$imagex.'", `tag`="'.$req['keyword'].'", `group`='.$group.', `level`='.$level.', `status`="'.$req['allwpb'].'" WHERE `req_id`="'.$key.'"') || die(mysql_error());

		if($sql){
				$ret['result'] = 1;
				$ret['pid'] = $req['key'];
		}

	}
 else
	{
		if($req['title']!=''&&$req['content']!='')
		{
			//Write file
			if(file_put_contents($contents_path.'/'.$key.'.html', $req['content']))
			{
				//Check files.
				$sql = mysql_query('SELECT req_id FROM '.conf('table_prefix').'_subject WHERE req_id="'.$key.'"');
				$srl = mysql_fetch_array($sql);
				if($srl['req_id']=='')
					$cmd = 'INSERT INTO '.conf('table_prefix').'_subject (`bid`,`username`,`subject`,`desc`,`req_id`,`image`,`group`,`access_role`,`timestamp`,`approve`,`level`,`filetype`,`tag`, `status`) VALUES(NULL,"'.$u['user'].'","'.$req['title'].'","'.mysql_real_escape_string($category).'","'.$key.'","'.$imagex.'",'.$group.',0,'.time().',"yes",'.$level.',"blog","'.$req['keyword'].'","'.$req['allwpb'].'")';
				else
					$cmd = 'UPDATE '.conf('table_prefix').'_subject SET `desc`="'.mysql_real_escape_string($category).'", `level`='.$level.', `group`='.$group.', `timestamp`='.time().' WHERE `req_id`="'.$key.'"';

				//echo $cmd;
				$do = mysql_query($cmd);
				if($do)
				{
					$ret['result'] = 1;
					$ret['pid'] = $key;
				}
				else
				{
					$ret['result'] = 0;
				}

			}
		}
	}
	echo json_encode($ret);
}

if($_REQUEST['siteconfig']=='sysupdate')
	{

		switch($_REQUEST['q'])
		{
			case 'footer':
				$sqlsave = "UPDATE ".conf('table_prefix')."_config SET value='".mysql_real_escape_string($_POST['data'])."' WHERE name='footer_text'";

				if(mysql_query($sqlsave))
					echo 1;
				else
					echo 0;
				break;
			case 'banner':
					$dir = conf('root') . '/user/'.$_SESSION['loginid']['nickname'].'/';
					$_FILES['Filedata']['type'] = strtolower($_FILES['Filedata']['type']);
							// file type
							switch($_FILES['Filedata']['type'])
							{
								case 'image/jpeg':
									$fex = 'jpg';
								break;
								case 'image/png':
									$fex = 'png';
								break;
								case 'image/gif':
									$fex = 'gif';
								break;
								default:
									$fex = 'jpg';
								break;
							}

					if ($_FILES['Filedata']['type'] == 'image/png'
					|| $_FILES['Filedata']['type'] == 'image/jpg'
					|| $_FILES['Filedata']['type'] == 'image/gif'
					|| $_FILES['Filedata']['type'] == 'image/jpeg'
					|| $_FILES['Filedata']['type'] == 'image/pjpeg')
					{
						// setting file's mysterious name
						$filename = md5(date('YmdHis')).'_bn.'.$fex;
						$file = $dir.$filename;

						// copying
						if(copy($_FILES['Filedata']['tmp_name'], $file)){
							$sqlsave = "UPDATE ".conf('table_prefix')."_config SET value='<div id=\"webbn\"><img src=\"/image?width=980&height=110&cropratio=10:1&image=/user/".$_SESSION['loginid']['nickname']."/$filename\"></div>' WHERE name='header'";

							if(mysql_query($sqlsave))
								echo 1;
							else
								echo 'save_error';
						}
						else
							echo 'upload_error';

					}
					else
						echo 'type';
				break;
		}
	}

if($_REQUEST['q']=='acm-update')
{
	$_REQUEST['public'] = ($_REQUEST['public']!=1) ? 0 : 1;
	$update = "UPDATE `".conf('table_prefix')."_subject` SET `subject`='".$_REQUEST['title']."', `desc`='".$_REQUEST['fileinfo']."', `open`=".$_REQUEST['public']." WHERE `req_id`='".$_REQUEST['id']."'";
	if(mysql_query($update))
	{
		echo '<br><br><p align="center"><img src="/library/icon/checkmark.png"> <font color="#33cc00">บันทึกเรียบร้อย โปรดรอสักครู่ หรือ<a href="/my?param=contents">คลิกที่นี่</a> เพื่อดำเนินการต่อทันที</font>';
		header("refresh:5; url=/my?param=contents");
	}else{
		echo '<br><br><p align="center"><img src="/library/icon/warning.png"> <font color="#ff6633">Error 4: ไม่สามารถบันทึกได้ในขณะนี้<a href="/my?param=contents">คลิกที่นี่</a> เพื่อลองอีกครั้ง</font>';
	}
}

if($_REQUEST['q']=='classroom_initial_insert')
{
    //request data = class 1-n , schooltype
    //Build Query
    echo 'Processing...';
    $check_school_type = mysql_fetch_array(mysql_query("select stype from tc_school where sid=".$u['org']));
    if($check_school_type['stype']!=0)
    {
        echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/my?param=class">';
        exit(200);
    }
    $stype = mysql_query("select * from tc_school_type where `stid` = ".$_REQUEST['schooltype']);
    $stype_data = mysql_fetch_array($stype);
    
    $initial_command = "insert into tc_classroom (`clsid`, `grade`, `cls_number`, `sid`, `uid`, `title`, `class_type`, `auto_approved`) values";
    for($c = $stype_data['min_grade'];$c<=$stype_data['max_grade'];$c++){
        for($n=1;$n<=$_REQUEST['class_'.$c];$n++){
        
            if($c<=6) $class_title = 'ประถมศึกษา '.$c.'/'.$n;
            if($c>=7&&$c<=12) $class_title = 'มัธยมศึกษาปีที่ '.($c-6).'/'.$n;
            
            $initial_command .= "(NULL, $c, $n, ".$u['org'].", ".$u['ssid'].", '$class_title', 'standard', 0)";
            if($c==$stype_data['max_grade']&&$n==$_REQUEST['class_'.$c]) $initial_command .= ';';
            else $initial_command .= ',';
        }
    }
    //Set school type.
    $set_school = "update tc_school set `stype` = ".$stype_data['stid']." where `sid`=".$u['org'];
    $q1 = mysql_query($initial_command);
    $q2 = mysql_query($set_school);
    
    //Classroom register
    $std_info = mysql_query("select `class`, `grade`, `org`, `ssid` from tc_profile where `org`=".$u['org']." and (`role`=2 or `role`=3)");
    $sql_register = "insert into tc_class_register (`register_id`,`class_id`,`uid`,`status`,`timestamp`) values";
    $s = 1;
    $num = mysql_num_rows($std_info);
    while($std_data = mysql_fetch_array($std_info)){

        
        $cls_info = mysql_fetch_array(mysql_query("select `clsid` from tc_classroom where `sid`=".$std_data['org']." and `grade`=".$std_data['grade']." and `cls_number`=".$std_data['class']));

        
        $sql_register .= "(NULL, ".$cls_info['clsid'].", ".$std_data['ssid'].",1,".time().")";
        
        if($s==$num) $sql_register .= ';';
        else $sql_register .= ',';
        
    }
    //echo $sql_register;
    $q3 = mysql_query($sql_register);
   
   if($q1&&$q2)
        echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/profile/'.$u['user'].'?user=class">';
    else
        echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/profile/'.$u['user'].'?user=class&error=save_fail">';
    
}

if($_REQUEST['q']=='classroom_single_insert')
{
    echo 'Processing...';
    $image = 'NULL';
    $destination = conf('root') . '/user/'.$_SESSION['loginid']['nickname'].'/';
    $fileinfo = explode('/',$_FILES['class_image']['type']);
    $new_image_name = md5($_FILES['class_image']['name']).'.'.$fileinfo[1];
    $upload = move_uploaded_file($_FILES['class_image']['tmp_name'], $destination.$new_image_name);
    
    if($upload) $image = '/user/'.$_SESSION['loginid']['nickname'].'/'.$new_image_name;
    else echo '<br>Upload fail '.$new_image_name;
    
    //request data = allow_public, class_name, class_detail, class_image, 
    //Build Query

    //create classroom
    $initial_command = "insert into tc_classroom (`clsid`, `grade`, `cls_number`, `sid`, `uid`, `title`,`detail`, `class_type`, `image`, `auto_approved`, `public`, `teacher`) values";
     
    $initial_command .= "(NULL, 0, 0, ".$u['org'].", ".$u['ssid'].", '".mysql_real_escape_string($_REQUEST['class_name'])."', '".mysql_real_escape_string($_REQUEST['class_detail'])."', 'special', '$image', 0, ".$_REQUEST['allow_public'].", '".json_encode($_REQUEST['member_group'])."');";
    
    //echo $initial_command;
    $q1 = mysql_query($initial_command);
    $insertid = mysql_insert_id();
    $sql_register = "insert into tc_class_register (`register_id`,`class_id`,`uid`,`status`,`timestamp`, `role`) values";
    $member_count = count($_REQUEST['member_group']);
    for($s = 0;$s<$member_count;$s++)
    {
        //echo $_REQUEST['member_group'][$s];
        $sql_register .= "(NULL, $insertid, ".$_REQUEST['member_group'][$s].",1,".time().",2)";
        
        if($s==($member_count-1)) $sql_register .= ';';
        else $sql_register .= ',';
    }
    //echo $sql_register;
    $q2 = mysql_query($sql_register);
    
    if($q1&&$q2){
        echo '<META HTTP-EQUIV="Refresh" CONTENT="2;URL=/profile/'.$u['user'].'?user=class">';
        echo '<br>Succcess';
    }
    else{
        echo '<META HTTP-EQUIV="Refresh" CONTENT="2;URL=/profile/'.$u['user'].'?user=class&error=save_fail">';
        echo '<br>fail!';
    }

}

if($_REQUEST['q']=='classroom_single_update')
{
    //request data = class 1-n , schooltype, member_group[]
    //Build Query


}

if($_REQUEST['q']=='group_single_insert')
{
	echo 'Processing...';
    $image = 'NULL';
    $destination = conf('root') . '/user/'.$_SESSION['loginid']['nickname'].'/';
    $fileinfo = explode('/',$_FILES['group_image']['type']);
    $new_image_name = md5($_FILES['group_image']['name']).'.'.$fileinfo[1];

    $upload = move_uploaded_file($_FILES['group_image']['tmp_name'], $destination.$new_image_name);
    if($upload) $image = '/user/'.$_SESSION['loginid']['nickname'].'/'.$new_image_name;
    else echo '<br>Upload fail '.$new_image_name;

    //Build Query
	$sid = ($_POST['group_type']==1) ? 'NULL' : $client['org'];
	$strSQL = "INSERT INTO `tc_social_group`(`sgid`, `name`, `logo`, `url`, `timestamp`, `sid`, `admin`, `auto_approved`, `banner`, `type`) 
	VALUES (NULL,'".mysql_real_escape_string($_POST['group_name'])."','$image','".substr(md5($_POST['group_name']),0,10)."','".time()."',$sid,'".$client['user']."',
		".$_POST['allow_public'].",NULL,".$_POST['group_type'].")";

	$query = mysql_query($strSQL) || die(mysql_error());

	$insertid = mysql_insert_id();
    $sql_register = "INSERT INTO `tc_group_register`(`gid`, `uid`, `status`, `timestamp`, `id`, `role`) VALUES";
    $member_count = count($_REQUEST['member_group']);
    for($s = 0;$s<$member_count;$s++)
    {
        //echo $_REQUEST['member_group'][$s];
        $sql_register .= "($insertid, ".$_REQUEST['member_group'][$s].",1,".time().",NULL, 1)";
        
        if($s==($member_count-1)) $sql_register .= ';';
        else $sql_register .= ',';
    }
 
    $q2 = mysql_query($sql_register);

    
    if($query){
        echo '<META HTTP-EQUIV="Refresh" CONTENT="2;URL=/profile/'.$u['user'].'?user=group">';
        echo 'Success';
    }
    else{
        echo '<META HTTP-EQUIV="Refresh" CONTENT="2;URL=/profile/'.$u['user'].'?user=group&error=save_fail">';
        echo 'fail';
    }

}

if($_REQUEST['q']=='course_save')
{
    echo 'Processing...';
    $image = 'NULL';
    $destination = conf('root') . '/user/'.$_SESSION['loginid']['nickname'].'/';
    $fileinfo = explode('/',$_FILES['course_image']['type']);
    $new_image_name = md5($_FILES['course_image']['name']).'.'.$fileinfo[1];
    $upload = move_uploaded_file($_FILES['course_image']['tmp_name'], $destination.$new_image_name);
    
    if($upload) $image = '/user/'.$_SESSION['loginid']['nickname'].'/'.$new_image_name;
    else echo '<br>Upload fail '.$new_image_name;
    $_REQUEST['group'] = ($_REQUEST['group']=='') ? 'NULL' : $_REQUEST['group'];
    $_REQUEST['grade'] = ($_REQUEST['grade']=='') ? 'NULL' : $_REQUEST['grade'];
    
    $sqlcode = "INSERT INTO tc_course (course_id, uid, cname, cdetail, img, sid, type, public, main_group, alternet_teacher_id,class_id) VALUES
    (NULL, ".$u['ssid'].", '".$_REQUEST['course_name']."','".mysql_real_escape_string($_REQUEST['course_detail'])."',
    '".$image."',".$u['org'].",'".$_REQUEST['course_type']."',".$_REQUEST['allow_public'].",".$_REQUEST['group'].",'".json_encode($_POST['member_group'])."','".$_POST['grade']."');";
  
    
    $query = mysql_query($sqlcode) || die(mysql_error());
    
    $insertid = mysql_insert_id();
    $sql_register = "INSERT INTO `tc_course_register`(`course_id`, `uid`, `status`, `timestamp`, `role`, `id`) VALUES";
    $member_count = count($_REQUEST['member_group']);
    for($s = 0;$s<$member_count;$s++)
    {
        //echo $_REQUEST['member_group'][$s];
        $sql_register .= "($insertid, ".$_REQUEST['member_group'][$s].",1,".time().", 2,NULL)";
        
        if($s==($member_count-1)) $sql_register .= ';';
        else $sql_register .= ',';
    }
 
    $q2 = mysql_query($sql_register);

    if($query){
        echo '<META HTTP-EQUIV="Refresh" CONTENT="2;URL=/profile/'.$u['user'].'?user=course">';
        echo 'Success';
    }
    else{
        echo '<META HTTP-EQUIV="Refresh" CONTENT="2;URL=/profile/'.$u['user'].'?user=course&error=save_fail">';
        echo 'fail';
    }
    
}

?>