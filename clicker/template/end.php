<?php $course->endClass($http->request()->sessionid);?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron">
			<h1>สิ้นสุดการเรียนในรอบนี้แล้ว</h1>
			<p>ท่านสามารถเข้ามาดูข้อมูลย้อนหลังของการเรียนในครั้งนี้ได้ใหม่ที่ </p>
				<div class="form-group">
					<input type="text" class="form-control" value="<?php echo $config['address'].str_replace('q=end','q=session_media', $_SERVER['REQUEST_URI'])."&return_type=return_media";?>">
				</div>

			</div>
		</div>
	</div>
</div>