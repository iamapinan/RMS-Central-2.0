		<link href="include/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
        <script src="include/bootstrap-fileinput/js/fileinput.min.js" type="text/javascript"></script>
        <script src="include/bootstrap-fileinput/js/fileinput_locale_th.js"></script>
        <script language="javascript">
        $(document).ready(function(){
        	$("#mediainput").fileinput({
        		language: "th",
    			uploadUrl: "uploadfile.php",
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
			    	console.log(data.response);
			});
        })
        </script>
		<div class="row">
		  	<div class="col-md-8">
			  	<div class="panel panel-default">
			  		  <div class="panel-heading">
			  		  	สื่อการสอน
					  </div>
					  <div class="panel-body mediaList">
					  </div>
				</div>
		  	</div>
		  	<div class="col-md-4">
		  		<div class="panel panel-default">
				  <div class="panel-body">
					<form action="uploadfile.php" method="post" >
					  <div class="form-group">
					    <label for="mediainput"><i class="fa fa-cloud-upload"></i> นำเข้าเนื้อหาสำหรับครั้งนี้</label>
					    <input type="file" name="mediafile" id="mediainput" placeholder="เลือกไฟล์" class="file-loading" multiple=true>
					  </div>
					</form>
				  </div>
				</div>
		  	</div>
		</div>