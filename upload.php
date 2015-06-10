<?php
include 'initial.min.php';
$req = $_REQUEST;
//$myinfo = UserInfo();
$key = $req['rq'];

if($req['ft']=='blog_img')
{
	// files storage folder
	$dir = conf('root') . '/user/'.$_SESSION['loginid']['nickname'].'/blog_file/';
	if(!is_dir(conf('root') .  '/user/'.$_SESSION['loginid']['nickname'])) mkdir(conf('root') .  '/user/'.$_SESSION['loginid']['nickname']);
	if(!is_dir($dir)) mkdir($dir);
	$_FILES['file']['type'] = strtolower($_FILES['file']['type']);
			// file type
			switch($_FILES['file']['type'])
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

	if ($_FILES['file']['type'] == 'image/png'
	|| $_FILES['file']['type'] == 'image/jpg'
	|| $_FILES['file']['type'] == 'image/gif'
	|| $_FILES['file']['type'] == 'image/jpeg'
	|| $_FILES['file']['type'] == 'image/pjpeg')
	{
		// setting file's mysterious name
		$file = $dir.md5(date('YmdHis')).'.'.$fex;

		// copying
		copy($_FILES['file']['tmp_name'], $file);
		// displaying file
		$array = array(
			'filelink' => '/user/'.$_SESSION['loginid']['nickname'].'/blog_file/'.md5(date('YmdHis')).'.'.$fex
		);

		echo stripslashes(json_encode($array));
	}
}

if($req['ft']=='blog_file')
{
	// files storage folder
		$dir = conf('root') . '/user/'.$_SESSION['loginid']['nickname'].'/blog_file/';
		if(!is_dir($dir)) mkdir($dir);

		$extension = end(explode('.', $_FILES['file']['name']));
		$file = $dir.md5(date('YmdHis')).'.'.$extension;

		copy($_FILES['file']['tmp_name'], $file);
		$array = array(
			'filelink' => '/user/'.$_SESSION['loginid']['nickname'].'/blog_file/'.md5(date('YmdHis')).'.'.$extension,
			'filename' => $_FILES['file']['name']
		);
		echo stripslashes(json_encode($array));
}
?>