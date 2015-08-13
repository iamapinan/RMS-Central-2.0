<?php
include 'initial.min.php';

//Check photo.
if($_POST['photo']=='') $_POST['photo'] = $_SERVER['DOCUMENT_ROOT'].'/library/images/image.jpg';

//Array prepare and determine for username to use.
	$USERNAME = $_POST['user'];
	if($_POST['user']=='') { 
		$USERNAME = $_POST['id']; //Google and facebook will return ID 
	}

	if($_POST['firstname']==''&&$_POST['email']==''&&$USERNAME=='') header('location: /');
//Check for user exists.
	$res = UserInfo($USERNAME);
//User status.
	$status = ($res['user']=='') ? 0 : 1;
	$pin = pathinfo($_POST['photo']);

//Preparing data.
	$prepare['user'] = $USERNAME;
	$prepare['email'] = $_POST['email'];
	$prepare['photo'] = $_POST['photo'];
	$prepare['name'] = $_POST['firstname'].'|'.$_POST['lastname'];
	$prepare['bd'] = $_POST['birthday'];
	$prepare['role'] = ($_POST['role']!='') ? $_POST['role'] : '';
	$prepare['gender'] = $_POST['gender'];
	$prepare['password'] = ($_POST['password']!='') ? $_POST['password'] : rand(111111,999999);
	$prepare['local'] = 'th';
	$prepare['source'] = $_GET['prelogin'];
	$prepare['school'] = $_POST['school'];
	$prepare['status'] = $status;

//Create Directory if username not exists.
if($status == 0)
{
	$user_dir = $_SERVER['DOCUMENT_ROOT'].'/user/'.$USERNAME;
	$photo = md5($pin['basename']).'.jpg';
	$photopath = $user_dir.'/'.$photo;
	if(!is_dir($user_dir))
		mkdir($user_dir, 0777);

	if(!is_file($photopath)){
		@copy($_POST['photo'], $photopath);
		$prepare['photo'] = '/user/'.$USERNAME.'/'.$photo;
	}else{
		$prepare['photo'] = '/user/'.$USERNAME.'/'.$photo;
	}
}

//Final form
		if($prepare['email']!='') $dis['email'] = 'disabled';
		if($prepare['name']!='') $dis['name'] = 'disabled';
		if($prepare['role']!='')
		{
			$dis['role'] = 'disabled';
			$rse[1] = ($prepare['role']=='02') ? 'selected' : '';
			$rse[2] = ($prepare['role']=='01') ? 'selected' : '';
			$rse[3] = ($prepare['role']!='01'&&$prepare['role']!='02') ? 'selected' : '';
		}
		else
			$rse[0] = 'selected';

		if($prepare['gender']!='')
		{
			$dis['gender'] = 'disabled';
			$grs[1] = ($prepare['gender']=='male') ? 'selected' : '';
			$grs[2] = ($prepare['gender']=='female') ? 'selected' : '';
		}
		else
			$grs[0] = 'selected';

		if($prepare['school']!='')
		{
			$dis['school'] = 'disabled';
			//Check school name.
			$sql = mysql_query('select sname from '.conf('table_prefix').'_school where sid='.$prepare['school']);
			$res_sc = mysql_fetch_array($sql);

			if($res_sc['sname']!='') $scn = $res_sc['sname'];
			else $scn = '';
		}

		if($prepare['photo']!='') $photo = '<img src="/image?width=36&height=36&cropratio=1:1&image='.$prepare['photo'].'">';
		else $photo = '<div style="display: inline-block;width: 36px;height: 35px;background: #CCC;padding: 5x 3px;text-align: center;color: #fff;font-weight: bolder;font-size: 30px;">?</div>';

		$prn = '
		<form action="/profilesave.php?action=newp" method="post" id="newprofile" autocomplete="off">
		<p>
		<span class="label">&nbsp;</span>
		'.$photo.'
		<span style="display: inline-block;">
			<span style="font-size: 18px;vertical-align: top;margin: 3px 10px;display: block;">'.str_replace('|',' ', $prepare['name']).'</span>
			<span style="font-size: 12px;vertical-align: top;margin: 1px 10px;display: block;color: #acacac;">'.$prepare['email'].'</span>
		<span>
		</p>';
		$prn .= '<p>&nbsp;</p>';
		$prn .= '<p><span class="label">บทบาท</span>
		<span>';
		$prn .= '<select name="role"  id="role'.$lev['rid'].'"  onChange="switch_role($(this).find(\':selected\').val());">';
		$sql_lev = mysql_query('select * from '.conf('table_prefix').'_role order by rid asc limit 0,7');
		while($lev = mysql_fetch_array($sql_lev))
		{
			$prn .= '
			<option value="'.$lev['rid'].'"> '.$lev['rolename'].'</option>';
		}
		$prn .= '
		</select>
		</span>
		</p>';
//Organizations
		$prn .= '<div class="ogn" >
		<div style="position: relative;" class="org">
		<span class="label">เลือกองค์กร</span> <input type="text" name="school" id="schoolsh" value="'.$scn.'" placeholder="พิมพ์ชื่อองค์กรแล้วเลือกจากรายการ">
		<div id="shoolshpre"><ul class="scview"></ul></div>
		</div>
		</div>';
		$prn .= '<span class="subset"></span>';
		$prn .= '<p id="pos" style="display: none;">
					<span class="label">ตำแหน่ง</span> <input type="text" name="position" id="position">
				</p>';
		$prn .= '</p>';
		$prn .= '<input type="hidden" name="dcp"  id="dcp_password" value="'.$prepare['password'].'">';
		$prn .= '<input type="hidden" name="password"  id="password" value="'.$prepare['password'].'">';

		$prn .= '<p>&nbsp;</p>';

		$prn .= '<p><span class="label">&nbsp;</span><input type="submit" class="button btGray" id="verify_save" value=" ยืนยันบัญชีผู้ใช้ "> เพื่อรับสิทธิเพิ่มเติมในบางระบบ</p>';
		$prn .= '<p style="color: #ff6600;"><span class="label">&nbsp;</span>ท่านต้องเปิดใช้งาน cookie ในเว็บเบราเซอร์ของท่าน</a></p>';
		$prn .= '<p>&nbsp;</p>';
		$prn .= '<p><span class="label">&nbsp;</span><input type="submit" class="button btGreen" id="regis_save" value=" ตกลง "></p>';


	//Escape fields
		$prn .= '<input type="hidden" name="source"  value="'.$prepare['source'].'">';
		$prn .= '<input type="hidden" name="bd"  value="'.$prepare['bd'].'">';
		$prn .= '<input type="hidden" name="gender"  value="'.$prepare['gender'].'">';
		$prn .= '<input type="hidden" name="email"  value="'.$prepare['email'].'">';
		$prn .= '<input type="hidden" name="token"  value="'.$session_token.'">';
		$prn .= '<input type="hidden" name="user"  value="'.$prepare['user'].'">';
		$prn .= '<input type="hidden" name="ogrid" id="schoolid"  value="">';
		$prn .= '<input type="hidden" name="firstname"  value="'.$_POST['firstname'].'">';
		$prn .= '<input type="hidden" name="lastname"  value="'.$_POST['lastname'].'">';
		$prn .= '<input type="hidden" name="local"  value="'.$prepare['local'].'">';
		$prn .= '<input type="hidden" name="photo"  value="'.md5($pin['basename']).'.jpg'.'">';
		$prn .= '<input type="hidden" name="status"  value="'.$prepare['status'].'">';
		$prn .= '</form>';
if($status==0)
{
	//Out put
	$p->ShareHeader .= '
	<style type="text/css">
		.view input[type^=text]{
			width: 200px;
		}
	</style>
	<script type="text/javascript">
	<!--
		function switch_role(rk)
			{

				if(rk==7)
					$("#regis_save").hide();
				 else
					$("#regis_save").show();

				$.ajax({
				\'url\': \'/get.php?role=\'+rk,
				\'type\': \'GET\',
				\'dataType\': \'html\',
				\'success\': function (data)
					{
						$(".subset").html(data);
					}
				});
			}
	//-->
	</script>
	';
	echo $p->globalheader();
	echo '<div id="container">'."\n";
	eval('?>' . $p->getC('header'). '<?php '); //Banner
	echo $p->toolbar()."\n"; //toolbar
	echo '<div id="ContentBody" class="mainbody">';

	echo '<div class="view">';
	echo $prn;
	echo '</div>';
//Footere
	echo '</div>'."\n";
	echo '</div>'."\n";
	//display footer
	$ft_wrapper = '<div class="clear"></div>';
	$ft_wrapper .= '<div id="footer-wrapper">'."\n";
	$ft_wrapper .= '<div class="ft_link">'.GetConf('footer_link').'</div>';
	$ft_wrapper .= '<div class="footer-text">'.GetConf('footer_text').'</div>';
	$ft_wrapper .= '</div>'."\n";
	$p->privatefooter = $ft_wrapper;
	eval('?>' . $p->footer(). '<?php ');

}
else
{
	$info = array(
			'fullname'=>$prepare['name'],
			'nickname'=>$prepare['user'],
			'email'=>$prepare['email']
		);
	if($_SESSION['loginid']['nickname']!='')
		setcookie('userid', base64_encode($_SESSION['loginid']['nickname']),time()+378432000);

	$_SESSION['loginid'] = $info;
	if($_SESSION['loginid']['nickname']!=''&&$_SESSION['redirect_to']!='') header('location: '.$_SESSION['redirect_to'].'?auth='.$prepare['user']);
	else if($_SESSION['loginid']['nickname']!=''&&$_SESSION['redirect_to']=='') header('location: /');
}


?>