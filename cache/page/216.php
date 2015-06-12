<?php
$select[$_GET['gp']] = 'selected';
echo '<div class="display_0"><h2 class="title">FAQ ถาม-ตอบ <span class="page-option">
<select name="gp" id="gp" onchange="var setv = $(this).val();$(\'.news-items\').hide();if(setv!=\'\') $(\'.list-\'+setv).css(\'display\',\'block\'); else $(\'.news-items\').css(\'display\',\'block\');">
<option value="" selected >ดูทั้งหมด</option>';
		$sql = mysql_query('select * from '.conf('table_prefix').'_subcat where catid=20');
		while($gm=mysql_fetch_array($sql, MYSQL_ASSOC))
		{
		
				if($gm['type']==1){ 
					
					$sccontents=0;
					$sqlx = mysql_query('select `desc` from '.conf('table_prefix').'_subject where `desc` LIKE  \'%'.$gm['scid'].'%\' AND `group`=20');
					//echo 'select desc from '.conf('table_prefix').'_subject where `desc` LIKE  \'%'.$gm['scid'].'%\' AND `group`=20';
					while($op = mysql_fetch_array($sqlx, MYSQL_ASSOC))
					{
						$expos = explode(',', $op['desc']);
						if(in_array($gm['scid'], $expos)){
							$sccontents++;
						}
					}
					$gm['contents'] = $sccontents;
					$sc[$gm['scid']] = $gm;
				}
				
				if($gm['type']==2){
					$sccontents=0;
					$sqlx = mysql_query('select `desc` from '.conf('table_prefix').'_subject where `desc` LIKE  \'%'.$gm['scid'].'%\' AND `group`=20');
					while($op = mysql_fetch_array($sqlx, MYSQL_ASSOC))
					{
						$expos = explode(',', $op['desc']);
						if(in_array($gm['scid'], $expos)){
							$sccontents++;
						}
					}
					$gm['contents'] = $sccontents;
					$sc[$gm['mainsub']]['child'][] = $gm;
				}
			
			if($gm['type']==0)
			echo '<option value="'.$gm['scid'].'" '.$select[$gm['scid']].' class="option-color-disable " disabled>'.$gm['name'].'</option>';
			else if($gm['type']==1)
			echo '<option value="'.$gm['scid'].'" '.$select[$gm['scid']].'>'.$gm['name'].'</option>';
			else if($gm['type']==2)
				echo '<option value="'.$gm['scid'].'" '.$select[$gm['scid']].' style="background-color: #B0EDFF;">&#8212;'.$gm['name'].'</option>';
		}
echo '</select></span></h2>';

echo '<ul class="news-blck">';
echo '<h3>Software</h3>';
foreach($sc as $k)
{
	if($k['mainsub']==1){
		if((int)$k['contents']!=0) $icon = '<span class="lic">'.$k['contents'].'</span>'; else $icon = '';
		if(count($k['child'])!=0) $mbt = '<span class="morebt">+</span>'; else $mbt = '<span class="morebt">-</span>';
		echo '<li class="news-items main-a list-'.$k['scid'].'" onclick="getData('.$k['scid'].');$(\'.c-'.$k['scid'].'\').slideToggle();$(\'.news-items\').css(\'list-style\',\'none\');">'.$mbt.$k['name'].$icon.'</li>';
		echo '<span class="content-list-'.$k['scid'].'"></span>';
		foreach($k['child'] as $x){
			echo '<li class="news-items c-'.$k['scid'].' main-b list-'.$x['scid'].'"  onclick="getData('.$x['scid'].')">'.$x['name'].'</li>';
			echo '<span class="content-list-'.$x['scid'].'"></span>';
		}
	}
	
	//	echo '<li class="news-items main-b list-'.$k['child']['scid'].'">'.$k['child']['name'].'</li>';
/*
$sqlx = mysql_query('select * from '.conf('table_prefix').'_subject where `desc` LIKE  \'%'.$sc[1][$n]['scid'].'%\' AND `group`=20');
while($op = mysql_fetch_array($sqlx, MYSQL_ASSOC))
{
	$exp = explode(',',$op['desc']);
	if(in_array($sc[1][$n]['scid'], $exp)){
		echo '<li class="news-items main-b list-'.$sc[1][$n]['scid'].'"><span class="restext">
		<a href="/data/content/'.$op['req_id'].'/'.$op['req_id'].'.html&iframe=true&width=800&height=500" rel="prettyPhoto[iframe]">
		<img src="/library/icon/dot.png"> '.$op['subject'].'</a>
		</span></li>';
	}
}
*/
	//echo '<li class="news-items main-b list-'.$sc[1][$n]['scid'].'"><span class="restext">
	//<a href="/data/content/'.$sc[1][$n]['req_id'].'/'.$sc[1][$n]['req_id'].'.html&iframe=true&width=800&height=500" rel="prettyPhoto[iframe]"><img src="/library/icon/dot.png"> '.$sc[1][$n]['subject'].'</a>
	//</span></li>';
}
echo '</ul>';

echo '<ul class="news-blck">';
echo '<h3>Hardware</h3>';
foreach($sc as $k)
{
	if($k['mainsub']==19){
		if((int)$k['contents']!=0) $icon = '<span class="lic">'.$k['contents'].'</span>'; else $icon = '';
		if(count($k['child'])!=0) $mbt = '<span class="morebt">+</span>'; else $mbt = '<span class="morebt">-</span>';
		echo '<li class="news-items main-a list-'.$k['scid'].'" onclick="getData('.$k['scid'].');$(\'.c-'.$k['scid'].'\').slideToggle();$(\'.news-items\').css(\'list-style\',\'none\');">'.$mbt.$k['name'].$icon.'</li>';
		echo '<span class="content-list-'.$k['scid'].'"></span>';
		foreach($k['child'] as $x){
			if((int)$x['contents']!=0) $iconx = '<span class="lic">'.$x['contents'].'</span>'; else $iconx = '';
			echo '<li class="news-items main-b c-'.$k['scid'].' list-'.$x['scid'].'" onclick="getData('.$x['scid'].')">'.$x['name'].$iconx.'</li>';
			echo '<span class="content-list-'.$x['scid'].'"></span>';
		}
	}
}
echo '</ul>';

echo '</div>';
/*
echo '<li class="news-items main-b list-'.$fetch1['scid'].'"><span class="restext">
<a href="/data/content/'.$data['req_id'].'/'.$data['req_id'].'.html&iframe=true&width=800&height=500" rel="prettyPhoto[iframe]"><img src="/library/icon/dot.png"> '.$data['subject'].'</a>
</span></li>';
*/


?>
<script type="text/javascript" charset="utf-8">
	function getData(pid)
	{
		$('.content-list-'+pid).slideToggle();
		$.post('social_func.php', {"g":"post_data","_id":pid}, function(data){
		    for(x=0;x<data.length;x++)
			$('.content-list-'+pid).html(data[x]);
		    $('.news-items').css('list-style','none');
		    $("a[rel^='prettyPhoto']").prettyPhoto({
			  modal: false,
			  theme:'light_square',
			  iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
		     });
		},"json");
		
	}
  $(document).ready(function(){
	  $("a[rel^='prettyPhoto']").prettyPhoto({
	  modal: false,
	  theme:'light_square',
	  iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
	  });
  });
</script>