<?php
if(isset($_GET['del']))
$sql = mysql_query('DELETE FROM '.conf('table_prefix').'_subject WHERE req_id="'.$_GET['del'].'"');
if($sql)
	echo '<div class="view"><h2 class="title" align="center" id="mytitle">เนื้อหาไอดี '.$_GET['del'].' ถูกลบออกแล้ว</h2></div>';
else
	echo '<div class="view"><h2 class="title" align="center" id="mytitle">ไม่สามารถลบเนื้อหาไอดี '.$_GET['del'].' ได้</h2></div>';
?>