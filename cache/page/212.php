<?php
include_once('core/class.am.php');
$mif = UserInfo();
$permission = mysql_fetch_object(mysql_query('SELECT * FROM '.conf('table_prefix').'_am_permission WHERE rid='.$mif['role']));
if($_GET['m']=='edit')
{
	$idm = new IDM2API;
	$session = $idm->SessionInfo($_GET['sid']);
	$cmd = 'editsession&sid='.$_GET['sid'];
}
else $cmd = 'addsession';
?>
<form id="AddSession" method ="post" action="/am_session.php?cmd=<?php echo $cmd;?>">
    <center>
    <input type="hidden" value="<?php if($_GET['m']=='edit') echo $session->Records->Record->AuthorID; else echo $_GET['host'];?>" name="userid" id="userid"  class="arial12pt">
    <table class="api" padding="5">
        <tr>
			<td class="field">Conference name *:</td>
			<td><input type="text" value="<?php echo $session->Records->Record->ConfTitle;?>" name="title" id="title" maxlength=255 style="width:250px !important;" class="arial12pt"></td>
	</tr>
		<tr>
			<td class="field">Description:</td>
			<td>
			<textarea name="desc" id="desc" maxlength=255 style="width:250px !important;; height:60" class="arial12pt"><?php echo $session->Records->Record->ConfDesc;?></textarea>
			</td>
		</tr>
		<tr>
			<td class="field"><b>Conf Quality :</b></td>
			<td>
			<?php $quality[$permission->confquality] = 'selected'; ?>
			<select id=ConfQuality name=ConfQuality  style="width:150px !important;" class="arial12pt">
				<Option value="0" <?php if($session->Records->Record->ConfQuality==0) echo 'selected'; else echo $quality[0];?>>640x480</Option>
				<Option value="1" <?php if($session->Records->Record->ConfQuality==1) echo 'selected'; else echo $quality[1];?>>800x600</Option>
				<Option value="2" <?php if($session->Records->Record->ConfQuality==2) echo 'selected'; else echo $quality[2];?>>960x720</Option>
				<Option value="3"<?php if($session->Records->Record->ConfQuality==3) echo 'selected'; else echo $quality[3];?>>1280x720</Option>
			</select>	
			</td>	
		</tr>
	    <tr style="display:none;">
			<td class="field">Start with:</td>
			<td>
				
				<select name="startmode" id="startmode" style="width:150px !important;" class="arial12pt">
					<option value="0" <?php if($session->Records->Record->StartMode==0) echo 'selected'; else echo '';?>>Anyone</option>
					<option value="1" <?php if($session->Records->Record->StartMode==1) echo 'selected'; else echo 'selected';?>>Host</option>
				</select>
			</td>
		</tr>		
		<tr onmouseover="$('.conf_info').fadeIn();">
			<td class="field" >Conference Mode:</td>
			<td class="arial12pt">
				
				<select name="confmode" id="confmode" style="width:150px !important;" class="arial12pt">
				<option value="0" <?php if($session->Records->Record->ConfMode==0) echo 'selected'; else echo '';?>>Host Control</option>
				<option value="1" <?php if($session->Records->Record->ConfMode==1) echo 'selected'; else echo 'selected';?>>Interactive</option>
				<option value="3" <?php if($session->Records->Record->ConfMode==2) echo 'selected'; else echo '';?>>Large Conference</option>			
				<option value="4" <?php if($session->Records->Record->ConfMode==3) echo 'selected'; else echo '';?>>Video Conference</option>	
				</select>
			</td>
		</tr>		

		
		<tr style="display:none;">
			<td class="field">Call out:</td>
			<td><input type=checkbox name="iscallout" id="iscallout" value="1"></td>	
		</tr>
		<tr style="display:none;">
			<td class="field">Call you:</td>
			<td><input type=checkbox name="iscallyou" id="iscallyou" value="1"></td>	
		<tr style="display:none;">
			<td class="field">Call me:</td>
			<td><input type=checkbox name="iscallme" id="iscallme" value="1"></td>	
		</tr>	
		<tr style="display:none;">
			<td class="field">Monitor:</td>
			<td><input type=checkbox name="monitor" id="monitor" value="1"></td>	
		</tr>	
		<tr>
			<td class="field">Allow all to recording:</td>
			<?php if($session->Records->Record->FreeRecording==1) $rec = 'checked'; else $rec='checked';?>
			<td><input type=checkbox name="freerecording" id="freerecording" value="1" <?php echo $rec;?> ></td>	
		</tr>	

		<input type=hidden name=isPresenterMode id=isPresenterMode value="1">
		
		<tr>
			<td class="field">Max participant:</td>
			<td><input type="text" value="<?php if($_GET['host']!='') echo $permission->maxparticipant; else echo $session->Records->Record->MaxUserCount;?>" placeholder="Maximum to <?php echo $permission->maxparticipant;?>" name="maxparticipant" id="maxparticipant" style="width:250px !important;" class="arial12pt"></td>
		</tr>
		
		<tr>
			<td class="field">Max speaker:</td>
			<td><input type="text" value="<?php if($_GET['host']!='') echo $permission->maxspeaker; else echo $session->Records->Record->MaxSpeakerCount;?>" placeholder="Maximum to <?php echo $permission->maxspeaker;?>" name="maxspeaker" id="maxspeaker" style="width:250px !important;" class="arial12pt"></td>
		</tr>
		

		<tr>
			<td class="field">Access code:</td>
			<td><input type="checkbox" onchange="if($(this).is(':checked')) $('#accesscode').show(); else $('#accesscode').hide(); ">
				<input type="text" value="" name="accesscode" id="accesscode" placeholder="Your passcode here." style="width:250px !important;display:none;" class="arial12pt"></td>
		</tr>
			<tr  style="display:none;">
			<td class="field">Clear documents on exit:</td>
			<td class="arial12pt">
				<input type="radio" name="act" id="act" value=1 checked>Yes
				<input type="radio" name="act" id="act" value=0>No
			</td>
		</tr>
		<tr>
		    <td colspan="2">
			<center><input type="Submit" value="Submit" class="button btPost"></center>
		    </td>
		</tr>
    </table>
    </center>

</form>
<div class="conf_info">
	<p class="title">คำอธิบายเพิ่มเติม</p>
	<ul class="mode_desc">
		<li><b style="color: orange">Host control</b> ปรากฎวีดีโอเฉพาะผู้บรรยาย และผู้ที่ได้รับอนุญาต เหมาะสำหรับห้องเรียนขนาดใหญ่</li>
		<li><b style="color: orange">Interactive</b> ปรากฎวีดีโอของทุกคน เหมาะสำหรับห้องประชุมขนาดเล็ก ที่ต้องการเห็นวีดีโอทุกจุด</li>
		<li><b style="color: orange">Large Conference</b> ปรากฎวีดีโอเฉพาะผู้บรรยายและประธาน เหมาะสำหรับห้องประชุมขนาดใหญ่ เฉพาะประธานสามารถเห็นผู้ร่วมประชุมทุกคน</li>
		<li><b style="color: orange">Video Conference</b> ปรากฎวีดีโอทุกคน มีเสียงเฉพาะประธานและผู้ที่ได้รับอนุญาต เหมาะสำหรับห้องประชุมขนาดเล็ก</li>
	</ul>
	<br>
	<p align="right"><input type="button" onclick="$('.conf_info').hide();" class="button btGray" value=" close "></p>
</div>
<center>