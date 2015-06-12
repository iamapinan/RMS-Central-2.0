<?php
$seletedtype[0] = ($_GET['type']==0&&$_GET['type']!='all') ? 'selected' : '';
$seletedtype[1] = ($_GET['type']==1) ? 'selected' : '';
$seletedtype[2] = ($_GET['type']==2) ? 'selected' : '';
$seletedtype['all'] = ($_GET['type']=='all') ? 'selected' : '';
if(!isset($_GET['type'])) $seletedtype['all'] = 'selected';

if(isset($_GET['align'])){
	$align[$_GET['align']] = 'selected';
	switch($_GET['align'])
	{
		case 0:
		$qr = 'subject DESC';
		break;
		case 1:
		$qr = 'bid ASC';
		break;
		case 2:
		$qr = 'bid DESC';
		break;
		case 3:
		$qr = '`group` DESC';
		break;
		default:
		$qr = 'bid DESC';
		break;
	}
}else{
	$qr = 'bid DESC';
}

for($c=0;$c<count($_GET['category']);$c++){
	$shcat[$_GET['category'][$c]] = 1;
}
	$cmd = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE type=0 AND catid='.$_GET['gp']);
	$resp = '<br><h3>หมวดหมู่ย่อย</h3>';
	
	while($res = mysql_fetch_array($cmd))
	{
		if($res['type']==0){
			$resp .= '<h3 align="left">'.$res['name'].'</h3><div class="subx">';
			$subcatq = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE mainsub="'.$res['scid'].'" AND type!=0 AND catid='.$_GET['gp']);
			while($ress = mysql_fetch_array($subcatq)){
				if($shcat[$ress['scid']]==1) $chked = 'checked'; else $chked = '';
				$resp .= '<span class="choice"><input type="checkbox" value="'.$ress['scid'].'" name="category[]" '.$chked.'> '.$ress['name'].' </span>&nbsp;';
			}
			$resp .= '</div>';
		}
		$x=1;
	}
	if($x!=1) $resp = '';

if(isset($_GET['type'])){	
echo '<div class="searchtitle"><h2>ค้นหา</h2>';
echo '<br>
<form method="get" action="/acu-result" style="text-align: center;">
	<input type="text" id="kk" name="kk" maxlength="100" value="'.str_replace('%20',' ',$_GET['kk']).'" placeholder="คำค้น" required> 
	<input type="text" id="ka" name="ka" "'.$_GET['ka'].'" placeholder="ชื่อผู้สร้าง"> 
	<input type="text" id="kc" name="kc" "'.$_GET['kc'].'" placeholder="ชื่อองค์กร">
	<select id="type" name="type">
		<option value="all" '.$seletedtype['all'].'>All type</option>
		<option value="0" '.$seletedtype[0].'>Studio/Live</option>
		<option value="1" '.$seletedtype[1].'>Schedule live</option>
		<option value="2" '.$seletedtype[2].'>Blog</option>
	</select>
	<select name="align">
		<option value="no" '.$align['no'].'  class="option-color-disable " disabled>รูปแบบการจัดเรียง</option>
		<option value="0" '.$align[0].'>เรียงตามตัวอักษร</option>
		<option value="1" '.$align[1].'>เรียงตามเก่า-ใหม่</option>
		<option value="2" '.$align[2].'>เรียงตามใหม่-เก่า</option>
		<option value="3" '.$align[3].'>เรียงตามหมวดหมู่</option>
	</select>
	<select data-type="1" name="gp" onchange="getsub($(this).val(),$(this).attr(\'data-type\'));">
		<option value="" selected  class="option-color-disable " disabled>หมวดหมู่หลัก</option>';
		$sql = mysql_query('select * from '.conf('table_prefix').'_group order by gid asc');
		while($gm=mysql_fetch_array($sql))
		{
			if($_GET['gp']==$gm['gid']) $select = 'selected'; else $select = '';
			if($gm['gid']<18)  echo '<option value="'.$gm['gid'].'" '.$select.' >'.$gm['name'].'</option>';
			if(($client['role']==8||$client['admin']==1)&&($gm['gid']>=18)) 
			echo '<option value="'.$gm['gid'].'" '.$select.' class="option-color-secret">'.$gm['name'].'</option>';
		}
echo '</select>
	<br>
<div id="choice">'.$resp.'</div>
<br>
<input type="submit"  class="button btPost" value=" Search ">
</form><br>
</div>';

if($_GET['type']=='all'||$_GET['type']==2)
{
	if(isset($_GET['gp'])) $shgp = 'AND `group`='.$_GET['gp']; else $shgp = '';
	$query = mysql_query('SELECT * FROM '.conf('table_prefix').'_subject 
	WHERE filetype="blog" 
	AND (`tag` LIKE  "%'.$_GET['kk'].'%" OR `subject` LIKE  "%'.$_GET['kk'].'%") '.$shgp.'
	ORDER BY '.$qr.' LIMIT 0,20');
	
	echo '<h2 class="title searchtitle">ผลการค้นหา</h2>';
	echo '<ul class="blog-content-result">';
	$c=0;
	while($bgr = mysql_fetch_array($query))
	{
	
		$checksubcategory = explode(',', $bgr['desc']);
		if(isset($_GET['category']))
		{
			for($c=0;$c<count($_GET['category']);$c++){
				if(!in_array($_GET['category'][$c], $checksubcategory)) continue;
				else{
						
		$gname = mysql_fetch_array(mysql_query('SELECT `name` FROM '.conf('table_prefix').'_group WHERE gid='.$bgr['group']));
		
		for($x=0;$x<=count($checksubcategory);$x++)
		{
			$catq = mysql_fetch_array(mysql_query('SELECT `name`,`scid` FROM '.conf('table_prefix').'_subcat WHERE scid='.$checksubcategory[$x]));
			$catname .= $catq['name'];
		}
		if($bgr['image']!='') $imgsrc = '/image?width=100&height=100&cropratio=1:1&image=/data/content/'.$bgr['req_id'].'/'.$bgr['image'];
		else $imgsrc = 'holder.js/100x100/random';
		echo '<a href="/blog/'.$bgr['req_id'].'">
		<li><img src="'.$imgsrc.'" class="bgpic"/> 
		<span class="restext">'.$bgr['subject'].'<br>
		<span style="color:#aaa;">'.$bgr['username'].' @'.date('d/m/Y, H:i',$bgr['timestamp']).'</span>
		<br><span style="color:#668AC3;">'.$gname['name'].' ['.$catname.']</span></span></li></a>';
		$c = 1;
		
				}
			}
		}else{
						
			$gname = mysql_fetch_array(mysql_query('SELECT `name` FROM '.conf('table_prefix').'_group WHERE gid='.$bgr['group']));
			
			for($x=0;$x<=count($checksubcategory);$x++)
			{
				$catq = mysql_fetch_array(mysql_query('SELECT `name`,`scid` FROM '.conf('table_prefix').'_subcat WHERE scid='.$checksubcategory[$x]));
				$catname .= $catq['name'];
			}
			if($bgr['image']!='') $imgsrc = '/image?width=100&height=100&cropratio=1:1&image=/data/content/'.$bgr['req_id'].'/'.$bgr['image'];
			else $imgsrc = 'holder.js/100x100/random';
			echo '<a href="/blog/'.$bgr['req_id'].'">
			<li><img src="'.$imgsrc.'" class="bgpic"/> 
			<span class="restext">'.$bgr['subject'].'<br>
			<span style="color:#aaa;">'.$bgr['username'].' @'.date('d/m/Y, H:i',$bgr['timestamp']).'</span>
			<br><span style="color:#668AC3;">'.$gname['name'].' ['.$catname.']</span></span></li></a>';
			$c = 1;
		}

	}
	if($c==0)
		echo '<p style="text-align: center;margin: 10px;padding: 10px;">ไม่พบรายการที่ท่านค้นหา</p>';
	echo '</ul>';
}

if(!isset($_GET['type'])||$_GET['type']=='all'||$_GET['type']!=2){
	echo '<h2 class="title searchtitle">ผลการค้นหา AcuStudio/AcuLive</h2>';
	echo '<iframe class="frameresult"  
	src="http://acumanager.bll.in.th/search/search.asp?kk='.$_GET['kk'].'&ka='.$_GET['ka'].'&kc='.$_GET['kc'].'&type='.$_GET['type'].'" 
	frameborder="no" framespacing="no" width="100%" height="800"></iframe>';
}

}else{

echo '<div class="searchtitle"><br><h2 align="center">เริ่มค้นหา <i class="fa fa-search"></i></h2>';
echo '<br>
<form method="get" action="/acu-result" style="text-align: center">
	<input type="text" id="kk" name="kk" maxlength="100" value="'.$_GET['kk'].'" placeholder="คำค้น"> หรือ 
	<input type="text" id="ka" name="ka" "'.$_GET['ka'].'" placeholder="ชื่อผู้สร้าง"> หรือ 
	<input type="text" id="kc" name="kc" "'.$_GET['kc'].'" placeholder="ชื่อองค์กร"> ประเภท  
	<select id="type" name="type">
		<option value="all" '.$seletedtype['all'].'>All</option>
		<option value="0" '.$seletedtype[0].'>Studio/Live</option>
		<option value="1" '.$seletedtype[1].'>Schedule live</option>
		<option value="2" '.$seletedtype[2].'>Blog</option>
	</select>
	<select name="align">
		<option value="no" '.$align['no'].'>รูปแบบการจัดเรียง</option>
		<option value="0" '.$align[0].'>เรียงตามตัวอักษร</option>
		<option value="1" '.$align[1].'>เรียงตามเก่า-ใหม่</option>
		<option value="2" '.$align[2].'>เรียงตามใหม่-เก่า</option>
		<option value="3" '.$align[3].'>เรียงตามหมวดหมู่</option>
	</select>
	<br>
	<div class="catblock">'.$resp.'</div>
	<br>
	<input type="submit"  class="button btPost" value=" Search ">
</form><br>
</div>';
}
?>