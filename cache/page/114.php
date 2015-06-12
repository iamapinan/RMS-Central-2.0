<div class="area">

<?php
echo useronline('count').' User Online<br>';
echo useronline('active').' active<br>';
echo useronline('away').' away<br>';
echo useronline('busy').' busy<br>';
echo '<hr> Online User<br>';

$sql_qr = mysql_query('SELECT * FROM '.conf('table_prefix').'_online ORDER BY status ASC');
while($result = mysql_fetch_array($sql_qr))
{
	useronline('update',$result['uid']);
	if($result['status']=='active') $res = '<span class="sprites ico_ui_bullet_green"></span>';
	if($result['status']=='away') $res = '<span class="sprites ico_ui_bullet_orange"></span>';
	if($result['status']=='busy') $res = '<span class="sprites ico_ui_bullet_white"></span>';

	$inf = UserById($result['uid']);

	echo '<span class="sprites2 ico_ui_2_hire-me"></span> <a href="/u/'.$inf['user'].'" target="_blank">'.str_replace('|','&nbsp;',$inf['fullname']).'</a> <font color="blue">[IP='.$result['ipaddr'].']</font>&nbsp;'.$res.'&nbsp;'.date('d/m/Y, H:i:s',$result['timestamp']).'<br>';
}


?>
</div>