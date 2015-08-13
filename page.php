<?php
		include 'initial.min.php';

		$q = $_REQUEST;
		if(isset($_REQUEST['p'])){
			$cond = ' url = "'.$_REQUEST['p'].'"';
		}
		
		if(isset($_REQUEST['rq'])){
			$cond = ' id='.$_REQUEST['rq'];
		}

		//Get page Data;
		$result = $do->select(array(
				'table' => conf('table_prefix').'_page',
				'fields' => '`id`,`uid`,`title`,`url`,`option`,`access_role`',
				'condition' => $cond
			));

		$pagename = $result[0]['url'];
		$pageid = $result[0]['id'];

		if(!is_file(conf('dir').'cache/page/'.$result[0]['id'].'.php')||!is_file(conf('dir').'cache/page/'.$result[0]['id'].'.header')){
			$result = $do->select(array(
				'table' => conf('table_prefix').'_page',
				'fields' => '`id`,`uid`,`title`,`url`,`option`,`access_role`,`include`,`body`',
				'condition' => $cond
			));
			file_put_contents(conf('dir').'cache/page/'.$pageid.'.header',$result[0]['include']);
			file_put_contents(conf('dir').'cache/page/'.$pageid.'.php',$result[0]['body']);
		}else{
			$result[0]['include'] = file_get_contents(conf('dir').'cache/page/'.$pageid.'.header');
			$result[0]['body'] = file_get_contents(conf('dir').'cache/page/'.$pageid.'.php');
		}

//404 error
		if(count($result)==0){
			header('location: /404');
		}

//Page options
		$pop = explode(";",$result[0]['option']);
		function getOptionVal($o)
		{
			$ret = explode("=",$o);
			return $ret[1];
		}


	$i->PageTitle= $result[0]['title'];
	echo $i->minheader($cond);
	echo '<div id="container">'."\n";

	if(getOptionVal($pop[4])==1){
		eval('?>' . $p->getC('header'). '<?php ');
	}//end banner
    if(getOptionVal($pop[0])!=''){
        echo '<style type="text/css">
                    body{background: url('.getOptionVal($pop[0]).') repeat-x left top !important;}
                </style>';
    }
	if(getOptionVal($pop[5])==1){
		echo $p->toolbar()."\n";
	}//end toolbar

//Check access role
	$chr = $result[0]['access_role'];
	(int)$role = explode(',',$chr);

	if($role[0]==0) //if access role = 0 all member can access.
		$role = array('0','1','2','3','4','5','6','7','8');

	echo '<div id="ContentBody">';

	if(checkrole_access($role)==true&&$p->getRole('int')!=('10'))
	{

	if($q['edit']=='body')
	{
		if(!isset($_SESSION['loginid']['nickname'])||($client['role']!=8)){
				header("location: /403");
		}


		echo '<link rel="stylesheet" href="'.conf('url').'library/syntax/lib/codemirror.css">
	    <script src="'.conf('url').'library/syntax/lib/codemirror.js"></script>
	    <script src="'.conf('url').'library/syntax/mode/xml/xml.js"></script>
	    <script src="'.conf('url').'library/syntax/mode/javascript/javascript.js"></script>
	    <script src="'.conf('url').'library/syntax/mode/css/css.js"></script>
	    <script src="'.conf('url').'library/syntax/mode/clike/clike.js"></script>
	    <script src="'.conf('url').'library/syntax/mode/php/php.js"></script>
	    <script src="'.conf('url').'library/syntax/lib/util/searchcursor.js"></script>
	    <script src="'.conf('url').'library/syntax/lib/util/search.js"></script>
	    <script src="'.conf('url').'library/syntax/lib/util/match-highlighter.js"></script>
	    <script src="'.conf('url').'library/syntax/lib/util/foldcode.js"></script>
	    <script src="'.conf('url').'library/syntax/lib/util/fullscreen.js"></script>
	    <link rel="stylesheet" href="'.conf('url').'library/syntax/theme/monokai.css">';

//Form
		echo '<div id="stat"></div>';
		echo '<form method="post" action="/BodySave.php" id="editbody">
		<h1><i class="fa fa-pencil"></i> '.$result[0]['title'].'</h1><br>';
		echo '
		<div id="toolbar">
			<a href="'.conf('url').$pagename.'" class="button btBlue"><i class="fa fa-mail-reply"></i> กลับไปที่หน้า</a>

			<a href="#" class="button btGray" onclick="pageTab(\'setting\');"><i class="fa fa-gear"></i> Settings</a>
			<a href="#" class="button btGray" onclick="pageTab(\'header\');"><i class="fa fa-code"></i> Header</a>
			<a href="#" class="button btGray" onclick="pageTab(\'body\');"><i class="fa fa-code"></i> Body</a>

			<button type="submit" class="button btGreen"><i class="fa fa-save"></i> บันทึก</button>
			 &nbsp;<a href="#" onclick="window.scrollTo(0,0)" class="button btn-link"><i class="fa fa-chevron-up"></i> ขึ้นข้างบน</a>

		</div>';

		if(empty($result[0]['include'])) 
			$result[0]['include'] = "<!-- Header -->\n\n\n"; 
		else 
			$result[0]['include'] = htmlspecialchars($result[0]['include']);
		echo '<div id="pageConfig" class="pageobj" style="display:block;">';
		echo '<h2>Title</h2>';
		echo '<input type="text" name="ptitle" class="text-input" value="'.$result[0]['title'].'">';
		echo '<h2>Page configs</h2>';
		echo '<input type="text" name="pconfig" class="text-input" value="'.$result[0]['option'].'">';
		echo '<h2>Access Role</h2>';
		echo '<input type="number" min=0 max=10 name="prole" value="'.$result[0]['access_role'].'">';
		echo '<h2>Page ID</h2>';
		echo '<input type="text" name="page" readonly=true id="pageid" value="'.$pageid.'">';
		echo '<h2>URL</h2>';
		echo '<input type="text" name="pn" readonly=true id="pn" value="'.$pagename.'">';
		echo '</div>';

		echo '<div id="pageHeader" class="pageobj">';
		echo '<h2>Header Source Code [JS+CSS+HTML]</h2>';
		echo '<textarea class="text-area edit-body script-body" id="header" name="script">'.$result[0]['include'].'</textarea><br>';
		echo '</div>';

		echo '<div id="pageBody" class="pageobj">';
		echo '<h2>Body Source Code [PHP+HTML]</h2>';
		echo '<textarea class="text-area edit-body" id="code" name="code">'.htmlspecialchars($result[0]['body']).'</textarea>';
		

		
		echo '<p>
				  ใช้ตัวช่วยสะกดสำหรับ Javascript กด Ctrl+Space
			  </p>';
		echo '</div>';
		echo '</form>';
		echo '<div class="preview">';

//Config
		echo '<script src="'.conf('url').'library/syntax/complete.js"></script>';

	}
	else
	if($q['edit']=='delete')
	{
        if($q['p']!=''&&$q['p']==$result[0]['url']){
		  $ret = $do->delete(conf('table_prefix').'_page','id='.$pageid);
		  echo '<div class="view" style="text-align:center"><p><i class="fa fa-trash fa-5x"></i></p><h2> ลบหน้า '.$pagename.' แล้ว <br><br><a href="javascript:history.back(-1);" class="btGray">Back</a></h2></div>';
        }else{
        	echo '<div class="view" style="text-align:center"><p><i class="fa fa-exclamation-circle fa-5x"></i></p><h2>
        	 ไม่พบหน้าที่ต้องการทำรายการ<br><br><a href="javascript:history.back(-1);" class="button btGray">Back</a></h2></div>';
    	}
	}
	else //if not full screen
	{
		if(getOptionVal($pop[2])==1){
			echo '<div id="leftContainer">';
			eval('?>' . $p->getC('left_nav'). '<?php ');
			echo '</div>';
		}//end left

		if(getOptionVal($pop[1])==0){
			echo '<div id="middleContainer">';
			eval('?>' .$result[0]['body'] .'<?php ');
			echo '</div>';
		}
			else
		{
			eval('?>' .$result[0]['body'] .'<?php ');
		}

		if(getOptionVal($pop[3])==1){
			echo '<div id="rightContainer"  class="scroll-sidebar">';
			eval('?>' . $p->getC('right_nav'). '<?php ');
			echo '</div>';
		}//end right option

	}//end fullpage

	}
	else
	{
		header("location: /login");
	}

	echo '</div>'."\n";
	echo '</div>'."\n";


	if(getOptionVal($pop[6])==1){
		//display footer
		$ft_wrapper = '<div class="clear"></div>';
		$ft_wrapper .= '<div id="footer-wrapper">'."\n";
		$ft_wrapper .= '<div class="ft_link">'.GetConf('footer_link').'</div>';
		$ft_wrapper .= '<div class="footer-text">'.GetConf('footer_text').'</div>';
		$ft_wrapper .= '</div>'."\n";
		$p->privatefooter = $ft_wrapper;
		eval('?>' . $p->footer(). '<?php ');
	}
