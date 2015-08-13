		<link href="include/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
        <script src="include/bootstrap-fileinput/js/fileinput.min.js" type="text/javascript"></script>
        <script src="include/bootstrap-fileinput/js/fileinput_locale_th.js"></script>
        <script src="include/jquery-sortable-min.js"></script>
        <script language="javascript">
        $(document).ready(function(){
        	$("#mediainput").fileinput({
        		language: "th",
    			uploadUrl: "uploadfile.php?cid=<?php echo $http->courseId();?>",
    			allowedPreviewTypes: ['image', 'html', 'text', 'video', 'audio'],
    			previewSettings: {
								    image: {width: "auto", height: "60px"},
								    html: {width: "auto", height: "60px"},
								    text: {width: "auto", height: "60px"},
								    video: {width: "auto", height: "60px"},
								    audio: {width: "auto", height: "60px"},
								    flash: {width: "auto", height: "60px"},
								    object: {width: "auto", height: "60px"},
								    other: {width: "auto", height: "60px"}
								}
    		});

    		$('#mediainput').on('fileuploaded', function(event, data) {
			    	
			    	$('.mediaList').append("<li class=file-list data-id='"+data.response.id+"'>\
			    		<a href=# onclick=\"share-url\" data-action=getfileurl data-id="+data.response.id+"><i class=\"option-item fa fa-link\"></i></a>\
					  	<a href=# onclick=\"toggle\" data-action=toggle data-id="+data.response.id+"><i class=\"option-item fa fa-eye-slash\" style=\"margin-left: 35px;\"></i></a>\
					  	<a href=# onclick=\"trash\" data-action=trash data-id="+data.response.id+"><i class=\"option-item fa fa-trash\" style=\"margin-left: 72px;\"></i></a>\
			    		<a href=\""+data.response.simplePath+"\" target=\"_blank\">\
			    		<img src='"+data.response.image+"'></a>\
			    		<p>"+data.response.title+"</p></li>");
			});

			var group = $("ul.mediamoveable").sortable({
			  group: 'mediamoveable',
			  onDrop: function ($item, container, _super) {
			    var data = group.sortable("serialize").get();

			    var jsonString = JSON.stringify(data, null, '');

			    $('#serialize_output').text(jsonString);
			    mediaSorting(jsonString,'<?php echo $http->get()->sessionid;?>');
			    _super($item, container);
			  }
			});
        })

	        $('#clickermodal').on('show.bs.modal', function (event) {
			  var button = $(event.relatedTarget).context.dataset // Button that triggered the modal
			  var fileId = button.id; // Extract info from data-* attributes
			  var action = button.action;
			  var med = button.status;
			  var modal = $(this)
			  modal.find('.modal-title').text('')
			  modal.find('.modal-body').html('')
			  $.post('update.php',{'q':action,'id':fileId,'data':med}, function(data){
			  	modal.find('.modal-title').text(data.title)
			  	modal.find('.modal-body').html(data.body)
			  },"JSON")

			  
			})
        </script>
		<div class="row">
		  	<div class="col-md-8">
			  	<div class="panel panel-primary">
			  		  <div class="panel-heading">
			  		  	<b>สื่อการสอนในครั้งนี้</b>
			  		  	<a href="#" data-toggle="modal" data-action="getsessionurl" data-target=".x-modal-lg" data-id="<?php echo $sessionid->session;?>" class="panel-icon"><i class="share-url fa fa-link"></i></a>
					  </div>
					  <ul class="panel-body mediaList mediamoveable" data-id="<?php echo $sessionid->session;?>">
					  	<li data-session="<?php echo $sessionid->session;?>">
					  	
					  	<?php 
						  	$f = $course->courseFiles($sessionid->session); 
						  	for($a=0;$a<count($f);$a++): 
						  		
						  		switch($f[$a]['ftype']){
						  			case 'png':
						  				$icon = '<img src="img.php?width=100&height=100&cropratio=1:1&image='.$config['install_path'].'data/'.$http->courseId().'/files/'.$f[$a]['filename'].'">';
						  			break;
						  			case 'jpg':
						  				$icon = '<img src="img.php?width=100&height=100&cropratio=1:1&image='.$config['install_path'].'data/'.$http->courseId().'/files/'.$f[$a]['filename'].'">';
						  			break;
						  			case 'gif':
						  				$icon = '<img src="img.php?width=100&height=100&cropratio=1:1&image='.$config['install_path'].'data/'.$http->courseId().'/files/'.$f[$a]['filename'].'">';
						  			break;
						  			case 'zip':
						  				$icon = '<img src="holder.png/100x100/Compressed">';
						  			break;
						  			case 'pptx':
						  			case 'ppt':
						  				$icon = '<img src="holder.png/100x100/Presentation">';
						  			break;
						  			case 'doc':
						  			case 'docx':
						  				$icon = '<img src="holder.png/100x100/Document">';
						  			break;
						  			case 'xls':
						  			case 'xlsx':
						  				$icon = '<img src="holder.png/100x100/Excel">';
						  			break;
						  			case 'mp4':
						  			case 'avi':
						  			case 'ogg':
						  			case 'webm':
						  			case 'flv':
						  			case 'mpg':
						  			case 'ogv':
						  			case 'avi':
						  				$icon = '<img src="holder.png/100x100/Video">';
						  			break;
						  			break;
						  			case 'mp3':
						  			case 'wav':
						  				$icon = '<img src="holder.png/100x100/Audio">';
						  			break;
						  			case 'link':
						  				$icon = '<img src="holder.png/100x100/URL">';
						  			break;
						  			default:
						  				$icon = '<img src="holder.png/100x100/File">';
						  			break;
						  		}
					  	?>
						  	<li class=file-list data-id=<?php echo $f[$a]['id'];?>>
						  	<a href="#" data-toggle="modal" data-target=".x-modal-lg" data-action=getfileurl data-id=<?php echo $f[$a]['id'];?>><i class="option-item fa fa-link"></i></a>
					  		<a href="#" data-toggle="modal" data-target=".x-modal-lg" data-status=<?php echo $f[$a]['accessible'];?> data-action=toggle data-id=<?php echo $f[$a]['id'];?>><i class="option-item fa fa-eye-slash" style="margin-left: 35px;"></i></a>
					  		<a href="#" data-toggle="modal" data-target=".x-modal-lg" data-action=trash data-id=<?php echo $f[$a]['id'];?>><i class="option-item fa fa-trash" style="margin-left: 72px;"></i></a>

						  	<a href="<?php if($f[$a]['ftype']!='link'){
						  	echo $config['install_path'];?>data/<?php echo $http->courseId();?>/files/<?php echo $f[$a]['filename']; }else{ echo $f[$a]['filename']; }?>" target="_blank">
						  	<?php echo $icon;?>
				    		</a>
				    		<p><?php echo $f[$a]['title'];?></p></li>
			    		<?php endfor;?>
			    		</li>
					  </ul>
				</div>

			<?php $pastSession = $db->select("SELECT `session_id`,`timestamp` FROM `session` WHERE course_id=".$http->courseId()." ORDER BY timestamp DESC"); 
					foreach($pastSession as $pasted):
					if($pasted['session_id']==$http->get()->sessionid) continue;
					$f = $course->courseFiles($pasted['session_id']); 
					$fc = count($f);
					if($fc==0) continue;
			?>
				<div class="panel panel-default">
			  		  <div class="panel-heading">
			  		  	เมื่อ <?php echo date('d/n/Y, H:i',$pasted['timestamp']);?>
			  		  	<a href="#" data-toggle="modal" data-action="getsessionurl" data-target=".x-modal-lg" class="panel-icon" data-id="<?php echo $pasted['session_id'];?>"><i class="share-url fa fa-link"></i></a>
					  </div>
					  <ul class="panel-body old-mediaList mediamoveable">
					  	<li data-session="<?php echo $pasted['session_id'];?>">
					  	<?php 
						  	
						  	for($p=0;$p<$fc;$p++):
						  		
						  		switch($f[$p]['ftype']){
						  			case 'png':
						  				$icon = '<img src="img.php?width=100&height=100&cropratio=1:1&image='.$config['install_path'].'data/'.$http->courseId().'/files/'.$f[$p]['filename'].'">';
						  			break;
						  			case 'jpg':
						  				$icon = '<img src="img.php?width=100&height=100&cropratio=1:1&image='.$config['install_path'].'data/'.$http->courseId().'/files/'.$f[$p]['filename'].'">';
						  			break;
						  			case 'gif':
						  				$icon = '<img src="img.php?width=100&height=100&cropratio=1:1&image='.$config['install_path'].'data/'.$http->courseId().'/files/'.$f[$p]['filename'].'">';
						  			break;
						  			case 'zip':
						  				$icon = '<img src="holder.png/100x100/Compressed">';
						  			break;
						  			case 'pptx':
						  			case 'ppt':
						  				$icon = '<img src="holder.png/100x100/Presentation">';
						  			break;
						  			case 'doc':
						  			case 'docx':
						  				$icon = '<img src="holder.png/100x100/Document">';
						  			break;
						  			case 'xls':
						  			case 'xlsx':
						  				$icon = '<img src="holder.png/100x100/Excel">';
						  			break;
						  			case 'mp4':
						  			case 'avi':
						  			case 'ogg':
						  			case 'webm':
						  			case 'flv':
						  			case 'mpg':
						  			case 'ogv':
						  			case 'avi':
						  				$icon = '<img src="holder.png/100x100/Video">';
						  			break;
						  			break;
						  			case 'mp3':
						  			case 'wav':
						  				$icon = '<img src="holder.png/100x100/Audio">';
						  			break;
						  			case 'link':
						  				$icon = '<img src="holder.png/100x100/URL">';
						  			break;
						  			default:
						  				$icon = '<img src="holder.png/100x100/File">';
						  			break;
						  		}
					  	?>
						  	<li class=file-list data-id=<?php echo $f[$p]['id'];?>>
						  	<a href="#" onclick="share-url" data-action=getfileurl data-id=<?php echo $f[$p]['id'];?>><i class="option-item fa fa-link"></i></a>
					  		<a href="#" onclick="toggle" data-status=<?php echo $f[$p]['accessible'];?> data-action=toggle data-id=<?php echo $f[$p]['id'];?>><i class="option-item fa fa-eye-slash" style="margin-left: 35px;"></i></a>
					  		<a href="#" onclick="trash" data-action=trash data-id=<?php echo $f[$p]['id'];?>><i class="option-item fa fa-trash" style="margin-left: 72px;"></i></a>

						  	<a href="<?php if($f[$p]['ftype']!='link'){
						  	echo $config['install_path'];?>data/<?php echo $http->courseId();?>/files/<?php echo $f[$p]['filename']; }else{ echo $f[$p]['filename']; }?>" target="_blank">
						  	<?php echo $icon;?>
				    		</a>
				    		<p><?php echo $f[$p]['title'];?></p></li>
			    		<?php endfor;?>
			    		</li>
					  </ul>
				</div>
			<?php endforeach;?>
		  	</div>
		  	<div class="col-md-4">
		  		<div class="panel panel-default">
				  <div class="panel-body">
					<form action="uploadfile.php" method="post">
					  <div class="form-group">
					    <label for="mediainput"><i class="fa fa-cloud-upload"></i> นำเข้าเนื้อหาสำหรับครั้งนี้</label>
					    <input type="file" name="mediafile" id="mediainput" placeholder="เลือกไฟล์" data-show-upload="true" 
					    data-show-caption="true" class="file-loading" multiple=true>
					  </div>
					</form>
				  </div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-body">
					<form action="uploadfile.php" method="post" id="addmedialink">
					  <input type="hidden" name="uploadtype" value="link">
					  <div class="form-group">
					    <label for="file_title">ชื่อลิงค์</label>
					    <input type="text" name="ftitle" id="file_title" placeholder="บอกชื่อลิ้งค์" required class="form-control">
					  </div>
					  <div class="form-group">
					    <label for="linkinput"><i class="fa fa-link"></i> แนบลิ้งค์</label>
					    <input type="text" name="link" id="linkinput" placeholder="วางลิ้งค์ของคุณ" required class="form-control">
					  </div>
					  <button type="submit" class="btn btn-primary">Save</button>
					</form>
				  </div>
				</div>
		  	</div>
		</div>
		<div class="serialize_output"></div>