<?php

echo '<div class="display_0"><h2 class="title">Release Note</h2>
<ul class="news-blck">';

$SQLDB0 = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE `catid`=23 AND type=0 ORDER BY scid, type ASC');
$ls = array('');
while($fetch0 = mysql_fetch_array($SQLDB0))
{

	$query = mysql_query('SELECT  `desc` ,  `subject` ,  `req_id`  FROM '.conf('table_prefix').'_subject 
	WHERE  `desc`<>"" AND `desc` LIKE  "%'.$fetch0['scid'].'%" AND `group`=23');
	echo '<ol type="1">';
	
	while($data = mysql_fetch_array($query)){

		$cat = explode(',', $data['desc']);
		if(!in_array($fetch0['scid'], $cat)) continue;
		
		echo '<li class="news-items main-a"><span class="restext">
<a href="/data/content/'.$data['req_id'].'/'.$data['req_id'].'.html&iframe=true&width=800&height=500" rel="prettyPhoto[iframe]"><img src="/library/icon/dot.png"> '.$data['subject'].'</a>
		</span></li>';
	}
	echo '</ol>';
	
	$SQLDB1 = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE `catid`=23 AND `mainsub`='.$fetch0['scid'].' AND type=1 ORDER BY scid, type ASC');
	while($fetch1 = mysql_fetch_array($SQLDB1))
	{
		echo '<li class="title news-items main-b">'.$fetch1['name'].'</li>';
	
		$query = mysql_query('SELECT  `desc` ,  `subject` ,  `req_id`  FROM '.conf('table_prefix').'_subject 
		WHERE  `desc`<>"" AND `desc` LIKE  "%'.$fetch1['scid'].'%" AND `group`=23');
		echo '<ol type="1">';
		while($data = mysql_fetch_array($query)){
	
			$cat = explode(',', $data['desc']);
			if(!in_array($fetch1['scid'], $cat)) continue;
			
			echo '<li class="news-items main-b"><span class="restext">
			<a href="/data/content/'.$data['req_id'].'/'.$data['req_id'].'.html&iframe=true&width=800&height=500" rel="prettyPhoto[iframe]"><img src="/library/icon/dot.png"> '.$data['subject'].'</a>
			</span></li>';
		}
		echo '</ol>';
		
		$SQLDB2 = mysql_query('SELECT * FROM '.conf('table_prefix').'_subcat WHERE `catid`=20 AND `mainsub`='.$fetch1['scid'].' AND type=2 ORDER BY scid, type ASC');
		while($fetch2 = mysql_fetch_array($SQLDB2))
		{
			echo '<li class="title news-items main-c">'.$fetch2['name'].'</li>';
		
			$query = mysql_query('SELECT  `desc` ,  `subject` ,  `req_id`  FROM '.conf('table_prefix').'_subject 
			WHERE  `desc`<>"" AND `desc` LIKE  "%'.$fetch2['scid'].'%" AND `group`=23');
			echo '<ol type="1">';
			while($data = mysql_fetch_array($query)){
		
				$cat = explode(',', $data['desc']);
				if(!in_array($fetch2['scid'], $cat)) continue;
				
				echo '<li class="news-items main-c"><span class="restext">
				<a href="/data/content/'.$data['req_id'].'/'.$data['req_id'].'.html&iframe=true&width=800&height=500" rel="prettyPhoto[iframe]"><img src="/library/icon/dot.png"> '.$data['subject'].'</a>
				</span></li>';
			}
			echo '</ol>';
		}
		
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