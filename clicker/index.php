<?php
include('startup.php');

$online = new libs\OnlineDetect;
$student['count'] = 10;//count($course->cdp['student']);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php echo $course->cdp['cname'];?></title>
		<link href="include/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="include/custome.css" rel="stylesheet">
		<link href="include/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<script src="include/jquery-1.11.2.min.js"></script>
		<script src="include/bootstrap/js/bootstrap.js"></script>
		<script src="include/script.js"></script>
		<script src="holder.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container-fluid ">
				<div class="navbar-header">
					<a class="navbar-brand">
						<span><img class="img-rounded" 
						src="img.php?width=30&height=30&cropratio=1:1&image=/clicker/data/<?php echo $course->cdp['course_id'];?>/default_thumbnail.jpg" 
						width="30" height="30"></span>
						&nbsp;<?php echo $course->cdp['cname'];?>
					</a>
				</div>
			</div>
		</nav>
		<div class="container-fluid">
		  <div class="row">
		  	<div class="col-md-10">
		  		<h2 class="text-center">นักเรียน 
		  		<a href="#" data-toggle="tooltip" data-placement="right" title="จำนวนนักเรียนที่ลงทะเบียนเรียนวิชานี้">(<?php echo $student['count'];?>)</a>
		  		</h2>
		  		<br>
		  		<div class="panel panel-default">
				  <div class="panel-body display-area">

					    <div class="thumbnail" id="1">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded online">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>

					    <div class="thumbnail" id="2">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded offline">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>

					    <div class="thumbnail" id="3">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded online">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>

					    <div class="thumbnail" id="4">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded online">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>

					    <div class="thumbnail" id="5">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded online">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>

					    <div class="thumbnail" id="6">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded online">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>

					    <div class="thumbnail" id="7">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded offline">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>

					    <div class="thumbnail" id="8">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded online">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>

					    <div class="thumbnail" id="9">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded online">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>

					    <div class="thumbnail" id="10">
					      <img src="img.php?width=120&height=120&cropratio=1:1&image=/clicker/data/70/std-111.jpg" class="img-rounded offline">
					      <div class="caption text-center">
					        <h3>วินัย ใจดี</h3>
					      </div>
					    </div>
	
				  </div>
				</div>
		  	</div>
		    <div class="col-md-2">
		  		<div class="list-group">
		  		  <a href="#" class="list-group-item"><i class="fa fa-check"></i> &nbsp;เช็คชื่อเข้าเรียน</a>
				  <a href="javascript:repeat();" class="list-group-item"><i class="fa fa-random"></i> &nbsp;สุ่มนักเรียน 1 คน</a>
				  <a href="#" class="list-group-item"><i class="fa fa-magnet"></i> &nbsp;สุ่มนักเรียนโดยระบุจำนวนคน</a>
				  <a href="#" class="list-group-item disabled"><i class="fa fa-users"></i> &nbsp;จัดการกลุ่ม</a>
				  <a href="#" class="list-group-item disabled"><i class="fa fa-trophy"></i> &nbsp;จัดการคะแนน</a>
				  <a href="#" class="list-group-item"><i class="fa fa-gear"></i> &nbsp;การตั้งค่า</a>
				  <a href="#" class="list-group-item"><i class="fa fa-sign-out"></i> &nbsp;เลิกเรียน</a>
				</div>
		  	</div>
		  </div>

		</div>
	</body>
</html>