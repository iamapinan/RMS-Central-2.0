<?php
include 'initial.min.php';

$stime = time();
$userid = UserInfo();
$uid = $userid['ssid'];
$setting = 'background='.$_POST['setting_bg'].';fullpage=1;left=0;right=0;banner='.$_POST['setting_banner'].';ribbon='.$_POST['setting_ribbon'].';footer='.$_POST['setting_footer'];
if(isset($_POST['action'])&&$_POST['action']=='newpage')
{
	if($_POST['title']==''||$_POST['body']=='')
	{ $save = false; }
	else
	{
	$url = str_replace(' ','_',strtolower($_POST['url']));

	$q = 'INSERT INTO `'.conf('table_prefix').'_page`(`id`, `tid`, `uid`, `title`, `url`, `body`, `include`, `option`, `image`, `access_role`, `timestamp`) 
	VALUES (NULL,0,'.$uid.',"'.mysql_real_escape_string($_POST['title']).'","'.str_replace('.','',$url).'","'.mysql_real_escape_string($_POST['body']).'","'.mysql_real_escape_string($_POST['include']).'","'.mysql_real_escape_string($setting).'",NULL,'.$_POST['role'].',"'.$stime.'")';
	$save = mysql_query($q) || die(mysql_error());
	$result['url'] = mysql_insert_id();

	file_put_contents(conf('dir').'cache/page/'.$result['url'].'.header', $_POST['include']);
	file_put_contents(conf('dir').'cache/page/'.$result['url'].'.php', $_POST['body']);
	}
}
else
{
	file_put_contents(conf('dir').'cache/page/'.$_POST['page'].'.header', $_POST['script']);
	file_put_contents(conf('dir').'cache/page/'.$_POST['page'].'.php', $_POST['code']);

	$save = mysql_query('UPDATE `'.conf('table_prefix').'_page` SET 
		`option`="'.mysql_real_escape_string($_POST['pconfig']).'", 
		`access_role`="'.$_POST['prole'].'", 
		`title`="'.$_POST['ptitle'].'", 
		`body` = "'.mysql_real_escape_string($_POST['code']).'" , 
		`include` = "'.mysql_real_escape_string($_POST['script']).'" 
		WHERE `id` ='.$_POST['page']) || die(mysql_error());
}

$result['stat']=$save?1:0;


echo json_encode($result);


function eval_php($str=null,$prefix=NULL,$postfix=NULL) {
	if (!isset($str)) return false;
	ob_start();
	eval('?>'.$str);
	return $prefix.ob_get_clean().$postfix;
}

?>