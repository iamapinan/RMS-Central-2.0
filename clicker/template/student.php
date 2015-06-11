		<div class="row">
		  	<div class="col-md-10">
		  		<div class="row">
		  			<div class="col-md-12 workspace"></div>
		  		</div>
		  		<br>
		  		<div class="panel panel-default">
		  		  <div class="panel-heading">
			  		  <a href="#collapseThree" class="collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseThree">
			  		  <i class="fa fa-caret-square-o-down"></i> นักเรียน (<?php echo $student['count'];?>)</a>
		  		  </div>
				  <div class="panel-body stdArea collapse in" id="collapseThree">

					    <div class="thumbnail" id="1">
					      <img src="img.php?width=66&height=66&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded">
					      <div class="caption text-center">
					        <p class="stdCap">นิภา กุนเชียง</p>
					      </div>
					    </div>

					    <div class="thumbnail" id="2">
					      <img src="img.php?width=66&height=66&cropratio=1:1&image=/clicker/data/70/std-112.jpg" class="img-rounded">
					      <div class="caption text-center">
					        <p class="stdCap">วินัย ใจดำ</p>
					      </div>
					    </div>

				  </div>
				</div>
		  	</div>
		    <div class="col-md-2">
		  		<div class="list-group">
		  		  <a href="#" class="list-group-item disabled"><i class="fa fa-check"></i> &nbsp;เช็คชื่อเข้าเรียน <span class="badge">soon</span></a>
				  <a href="javascript:repeat();" class="list-group-item"><i class="fa fa-random"></i> &nbsp;สุ่มนักเรียน 1 คน</a>
				  <a href="#" class="list-group-item"><i class="fa fa-magnet"></i> &nbsp;สุ่มนักเรียนโดยระบุจำนวนคน</a>
				  <a href="#" class="list-group-item disabled"><i class="fa fa-users"></i> &nbsp;จัดการกลุ่ม <span class="badge">soon</span></a>
				  <a href="#" class="list-group-item disabled"><i class="fa fa-trophy"></i> &nbsp;จัดการคะแนน <span class="badge">soon</span></a>
				</div>
		  	</div>
		</div>
		<audio id="BGMPlayer">
			<source src="include/sounds/bgm/drum.ogg" preload="auto"></source>
			Your browser isn't invited for super fun audio time.
		</audio>