<?php
$sqlSh = mysql_query('SELECT * FROM '.conf('table_prefix').'_subject WHERE `group`=18 ORDER BY bid DESC LIMIT 0,20');
echo '<div class="display_0">
<h2 class="title">ข่าวสาร</h2>
<ul class="news-blck">';
while($news = mysql_fetch_array($sqlSh)){

		if($news['image']!='') $imgsrc = '/image?width=150&height=150&cropratio=1:1&image=/data/content/'.$news['req_id'].'/'.$news['image'];
		else $imgsrc = '/image?width=150&height=150&cropratio=1:1&image=/library/images/thumbnail.png';
		echo '<a href="/blog/'.$news['req_id'].'"><li class="news-items"><img src="'.$imgsrc.'" class="bgpic"/> <span class="restext">'.$news['subject'].'<br><span style="color:#aaa;"> @'.date('d/m/Y, H:i',$news['timestamp']).'</span></span></li></a>';
}
echo '</ul></div>';
?>