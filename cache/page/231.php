<?php
	$sql = mysql_query("SELECT * FROM ".conf('table_prefix')."_course WHERE `type`='public' AND uid!=".$client['ssid']." ORDER BY course_id DESC LIMIT 0,30");
	
	if(mysql_num_rows($sql)==0){
		echo '<h3 align="center"><i class="fa fa-angellist fa-5x"></i><br><br>ยังไม่มีหลักสูตรสาธารณะเปิดสอนในตอนนี้</h3>';
		exit;
	}
	?>

<ul class="bxslider">
<?php 
$sqlx = mysql_query("SELECT * FROM ".conf('table_prefix')."_course WHERE `type`='public'  ORDER BY course_id DESC LIMIT 0,4");
while($csx = mysql_fetch_array($sqlx)): 
?>
  <a onclick="lightbox(<?php echo $csx['course_id'];?>,'courseInfo')" href=#><li><img src="/image?width=980&height=210&cropratio=1:0.3&image=<?php echo $csx['img'];?>" title="<?php echo $csx['cname'];?>" /></li></a>
<?php endwhile;?>
</ul>

	<?php
echo '<div class="thumbnail-list">';
	while($cs = mysql_fetch_array($sql)){
		
		$sqlCheck = mysql_query("SELECT * FROM tc_course_register WHERE course_id=".$cs['course_id']." and img<>'' and uid=".$client['ssid']);
		$result = mysql_fetch_array($sqlCheck);
		if(empty($result)){
				$registerbt = '<button class="button btGreen courseRegister" onclick="lightbox('.$cs['course_id'].',\'registerbt\')">สมัครเข้าร่วม</button> ';
		}

		if($cs['img']=='')
			$cs['image'] = 'holder.png/100x80/Thumbnail';
		else
			 $cs['image'] = 'image?width=300&height=200&cropratio=1.5:1&image='.$cs['img'];
		echo '
					<div class="list-items">
						<img src="'.$cs['image'].'" class="list-img">
						<div class="detail">
						<h3>'.$cs['cname'].'</h3>
							'.$registerbt.' 
							<button class="button btBlue courseInfo" onclick="lightbox('.$cs['course_id'].',\'courseInfo\')">รายละเอียด</button>
						</div>
					</div>
			';
		unset($registerbt);
		unset($result);
	}

	echo '</div>';
?>