<?php
include('startup.php');



	$online = new libs\OnlineDetect;
	$student['count'] = 2;//count($course->cdp['student']);
	
	//print_r($http->request());
	$SReq = @$http->get()->q;
	if($SReq=='') $SReq = $config['hometab'];
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
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
					<a class="navbar-brand">
						<span><img alt="Brand" class="img-rounded" 
						src="img.php?width=30&height=30&cropratio=1:1&image=/clicker/data/<?php echo $course->cdp['course_id'];?>/default_thumbnail.jpg" 
						width="30" height="30"></span>
						&nbsp;<?php echo $course->cdp['cname'];?>
					</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      				<ul class="nav navbar-nav">
      					<li <?php if($SReq=='student') echo 'class="active"';?>>
      						<a href="<?php echo $http->currents('crs').'&q=student';?>"><i class="fa fa-child"></i> นักเรียน</a>
      					</li>
      					<li <?php if($SReq=='media') echo 'class="active"';?>>
      						<a href="<?php echo $http->currents('crs').'&q=media';?>"><i class="fa fa-leanpub"></i> สื่อการสอน</a>
      					</li>
      					<li <?php if($SReq=='report') echo 'class="active"';?>>
      						<a href="<?php echo $http->currents('crs').'&q=report';?>"><i class="fa fa-bar-chart"></i> รายงาน</a>
      					</li>
      					<li <?php if($SReq=='configs') echo 'class="active"';?>>
      						<a href="<?php echo $http->currents('crs').'&q=configs';?>"><i class="fa fa-gear"></i>  ตั้งค่า</a>
      					</li>
      				</ul>
      				<button type="button" class="btn btn-danger navbar-btn navbar-right">เลิกเรียน <i class="fa fa-sign-out"></i></button>
      			</div>
			</div>
		</nav>
		<div class="container-fluid">
		  <?php
		  	include('template/'.$SReq.'.php');
		  ?>
		</div>
	</body>
</html>