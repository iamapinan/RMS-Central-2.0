<?php
include 'initial.min.php';

$uinf = $_GET['indentity'];
$iCUSTOME = $uinf;
$Uinfo = UserInfo($uinf);
if($Uinfo['user']==''||$Uinfo['session']=='Protected User')
{
	header('location: /404');
}


if(!isset($_SESSION['loginid'])&&$_GET['user']!='blog') header('location: /login');
$ActiveMN[$_REQUEST['user']] = 'active';
	
if(!isset($_GET['user'])) $ActiveMN['home'] = 'active';
    //Assign Header
	$p->PageHeader = '<META NAME="ROBOTS" CONTENT="ALL">';
	$p->PageHeader .= '<meta property="og:title" content="'.str_replace("|"," ",$Uinfo['fullname']).'" />'."\n";
	$p->PageHeader .= '<meta property="og:site_name" content="Boundless Learning" />'."\n";
	$p->PageHeader .= '<meta property="og:url" content="'.conf('url').'profile/'.$Uinfo['user'].'" />'."\n";
	$p->PageHeader .= '<link rel="canonical" href="'.conf('url').'profile/'.$Uinfo['user'].'" />'."\n";
	$p->PageHeader .= '<meta name="title" content="'.str_replace("|"," ",$Uinfo['fullname']).'" />'."\n";
	$p->PageHeader .= '<meta property="og:image" content="'.conf('url').'user/'.$Uinfo['user'].'/'.str_replace('orig','m',$Uinfo['avatar']).'" />'."\n";
	$p->PageHeader .= '<link rel="image_src" href="'.conf('url').'user/'.$Uinfo['user'].'/'.str_replace('orig','m',$Uinfo['avatar']).'" />'."\n";

//Assign Page Title
	$p->PageTitle = str_replace("|"," ",$Uinfo['fullname'])."\n";
    
if($Uinfo['bday']=='')
	$Uinfo['bday'] = 'ยังไม่ได้เพิ่มวันเกิด';

if($Uinfo['school']=='')
	$Uinfo['school']='ยังไม่ได้เพิ่มโรงเรียนหรือสถานศึกษา';

if($Uinfo['gender']=='m')
	$Uinfo['gender'] = 'ชาย';
else
	$Uinfo['gender'] = 'หญิง';

if($Uinfo['language']=='th')
	$Uinfo['language'] = 'ไทย';
else
	$Uinfo['language'] = 'English';

if($Uinfo['about']=='')
	$Uinfo['about'] = 'ยังไม่มีข้อมูล';

	$msg_status = ($Uinfo['status']==0) ? 'ท่านต้องยืนยันบัญชีของท่านก่อน โดยตรวจสอบอีเมล์ในกล่องจดหมายขาเข้า' : '';
	$cond = ' url = "'.@$_REQUEST['uid'].'"';

	echo $p->globalheader();
	echo '<script type="text/javascript" src="/library/ajaxfileupload.js"></script>';
	echo '<div id="container">'."\n";
	echo '<script type="text/javascript">
		<!--
		 $(function() {
		   $(\'a[rel=tooltip]\').tipsy({fade: true, gravity: \'n\'});
		 });

		function ajaxFileUpload()
		{

			$(\'#alert_status\').slideUp();
			$.ajaxFileUpload
			(
				{
					url:"/save.php?action=userbanner",
					secureuri:true,
					fileElementId:"banneruploader",
					dataType: "json",
					success: function (data, status)
					{

					   if(data.error==0){
							$(\'#ubn\').attr(\'style\',\'background: #333 url(/image?width=988&height=310&cropratio=4:1&image=/user/'.$_SESSION['loginid']['nickname'].'/gallery/banner/\'+data.img+\') no-repeat  center center;\');
					   }
					  else
					   {
							$("#alert_status .text").html(data.msg);
							 $(\'#alert_status\').slideDown();
					   }
					}
				}
			)
			return false;
		}
	//-->
	</script>';

	eval('?>' . $p->getC('header'). '<?php ');
	echo $p->toolbar($uinf)."\n";
	echo '<div id="popup-view"><div class="view-html"></div><p><span class="btclose button btGray" onclick="$(\'#popup-view\').hide()" style="padding: 4px 21px;font-size: 16px;">Close</span></p></div>';
	echo '<div id="ContentBody">';
	if($msg_status!='')
		echo '<div id="status_message">'.$msg_status.'</div>';

	if($Uinfo['banner']==null)
	echo '<div id="ubn" style="background: #333 url(/image?width=988&height=310&cropratio=4:1&image=/library/images/sample_ubuntu1.jpg) no-repeat center center;">';
	else
	echo '<div id="ubn" style="background: #333 url(/image?width=988&height=310&cropratio=4:1&image=/user/'.$uinf.'/gallery/banner/'.$Uinfo['banner'].') no-repeat center center;">';
	if($_GET['indentity']==$_SESSION['loginid']['nickname']&&$bs['name'] != 'Internet Explorer')
		echo '<a href="javascript:;" class="botton btTransparent" onClick="$(\'#banneruploader\').click();" rel="tooltip" original-title="รูปภาพที่ใช้ต้องเป็นไฟล์ jpg, png และมีความขนาด กว้าง 1024px สูง 220px ขึ้นไป โดยจะตัดส่วนบนและล่างของภาพออก"><i class="fa fa-pencil"></i> เปลี่ยนรูปภาพปก</a>';
	echo '<form action method="post" id="uploadfrm" enctype="multipart/form-data">

	<input type="file" name="banneruploader" id="banneruploader" onChange="ajaxFileUpload();" style="width: 100px;height:20px;opacity: 0;position:relative;z-index: 0;" accept="image/jpeg,image/png,image/bmp">
	</form>';
	echo '<div class="profile-top-info"><span class="title-name">'.str_replace('|','&nbsp;',$Uinfo['fullname']).'</span>'; //Username
	echo '<span class="profile-detail"><i class="fa fa-flag"></i> '.uRole($Uinfo['role']).'&nbsp; / &nbsp;<i class="fa fa-graduation-cap"></i>  '.orgname($Uinfo['org']).'</span>';
	echo '</div>'; // Use info

	if($iCUSTOME==null)
	{
		echo  $social->uAvatar($_SESSION['loginid']['nickname']);
	}
	else
	{
		echo  $social->uAvatar($iCUSTOME,'home');
	}
	echo '</div>';
//left bar
	echo '<div id="leftContainer">';

	if(isset($_SESSION['loginid']['nickname'])&&(friendCheck($Uinfo['ssid'])=='true'||friendCheck($Uinfo['ssid'])=='me'))
	{
			echo	'
			<div class="menu">
			<ul>
			<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=home">
			<li class="erasein '.@$ActiveMN['home'].'"><i class="fa fa-home fa-lg"></i> &nbsp;My Home</li>
			</a>
			<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=account">
			<li class="erasein '.@$ActiveMN['account'].'"><i class="fa fa-user fa-lg"></i> &nbsp;My Account</li>
			</a>';
		if(getConf('studio')==1){
			echo '<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=contents">
			<li class="erasein '.@$ActiveMN['contents'].'"><i class="fa fa-play-circle fa-lg"></i> &nbsp;My AcuStudio</li>
			</a>';
		}
		if(getConf('conference')==1){
			echo '<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=conference">
			<li class="erasein '.@$ActiveMN['conference'].'"><i class="fa fa-video-camera fa-lg"></i>  &nbsp;My Conference</li>
			</a>';
		}
		if(getConf('live')==1){
			echo '<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=live">
			<li class="erasein '.@$ActiveMN['live'].'"><i class="fa fa-bullhorn fa-lg"></i> &nbsp;My AcuLive</li>
			</a>';
		}
		if(getConf('blog')==1){
			echo '<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=blog">
			<li class="erasein '.@$ActiveMN['blog'].'"><i class="fa fa-quote-right fa-lg"></i> &nbsp;My Blog</li>
			</a>';
		}
        if(getConf('devmode')==1&&$Uinfo['role']>=5){
			echo '<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=class">
			<li class="erasein '.@$ActiveMN['class'].'"><i class="fa fa-cube fa-lg"></i> &nbsp;My Organization</li>
			</a>';
		}
		if(getConf('devmode')==1&&$Uinfo['role']>=5){
			echo '<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=course">
			<li class="erasein '.@$ActiveMN['course'].'"><i class="fa fa-book fa-lg"></i> &nbsp;My Courses</li>
			</a>';
		}
		if(getConf('devmode')==1&&$Uinfo['role']>=5){
			echo '<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=group">
			<li class="erasein '.@$ActiveMN['group'].'"><i class="fa fa-users fa-lg"></i> &nbsp;My Groups</li>
			</a>';
		}


		if((int)$Uinfo['role']>=8||$Uinfo['admin']==1){
			echo '
			<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=userman">
			<li class="erasein '.@$ActiveMN['userman'].'"><i class="fa fa-umbrella fa-lg"></i> &nbsp;User Management</li>
			</a>';
			echo '
			<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=page">
			<li class="erasein '.@$ActiveMN['page'].'"><i class="fa fa-file fa-lg"></i> &nbsp;Page Management</li>
			</a>';
			echo '
			<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=settings">
			<li class="erasein '.@$ActiveMN['settings'].'"><i class="fa fa-toggle-on fa-lg"></i> &nbsp;Settings</li>
			</a>';
			}
			if((int)$Uinfo['role']==8){
				echo '<a href="'.conf('url').'profile/'.$_GET['indentity'].'?user=version">
				<li class="erasein '.@$ActiveMN['version'].'"><i class="fa fa-info-circle fa-lg"></i> &nbsp;About</li>
				</a>';
			}


		echo '</ul>
			</div>';
	}
echo '</div>';

//middle content
echo '<div id="middleContainer">';
if(!isset($_GET['user'])||$_GET['user']=='home')
{

	
}
if($_GET['user']=='version')
{
	if((int)$Uinfo['role']<=6&&$Uinfo['admin']==0) header('location: /my');
	echo '
		<div id="boxUI">
			<div class="header"><i class="fa fa-info-circle fa-lg"></i> About RMS</div>';
		echo '
			<div class="boxContent">
			<p><span class="label">Last update</span> <span>'.date ("d M y H:i:s", filemtime(conf('dir').'version.rms')).'</span></p>
			<p><span class="label">Software</span> <span>'.$version['name'].'</span></p>
			<p><span class="label">RMS Version</span> <span>'.conf('version').'</span></p>
			<p><span class="label">Patch</span> <span>'.$version['patch'].'</span></p>
			<p><span class="label">API Version</span> <span>'.conf('idm_apiversion').'</span></p>
			<p><span class="label">Server IP</span> <span>'.conf('domain').'</span></p>
			<p><span class="label">Organization</span> <span>'.conf('orgname').'</span></p>
			<p><span class="label">Time zone</span> <span>'.$timezone.'</span></p>
			<p><span class="label">Minimum Resolution</span> <span>1024x768</span></p>
			<p>&nbsp;</p>
			<p><span class="label">&nbsp;</span> <span>
			<a href="/release-note" class="button btGray">Release note</a>
			</span></p>
			</div>
		</div>';
}
if($_GET['user']=='account')
{

		$sql_school_info = mysql_query('select sname from '.conf('table_prefix').'_school where sid='.$Uinfo['org']);
	$sci = @mysql_fetch_array($sql_school_info);
	$grp = explode(',', $Uinfo['remark']);
	$grp = explode('=', $grp[0]);
	$roleinfo = uRole($Uinfo['role']);
	$levinfo = Level($Uinfo['level']);

	echo '<div id="boxUI">
			<p class="header"><i class="fa fa-user fa-lg"></i> ข้อมูลบัญชีผู้ใช้ &nbsp;<a href="'.conf('url').'edit_profile"><i class="fa fa-pencil"></i> Edit Profile</a></p>

				<span class="boxContent" id="class-alert" style="font-size: 12px;">
					<p style="width: 100;"><span class="label">ชื่อ-สกุล</span><span>'.str_replace('|','&nbsp;',$Uinfo['fullname']).'</span></p>
					<p style="width: 100;"><span class="label">วันเกิด</span><span>'.$Uinfo['bday'].'</span></p>
					<p style="width: 100;"><span class="label">เพศ</span><span>'.$Uinfo['gender'].'</span></p>
					<p style="width: 100;"><span class="label">หน่วยงาน</span><span>'.$sci['sname'].'</span></p>
					<p style="width: 100;"><span class="label">อีเมล์</span><span>'.$Uinfo['email'].'</span></p>
					<p style="width: 100;"><span class="label">เบอร์โทร</span><span>'.$Uinfo['mobile'].'</span></p>
					<p style="width: 100;"><span class="label">สถานะ</span><span>'.$roleinfo.'</span></p>';

					if($Uinfo['role']==5)
						echo '<p style="width: 100%;"><span class="label">กลุ่มสาระ</span><span>'.$grp[1].'</span></p>';
					if($Uinfo['role']==3)
						echo '<p style="width: 100%;"><span class="label">ระดับชั้น</span><span>'.$levinfo['name'].' ห้อง '.$Uinfo['class'].'</span></p>';

			echo '</span>
			</div>';

		echo '
		<div id="boxUI">
			<div class="header"><i class="fa fa-at fa-lg"></i> ข้อมูลการเข้าสู่ระบบ AcuLearn</div>';
		echo '
			<div class="boxContent">
				<p><span class="label" >เซิร์ฟเวอร์</span> <span>'.str_replace('/', '', str_replace('http:', '', conf('idm_server'))).'</span></p>
				<p><span class="label" >องค์กร</span> <span>'.$Uinfo['org'].'</span></p>
				<p><span class="label" >เข้าสู่ระบบโดย</span> <span>AcuManager</span></p>
				<p><span class="label" >รหัสผู้ใช้</span> <span>'.$Uinfo['user'].'</span></p>
				<p><span class="label">รหัสผ่าน</span> <span><i id="passview" data-pass="'.openssl_decrypt($Uinfo['password'], 'aes128', '').'">ใช้รหัสเดียวกันกับที่เข้าสู่ระบบนี้</i> &nbsp;
						<span class="aculearn_password_toolbox">
							<a href="#" id="acupassview" title="View aculearn password.">
								<i class="fa fa-eye"></i>
							</a> 
							<a href="#" id="acupassch" title="Change aculearn password.">
								<i class="fa fa-pencil" ></i>
							</a>
						</span>
					</span></p>';
			if($Uinfo['org']=='') 
				{
					echo '<p><span class="label" >&nbsp;</span> <span>บัญชีนี้ไม่มีสามารถใช้งาน Aculearn</span></p';
				}
			echo '<br>
				<p><span class="label">&nbsp;</span><a href="'.conf('idm_server').'aculearn-idm/setup/acuconsole7_setup.exe" class="btBlue button"><icon class="fa fa-cloud-download"></icon> ดาวน์โหลด AcuConsole 7</a>
			</div>
		</div>';

}

if($_GET['user']=='userman')
{
	if((int)$Uinfo['role']<=6&&$Uinfo['admin']==0) header('location: /my');

	?>
	<link href="/library/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/library/jui/css/smoothness/jquery-ui-1.10.4.custom.min.css">
	<script src="/library/jui/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script language="javascript" src="/library/jtable/jquery.jtable.js"></script>
		<div id="boxUI">
			<div class="header"><i class="fa fa-umbrella fa-lg"></i> User Management </div>
			<div class="boxContent">
					<div class="filtering" style="display:block;padding: 5px 10px 15px 10px;">
						<form>
							Name: <input type="text" name="name" id="shname" class="text-input" style="width: 280px;"/>
							<button id="LoadRecordsButton" class="button btBlue">Search</button>
						</form>

					</div>

				<div class="uTable"></div>
			</div>
		</div>


	<script type="text/javascript">

		$(document).ready(function () {

			//Prepare jtable plugin
			$('.uTable').jtable({
					title: 'User List',
				paging: true,
				sorting: true,
				pageSize: 10,
				multiSorting: true,
				selecting: true, //Enable selecting
				multiselect: true, //Allow multiple selecting
				selectingCheckboxes: true, //Show checkboxes on first column
				selectOnRowClick: false, //Enable this to only select using checkboxes
				openChildAsAccordion: true,
				defaultSorting: 'ssid ASC',
				actions: {
					listAction: '/list.min.php?qt=listSchool&org=<?php echo $Uinfo['org']; ?>',
					deleteAction: '/list.min.php?qt=deleteUser&org=<?php echo $Uinfo['org']; ?>',
					updateAction: '/list.min.php?qt=updateUser&org=<?php echo $Uinfo['org']; ?>',
					createAction: '/list.min.php?qt=createUser&org=<?php echo $Uinfo['org']; ?>'
				},
				toolbar: {
					hoverAnimation: true, //Enable/disable small animation on mouse hover to a toolbar item.
					hoverAnimationDuration: 60, //Duration of the hover animation.
					hoverAnimationEasing: undefined, //Easing of the hover animation. Uses jQuery's default animation ('swing') if set to undefined.
					items: [{
						text: 'Approve',
						cssClass: 'verify_all_selected',
					},{
						text: 'Verify',
						cssClass: 'set_all_selected',
					},{
						text: 'Delete',
						cssClass: 'delete_all_selected',
					}] //Array of your custom toolbar items.
				},
				fields: {
					ssid: {
						key: true,
						create: false,
						edit: false,
						list: false
					},
					Exams: {
						title: '',
						width: '5%',
						sorting: false,
						edit: false,
						create: false,
						display: function (studentData) {
							//Create an image that will be used to open child table
							var $img = $('<img src="/library/images/list_metro.png" />');
							//Open child table when user clicks the image
							$img.click(function () {
								$('.uTable').jtable('openChildTable',
										$img.closest('tr'), //Parent row
										{
										title: studentData.record.fullname + ' - Info',
										actions: {
											listAction: '/list.min.php?qt=uinfo&uid=' + studentData.record.ssid,
										},
										fields: {
											ssid: {
												type: 'hidden',
												defaultValue: studentData.record.ssid
											},
											StudentExamId: {
												key: true,
												create: false,
												edit: false,
												list: false
											},
											title: {
												title: 'Title',
												width: '30%',
												type: 'text',
											},
											value: {
												title: 'Value.',
												width: '50%',
												type: 'text'
											},
										}
									}, function (data) { //opened handler
										data.childTable.jtable('load');
									});
							});
							//Return image to show on the person row
							return $img;
						}
					},
					fullname: {
						title: 'Name',
						width: '20%',
						type: 'text',
						inputClass: 'validate[required]'
					},
					citizen_id: {
						title: 'เลขบัตรประชาชน',
						width: '15%',
						type: 'text',
						list: false,
						inputClass: 'validate[required]'
					},
					password: {
						title: 'Password',
						type: 'password',
						list: false,
						inputClass: 'validate[required]',
						defaultValue: ''
					},
					gender: {
						title: 'Gender',
						width: '7%',
						type: 'select',
						list: false,
						options: { 'm': 'Male', 'f': 'Female' }
					},
					update: {
						title: 'Sync',
						width: '7%',
						type: 'select',
						defaultValue: '0',
						edit: false,
						options: { '1': 'Yes', '0': 'No'},
					},
					email: {
						title: 'Email address',
						width: '15%',
						type: 'text',
						list: false,
						inputClass: 'validate[required,custom[email]]'
					},
					mobile: {
						title: 'Mobile',
						type: 'text',
						width: '14%',
						list: false,
					},
					bday: {
						title: 'Birth day',
						type: 'text',
						width: '14%',
						list: false,
					},
					role: {
						title: 'Role',
						type: 'select',
						width: '12%',
						options: { '1': 'บุคคลทั่วไป', '2': 'ผู้ปกครอง', '3': 'นักเรียน' , '4': 'พนักงานทั่วไป', '5': 'ครู', '6':'ผู้บริหาร'},
						inputClass: 'validate[required]'
					},
					admin: {
						title: 'Admin',
						type: 'select',
						width: '5%',
						options: { '1':'Admin' , '0':'User'},
						list: false,
					},
					org: {
						title: 'รหัสโรงเรียน',
						type: 'text',
						width: '13%',
						list: true
					},
					scn: {
						title: 'โรงเรียน',
						type: 'text',
						edit: false,
						width: '13%',
						list: true
					},
					grade: {
						title: 'Grade',
						width: '7%',
						list: false,
						type: 'select',
						options: { '0':'', '1': 'ป.1', '2': 'ป.2', '3': 'ป.3' , '4': 'ป.4', '5': 'ป.5', '6':'ป.6', '7':'ม.1', '8': 'ม.2','9': 'ม.3','10': 'ม.4','11': 'ม.5','12': 'ม.6'}
					},
					position: {
						title: 'Position',
						type: 'text',
						list: false,
						width: '12%'
					},
					timestamp: {
						title: 'Register date',
						type: 'text',
						edit: false,
						list: true,
						width: '23%'
					},
					status: {
						title: 'Verify',
                        					type: 'select',
						options: { '0': 'No', '1': 'Yes' }
					},
					verify: {
						title: 'Approve',
						type: 'select',
						width: '8%',
						options: { '0': 'No', '1': 'Yes', '2': 'Waiting' }
					}
				},
				rowInserted: function (event, data) {
						$('.uTable').jtable(data.row);
				}
			});
			//Re-load records when user click 'load records' button.
			$('#LoadRecordsButton').button().click(function (e) {
				e.preventDefault();
				$('.uTable').jtable('load', {
					fullname: $('#shname').val(),
				});
			});

			//Load all records when page is first shown
			$('.uTable').click();
			//Load student list from server
			$('.uTable').jtable('load');
			$('.verify_all_selected').click(function () {
				var $selectedRows = $('.uTable').jtable('selectedRows');
                if ($selectedRows.length > 0) {
                    $selectedRows.each(function () {
                        var record = $(this).data('record');
						$.get( "/profilesave.php?action=verify_stat&uid="+record.ssid, function(data) {
						  $( ".result" ).html(data);
						  console.log("Verify : "+record.ssid+" : "+record.fullname+" = "+data);
						  $('.uTable').jtable('reload');
						});
                    });
                }
			});
			$('.set_all_selected').click(function () {
				var $selectedRows = $('.uTable').jtable('selectedRows');
                if ($selectedRows.length > 0) {
                    $selectedRows.each(function () {
                        var record = $(this).data('record');
						$.get( "/profilesave.php?action=change_status&uid="+record.ssid, function(data) {
						  $( ".result" ).html(data);
						  console.log("Status change : "+record.ssid+" : "+record.fullname+" = "+data);
						  $('.uTable').jtable('reload');
						});
                    });
                }
			});
			$('.delete_all_selected').click(function () {
				var $selectedRows = $('.uTable').jtable('selectedRows');

                if ($selectedRows.length > 0) {
                    var rc=confirm("Will delete all selected records. Are you sure?");
					if (rc==true)
						{
							$selectedRows.each(function () {
								var record = $(this).data('record');
								$('.uTable').jtable('deleteRows', $selectedRows);
							});
						}
                }
			});
		});

	</script>
	<?php

}
if($_GET['user']=='settings')
{
		if((int)$Uinfo['role']<=6&&$Uinfo['admin']==0) header('location: /my');
		if(getConf('auto_verify')==1) $verifybox = 'checked';
		if(getConf('auto_approve')==1) $approvebox = 'checked';
		if(getConf('auto_update')==1) $updatebox = 'checked';
		if(getConf('studio')==1) $studiobox = 'checked';
		if(getConf('conference')==1) $conferencebox = 'checked';
		if(getConf('live')==1) $livebox = 'checked';
		if(getConf('blog')==1) $blogbox = 'checked';
		if(getConf('devmode')==1) $devmode = 'checked';
		?>
	  <link rel="stylesheet" href="/library/toggle_style.css">
	  <link rel="stylesheet" href="/library/plugin/redactor/redactor.css" type="text/css">
      	  <script src="/library/plugin/redactor/redactor.min.js"></script>
	  <script src="/library/plugin/redactor/fontsize.js"></script>
	   <script src="/library/plugin/redactor/fontcolor.js"></script>
	  <link rel="stylesheet" type="text/css" href="/library/plugin/uploadifive/uploadifive.css">
	  <script type="text/javascript" src="/library/plugin/uploadifive/jquery.uploadifive.min.js"></script>
	  <script type="text/javascript">
	  <!--
		$('document').ready(function(){
			$("input#sw-proved").change(function(){
				if($(this).is(":checked"))	$.post( "/save.php", { q: "settings", tp: "prove", set: 1 } );
				else 	$.post( "/save.php", { q: "settings", tp: "prove", set: 0 } );
			});
			$("input#sw-devmode").change(function(){
				if($(this).is(":checked"))	$.post( "/save.php", { q: "settings", tp: "devmode", set: 1 } );
				else 	$.post( "/save.php", { q: "settings", tp: "devmode", set: 0 } );
			});
			$("input#sw-verify").change(function(){
				if($(this).is(":checked"))	$.post( "/save.php", { q: "settings", tp: "verify", set: 1 } );
				else 	$.post( "/save.php", { q: "settings", tp: "verify", set: 0 } );
			});
			$("input#sw-update").change(function(){
				if($(this).is(":checked"))	$.post( "/save.php", { q: "settings", tp: "auto-update", set: 1 } );
				else 	$.post( "/save.php", { q: "settings", tp: "auto-update", set: 0 } );
			});

			$("input#sw-studio").change(function(){
				if($(this).is(":checked"))	$.post( "/save.php", { q: "settings", tp: "auto-studio", set: 1 } );
				else 	$.post( "/save.php", { q: "settings", tp: "auto-studio", set: 0 } );
			});
			$("input#sw-conference").change(function(){
				if($(this).is(":checked"))	$.post( "/save.php", { q: "settings", tp: "auto-conference", set: 1 } );
				else 	$.post( "/save.php", { q: "settings", tp: "auto-conference", set: 0 } );
			});
			$("input#sw-live").change(function(){
				if($(this).is(":checked"))	$.post( "/save.php", { q: "settings", tp: "auto-live", set: 1 } );
				else 	$.post( "/save.php", { q: "settings", tp: "auto-live", set: 0 } );
			});
			$("input#sw-blog").change(function(){
				if($(this).is(":checked"))	$.post( "/save.php", { q: "settings", tp: "auto-blog", set: 1 } );
				else 	$.post( "/save.php", { q: "settings", tp: "auto-blog", set: 0 } );
			});
			$('#ftsetting').redactor({imageUpload: '/upload.php?ft=blog_img&rq='+<?php echo rand(10,10);?>,plugins: ['fontsize','fontcolor'],focus: true,buttonSource: true,buttons: ['html', 'formatting', 'bold', 'italic','unorderedlist','orderedlist','outdent','indent','orderedlist','horizontalrule']});
		});

		function siteconfigsave(datasave)
		{
			$('.ft_title').append(' <font color="#8dd69f">Saving...</font>');
			$.post( "/save.php?siteconfig=sysupdate&q=footer", { data: datasave}).done(function( data ) {
				if(data==1){
					$('.ft_title').append(' <font color="green">Saved</font>');
					window.location.reload();
				}
			});

		}
		$(function() {
			$('#bnupload').uploadifive({
			  'uploadScript'  : '/save.php',
			  'formData'  : {'siteconfig':'sysupdate','q':'banner'},
				  'method'   : 'post',
				  'fileObjName' : 'Filedata',
				  'buttonText'   : 'Browse',
				  'auto'    : true,
				  'fileSizeLimit'   : '5MB',
				  'onUploadComplete'  : function(file, data) {
					$('.bn_title').append(' <font color="green">Saved</font>');
					window.location.reload();
				  }
			});

		});
	  //-->
	  </script>
		<div id="boxUI">
			<div class="boxContent">
			<p>
				<i class="fa fa-toggle-on fa-lg"></i>
				<a href="?user=settings&config=system" class="button <?php if(!isset($_GET['config'])||$_GET['config']=='system') echo ''; else echo 'btGray';?>">System</a>
				<a href="?user=settings&config=feature" class="button <?php if($_GET['config']=='feature') echo ''; else echo 'btGray';?>">Features</a>
				<a href="?user=settings&config=personalize" class="button <?php if($_GET['config']=='personalize') echo ''; else echo 'btGray';?>">Personalize</a>
			</p>
			<br>
			<?php
				if(!isset($_GET['config'])||$_GET['config']=='system'){
			?>
				<div class="boxLeft">
					<h2 class="header">System settings</h2>
					<p class="settings_title">Auto approve new user.</p>
						<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="sw-proved" <?php echo $approvebox;?>>
						<label class="onoffswitch-label" for="sw-proved">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>
					<br>
					<p class="settings_title">Auto verify new user.</p>
						<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="sw-verify" <?php echo $verifybox;?>>
						<label class="onoffswitch-label" for="sw-verify">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>
					<p class="settings_title">Auto system update.</p>
						<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="sw-update" <?php echo $updatebox;?>>
						<label class="onoffswitch-label" for="sw-update">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>
					<p class="settings_title">Development mode.</p>
						<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="sw-devmode" <?php echo $devmode;?>>
						<label class="onoffswitch-label" for="sw-devmode">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>

				</div>
				<?php } if($_GET['config']=='feature'){ ?>
				<div class="boxLeft">
					<h2 class="header">Feature settings</h2>
					<p class="settings_title">My AcuStudio</p>
						<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="sw-studio" <?php echo $studiobox;?>>
						<label class="onoffswitch-label" for="sw-studio">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>
					<br>
					<p class="settings_title">My AcuConference</p>
						<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="sw-conference" <?php echo $conferencebox;?>>
						<label class="onoffswitch-label" for="sw-conference">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>
					<p class="settings_title">My AcuLive</p>
						<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="sw-live" <?php echo $livebox;?>>
						<label class="onoffswitch-label" for="sw-live">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>
					<p class="settings_title">My Blog</p>
						<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="sw-blog" <?php echo $blogbox;?>>
						<label class="onoffswitch-label" for="sw-blog">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>
				</div>
			<?php
				}
				if($_GET['config']=='personalize'){
			?>
			<style type="text/css">
				.redactor-toolbar {
				  background: #0FA4C5;
				  width: 513px !important;
				}
				.redactor-toolbar li a {
					color: #fff;
				}
				// buttons hover state
				.redactor-toolbar li a:hover {
					background: #d70a16;
					color: #fff;
				}
				 
				// buttons active state
				.redactor-toolbar li a:active,
				.redactor-toolbar li a.redactor-act {
					background: #000;
					color: #aaa;
				}
			</style>
			<div class="boxLeft">
					<h2 class="header">Personalize settings</h2>
					<p class="bn_title">Organize Banner</p><br>
					<div class="setting-image"><?php echo GetConf('header');?></div>
					<p style="color: #ff0000;">Width 980px, Height 110px</p>
					<br>
					<input type="file" name="bnsetting" id="bnupload">
					<br><br>
					<p class="ft_title">Footer</p>
					<div id="ftsetting" style="width: 471px;height: 105px;border: 1px solid #aaa;" contenteditable=true><?php echo GetConf('footer_text');?></div>
					<button onClick="siteconfigsave($('#ftsetting').redactor('code.get'))" class="button btGreen">Save</button>
			</div>
			<?php
				}
			?>
		</div>

		<?php
}
if($_GET['user']=='course')
{

    	$cimage = 'holder.js/100x80/random';
   	$sql = mysql_query("SELECT * FROM ".conf('table_prefix')."_course WHERE `uid`=".$Uinfo['ssid']." AND `type`='public'");
	$sqlx = mysql_query("SELECT * FROM ".conf('table_prefix')."_course WHERE `sid`=".$Uinfo['org']." AND `uid`=".$Uinfo['ssid']." AND `type`='extra' ORDER BY course_id DESC");
    	$sqla = mysql_query("SELECT * FROM ".conf('table_prefix')."_course WHERE `sid`=".$Uinfo['org']." AND `uid`=".$Uinfo['ssid']." AND `type`='standard' ORDER BY course_id DESC");

	echo '<div id="boxUI">
		<div class="header">
		<i class="fa fa-book fa-lg"></i> My Courses
	';
	if($Uinfo['role']>=5)
		echo  '<a href="/new_course"><span class="box-option btBlue button"><i class="fa fa-file"></i> สร้างรายวิชา</span></a>';
			//echo '<span class="pos-right"><input type="text" name="css" id="cs-search" placeholder="ค้นหา"></span>';
	echo '</div>';
     	
	echo '<div class="boxContent" id="idmCL">';
	echo '<div class="thumbnail-list">';
	
	
	$strSQL = mysql_query('select * from '.conf('table_prefix').'_course_type order by ctid asc');
	while($result = mysql_fetch_array($strSQL))
		{
			echo '<h2>'.$result['thText'].'</h2>';
			switch ($result['name']) {
				case 'standard':
					while($cs = mysql_fetch_array($sqla)){
					$class = classinfo($cs['class_id']);
					$standardgroup = GroupInfo($cs['main_group']);
					$teacher_json = json_decode($cs['alternet_teacher_id']);
					foreach($teacher_json as $teacherOnce)
					{
						$tear = UserById($teacherOnce);
						@$teacherText[1] .= str_replace('|',' ',$tear['fullname']).',';
					}

			        		if($cs['img']=='') $img = $cimage; else $img = image_resize($cs['img'],100,80);
					
					echo '
						<div class="list-items">
							<img src="'.$img.'">
							<div class="detail">
								<h3>'.$cs['cname'].'</h3>
								 <p>ชั้น '.$class['text'].'/'.$class['cls_number'].'</p>
								 <p>กลุ่มสาระ '.$standardgroup['name'].'</p>
								 <p>โดย '.$teacherText[1].'</p>
							</div>
							<div class="listbt">
								<button class="button btGray"><i class="fa fa-child"></i> นักเรียน</button>
								<button class="button btGray"><i class="fa fa-pencil"></i> แก้ไข</button>
								<button class="button btGray" disabled><i class="fa fa-trash-o"></i> ลบ</button>
								<button class="button btBlue" onClick="lightbox('.$cs['course_id'].')"><i class="fa fa-simplybuilt"></i> เริ่มห้องเรียน</button>
							</div>
						</div>
						';

					}
					if(mysql_num_rows($sqla)==0){
				            	echo '<p class="divider"></p><p align="center">ไม่พบรายวิชาในกลุ่มนี้ <a href="/new_course">คลิกที่นี่</a> เพื่อสร้างวิชาใหม่</p><p class="divider"></p>';
				        	}
				break;
				case 'extra':
					while($cs = mysql_fetch_array($sqlx)){
			        		if($cs['img']=='') $img = $cimage; else $img = image_resize($cs['img'],100,80);
			        		$class = classinfo($cs['class_id']);
			        		$teacher_json = json_decode($cs['alternet_teacher_id']);
					foreach($teacher_json as $teacherOnce)
					{
						$tear = UserById($teacherOnce);
						@$teacherText[2] .= str_replace('|',' ',$tear['fullname']).',';
					}

					echo '
						<div class="list-items">
							<img src="'.$img.'">
							<div class="detail">
								<h3>'.$cs['cname'].'</h3>
								  <p>ชั้น '.$class['text'].'/'.$class['cls_number'].'</p>
								  <p>โดย '.$teacherText[2].'</p>
							</div>
							<div class="listbt">
								<button class="button btGray"><i class="fa fa-pencil"></i> แก้ไข</button>
								<button class="button btGray" disabled><i class="fa fa-trash-o"></i> ลบ</button>
								<button class="button btBlue" onClick="lightbox('.$cs['course_id'].')"><i class="fa fa-simplybuilt"></i> เริ่มห้องเรียน</button>
							</div>
						</div>
						';

					}
					if(mysql_num_rows($sqlx)==0){
				            	echo '<p class="divider"></p><p align="center">ไม่พบรายวิชาในกลุ่มนี้ <a href="/new_course">คลิกที่นี่</a> เพื่อสร้างวิชาใหม่</p><p class="divider"></p>';
				        	}
				break;
				case 'public':
					while($cs = mysql_fetch_array($sql)){
			        		if($cs['img']=='') $img = $cimage; else $img = image_resize($cs['img'],100,80);
			        		$teacher_json = json_decode($cs['alternet_teacher_id']);
					foreach($teacher_json as $teacherOnce)
					{
						$tear = UserById($teacherOnce);
						@$teacherText[3] .= str_replace('|',' ',$tear['fullname']).',';
					}

					echo '
						<div class="list-items">
							<img src="'.$img.'">
							<div class="detail">
								<h3>'.$cs['cname'].'</h3>
								 <p>'.str_pad(mb_substr($cs['cdetail'],0, 60, 'UTF-8'),63,'.').'</p>
								 <p>โดย '.$teacherText[3].'</p>
							</div>
							<div class="listbt">
								<button class="button btGray"><i class="fa fa-pencil"></i> แก้ไข</button>
								<button class="button btGray" disabled><i class="fa fa-trash-o"></i> ลบ</button>
								<button class="button btBlue" onClick="lightbox('.$cs['course_id'].')"><i class="fa fa-simplybuilt"></i> เริ่มห้องเรียน</button>
							</div>
						</div>
						';

					}
					if(mysql_num_rows($sql)==0){
				            	echo '<p class="divider"></p><p align="center">ไม่พบรายวิชาในกลุ่มนี้ <a href="/new_course">คลิกที่นี่</a> เพื่อสร้างวิชาใหม่</p><p class="divider"></p>';
				        	}
				break;
			}
		}
	echo '</div>';
	echo '</dvi>';
	echo '</div>';
	unset($teacherText);
}
if($_GET['user']=='class')
{

    echo '<script language="javascript">
        <!--
        $(document).ready(function(){
            
            $(\'#ssettype\').change(function(){
                var classhtml = "<h3>กำหนดจำนวนห้องเรียนในแต่ละชั้นของคุณ</h3><p class=\"divider\"></p>";
                    for(x=$(this).find(\':selected\').data(\'min\');x<=$(this).find(\':selected\').data(\'max\');x++){
                        if(x<=6)
                            classhtml += "<div class=\"form-row\"><label>ประถมศึกษาปีที่ "+x+" จำนวนห้อง:</label> <input type=\"number\" name=\"class_"+x+"\" value=\"1\" style=\"width: 30px\" max=15 min=1></div>"
                        else if(x>=7)
                            classhtml += "<div class=\"form-row\"><label>มัธยมศึกษาปีที่ "+(x-6)+" จำนวนห้อง:</label> <input type=\"number\" name=\"class_"+x+"\" value=\"1\" style=\"width: 30px\" max=15 min=1></div>"
                    }
                    classhtml += "<p class=\"divider\"></p><button class=\'btGreen button\'>บันทึกการตั้งค่าห้องเรียน</button>"  
                    $(".class_option").html(classhtml);
                
            })
        });
        //-->
    </script>';

	echo '<div id="boxUI">
				<div class="header"><i class="fa fa-cube fa-lg"></i> My Organization
				<a href="/new_classroom"><span class="box-option btBlue button"><i class="fa fa-file"></i> สร้างห้องเรียนพิเศษ</span></a>
				</div>';
	echo '<div class="boxContent" id="idmCL">';
	echo '<div class="thumbnail-list">';
    $initial_setup_check = mysql_query("select stype from tc_school where sid=".$Uinfo['org']);
    $initial_sch_data = mysql_fetch_array($initial_setup_check);

    if(($client['role']>=6||$client['admin']==1)&&$initial_sch_data['stype']==0){

        $get_stype = mysql_query("select * from ".conf('table_prefix')."_school_type");
        
        echo '<div class="block-center">
            <h3><i class="fa fa-gear"></i> ตั้งค่าสถานศึกษา/องค์กร</h3>
            <p class="divider"></p>
            <form action="/save.php" method="post">
                <input type="hidden" name="q" value="classroom_initial_insert">
                <div class="form-row">
                    <select name="schooltype" id="ssettype">
                        <option value="0">กรุณาเลือกประเภทสถานศึกษา</a>';
        while($stl = mysql_fetch_array($get_stype)){
            echo '<option value='.$stl['stid'].' data-max='.$stl['max_grade'].' data-min='.$stl['min_grade'].'>'.$stl['name'].'</option>';
        }
        echo '      </select>
                </div>
                <p class="divider"></p>
                <div class="class_option"></div>
            </form>
        </div>';
    }else if($initial_sch_data['stype']==0)
    {
        echo 'ไม่พบห้องเรียนของคุณ โปรดติดต่อผู้ดูแลของโรงเรียน';
    }
    else
    {
        //Special
        echo '<h2>ห้องเรียนพิเศษ</h2>';
        $sql_my_class = mysql_query("SELECT * FROM ".conf('table_prefix')."_classroom WHERE `uid`=".$Uinfo['ssid']." and class_type='special' ORDER BY clsid DESC");
        if(mysql_num_rows($sql_my_class)==0){
            echo '<p class="divider"></p><p align="center">ไม่พบห้องเรียน <a href="/new_classroom">คลิกที่นี่</a> เพื่อสร้างห้องเรียนพิเศษของคุณ</p><p class="divider"></p>';
        }
        //List classroom
        while($cs = mysql_fetch_array($sql_my_class)){
            
                    $class_member_check1 = mysql_query("select `uid` from tc_class_register where `status`=1 and role=1 and `class_id`= ".$cs['clsid']);
                    $class_register_check1 = mysql_num_rows($class_member_check1);
                    $class_teacher = json_decode($cs['teacher']);
                    foreach($class_teacher as $teacher)
                    {
                    	$t1 = UserDescript('fullname', $teacher);
                    	@$teacher_name .= str_replace('|', ' ', $t1['fullname']).' ';
                    }
                $cs['image'] = ($cs['image']=='') ? 'holder.js/100x80/sky/text:Extra Classroom' : image_resize($cs['image'],100,80);
            
            echo '
                        <div class="list-items">
                            <img src="'.$cs['image'].'">

                            <div class="detail">
                            <h3>'.$cs['title'].'</h3>
                             <p>ครูประจำชั้น '.$teacher_name.'</p>
                             <p>จำนวนนักเรียน '.$class_register_check1.' คน<p>
                            </div>
                            <div class="listbt">
                                <button class="button btGray"><i class="fa fa-users"></i> นักเรียน</button>
                                <button class="button btGray"><i class="fa fa-pencil"></i> แก้ไข</button>
                                <button class="button btGray"><i class="fa fa-trash-o"></i>  ลบ</button>
                            </div>
                            
                        </div>
                ';
        }
        //Standard
        echo '<h2>ห้องเรียนมาตรฐาน</h2>';
        $sql = mysql_query("SELECT * FROM ".conf('table_prefix')."_classroom WHERE `sid`=".$Uinfo['org']." and class_type='standard' ORDER BY clsid ASC");
        while($css = mysql_fetch_array($sql))
        {
            if($css['image']=='')
                    $class_member_check = mysql_fetch_array(mysql_query("select count(*) from tc_class_register where `status`=1 and `role`=1 and `class_id`= ".$css['clsid']));
                    $class_teacher = json_decode($css['teacher']);
                    foreach($class_teacher as $teacher)
                    {
                    	$t1 = UserDescript('fullname', $teacher);
                    	@$teacher_name .= str_replace('|', ' ', $t1['fullname']);
                    }
                    $css['image'] = ($css['image']=='') ? 'holder.js/100x80/vine/text:'.csname($css['grade']).'%2F'.$css['cls_number'] : image_resize($css['image'],100,80);
  
            echo '
                        <div class="list-items">
                            <img src="'.$css['image'].'">

                            <div class="detail">
                            <h3>'.$css['title'].'</h3>
                             <p>จำนวนนักเรียน '.$class_member_check['count(*)'].' คน<p>
                            </div>';
                         if($client['role']>=6):
                         echo '
                            <div class="listbt">
                                <button class="button btGray"><i class="fa fa-users"></i> นักเรียน</button>
                                <button class="button btGray"><i class="fa fa-pencil"></i> แก้ไข</button>
                                <button class="button btGray"><i class="fa fa-trash-o"></i>  ลบ</button>
                            </div>';
                         endif;

                   echo '</div>
                ';

        }
    }

	echo '</div>';
	echo '</dvi>';
	echo '</div>';
}
if($_GET['user']=='group')
{
	$strsql = mysql_query("SELECT * FROM `".conf('table_prefix')."_social_group` WHERE `admin`='".$Uinfo['user']."'  ORDER BY `sgid` DESC LIMIT 0,30");
	echo '<div id="boxUI">
				<div class="header"><i class="fa fa-users fa-lg"></i> My Groups
				<a href="/new_group"><span class="box-option button btBlue"><i class="fa fa-file"></i> สร้างกลุ่มใหม่</span></a>
				</div>';
	echo '<div class="boxContent" id="idmCL">';
	echo '<div class="md-list">';
	while($cs = mysql_fetch_array($strsql)){
		$cs['logo'] = ($cs['logo']!='') ?  '/image?width=65&height=65&cropratio=1:1&image='.$cs['logo'] : 'holder.js/60x60';
		echo '
					<div class="md-list-items">
						<!--<a href="/group/'.$cs['url'].'"><img src="'.$cs['logo'].'" class="img-radius"></a>-->
						<a href="#"><img src="'.$cs['logo'].'" class="img-radius"></a>
						<span class="green-badge">10</span>
						<div class="detail">
						<a href="#">'.$cs['name'].'</a>
						</div>
					</div>
			';

	}
	if(mysql_num_rows($strsql)==0){
            echo '<p class="divider"></p><p align="center">ไม่มีกลุ่มใดๆ <a href="/new_group">คลิกที่นี่</a> เพื่อสร้างกลุ่มของคุณ</p><p class="divider"></p>';
        }

	echo '</div>';
	echo '</dvi>';
	echo '</div>';
}
if($_GET['user']=='page')
{
	if((int)$Uinfo['role']<=6&&$Uinfo['admin']==0) header('location: /my');

	$sql = mysql_query("SELECT id,title,url,access_role,timestamp FROM ".conf('table_prefix')."_page");

	echo '<div id="boxUI">
				<div class="header"><i class="fa fa-file fa-lg"></i> Page Management<a href="/newpage"><span class="box-option button btBlue"><icon class="fa fa-file"></icon> สร้างหน้าใหม่</span></a></div>';
	echo '<div class="boxContent" id="idmCL">';
	while($page = mysql_fetch_array($sql)){
		echo '<div class="plist">
                <span class="title_bar"></span>
				<span class="des">
					<b><a href="/'.$page['url'].'" target="_blank">'.$page['title'].'</a></b> <i>/'.$page['url'].'</i>
					<small>
					<a href="/'.$page['url'].'?edit=body"  target="_blank" class="edit-page-btn">&nbsp;<i class="fa fa-pencil"></i> แก้ไข</a>&nbsp;
					<a href="/'.$page['url'].'?edit=delete" onclick="if(confirm(\'ยืนยันการลบหน้านี้!\')==true) window.location.href=$(this).attr(\'href\'); else return false;" class="edit-page-btn"> <i class="fa fa-trash-o"></i> ลบ</a>
					</small>
					<br>
					<span style="padding-left: 5px;margin-top:8px;color: #666;display:block;">
					Access Role: '.$page['access_role'].'
					<br>Last Update: '.date('d/n/Y, H:i', $page['timestamp']).'<br />
					</span>
				</span>
			</div>';

	}
	echo '</dvi>';
	echo '</div>';
}
if($_GET['user']=='conference')
{

	if(getConf('conference')!=1) header('location: /404');
	include(conf('dir').'core/class.am.php');
	$idm_call = new IDM2API;

	if(isset($_GET['sid']))
	$idm_call->remove_session($_GET['sid']);
	//echo $Uinfo['amid'];
	$retme = $idm_call->get_userid($Uinfo['org'], $Uinfo['user']);

	$list = $idm_call->getConference($retme);

	echo '<div id="boxUI">
				<div class="header"><i class="fa fa-video-camera fa-lg"></i> My Conference <a href="/newconf?host='.$retme.'"><span class="box-option button"><icon class="fa fa-file"></icon> สร้างห้องประชุมออนไลน์</span></a></div>';
	echo '<div class="boxContent" id="idmCL">';
	if($retme=='') exit(0);
		for($cn=0;$cn<count($list->Record);$cn++)
			{
				$btx->Record = ($list->Record[$cn]->Started==0) ? 'Start' : 'Join';
				$list->Record[$cn]->Started = ($list->Record[$cn]->Started==0) ? '<font color="red">Conference stop.</font>' : '<font color="green">Conference started.</font>';
				if(count($list)==0) continue;
				$n = $cn+1;
				if($cn%2==0) $color = '#fff'; else $color = '#f5f5f5';
				echo '<p style="background: '.$color.';" onmouseover="$(this).find(\'.cc\').show()"  onmouseout="$(this).find(\'.cc\').hide()" id="acu-'.$list->Record[$cn]->Session_Id.'">
				<span class="des">
					<b>'.$list->Record[$cn]->Session_Title.'</b><br>
					<span style="padding-left: 5px;margin-top:5px;color: #666;display:block;">
					'.$list->Record[$cn]->Session_Desc.'
					<br>Status: '.$list->Record[$cn]->Started.'<br />
					</span>
				</span>

				<span style="float: right; position: absolute; bottom: 5px; right: 10px; display: none;" class="cc">
				<a href="javascript:void(0);" onclick="poponload(\'/newconf?m=edit&sid='.$list->Record[$cn]->Session_Id.'\');"><img src="/library/icon/pencil.png"> Edit</a> &nbsp;&nbsp;&nbsp;
				<a href="javascript:;" onClick="if(confirm(\'Are you sure?\')) window.location.href=\'?user=conference&sid='.$list->Record[$cn]->Session_Id.'\';"><img src="/library/icon/trash.png"> Delete</a> &nbsp;&nbsp;&nbsp;
				<a href="javascript:void(0);" onclick="$(\'.urlcontainer\').html(\''.conf('idm_server').'login/editconf2.asp?author='.$list->Record[$cn]->User_Id.'&modulename='.$list->Record[$cn]->Session_Id.'&cat=acuconference-av\');
						$(\'.embedcontainer\').html(\'<textarea cols=94 rows=4 onclick=\&quot;this.focus();this.select()\&quot; readonly=\&quot;readonly\&quot;>&lt;iframe width=\&quot;100%\&quot; height=\&quot;600\&quot; src=\&quot;'.conf('idm_server').'login/editconf2.asp?author='.$list->Record[$cn]->User_Id.'&modulename='.$list->Record[$cn]->Session_Id.'&cat=acuconference-av&quot; frameborder=\&quot;0\&quot;&gt;&lt;/iframe&gt;</textarea>\');$(\'#sharedlg\').slideDown(200);"><img src="/library/icon/share.png"> Share</a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:void(0);" onClick="NewPopup(\'/StartSession.php?sid='.$list->Record[$cn]->Session_Id.'&Name1='.$Uinfo['fullname'].'\',\'session\',600,460)"><img src="/library/icon/switch.png"> '.$btx->Record.'</a>
				</span>


				</p>';
			}
	echo '</dvi>';
	echo '</div>';
	echo '<div id="sharedlg">
			<h2 class="title"><img src="/library/icon/newtab.png"> Share url.</h2>
			<div class="urlcontainer"></div>
			<br>
			<h2 class="title"><img src="/library/icon/embed.png"> Embed code.</h2>
			<div class="embedcontainer" contenteditable="true"></div>
			<br>
			<hr>
			<br>
			<p align="right">
			<a href="javascript:;" onclick="poponload(\'mailto:someone@example.com?subject=You have a meeting here.&body=Hello: Your partner name here.%0D%0A%0D%0A'.str_replace('|',' ',$client['fullname']).' have invite you to conference.%0D%0AHere is your conference url: \'+escape($(\'.urlcontainer\').html()));" class="button btPost" target="_blank"> Send Email </a>
			<span class="button" onclick="$(\'#sharedlg\').slideUp(200);"><img src="/library/icon/times.png"> Close</span></p>
			</div>';
}
if($_GET['user']=='live')
{
	if(getConf('live')!=1) header('location: /404');
	include(conf('dir').'core/class.am.php');
	$idm_call = new IDM2API;
	$retme = $idm_call->get_userid($Uinfo['org'], $Uinfo['user']);
	$list = $idm_call->getLive($retme);
	echo '<div id="boxUI">
				<div class="header"><i class="fa fa-bullhorn fa-lg"></i> My AcuLive</div>';
	echo '<div class="boxContent" id="idmCL">';
		for($cn=0;$cn<count($list->Record);$cn++)
			{
				if(count($list->Record)==0) continue;
				$list->Record[$cn]->Started = ($list->Record[$cn]->Started==0) ? '<font color="red">Offline</font>' : '<font color="green">Online</font>';
				if($cn%2==0) $color = '#fff'; else $color = '#f5f5f5';
				echo '<p style="background: '.$color.';" onmouseover="$(this).find(\'.cc\').show()"  onmouseout="$(this).find(\'.cc\').hide()" id="acu-'.$list->Record[$cn]->Session_Id.'"><span class="des">
				<b>'.$list->Record[$cn]->Session_Title.'</b>
					<span style="padding-left: 5px;margin-top:5px;color: #666;display:block;">
					'.$list->Record[$cn]->Session_Desc.'
					<br>Status: '.$list->Record[$cn]->Started.'
					</span>
					<span style="float: right; position: absolute; bottom: 5px; right: 10px; display: none;" class="cc">

					<a href="javascript:void(0);" onclick="$(\'.urlcontainer\').html(\''.$list->Record[$cn]->Copy_Link.'\');
						$(\'.embedcontainer\').html(\'<textarea cols=94 rows=4 onclick=\&quot;this.focus();this.select()\&quot; readonly=\&quot;readonly\&quot;>&lt;iframe width=\&quot;100%\&quot; height=\&quot;600\&quot; src=\&quot;'.$list->Record[$cn]->Copy_Link.'&quot; frameborder=\&quot;0\&quot;&gt;&lt;/iframe&gt;</textarea>\');$(\'#sharedlg\').slideDown(200);">
						<img src="/library/icon/share.png"> Share
					</a>&nbsp;&nbsp;&nbsp;
					<a href="'.$list->Record[$cn]->Copy_Link.'" target="_blank"><img src="/library/icon/newtab.png"> Open</a>
					</span>
				</span>

				</p>';
			}
	echo '</dvi>';
	echo '</div>';
		echo '<div id="sharedlg">
			<h2 class="title"><img src="/library/icon/newtab.png"> Share url.</h2>
			<div class="urlcontainer"></div>
			<br>
			<h2 class="title"><img src="/library/icon/embed.png"> Embed code.</h2>
			<div class="embedcontainer" contenteditable="true"></div>
			<br>
			<hr>
			<br>
			<p align="right"><span class="button" onclick="$(\'#sharedlg\').slideUp(200);"><img src="/library/icon/times.png"> Close</span></p>
			</div>';
}
if($_GET['user']=='blog')
{
	if(getConf('blog')!=1) header('location: /404');
	switch($_GET['sort'])
	{
		case 'desc':
			$order = "ORDER BY bid DESC";
			break;
		case 'asc':
			$order = "ORDER BY bid ASC";
			break;
		case 'subject':
			$order = "ORDER BY subject ASC";
			break;
		case 'default':
			$order = "ORDER BY bid DESC";
			break;
		default:
			$order = "ORDER BY bid DESC";
			break;
	}

	$sql = mysql_query("SELECT * FROM ".conf('table_prefix')."_subject WHERE username='".$Uinfo['user']."'  AND `group`<>22 $order LIMIT 0,30");

	echo '<div id="boxUI">
				<div class="header"><i class="fa fa-quote-right fa-lg"></i> My Blog ';
	if($_SESSION['loginid']['nickname']==$_GET['indentity'])
		echo '&nbsp;<a href="/new_blog"><span class="box-option button btBlue"><icon class="fa fa-file"></icon> สร้างบทควาามใหม่</span></a>&nbsp;';

	if($_GET['sort']=='desc'||$_GET['sort']=='') $lock[1]='selected';
	if($_GET['sort']=='asc') $lock[2]='selected';
	if($_GET['sort']=='subject') $lock[3]='selected';
	echo '&nbsp;<select id="bsort"  class="box-option" style="height: 28px !important;">
				<option value="desc" '.$lock[1].'>ใหม่อยู่ด้านบน</option>
				<option value="asc" '.$lock[2].'>เก่าสุดอยู่ด้านบน</option>
				<option value="subject" '.$lock[3].'>เรียงตามตัวอักษร</option>
				</select>&nbsp;
		</div>';
	echo '
	<script type="text/javascript">
	<!--
		$("#bsort").on("change", function() {
			if(this.value=="default") return false;
		    window.location.href="/profile/'.$_GET['indentity'].'?user=blog&sort="+this.value;
		})
	//-->
	</script>

	';
	echo '<div class="boxContent" id="idmCL">';
	while($qr = mysql_fetch_array($sql)){
		$g = GroupInfo($qr['group']);
		$lv = Level($qr['level']);

		echo "<p onmouseover=\"$(this).find('.cc').show()\"  onmouseout=\"$(this).find('.cc').hide()\"><a href='/blog/".$qr['req_id']."' target='_blank'><img src='/image-api.php?width=100&height=100&cropratio=1:1&image=/data/content/".$qr['req_id']."/".$qr['image']."' data-src=\"holder.js/100x100/random\" class='stdo-image'></a><a href='/blog/".$qr['req_id']."' target='_blank'>".$qr['subject']."</a><span class='des'>".$g['name']." - ".$lv['name']." <span style='color: #aaa;font-size:11px;font-weight: normal;'>เมื่อ ".date("d/m/Y, H:i:s", $qr['timestamp'])."</span></span>";
		if($_SESSION['loginid']['nickname']==$_GET['indentity']&&$_GET['user']=='blog'){
			echo "<br> <span style='float: right; position: absolute; bottom: 5px; right: 10px; display: none;' class='cc'>
			<a href=\"javascript:void(0);\" onclick=\"$('.urlcontainer').html('".conf('url')."blog/".$qr['req_id']."');
						$('.embedcontainer').html('<textarea cols=94 rows=4 onclick=\&quot;this.focus();this.select()\&quot; readonly=\&quot;readonly\&quot;>&lt;iframe width=\&quot;100%\&quot; height=\&quot;600\&quot; src=\&quot;".conf('url')."blog/".$qr['req_id']."&quot; frameborder=\&quot;0\&quot;&gt;&lt;/iframe&gt;</textarea>');$('#sharedlg').slideDown(200);\"><img src=\"/library/icon/share.png\"> Share</a>&nbsp;&nbsp;&nbsp;
			<a href='/actions?del=".$qr['req_id']."' onclick=\"if(confirm('ยืนยันการลบบทความนี้!')==true) window.location.href=$(this).attr('href'); else return false;\" > <img src='/library/icon/trash.png'> Delete</a> &nbsp;&nbsp;&nbsp;<a href='/new_blog?edit=".$qr['req_id']."&token=".$session_token."'>  <img src='/library/icon/pencil.png'> Edit</a></span></p>";
		}
	}
	echo '</dvi>';
	echo '</div>';
		echo '<div id="sharedlg">
			<h2 class="title"><img src="/library/icon/newtab.png"> Share url.</h2>
			<div class="urlcontainer"></div>
			<br>
			<h2 class="title"><img src="/library/icon/embed.png"> Embed code.</h2>
			<div class="embedcontainer" contenteditable="true"></div>
			<br>
			<hr>
			<br>
			<p align="right">
			<a href="javascript:;" onclick="var url=\'https://www.facebook.com/sharer/sharer.php?u=\'+$(\'.urlcontainer\').html();NewPopup(url);" class="button btPost"><img src="/library/icon/facebook.png"> Share on Facebook</a>
			<span class="button" onclick="$(\'#sharedlg\').slideUp(200);"><img src="/library/icon/times.png"> Close</span>
			</p>
			</div>';
}
if($_GET['user']=='contents')
{
		echo '
		<div id="boxUI">
			<div class="header"><i class="fa fa-play-circle fa-lg"></i> My AcuStudio';

			include(conf('dir').'core/class.am.php');
			$idm_call = new IDM2API;
			$retme = $idm_call->get_userid($Uinfo['org'], $Uinfo['user']);
			$idm_call->debug=1;
			$list = $idm_call->getStudioList($retme);


			echo '</div>';
			echo '<div class="boxContent" id="idmCL">';

			for($cn=0;$cn<count($list->Record);$cn++)
			{

				if(count($list)==0) continue;

				echo '<p onmouseover="$(this).find(\'.cc\').show()"  onmouseout="$(this).find(\'.cc\').hide()" id="acu-'.$qr['req_id'].'">
				<a href="#" onClick="window.open(\''.$list->Record[$cn]->Copy_Link.'\')">
				<img src="/image-api.php?width=100&height=100&image='.conf('idm_server').'aculearn-idm/contentbackup/channel1/users/'.$list->Record[$cn]->User_Id.'/'.$list->Record[$cn]->Course_Id.'/index/0.jpg" class="stdo-image"></a>
				<a href="#" onClick="window.open(\''.$list->Record[$cn]->Copy_Link.'\')">'.$list->Record[$cn]->Course_Title.'</a>
				<span class="des">'.$list->Record[$cn]->description.'<br>

				<span style="float: right; position: absolute; bottom: 5px; right: 10px; display: none;" class="cc">
				<a href="javascript:void(0);" onclick="embeddlg(\''.$list->Record[$cn]->Copy_Link.'\');"><img src="/library/icon/share.png"> Share</a> &nbsp;&nbsp;&nbsp;
				</span>
				</span>
				</p>';
			}
		    echo '</div>
			</div>
			<div id="studio-uploader">
			<form action="/acm-upload.php" method="post" id="acmuploadfrm"  enctype="multipart/form-data">
			<p><span class="text-title">เลือกไฟล์</span> <input type="file" name="acmf" placeholder="Choose acm, acmx, zip file" required></p>
			<p><span class="text-title">ชื่อเรื่อง</span> <input type="text" name="title" required> </p>
			<p><span class="text-title">ผู้ผลิต</span> <input type="text" name="author" required> </p>
			<p><textarea name="fileinfo" class="acminfo" placeholder="เลือกไฟล์ที่มีนามสกุล acm, acmx หรือ zip เท่านั้น แล้วเติมคำอธิบายสักหน่อย..." ></textarea></p>
			<p>
				<input type="submit"  value=" Upload " class="button btPost"/>
				<input type="button" class="btGray button" onclick="$(\'#studio-uploader\').slideUp(100);" value=" Cancel "/>
				<input type="checkbox" name="public" value="1"> เผยแพร่ต่อสาธารณะหรือไม่?
			</p>
			</form>
			</div>

			<div id="studio-update">
			<form action="/save.php" method="post" id="acmupdate">
			 <input type="hidden" name="id" id="acm-id">
			 <input type="hidden" name="q" value="acm-update">
			<p><span class="text-title">ชื่อเรื่อง</span> <input type="text" name="title" id="acm-update-title" required> </p>
			<p><textarea name="fileinfo" class="acminfo" id="acm-update-desc"></textarea></p>
			<p>
				<input type="submit"  value=" Save " class="btPost button"/>
				<input type="button" class="btGray button" onclick="$(\'#studio-update\').slideUp(100);" value=" Cancel "/>
				<input type="checkbox" name="public" value="1" id="acm-update-pb"> เผยแพร่ต่อสาธารณะหรือไม่?
			</p>
			</form>
			</div>

			<div id="sharedlg">
			<h2 class="title"><img src="/library/icon/newtab.png"> Share url.</h2>
			<div class="urlcontainer"></div>
			<br>
			<h2 class="title"><img src="/library/icon/embed.png"> Embed code.</h2>
			<div class="embedcontainer" contenteditable="true"></div>
			<br>
			<hr>
			<br>
			<p align="right"><span class="button" onclick="$(\'#sharedlg\').slideUp(200);"><img src="/library/icon/times.png"> Close</span></p>
			</div>
			<script type="text/javascript">
			<!--
				function embeddlg(urls){
					$("#sharedlg").slideDown(200);
					$(".urlcontainer").html(urls);
					$(".embedcontainer").html(\'&lt;iframe width=\&quot;100%\&quot; height=\&quot;600\&quot; src=\&quot;\'+urls+\'&quot; frameborder=\&quot;0\&quot;&gt;&lt;/iframe&gt;\');
				}
				$(".acm_upload").click(function(){
					$("#studio-uploader").slideDown(100);
				});
				$("#bsort").on("change", function() {
					if(this.value=="default") return false;
					window.location.href="/profile/'.$_GET['indentity'].'?user=contents&sort="+this.value;
				})
			//-->
			</script>
			';
}

echo '</div>';
//End Middle content
if(!isset($_SESSION['loginid'])&&@$_SESSION['loginid']['nickname']=='')
{
	echo '<div id="rightContainer"  class="scroll-sidebar">';
	echo '<div class="u-action">';
	echo '<a href="/home"><span class="button btGreen">เข้าสู่ระบบ</span></a>';
	echo '<a href="/home"><span class="button btPost">สมัครสมาชิก</span></a>';
	echo '</div>';
	echo '<div class="clear">&nbsp;</div>';
	echo '</div>';
}

	echo '</div>'."\n";
	echo '</div>';
	echo '</div>'."\n";
	echo $p->bos('bottom-script.js');

	//display footer
	$ft_wrapper = '<div class="clear"></div>';
	$ft_wrapper .= '<div id="footer-wrapper">'."\n";
	$ft_wrapper .= '<div class="ft_link">'.GetConf('footer_link').'</div>';
	$ft_wrapper .= '<div class="footer-text">'.GetConf('footer_text').'</div>';
	$ft_wrapper .= '</div>'."\n";
	$p->privatefooter = $ft_wrapper;
	eval('?>' . $p->footer(). '<?php ');

?>