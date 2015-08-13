<?php
$align[$_GET['align']] = 'selected';
$select[$_GET['gp']] = 'selected';
$sc[$_GET['sc']] = 'selected';

if(isset($_GET['align'])){
	$align[$_GET['align']] = 'selected';
	switch($_GET['align'])
	{
		case 0:
		$aqr = 'subject DESC';
		break;
		case 1:
		$aqr = 'bid ASC';
		break;
		case 2:
		$aqr = 'bid DESC';
		break;
		case 3:
		$aqr = '`group` DESC';
		break;
		default:
		$aqr = 'bid DESC';
		break;
	}
}else{
	$aqr = 'bid DESC';
}

if(isset($_GET['sc'])) {

	$cmd = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE type=0 AND catid='.$_GET['gp']);
	$resp = '<select name="sc">';
	$x='';
	while($res = mysql_fetch_array($cmd))
	{
		if($res['type']==0){
			$subcatq = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE mainsub="'.$res['scid'].'" AND type!=0 AND catid!=21 AND catid='.$_GET['gp']);
			while($ress = mysql_fetch_array($subcatq)){
				$resp .= '<option value="'.$ress['scid'].'" '.$sc[$ress['scid']].'> '.$ress['name'].'</option>';
				$x++;
			}
		}

	}
	$resp .= '</select>&nbsp;';
}
echo '<div id="sharedlg">
	<h2 class="title">Share url.</h2>
	<div class="urlcontainer"></div>
	<br>
	<h2 class="title">Embed code.</h2>
	<div class="embedcontainer" contenteditable="true"></div>
	<br><hr><br>
	<p align="right"><span class="button" onclick="$(\'#sharedlg\').slideUp(200);"><i class="fa fa-times"></i> Close</span></p>
</div>';

echo '<div class="toolbox">
<form method="get"><input type="text" name="kw" placeholder="คำค้น" value="'.$_GET['kw'].'"> <select id="gp" data-type="1" name="gp" onchange="getsub($(this).val(),$(this).attr(\'data-type\'));$(this).attr(\'style\',\'background-color: #fff !important\');">
<option value="" selected>ดูทั้งหมด</option>';
$sqlx = mysql_query('select * from '.conf('table_prefix').'_group order by gid asc');
while($gm=mysql_fetch_array($sqlx))
{
	if($gm['gid']<18)  echo '<option value="'.$gm['gid'].'" '.$select[$gm['gid']].' >'.$gm['name'].'</option>';
	if(($client['role']==8||$client['admin']==1)&&($gm['gid']>=18)) 
	echo '<option value="'.$gm['gid'].'" '.$select[$gm['gid']].' class="option-color-secret">'.$gm['name'].'</option>';
}
echo '</select> <div id="choice">'.$resp.'</div>
	<select name="align">
		<option value="no" '.$align['no'].'  class="option-color-disable " disabled>รูปแบบการจัดเรียง</option>
		<option value="0" '.$align[0].'>เรียงตามตัวอักษร</option>
		<option value="1" '.$align[1].'>เรียงตามเก่า-ใหม่</option>
		<option value="2" '.$align[2].'>เรียงตามใหม่-เก่า</option>
		<option value="3" '.$align[3].'>เรียงตามหมวดหมู่</option>
	</select>
<input type="submit" value="ค้นหาเลย" class="button btPost"></form></div>';
if(isset($_GET['kw']))
{
	$searchText = '`subject` LIKE  \'%'.$_GET['kw'].'%\' AND ';
}
else
{
	$searchText = '';
}

$sql = mysql_query("SELECT * FROM ".conf('table_prefix')."_subject WHERE $searchText filetype='blog' AND status='public' ORDER BY ".$aqr." LIMIT 0,10");
echo '<div id="boxUI"><h1 align="center"> Public Blog</h1> ';
echo '<div class="boxContent" id="idmCL">';
while($qr = mysql_fetch_array($sql)){
	$checksubcategory = explode(',', $qr['desc']);
	$gname = mysql_fetch_array(mysql_query('SELECT `name` FROM '.conf('table_prefix').'_group WHERE gid='.$qr['group']));
	$Usr = UserInfo($qr['username']);
	if(isset($_GET['gp']))
	{
		for($x=0;$x<=count($checksubcategory);$x++)
		{
			$catq = mysql_fetch_array(mysql_query('SELECT `name`,`scid` FROM '.conf('table_prefix').'_subcat WHERE scid='.$checksubcategory[$x]));
			$catname .= $catq['name'];
		}
		
		if(in_array($_GET['gp'], $checksubcategory)||$_GET['gp']==$qr['group']){
		echo "<p><a href='/blog/".$qr['req_id']."' target='_blank'><img src='/image-api.php?width=100&height=100&image=/data/content/".$qr['req_id']."/".$qr['image']."' onerror=\"this.src='holder.js/100x100/Image'\" class='stdo-image'></a>
		<a href='/blog/".$qr['req_id']."' target='_blank' style=\"margin-top: 15px;display: inline-block;width: 75%;\">".$qr['subject']."</a>
		<span class='des'>โดย ".str_replace('|',' ',$Usr['fullname'])."<br>
		<span style='color: #aaa;font-size:11px;font-weight: normal;'><span style=\"color:#668AC3;\">".$gname['name']."</span> @เมื่อ ".date("d/m/Y, H:i:s", $qr['timestamp'])."</span>
		<br> <span style='float: left;' class='cc'><a href='#' onclick=\"embeddlg(".$qr['bid'].");\"><i class='fa fa-share'></i> Share</a></span>
		</p>";
		}
	}else
	{
		echo "<p><a href='/blog/".$qr['req_id']."' target='_blank'><img src='/image-api.php?width=100&height=100&image=/data/content/".$qr['req_id']."/".$qr['image']."' onerror=\"this.src='holder.js/100x100/Image'\" class='stdo-image'></a>
		<a href='/blog/".$qr['req_id']."' target='_blank' style=\"margin-top: 15px;display: inline-block;width: 75%;\">".$qr['subject']."</a>
		<span class='des'>โดย ".str_replace('|',' ',$Usr['fullname'])."<br>
		<span style='color: #aaa;font-size:11px;font-weight: normal;'><span style=\"color:#668AC3;\">".$gname['name']."</span> @เมื่อ ".date("d/m/Y, H:i:s", $qr['timestamp'])."</span>
		<br> <span style='float: left;' class='cc'><a href='#' onclick=\"embeddlg(".$qr['bid'].");\"><i class='fa fa-share'></i> Share</a></span>
		</p>";
	}
}
echo '</div></div>';
?>