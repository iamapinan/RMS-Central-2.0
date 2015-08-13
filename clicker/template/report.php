<div class="row">
	<div class="col-md-7">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">นักเรียน</h3>
	  </div>
	  <div class="panel-body">
	<?php
		$userlist = $usr->UserList();
		foreach($userlist as $ul):
		$uimg = $usr->avatar($ul['photo']);
		$uscore = $usr->score($ul['uid']);
	?>
	<div class="media">
	  <div class="media-left">
	    <a href="#">
	      <img class="media-object img-circle" src="<?php echo $uimg;?>">
	    </a>
	  </div>
	  <div class="media-body">
	    <h4 class="media-heading"><?php echo ucfirst($ul['firstname']).' '.ucfirst($ul['lastname']);?></h4>
	    <button class="btn btn-default"><img src="img.php?width=30&height=30&image=/clicker/include/images/Crystal.png" class="crystal-img">
	    &nbsp;<?php echo $uscore['crystal'];?></button>
	    <button class="btn btn-default"><img src="img.php?width=30&height=30&image=/clicker/include/images/Gold.png" class="gold-img">
	    &nbsp;<?php echo $uscore['gold'];?></button>
	    <button class="btn btn-default"><img src="img.php?width=30&height=30&image=/clicker/include/images/Coin.png" class="coin-img">
	    &nbsp;<?php echo $uscore['coin'];?></button>
	  </div>
	</div>
	<?php endforeach;?>
		</div>
	  </div>
	</div>
	<div class="col-md-5">
		<div class="panel panel-default">
		  <div class="panel-body">
		  	<?php 
		  		$result = $db->select("SELECT * FROM session ORDER BY timestamp DESC");
		  	?>
		  	<div class="form-group">
				<label class="col-sm-2 control-label">เริ่มแล้ว</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo count($result);?> ครั้ง</p>
				</div>
			</div>
			<?php foreach($result as $res): ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">วันเวลา</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo date('d/n/Y, H:i', $res['timestamp']);?></p>
				</div>
			</div>
			<?php endforeach;?>

		  </div>
		</div>
	</div>
</div>