<?php
include 'initial.min.php';

$show = new UStream;
$p = @trim($_REQUEST['p']);
$act = @$_REQUEST['act'];

		if($act=='search'&&$_REQUEST['q']!='')
		{
			$q = 'subject like \'%'.$_REQUEST['q'].'%\' OR tag like \'%'.$_REQUEST['q'].'%\' AND ';
		}else if($act=='search'&&$_REQUEST['q']=='')
		{
			$q = '';
		}


//Action


		if($act=='schoolsh')
		{
			//echo mb_detect_encoding($_GET['q']).utf8_decode($_GET['q']).'<br>';
			$sql = mysql_query('select * from '.conf('table_prefix').'_school where sname like \'%'.mysql_real_escape_string($_POST['q']).'%\' limit 0,10');

			while($res = mysql_fetch_array($sql))
			{
				$ret .= '<li onclick="$(\'#schoolsh\').val(\''.$res['sname'].'\');$(\'#schoolid\').val(\''.$res['sid'].'\');$(\'#shoolshpre\').hide();">'.$res['sname'].'</li>';
			}

		}

    if($_REQUEST['search_type']=='user'&&$_REQUEST['mod']!='')
    {
        //Public user search
        if($_REQUEST['mod']==1)
        {
            $sql = mysql_query('select fullname,ssid,avatar,org from '.conf('table_prefix').'_profile where fullname like \'%'.mysql_real_escape_string($_REQUEST['q']).'%\' limit 0,10');
            while($sd = mysql_fetch_assoc($sql))
            {
                $school = schoolinfo($sd['org'], 'sname');
                $ret .= '<li data-id="'.$sd['ssid'].'" onclick="select_user(\''.str_replace('|',' ',$sd['fullname']).'\','.$sd['ssid'].')">'.str_replace('|',' ',$sd['fullname']).' <span class="text-gray">/ '.$school['sname'].'</span></li>';
            }
        }
        //School user search
        if($_REQUEST['mod']==2)
        {
            $sql = mysql_query('select fullname,ssid,avatar,org from '.conf('table_prefix').'_profile where fullname like \'%'.mysql_real_escape_string($_REQUEST['q']).'%\' and org = '.$client['org'].' limit 0,10');

            while($sd = mysql_fetch_assoc($sql))
            {
                $school = schoolinfo($sd['org'], 'sname');
                $ret .= '<li data-id="'.$sd['ssid'].'" onclick="select_user(\''.str_replace('|',' ',$sd['fullname']).'\','.$sd['ssid'].')">'.str_replace('|',' ',$sd['fullname']).' <span class="text-gray">/ '.$school['sname'].'</span></li>';
            }
        }
        //Student search in school
        if($_REQUEST['mod']==3)
        {
            $sql = mysql_query('select fullname,ssid,avatar,org from '.conf('table_prefix').'_profile where fullname like \'%'.mysql_real_escape_string($_REQUEST['q']).'%\' and org = '.$client['org'].' and role<=3 limit 0,10');

            while($sd = mysql_fetch_assoc($sql))
            {
                $school = schoolinfo($sd['org'], 'sname');
                $ret .= '<li data-id="'.$sd['ssid'].'" onclick="select_user(\''.str_replace('|',' ',$sd['fullname']).'\','.$sd['ssid'].')">'.str_replace('|',' ',$sd['fullname']).' <span class="text-gray">/ '.$school['sname'].'</span></li>';
            }
        }
        //Teacher search in school
        //Student search in school
        if($_REQUEST['mod']==4)
        {
            $sql = mysql_query('select fullname,ssid,avatar,org from '.conf('table_prefix').'_profile where fullname like \'%'.mysql_real_escape_string($_REQUEST['q']).'%\' and org = '.$client['org'].' and role>=4 limit 0,10');

            while($sd = mysql_fetch_assoc($sql))
            {
                $school = schoolinfo($sd['org'], 'sname');
                $ret .= '<li data-id="'.$sd['ssid'].'" onclick="select_user(\''.str_replace('|',' ',$sd['fullname']).'\','.$sd['ssid'].')">'.str_replace('|',' ',$sd['fullname']).' <span class="text-gray">/ '.$school['sname'].'</span></li>';
            }
        }
    }
	echo $ret;

?>