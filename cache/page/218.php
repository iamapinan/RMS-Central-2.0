<?php

echo '<div class="display_0"><h2 class="title">Download Center <span class="page-option">
<select name="gp" id="gp" onchange="var setv = $(this).val();$(\'.news-items\').hide();if(setv!=\'\') $(\'.list-\'+setv).show(); else $(\'.news-items\').show();">
<option value="" selected >ดูทั้งหมด</option>';
		$sql = mysql_query('select * from '.conf('table_prefix').'_subcat where catid=21');
		while($gm=mysql_fetch_array($sql))
		{
			echo '<option value="'.$gm['scid'].'" '.$select[$gm['scid']].'>'.$gm['name'].'</option>';
		}
echo '</select></span>
</h2>
<ul class="news-blck">';

$SQLDB0 = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE `catid`=21  ORDER BY scid, type ASC');
while($fetch0 = mysql_fetch_array($SQLDB0))
{
	//echo '<li class="title news-items main-a">'.$fetch0['name'].'</li>';
	$query = mysql_query('SELECT  `desc` ,  `subject` ,  `req_id`  FROM '.conf('table_prefix').'_subject 
	WHERE  `desc`<>"" AND `desc` LIKE  "%'.$fetch0['scid'].'%" AND `group`=21');
	while($fetch1 = mysql_fetch_array($query))
	{
		if($fetch1['image']!='') $imgsrc = '/image?width=150&height=150&cropratio=1:1&image=/data/content/'.$fetch1['req_id'].'/'.$fetch1['image'];
		else $imgsrc = '/image?width=150&height=150&cropratio=1:1&image=/library/images/thumbnail.png';
		echo '
		<li class="news-items list-'.$fetch0['scid'].'">
		<a href="/data/content/'.$fetch1['req_id'].'/'.$fetch1['req_id'].'.html&iframe=true&width=800&height=500" rel="prettyPhoto[iframe]"><img src="'.$imgsrc.'" class="bgpic"/></a>
		<span class="restext"><a href="/data/content/'.$fetch1['req_id'].'/'.$fetch1['req_id'].'.html&iframe=true&width=800&height=500" rel="prettyPhoto[iframe]">'.$fetch1['subject'].'</a><br>'.$fetch0['name'].'</span></a>
		</li>
		';
	}
	
	
}


echo '</ul></div>';
?>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
	  $("a[rel^='prettyPhoto']").prettyPhoto({
	  modal: false,
	  theme:'light_square',
	  iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
	  });
  });
</script>