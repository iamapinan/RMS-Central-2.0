<?php
header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin: *');
include 'initial.min.php';
$req = @$_REQUEST;

if($req['g'] == 'checkuser')
{
	$cmd = mysql_query('SELECT user FROM '.conf('table_prefix').'_profile WHERE citizen_id="'.$req['data'].'"');
	$res = mysql_fetch_array($cmd);
	//$check_online = json_decode(file_get_contents('https://rms.bll.in.th/access?user='.$req['data']));
	/*
	if($res['user']!=''||$check_online->user!='')
		echo '{"result":"1"}';
	else
		echo '{"result":"0"}';
	*/
	if($res['user']!='')
		echo '{"result":"1"}';
	else
		echo '{"result":"0"}';
}

if($req['g'] == 'listsubcat')
{

	$cmd = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE type=0 AND catid='.$req['id']);
	$resp = '<br><h3>หมวดหมู่ย่อย</h3>';
	$x='';
	while($res = mysql_fetch_array($cmd))
	{
		if($res['type']==0){
			$resp .= '<h3 align="left">'.$res['name'].'</h3><div class="subx">';
			$subcatq = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE mainsub="'.$res['scid'].'" AND type!=0 AND catid!=21 AND catid="'.$req['id'].'"');
			while($ress = mysql_fetch_array($subcatq)){
				if($shcat[$ress['scid']]==1) $chked = 'checked'; else $chked = '';
				$resp .= '<span class="choice"><input type="checkbox" value="'.$ress['scid'].'" name="category[]" '.$chked.'> '.$ress['name'].' </span>&nbsp;';
				$x++;
			}
			$resp .= '</div>';
		}

	}
	if($x == '') $resp = 1;
	echo $resp;
}

if($req['g'] == 'listoption')
{

	$cmd = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE type=0 AND catid='.$req['id']);
	$resp = '<select name="sc">';
	$x='';
	while($res = mysql_fetch_array($cmd))
	{
		if($res['type']==0){
			$subcatq = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE mainsub="'.$res['scid'].'" AND type!=0 AND catid!=21 AND catid="'.$req['id'].'"');
			while($ress = mysql_fetch_array($subcatq)){
				$resp .= '<option value="'.$ress['scid'].'"> '.$ress['name'].'</option>';
				$x++;
			}
		}

	}
	$resp .= '</select>&nbsp;';
	if($x == '') $resp = 1;
	echo $resp;
}

if($req['g'] == 'post_data')
{
	$sqlx = mysql_query('select * from '.conf('table_prefix').'_subject where `desc` LIKE  \'%'.$req['_id'].'%\' ');
	$n=0;
	while($op = mysql_fetch_array($sqlx, MYSQL_ASSOC))
	{
		$exp = explode(',',$op['desc']);
		if(in_array($req['_id'], $exp)){
			$res[$n] = '<li class="news-items main-c list-'.$req['_id'].'"><a href="/data/content/'.$op['req_id'].'/'.$op['req_id'].'.html&iframe=true&width=800&height=500" rel="prettyPhoto[iframe]"> #Q'.$op['bid'].' '.$op['subject'].'</a></li>';
			$n++;
		}
	}
	echo json_encode($res);
}
?>