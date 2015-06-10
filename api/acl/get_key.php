<?php

require_once('inc_common.php');

try{
	$key = generateAmKey();
	echo "1|" . $key;
}catch(Exception $e){

	echo "0|" . $e->getMessage();
}


?>