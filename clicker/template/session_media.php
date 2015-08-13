<?php
	$ses = @$course->sessionInfo($http->get()->sessionid);
	$sf = $course->courseFiles($http->get()->sessionid);
	$fc = count($sf);
	$courseconfig = $course->getCourseConfig('course_text');

?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
			  <h1>เอกสารและสื่อการเรียนรู้</h1>
			  <h2><small>เมื่อ: <?php echo date('d/n/Y, H:i',$ses['timestamp']);?></small></h2>
			</div>
			<div class="jumbotron">
			<h2><?php echo $courseconfig;?></h2>
			
				<br>
				<ul class="media-list">

				<?php for($x=0;$x<$fc;$x++):
					if($sf[$x]['accessible']==0) continue;
					$fp = ($sf[$x]['ftype']!='link') ? $config['install_path'].$course->coursedir.'/files/'.$sf[$x]['filename'] : $sf[$x]['filename'];
					$sf[$x]['ftype'] = ($sf[$x]['ftype']=='link') ? "URL" : $sf[$x]['ftype'];
					$sf[$x]['image'] = $course->imageSetup($fp,'80x80', $sf[$x]['ftype']);
				?>
				  <li class="media">
				    <div class="media-left">
				      <a href="<?php echo $fp;?>" target="_blank">
				        <img class="media-object" src="<?php echo $sf[$x]['image'];?>">
				      </a>
				    </div>
				    <div class="media-body">
				      <h4 class="media-heading"><?php echo $sf[$x]['title'];?></h4>
				      <?php echo $sf[$x]['ftype'];?>
				    </div>
				  </li>
				<?php endfor;?>

				</ul>
			</div>
		</div>
	</div>
</div>