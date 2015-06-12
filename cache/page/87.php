<?php
if(isset($_GET['file'])&&file_exists(conf('dir').'/data/files/'.$_GET['file']))
{
	echo '<div class="view"><div class="cblock">
<h3>Downloading file '.$_GET['file'].' in 3 second...</h3>
<p><a href="#" onclick="window.close()" class="btPost">ปิดหน้านี้</a></p>
</div>
	</div>';
echo '<meta http-equiv="Refresh" content="3;url=/data/files/'.$_GET['file'].'" />';

}
else
{
	echo '<div class="view">
<div class="cblock">	
<h3>No file to download.!</h3>
<p><a href="#" onclick="window.close()" class="button btPost">ปิดหน้านี้</a></p>
</div></div>';
}
?>