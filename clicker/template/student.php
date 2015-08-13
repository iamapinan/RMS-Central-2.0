		<?php 
			$state = $course->sessionStatus($sessionid->session);
			if($state==0){ 
					header('location: '.$http->currents('crs').'&q=media'); 
					exit; 
			}
			if($state=='')
			{
				header('location: /404');
				exit;
			}

			$courseinfo = $course->courseInfo();
			$courseimage = $config['install_path'].$course->coursedir.'/'.$courseinfo['image'];

			$teacherinfo = $usr->teacherProfile();
			$teacherimage = $config['install_path'].$course->coursedir.'/'.$teacherinfo['photo'];
		?>
		<div class="row">
		  	<div class="col-md-10">
		  		<div class="row showcase">
					<div class="col-sm-5 workspace-image text-right">
						<img class="courseimg" src="<?php echo $course->imageSetup($courseimage,'200x200',"COURSE");?>">
					</div>
					<div class="col-sm-7 workspace-info">
						<?php 
							echo '<h1>'.$courseinfo['title'].'</h1>';
							echo '<div class="cdtail">'.nl2br($courseinfo['description']).'</div>';
						?>
					</div>
		  		</div>
		  		<br>
		  		<div class="panel panel-default">
				  <div class="panel-body stdArea collapse in" id="collapseThree">
				  <?php 
				  	$std = $course->getStdAll();
				  	$randspeed = $course->getCourseConfig('random_speed');
				  	echo "<input type=hidden id=stdcount value=".count($std).">";
				  	echo "<input type=hidden id=playtime value=$randspeed>";
				  	$x=0;
				  	foreach($std as $s):
				  		$x++;
				  		$score = $usr->Score($s['uid']);
				  		
				  ?>
					    <div class="thumbnail" id="<?php echo $x;?>" data-user="<?php echo $s['uid'];?>" data-photo="<?php echo $usr->avatar($s['uid'],'250x250');?>" 
					    data-crystal=<?php echo $score['crystal'];?> data-gold=<?php echo $score['gold'];?> 
					    data-coin=<?php echo $score['coin'];?>>
					      <img src="<?php echo $usr->avatar($s['uid'],'66x66');?>" class="img-rounded">

					      <div class="caption text-center">
					        <p class="stdCap"><?php echo ucfirst($s['firstname']).' '.ucfirst($s['lastname']);?></p>
					      </div>
					    </div>
					<?php endforeach;?>

				  </div>
				</div>
		  	</div>
		    <div class="col-md-2">
		    	<div class="teacher-avatar"><img src="<?php echo $course->imageSetup($teacherimage,'288x200','Teacher','1:0.7');?>">
		    	<h2>ครู <?php echo $teacherinfo['firstname'];?></h2></div>
		    	<div class="button-group">
			  		<a href="javascript:repeat();" class="btn btn-link" role="button">
			  			<i class="fa fa-random fa-5x"></i>
			  			<h3>สุ่มนักเรียน</h3>
			  		</a>
			  	</div>
			  	<div class="button-group">
			  		<a href="javascript:repeatmulti();" class="btn btn-link" role="button">
			  			<i class="fa fa-random fa-5x"></i>
			  			<h3>สุ่มนักเรียนหลายคน</h3>
			  		</a>
			  	</div>
		  		
		  	</div>
		</div>
		<audio id="BGMPlayer">
			<source src="include/sounds/bgm/drum.ogg" preload="auto"></source>
			Your browser isn't invited for super fun audio time.
		</audio>