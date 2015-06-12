<script language="javascript">
<!--
	var debugMode = 0;
	$(document).ready(function(){
		$('#deb').mousedown(function() {
		    if (!$(this).is(':checked')) {
			debugMode = 1;
			$(this).trigger("change");
			console.log(debugMode);
		    }
		else{
			debugMode = 0;
			console.log(debugMode);
		    }
		});
		<?php
			if($_GET['mode']=='auto') 
			echo 'SendRequest();';
		?>
	});
	function SendRequest()
	{
		var resb = $('#resultbox ul')
		var c = $('#count').val();
		$('#stats').html('Query is running please wait...');
		$.post('/idm_test.php?cmd=Bash&c='+c+'&dbug='+debugMode, '',function(data){
			resb.html('');
			resb.append('Successfully');
			resb.append(data);
			resb.append('<li>============ End result ===========</li>');
		});
	}
//-->
</script>
<div class="view">
	<br>
	<input type="text" id="count" style="width: 100px;" value="10">
	<button id="run" class="button btPost" onclick="SendRequest()">Run</button> &nbsp; <input type="checkbox" value="1" id="deb"> Debug
	<br>
	<div id="resultbox">
		<ul>
			<li id="stats">Click Run button to Start Query...</li>
		</ul>
	</div>
</div>
<div class="info">
	<b>Status</b>
	<br/>0	Failed
	<br/>1	Succeed
	<br/>2	User exists
	<br/>3	Group doesn’t exist
</div>