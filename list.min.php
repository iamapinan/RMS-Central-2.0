<?php
include_once 'initial.min.php';

try{
	if(isset($_SESSION['loginid']))
	{

		switch($_REQUEST['qt'])
			{
				case 'listSchool':
					if($_REQUEST['jtStartIndex']!='') $lim = 'LIMIT '.$_REQUEST['jtStartIndex'].', '.$_REQUEST['jtPageSize'];
					if($_REQUEST['jtSorting']!='') $order = 'ORDER BY '.$_REQUEST['jtSorting'];


					if($client['role']==8) {
						$seach_str = ($_POST['fullname']!='') ? 'WHERE `fullname` LIKE  \'%'.$_POST['fullname'].'%\' ' : '';
						$getdata = 'SELECT * FROM '.conf("table_prefix").'_profile '.$seach_str.' '.$order.' '.$lim;
						$crec = 'SELECT * FROM '.conf("table_prefix").'_profile';
					}
					else
					{
						$seach_str = ($_POST['fullname']!='') ? 'AND `fullname` LIKE  \'%'.$_POST['fullname'].'%\' ' : '';
						$getdata = 'SELECT * FROM '.conf("table_prefix").'_profile WHERE org='.$_REQUEST['org'].' '.$seach_str.' '.$order.' '.$lim;
						$crec = 'SELECT * FROM '.conf("table_prefix").'_profile WHERE org='.$_REQUEST['org'];
					}
					$numrec = mysql_num_rows(mysql_query($crec));

					$qr = mysql_query($getdata);
					if($qr) $Result['Result'] = 'OK';
					$op = array();

					while($res = mysql_fetch_array($qr))
					{
						$getSch = mysql_fetch_array(mysql_query('SELECT sname FROM '.conf("table_prefix").'_school WHERE sid='.$res['org']));
						$res['scn'] = $getSch['sname'];
						$res['fullname'] = str_replace('|',' ', $res['fullname']);
						$res['password'] = openssl_decrypt($res['password'], 'aes128', '');
						$op[]=$res;
					}
					$Result['TotalRecordCount'] = $numrec;
					$Result['Records'] = $op;
					break;
				case 'uinfo':
					$u = UserById($_GET['uid']);
					$Result = array();
					$Result['Result'] = "OK";
					$grp = explode(',', $u['remark']);
					$grp = explode('=', $grp[0]);
					$getSch = mysql_fetch_array(mysql_query('SELECT sname FROM '.conf("table_prefix").'_school WHERE sid='.$u['org']));
					$Result['Records'][0]['title'] = 'Name';
					$Result['Records'][0]['value'] = str_replace('|',' ',$u['fullname']);
					$Result['Records'][1]['title'] = 'User';
					$Result['Records'][1]['value'] = $u['citizen_id'];
					$Result['Records'][2]['title'] = 'Email';
					$Result['Records'][2]['value'] = $u['email'];
					$Result['Records'][3]['title'] = 'Organization';
					$Result['Records'][3]['value'] = $getSch['sname'];
					$Result['Records'][4]['title'] = 'Role';
					$Result['Records'][4]['value'] = $u['role'];
					$Result['Records'][5]['title'] = 'Gender';
					$Result['Records'][5]['value'] = $u['gender'];
					$Result['Records'][6]['title'] = 'Birth day';
					$Result['Records'][6]['value'] = $u['bday'];
					$Result['Records'][7]['title'] = 'Remark';
					$Result['Records'][7]['value'] = $u['remark'];
					$Result['Records'][8]['value'] = $u['mobile'];
					$Result['Records'][8]['title'] = 'Mobile';
					$Result['Records'][9]['title'] = 'AMID';
					$Result['Records'][9]['value'] = $u['amid'];
					if($u['role']==5){
					$Result['Records'][10]['title'] = 'กลุ่มสาระ';
					$Result['Records'][10]['value'] = $grp[1];
					}
					$Result['Records'][11]['title'] = 'เอกสารยืนยันตัวบุคคล';
					$Result['Records'][11]['value'] = '<a href="/user/'.$u['user'].'/verify_photo_0.jpg" target="_blank">verify_photo_0</a> , <a href="/user/'.$u['user'].'/verify_photo_1.jpg" target="_blank">verify_photo_1</a>';

					$Result['TotalRecordCount'] = 12;
				  break;
				case 'deleteUser':
					$u = UserById($_REQUEST['ssid']);
					//echo $u['amid'];
					include_once('core/class.am.php');
					$idm = new IDM2API;
					$idm->debug=1;
					$userid = $idm->DeleteUser('',$u['user']);
					//echo $idm->idm_debug();
					if($userid==1){
						$sql = mysql_query('DELETE FROM '.conf('table_prefix').'_profile WHERE ssid='.$_REQUEST['ssid']);
						if($sql)
						{
							$Result = array();
							$Result['Result'] = "OK";
						}else{
							$Result = array();
							$Result['Result'] = "ERROR";
							$Result['Message'] = 'Update fail please contact system administrator.';
						}
					}else{
							$Result = array();
							$Result['Result'] = "ERROR";
							$Result['Message'] = 'Remove fail contact system administrator.';
						}
					break;
				case 'updateUser':
					$qu= UserById($_REQUEST['ssid']);

						$unenc = $_REQUEST['password'];
						include_once('core/class.am.php');
						$idm = new IDM2API;
						$userid = $idm->get_userid('', $qu['user']);
						$idm_set = $idm->update_password($userid, $unenc);

					$qr = 'update '.conf('table_prefix').'_profile set fullname="'.str_replace(' ','|',$_REQUEST['fullname']).'",
					password="'.openssl_encrypt($_REQUEST['password'], 'aes128', '').'",
					email="'.$_REQUEST['email'].'",
					gender="'.$_REQUEST['gender'].'",
					bday="'.$_REQUEST['bday'].'",
					org="'.$_REQUEST['org'].'",
					grade="'.$_REQUEST['grade'].'",
					position="'.$_REQUEST['position'].'",
					status="'.$_REQUEST['status'].'",
					role="'.$_REQUEST['role'].'",
					citizen_id = "'.$_REQUEST['citizen_id'].'",
					verify="'.$_REQUEST['verify'].'",
					`update`=0,
					admin='.$_REQUEST['admin'].'
					where ssid='.$_REQUEST['ssid'];

					$sql = mysql_query($qr);
					if($sql){
						$Result = array();
						$Result['Result'] = "OK";
					}else{
						$Result = array();

						$Result['Result'] = "ERROR";
						$Result['Message'] = 'Update fail please contact system administrator.';
					}
					break;
				case 'createUser':
						$qr = 'INSERT INTO '.conf('table_prefix').'_profile (`user`, `provider`, `password`, `fullname`, `email`, `role`, `org`, `gender`, `language`, `bday`, `grade`, `citizen_id`, `position`, `status`, `verify`)
									VALUES ("'.$_REQUEST['citizen_id'].'","bll","'.openssl_encrypt($_REQUEST['password'], 'aes128', '').'","'.str_replace(' ','|', $_REQUEST['fullname']).'",
									"'.$_REQUEST['email'].'","'.$_REQUEST['role'].'","'.conf('schoolid').'","'.$_REQUEST['gender'].'","th",
									"'.$_REQUEST['bday'].'", "'.$_REQUEST['grade'].'", "'.$_REQUEST['citizen_id'].'", "'.$_REQUEST['position'].'", "'.$_REQUEST['status'].'","'.$_REQUEST['verify'].'")';
						//echo $qr;
						$chkemail = UserInfo($_REQUEST['citizen_id']);

						if($chkemail['user']=='')
						{
								$sql = mysql_query($qr);
								$result = mysql_query('SELECT * FROM '.conf('table_prefix').'_profile WHERE ssid = LAST_INSERT_ID()');
								$row = mysql_fetch_array($result);
								include_once('core/class.am.php');
								$idm = new IDM2API;
								$chkg = $idm->check_group($_REQUEST['org']);
								if($chkg!=true)
								{
									$qschool = 'select sname from '.conf('table_prefix').'_school where sid='.$_REQUEST['org'];
									$qsch = mysql_query($qschool);
									$school = mysql_fetch_array($qsch);
									$idm->add_group($_REQUEST['org'], $school['sname']);
								}
								$idmcall = $idm->create_user($_REQUEST['citizen_id'], $_REQUEST['password'], $_REQUEST['fullname'], $_REQUEST['role'], $_REQUEST['org']);
								if($sql)
								{
									$Result = array();
									$Result['Result'] = "OK";
									$Result['Record'] = $row;
								}else{
								$Result = array();
								$Result['Result'] = "ERROR";
								$Result['Message'] = '101 Update fail please contact system administrator.';
							}

						}else{
								$Result = array();
								$Result['Result'] = "ERROR";
								$Result['Message'] = '102 Update fail please contact system administrator.';
							}
					break;
			}
		echo json_encode($Result);
	}
}
catch(Exception $ex)
{
    //Return error message
	$Result = array();
	$Result['Result'] = "ERROR";
	$Result['Message'] = $ex->getMessage();
	print json_encode($Result);
}
?>
