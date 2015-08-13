<?php
	require_once 'configure';
	include_once 'core/class.db.php';
	include_once 'core/class.common.php';


//Create class objects
	$p = new common;
	$do = new mysql;


$selectStd = $do->query("SELECT `ssid`,`grade`,`class` FROM `tc_profile` WHERE `role`=3 AND `grade`!='' AND `class`!=''");

foreach($selectStd as $res){
	//$res['ssid'];
	$checkStdRec = $do->query("SELECT class_id FROM tc_class_register WHERE uid=".$res['ssid'], true);
	if(empty($checkStdRec)){
		$getClassInfo = $do->query("SELECT clsid FROM tc_classroom WHERE cls_number = ".$res['class']." AND grade=".$res['grade'], true);
		$insertdata = array("class_id"=>$getClassInfo[0]['clsid'],
			"uid"=>$res['ssid'],
			"status"=>1,
			"timestamp"=>time(),
			"role"=>1);
		$do->insert("tc_class_register", $insertdata);
	}
}
