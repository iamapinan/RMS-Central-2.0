		<?php
		$meta = $course->courseInfo();
		$meta['sid'] = $course->getCourseConfig('org');
		?>
		<div class="row">
		  	<div class="col-md-12">
		  		<div class="panel panel-default">
		  		  <div class="panel-heading">
			  		  <i class="fa fa-gear"></i> ตั้งค่าอื่นๆ
		  		  </div>
				  <div class="panel-body stdArea collapse in" id="collapseThree">
				  	<form class="form-horizontal" id="settingfrm" action="update.php">
				  	  <input type="hidden" name="q" value="config">
					  <div class="form-group">
					    <label class="col-sm-2 control-label">รหัสโรงเรียน</label>
					    <div class="col-sm-10">
					      <p class="form-control-static"><?php echo $meta['sid'];?></p>
					    </div>
					  </div>
					  <?php
					  	$teacher = $usr->TeacherList();
					  	foreach($teacher as $t):
					  		$speed = $course->getCourseConfig('random_speed');
					  ?>
					  <div class="form-group">
					    <label class="col-sm-2 control-label">ครู</label>
					    <div class="col-sm-10">
					      <p class="form-control-static"><?php echo $t['firstname'].' '.$t['lastname'];?></p>
					    </div>
					  </div>
					  <?php endforeach;?>
					  <div class="form-group">
					    <label for="confSpeed" class="col-sm-2 control-label">ความเร็วในการสุ่ม (วินาที)</label>
					    <div class="col-sm-10">
					      <input type="number" name="confSpeed" value="<?php echo $speed[0];?>" class="form-control" id="confSpeed" step="1" min=1 max=5>
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="msf" class="col-sm-2 control-label">เพลงในการสุ่ม</label>
					    <div class="col-sm-10">
					      <input type="file" name="Musicfile" class="form-control" id="msf">
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="randCount" class="col-sm-2 control-label">จำนวนการสุ่มแบบหลายคน</label>
					    <div class="col-sm-10">
					      <input type="number" name="randCount" value="<?php echo $course->getCourseConfig('randomcount');?>" class="form-control" id="randCount" step="1" min=1 max=10>
					    </div>
					  </div>
					  <div class="form-group">
					    <div class="col-sm-2"></div>
					    <div class="col-sm-10">
					      <button type="submit" role="button" id="configsave" class="btn btn-success">บันทึก</button>
					    </div>
					  </div>
					</form>
				  </div>
				</div>
		  	</div>
		</div>