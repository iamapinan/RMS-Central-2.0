<?php
include('startup.php');

	$SReq = @$http->get()->q;
	if(!isset($http->get()->return_type)){

		$online = new libs\OnlineDetect;
		$student['count'] = @count($course->cdp['student']);

		if(!isset($http->request()->sessionid)){
			$sessionid = $course->createSession($http->courseId());
		}
		else{
			//Activate last session.
			$db->query("UPDATE session SET status=0");
			$db->query("UPDATE session SET status=1 WHERE session_id='".$http->request()->sessionid."'");
			if(empty($http->get()->q)) $course->courseSync();
			$sessionid = $course->createSession($http->courseId());

		}

		if(!isset($http->get()->sessionid))
		{
			header('location: '.$_SERVER['REQUEST_URI'].'&sessionid='.$sessionid->session);
			exit;
		}
		$sessionif = $course->sessionInfo($sessionid->session);
		if($SReq=='') $SReq = $config['hometab'];
	}else{
		if($http->get()->return_type=='json'){
				$ses = $course->sessionInfo($http->get()->sessionid);
				$sf = $course->courseFiles($http->get()->sessionid);
				$fc = count($sf);
				$courseconfig = $course->getCourseConfig($http->courseId(), 'course_text');
				$jsondata['course_name'] = $courseconfig;
				$jsondata['course_file_dir'] = $config['address'].$config['install_path'].$course->coursedir.'/files/';
				$jsondata['session_id'] = $http->get()->sessionid;
				$jsondata['session_time'] = date('d/n/Y, H:i', $ses['timestamp']);
				$jsondata['file_count'] = $fc;
				$jsondata['files'] = $sf;
				header('Content-Type: application/json');
				echo json_encode($jsondata);
				exit;
		}
	}
	
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php echo @$course->cdp['cname'];?></title>
		<link href="include/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="include/custome.css" rel="stylesheet">
		<link href="include/Hover/css/hover-min.css" rel="stylesheet"  media="all">
		<link href="include/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<script src="include/jquery-1.11.2.min.js"></script>
		<script src="include/bootstrap/js/bootstrap.js"></script>
		<script src="include/script.js"></script>

	</head>
	<body>
	<div class="modal fade x-modal-lg" id="clickermodal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
        		</button>
        		<h4 class="modal-title"></h4>
      		</div>
      		<div class="modal-body"></div>
	    </div>
	  </div>
	</div>
		<?php if(isset($http->get()->return_type)){ include('template/'.$SReq.'.php'); exit; }?>
		<?php if($SReq==='end'){ include('template/'.$SReq.'.php'); exit; }?>
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
      					<!--
      					<li <?php if($SReq=='configs') echo 'class="active"';?>>
      						<a href="#"><i class="fa fa-gear"></i>  AcConference</a>
      					</li>
      					<li <?php if($SReq=='configs') echo 'class="active"';?>>
      						<a href="#"><i class="fa fa-gear"></i>  Plicker</a>
      					</li>
      					-->
      				</ul>

      				<button type="button" class="btn btn-danger navbar-btn navbar-right" onclick="window.location.href='<?php echo $http->currents('crs').'&q=end';?>'">เลิกเรียน <i class="fa fa-sign-out"></i></button>
      				<span class="navbar-right session_count"><i class="fa fa-clock-o"></i> สร้างเมื่อ: <?php echo date('D d F Y - H:i', $sessionif['timestamp']);?></span>
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