<?php

	$sql = mysql_query("SELECT * FROM ".conf('table_prefix')."_course WHERE `sid`=".$client['org']." AND `type`='extra' ORDER BY clsid DESC LIMIT 0,30");
	echo '<div class="thumbnail-list">';
	while($cs = mysql_fetch_array($sql)){
		if($cs['image']=='')
			$cs['image'] = 'holder.js/100x80';
		echo '
					<div class="list-items">
						<img data-src="'.$cs['image'].'">

						<div class="detail">
						<h3>'.$cs['cname'].'</h3>
						 <p>'.$cs['cdetail'].'</p>
						 <p>'.csname($cs['grade']).' ห้อง '.$cs['class'].' จำนวน '.$cs['member'].' คน<p>
						</div>
						<div class="listbt">
							<button class="btn btGray">สมัคร</button>
							<button class="btn btBlue">เปิดใน Clicker</button>
						</div>
					</div>
			';

	}

	echo '</div>';
?>