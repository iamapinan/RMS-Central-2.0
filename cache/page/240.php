<?php
   echo '<h1 align="center">เข้าร่วมห้องเรียนสาธารณะ</h1>';
	$sql = mysql_query("SELECT * FROM ".conf('table_prefix')."_classroom WHERE `public`=1 ORDER BY clsid DESC LIMIT 0,30");
	echo '<div class="thumbnail-list">';
	while($cs = mysql_fetch_array($sql)){
		if($cs['image']=='')
			$cs['image'] = 'holder.js/100x80';
		else
			 $cs['image'] = 'image?width=100&height=80&cropratio=1:1&image='.$cs['image'];
		echo '
					<div class="list-items">
						<img src="'.$cs['image'].'">
						<div class="detail">
						<h3>'.$cs['title'].'</h3>
						 <p>'.$cs['detail'].'</p>
							<button class="button btGreen courseRegister">สมัครเรียน</button> 
							<button class="button btBlue courseInfo">รายละเอียด</button>
						</div>
					</div>
			';

	}

	echo '</div>';
?>