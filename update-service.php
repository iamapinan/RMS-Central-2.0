<?php
header('Access-Control-Allow-Origin: *');
include 'configure';

				$con=mysql_connect($conf['host'],$conf['dbuser'],$conf['dbpass'],true) or die ('Error connecting to MySQL');
				mysql_select_db('rms_version', $con) or die('Database rms_version does not exist!');
				mysql_query("SET NAMES UTF8");
				mysql_query("SET character_set_results=UTF8");
				mysql_query("SET character_set_client=UTF8");
				mysql_query("SET character_set_connection=UTF8");

if($_REQUEST['pgn']!=''){

		$sql = mysql_query('SELECT * FROM `release` WHERE `name` ="'.$_REQUEST['pgn'].'"');
		$pgres = mysql_fetch_array($sql);


		if($pgres['version']>$_REQUEST['v']||($pgres['version']==$_REQUEST['v']&&$pgres['patch']>$_REQUEST['patch']))
		{
			$res['release_filename'] = $pgres['file'];
			$res['version'] = $pgres['version'];
			$res['patch'] = $pgres['patch'];
			echo json_encode($res);
		}
		else
		{
			$res['release_filename'] = '';
			$res['version'] = $_REQUEST['v'];
			$res['patch'] = $_REQUEST['patch'];
			echo json_encode($res);
		}
}


if(isset($_REQUEST['file'])&&$_REQUEST['cmd']=='update')
{

			$file = './data/school/release/'.$_REQUEST['version'].'/'.$_REQUEST['file'];

			if (file_exists($file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.basename($file));
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				ob_clean();
				flush();
				readfile($file);
				exit;
			}

}

?>