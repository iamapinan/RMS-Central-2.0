<!-- Menu -->
<nav class="navbar navbar-default navbar-static-top">
	<div class="container">
		<div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
			data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><img src="lib/imgs/rms-clicker100.png" height="24"></a>
    </div>
		<div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
          <li class="<?php if($_GET['service']=='dashboard') echo 'active';?>">
						<a href="@dashboard"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>
					<li class="<?php if($_GET['service']=='report') echo 'active';?>">
						<a href="@report"><i class="fa fa-pie-chart"></i> Report</a></li>
          <li class="<?php if($_GET['service']=='class') echo 'active';?>">
						<a href="@class"><i class="fa fa-cube"></i> Class</a></li>
          <li class="<?php if($_GET['service']=='groups') echo 'active';?>">
						<a href="@groups"><i class="fa fa-users"></i> Groups</a></li>
					<li class="<?php if($_GET['service']=='questions') echo 'active';?>">
						<a href="@questions"><span class="glyphicon glyphicon-question-sign"></span> Questions</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li><a href="@profile"><?php if($UsrProfile['image']!='')
					echo '<img src="imgx.php?src=data/'.$UsrProfile['image'].'&w=28&h=28&c=crop"
					class="img-circle top-avatar" width="28" height="28">';?> {fullname}</a></li>
					<li class="divider"></li>
					<li>
						<button type="button" id="ClassStartbt" data-toggle="modal" data-target=".modaldlg" class="btn btn-warning navbar-btn">
							<i class="fa fa-star"></i> Start Class</button>
					</li>
					<li class="divider"></li>
          <li><a href="@logout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
        </ul>
      </div><!--/.nav-collapse -->
	</div>
</nav>

<!-- body -->
<div class="container">
	<div class="page-header">
		<h2 class="text-capitalize">{page_title}</h2>
	</div>
  {page_body}
</div>
