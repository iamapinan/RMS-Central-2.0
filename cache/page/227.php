<?php
$tarr = array(0=>"AcuStudio/AcuLive",1=>"Schedule live",2=>"Blog",'all'=>"All type");
$seletedtype[0] = ($_GET['type']==0) ? 'selected' : '';
$seletedtype[1] = ($_GET['type']==1) ? 'selected' : '';
$seletedtype[2] = ($_GET['type']==2) ? 'selected' : '';
$seletedtype['all'] = ($_GET['type']=='all') ? 'selected' : '';

	//$sqlSubcat = mysql_query("SELECT * FROM tc_subcat WHERE type<>0");
$avsearch = '<div class="searchtitle"><h2>Advance Search Options</h2>';
$avsearch .= '<br>
<form method="get" action="/advance-search" style="text-align: left">
	<input type="text" id="kk" name="kk" maxlength="100" value="'.$_GET['kk'].'" placeholder="คำค้น"> หรือ 
	<input type="text" id="ka" name="ka" "'.$_GET['ka'].'" placeholder="ชื่อผู้สร้าง"> หรือ 
	<input type="text" id="kc" name="kc" "'.$_GET['kc'].'" placeholder="ชื่อองค์กร"> ประเภท 
	<select id="type" name="type">
		<option value="0" '.$seletedtype[0].'>Studio/Live</option>
		<option value="1" '.$seletedtype[1].'>Schedule live</option>
		<option value="2" '.$seletedtype[2].'>Blog</option>
		<option value="all" '.$seletedtype['all'].'>All</option>
	</select>
<p>&nbsp;</p>';
$sql = mysql_query('select * from '.conf('table_prefix').'_group order by gid asc');
$avsearch .= '<select id="gp" data-type="1" name="gp" onchange="getsub($(this).val(),$(this).attr(\'data-type\'));">';
$avsearch .= '<option value="">หมวดหมู่</option>';
while($gm=mysql_fetch_array($sql))
{
	if($_POST['group']==$gm['gid']) $select = 'selected'; else $select = '';
	if(($client['role']!=8||$client['admin']!=1)&&($gm['gid']>=18&&$gm['gid']<=21)) continue;
	$avsearch .= '<option value="'.$gm['gid'].'" '.$select.'>'.$gm['name'].'</option>';
}
$avsearch .= '</select>';
$avsearch .= '
<div id="choice"></div>
<p>&nbsp;</p>
<input type="submit"  class="button btPost" value=" Search ">
</form><br>
</div>
<script language="javascript">
function getsub(cat,t)
{
     $.post(\'/social_func.php?g=listsubcat&t=\'+t+\'&id=\'+cat, function(data){
         if(data==1){  
		 $(\'#choice\').hide();
		 $(\'#choice\').html(""); 
		 return false; }
         else{ 
		 $(\'#choice\').show();
		 $(\'#choice\').html(data); 
		}
     });
}

</script>
';
echo $avsearch;
if($_GET['type']=='all'||$_GET['type']=='blog')
{
	$query = mysql_query('SELECT * FROM '.conf('table_prefix').'_subject WHERE filetype="blog" AND (`tag` LIKE  "%'.$_GET['kk'].'%" OR `subject` LIKE  "%'.$_GET['kk'].'%") ORDER BY bid DESC LIMIT 0,8');
	echo '<h2 class="title searchtitle">ผลการค้นหาข่าวสารและบทความ</h2>';
	echo '<ul class="blog-content-result">';
	$c=0;
	while($bgr = mysql_fetch_array($query))
	{
		if($bgr['image']!='') $imgsrc = '/image?width=100&height=100&cropratio=1:1&image=/data/content/'.$bgr['req_id'].'/'.$bgr['image'];
		else $imgsrc = '/image?width=100&height=100&cropratio=1:1&image=/library/images/thumbnail.png';
		echo '<a href="/blog/'.$bgr['req_id'].'"><li><img src="'.$imgsrc.'" class="bgpic" onerror="this.src=\'/image?width=100&height=100&cropratio=1:1&image=/library/images/thumbnail.png\'" /> <span class="restext">'.$bgr['subject'].'<br><span style="color:#aaa;">'.$bgr['username'].' @'.date('d/m/Y, H:i',$bgr['timestamp']).'</span></span></li></a>';
		$c = 1;
	}
	if($c==0)
		echo '<p style="text-align: center;margin: 10px;padding: 10px;">ไม่พบรายการที่ท่านค้นหา</p>';
	echo '</ul>';
}

echo '</div>';

?>