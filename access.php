<?php
	include 'initial.min.php';
	$q = $_GET;
	session_start();
	header('Access-Control-Allow-Origin: *');


	$info = UserInfo($q['user']);

	$sql_school_info = mysql_query('select * from '.conf('table_prefix').'_school where sid='.$info['org']);
	$sci = @mysql_fetch_array($sql_school_info);

	$sql_level_info = mysql_query('select * from '.conf('table_prefix').'_level where level_id='.$info['grade']);
	$lvi = @mysql_fetch_array($sql_level_info);

	function unescape_utf16($string) {
		/* go for possible surrogate pairs first */
		$string = preg_replace_callback(
			'/\\\\u(D[89ab][0-9a-f]{2})\\\\u(D[c-f][0-9a-f]{2})/i',
			function ($matches) {
				$d = pack("H*", $matches[1].$matches[2]);
				return mb_convert_encoding($d, "UTF-8", "UTF-16BE");
			}, $string);
		/* now the rest */
		$string = preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
			function ($matches) {
				$d = pack("H*", $matches[1]);
				return mb_convert_encoding($d, "UTF-8", "UTF-16BE");
			}, $string);
		return $string;
	}

	function prettyPrint( $json )
	{
		$result = '';
		$level = 0;
		$prev_char = '';
		$in_quotes = false;
		$ends_line_level = NULL;
		$json_length = strlen( $json );

		for( $i = 0; $i < $json_length; $i++ ) {
			$char = $json[$i];
			$new_line_level = NULL;
			$post = "";
			if( $ends_line_level !== NULL ) {
				$new_line_level = $ends_line_level;
				$ends_line_level = NULL;
			}
			if( $char === '"' && $prev_char != '\\' ) {
				$in_quotes = !$in_quotes;
			} else if( ! $in_quotes ) {
				switch( $char ) {
					case '}': case ']':
						$level--;
						$ends_line_level = NULL;
						$new_line_level = $level;
						break;

					case '{': case '[':
						$level++;
					case ',':
						$ends_line_level = $level;
						break;

					case ':':
						$post = " ";
						break;

					case " ": case "\t": case "\n": case "\r":
						$char = "";
						$ends_line_level = $new_line_level;
						$new_line_level = NULL;
						break;
				}
			}
			if( $new_line_level !== NULL ) {
				$result .= "\n".str_repeat( "\t", $new_line_level );
			}
			$result .= $char.$post;
			$prev_char = $char;
		}

		return $result;
	}

	if($info['user']!='')
	{

		$cr = explode(',', $info['role']);
		$rolename = '';
		for($i=0;$i<(count($cr)-1);$i++)
		{
			$sql = mysql_query('select * from '.conf('table_prefix').'_role where rid ='.$cr[$i]);
			$res = @mysql_fetch_array($sql);
			$rolename .= $res['rolename'].', ';
		}
		$name = explode('|', $info['fullname']);
		$sci['group'] = 1;
		$ret['status'] = $info['status'];
		$ret['verify'] = $info['verify'];
		$ret['position'] = $_POST['position'];
		$ret['user'] = $info['user'];
		$ret['fullname'] =  $info['fullname'];
		$ret['firstname'] = $name[0];
		$ret['lastname'] = $name[1];
		$ret['birthday'] = $info['bday'];
		$ret['gender'] = $info['gender'];
		$ret['local'] = $info['language'];
		$ret['role'] = $info['role'];
		$ret['grade'] = $info['grade'];
		$ret['class'] = $info['class'];
		$ret['rolename'] = $rolename;
		$ret['remark'] = $info['remark'];
		$ret['email'] = $info['email'];
		$ret['photo'] = conf('url').'user/'.$info['user'].'/'.$info['avatar'];
		$ret['last_update'] = $info['timestamp'];
		$ret['organization']['id'] = $sci['sid'];
		$ret['organization']['group'] = $sci['group'];
		$ret['organization']['name'] = $sci['sname'];
		$ret['organization']['address'] = $sci['tambon'].' '.$sci['ampur'].' '.$sci['province'];
		$ret['organization']['division'] = $sci['divisionname'];
		if($_GET['scope']=='student'){
			$ret = array('');
			$stdSel = mysql_query('SELECT * FROM tc_profile WHERE org='.$sci['sid']);
			while($stdlist = mysql_fetch_array($stdSel, true)){
				$student[] = $stdlist;
			}
			$ret['student'][$sci['sid']] = $student;
		}
		if(!isset($_REQUEST['format'])||$_REQUEST['format']=='json'){
			header('Content-Type: application/json');
			echo prettyPrint(unescape_utf16(json_encode($ret)));

		}
		else if($_REQUEST['format']=='xml')
		{
			header('Content-Type: text/xml');
			echo '<?xml version="1.0" encoding="utf-8"?>
			<profile>
				<status>'.$info['status'].'</status>
				<verify>'.$info['verify'].'</verify>
				<firstname>'.$name[0].'</firstname>
				<lastname>'.$name[1].'</lastname>
				<user>'.$info['user'].'</user>
				<email>'.$info['email'].'</email>
				<gender>'.$info['gender'].'</gender>
				<grade>'.$info['grade'].'</grade>
				<class>'.$info['class'].'</class>
				<birthday>'.$info['bday'].'</birthday>
				<roleid>'.$info['role'].'</roleid>
				<rolename>'.$rolename.'</rolename>
				<language>'.$info['language'].'</language>
				<remark>'.$info['remark'].'</remark>
				<photo>'.conf('url').'user/'.$info['user'].'/'.$info['avatar'].'</photo>
				<last_update>'.$info['timestamp'].'</last_update>
				<position>'.$info['position'].'</position>
				<organization>
					<id>'.$sci['sid'].'</id>
					<name>'.$sci['sname'].'</name>
					<address>'.$sci['tambon'].' '.$sci['ampur'].' '.$sci['province'].'</address>
					<division>'.$sci['divisionname'].'</division>
				</organization>
			</profile>';
		}
	}
	else
	{
		header('Content-Type: application/json');
		$ret['status'] = '0';
		echo json_encode($ret);
	}


?>