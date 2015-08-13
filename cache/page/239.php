<?php
if(!isset($_GET['id'])||!isset($_GET['qt'])||$client['role']<=2)
{
	header('location: /403');
	exit;
}
?>
<div id="leftContainer">
	<div class="menu">
		<p>
			<a href="#"  class="button btGray"><i class="fa fa-arrow-left"></i> &nbsp;ย้อนกลับ</a> 
		</p>
		</div>
	</div>
</div>
<!-- Content -->
<div id="middleContainer">
	<div id="boxUI">
		<div class="header"><i class="fa fa-book fa-lg"></i> จัดการนักเรียน</div>
		<div class="boxContent">
			<form method="post" action="save.php" id="data-form"  enctype="multipart/form-data">
					
				<div class="frm-group">
					<p class="title"></p>
					<p class="frm-obj">
						<select name="course_type" required id="select_course_type">
							<option selected  value="">เลือกประเภทวิชา</option>
							<?php
								$sql_lev = mysql_query('select * from '.conf('table_prefix').'_course_type order by ctid asc');
								while($lev = mysql_fetch_array($sql_lev))
								{
									if($client['role']<6&&$lev['name']=='standard') continue; 
									echo '<option value="'.$lev['name'].'"> '.$lev['thText'].'</option>';
								}
							?>
						</select>
					</p>
				</div>

			</form>
		</div>
	</div>
</div>