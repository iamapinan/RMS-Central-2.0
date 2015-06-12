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
	});	
	function SendRequest(t)
	{
		var s = $('#server').val();
		var c = $('#count').val();
		var g = $('#gid').val();
		var resb = $('#resultbox ul')
		resb.append('<li id="tst" style="color: blue;">Please wait a minute...</li>');
		$.post('/idm_test.php?cmd='+t+'&g='+g+'&c='+c+'&s='+s+'&dbug='+debugMode, '',function(data){
			resb.append(data);
			$('#tst').html('Finished');
			resb.append('<li>============ End result ===========</li>');
		});
	}
	function create_g()
	{
	    var resb = $('#resultbox ul')
	    var gid = $('#gid').val();
	    resb.html('<li>Test type : Send create group to AcuManager</li>');
	    SendRequest('CG');
	}
	function create_u()
	{
	    var resb = $('#resultbox ul')
	    resb.html('<li>Test type : Send create user to AcuManager</li>');
	    SendRequest('CU');
	}
	function create_all()
	{
	    var resb = $('#resultbox ul')
	    var gid = $('#gid').val();
	    resb.html('<li>Test type : Send create group and user to AcuManager</li>');
	    SendRequest('CA');
	}
	function runit()
	{
	  var rid=$('#run_id').val();
	  var gid=$('#gid').val();
	  var svid = $('#server').val();
	  var resb = $('#resultbox ul')
	  if(rid=='') 
	  {
		alert('Please select test type.');
		resb.append('<li style="color: red;">Please select test type.</li>');
		return false;
	  }
	  if(svid=='') 
	  {
		alert('Please input value for Server IP');
		resb.append('<li style="color: red;">Please input value for Server IP</li>');
		$('#server').focus();
		return false;
	  }
	  if(rid=='group'&&gid!='') create_g();
	  else if(rid=='user'&&gid!='') create_u();
	  else if(rid=='all'&&gid!='') create_all(); 
	  else{
		  alert('Please specify value.');
		  resb.append('<li style="color: red;">Please specify group id  value.</li>');
		  return false;
		}
	}

//-->
</script>
<div class="view">
	<div class="title">AcuManager API Test console</div>
	<select id="run_type" onchange="$('#run_id').val($(this).find(':selected').val());">
		<option value="">Test type</option>
		<option value="group">Create group.</option>
		<option value="user">Create user.</option>
		<option value="all">Create user and group.</option>
	</select>
	<input type="text" id="server" placeholder="AM Server IP or Domain name"  style="width: 180px;" value="acumanager.bll.in.th">
	<input type="hidden" value="" id="run_id">
	<input type="text"  id="gid" placeholder="Group ID"> &nbsp; Loop: <input type="text"  id="count" value="10" placeholder="0" style="width: 45px;">
	<button id="run" class="button btPost" onclick="runit()">Run</button> &nbsp; <input type="checkbox" value="1" id="deb"> Debug
	<br>
	<hr>
	<h3>Result console.</h3>
	
	
	<div id="resultbox">
		<ul>
			<li>Test result</li>
			<li>Please choose option above before.</li>
			<li>&nbsp;</li>
			<li>==============================</li>
			<li>Instructions</li>
			<li>1. To test create group please specify number of loop but unnecessary to specify group id.</li>
			<li>2. To test create user please specify number of loop and group id too.</li>
			<li>3. To test create group and user please specify only number of loop.</li>
		</ul>
	</div>
	<div class="info">
		<b>Instructions</b>
		<br>1. To test create group please specify number of loop but unnecessary to specify group id.
		<br>2. To test create user please specify number of loop and group id too.
		<br>3. To test create group and user please specify only number of loop.
	</div>
</div>
