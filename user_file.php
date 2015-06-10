<?php
include 'initial.min.php';

$folder_path = 'user/'.$_SESSION['loginid']['nickname'].'/blog_file/';
if ($handle = opendir($folder_path)) {

	$x = 0;
    while (false !== ($entry = readdir($handle))) {
		$extension = end(explode('.', $entry));
		$imagef = array('jpg','gif','bmp','png','svg','ico');
		if(!in_array($extension, $imagef)) continue;
        if($entry=='.'||$entry=='..') continue;
		$f = explode('.', $entry);
		$file[$x]['thumb'] = '/image?width=100&height=100&image=/'.$folder_path.$entry;
		$file[$x]['image'] = '/'.$folder_path.$entry;
		$file[$x]['title'] = $f[0];
		$file[$x]['folder'] = 'Documents';
		$x++;
    }
    closedir($handle);

	echo json_encode($file);

}
?>