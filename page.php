<?php
include 'initial.min.php';

$q = $_REQUEST;
		if(isset($_REQUEST['p'])){
			$cond = ' url = "'.@$_REQUEST['p'].'"';
			if($_REQUEST['p']=='message')
				$ActiveMN['msg'] = 'active';
			else
			if($_REQUEST['p']=='event')
				$ActiveMN['event'] = 'active';
			else
			if($_REQUEST['p']=='alert')
				$ActiveMN['alert'] = 'active';
		}
		else
		if(isset($_REQUEST['rq'])){
			$cond = ' id='.@$_REQUEST['rq'];
			if($_REQUEST['rq']=='1')
				$ActiveMN['msg'] = 'active';
			else
			if($_REQUEST['rq']=='31')
				$ActiveMN['event'] = 'active';
			else
			if($_REQUEST['rq']=='8')
				$ActiveMN['alert'] = 'active';
		}

		//Get page Data;
		$result = $do->select(array(
				'table' => conf('table_prefix').'_page',
				'condition' => $cond
			));
//404 error
		if(count($result)==0){
			$cond = ' url = "404" ';
			$result = $do->select(array(
				'table' => conf('table_prefix').'_page',
				'condition' => $cond
			));
		}

//Page options
		$pop = explode(";",$result[0]['option']);
		function getOptionVal($o)
		{
			$ret = explode("=",$o);
			return $ret[1];
		}
$pagename = $result[0]['url'];
$pageid = $result[0]['id'];
/**
*  background=#fff;fullpage=1;left=0;right=0;banner=0;ribbon=0;footer=0
*  $options[0] = background
*  $options[1] = fullpage
*  $options[2] = left
*  $options[3] = right
*  $options[4] = banner
*  $options[5] = ribbon
*  $options[6] = footer
*/

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
				header("location: /404");
		}

		echo '<link rel="stylesheet" href="'.conf('url').'library/syntax/lib/codemirror.css">
    <script src="'.conf('url').'library/syntax/lib/codemirror.js"></script>
    <script src="'.conf('url').'library/syntax/mode/xml/xml.js"></script>
    <script src="'.conf('url').'library/syntax/mode/javascript/javascript.js"></script>
    <script src="'.conf('url').'library/syntax/mode/css/css.js"></script>
    <script src="'.conf('url').'library/syntax/mode/clike/clike.js"></script>
	<script src="'.conf('url').'library/syntax/mode/markdown/markdown.js"></script>
    <script src="'.conf('url').'library/syntax/mode/php/php.js"></script>
	<script src="'.conf('url').'library/syntax/mode/markdown/markdown.css"></script>
    <link rel="stylesheet" href="'.conf('url').'library/syntax/theme/rubyblue.css">';

//Form
		echo '<div id="stat"></div>';
		echo '<form method="post" action="/BodySave.php" id="editbody">
		<h2 class="title">แก้ไข: '.$pagename.'</h2>';
		echo '<div id="toolbar">
		<a href="'.conf('url').$pagename.'" class="button btGray">กลับไปที่หน้า</a>
		<a href="#" class="button script btGray"><span class="symbol">V</span> แก้ไข header</a>
		<input type="submit" value=" Save " class="button btGray"> <a href="#" onclick="window.scrollTo(0,0)">ขึ้นข้างบน</a>
		</div>';
		echo '<textarea class="text-area edit-body script-body" name="script">'.htmlspecialchars($result[0]['include']).'</textarea><hr class="line">';
		echo '<textarea class="text-area edit-body" id="code" name="code">'.htmlspecialchars($result[0]['body']).'</textarea>';
		echo '<input type="hidden" name="page" id="pageid" value="'.$pageid.'">';
		echo '<input type="hidden" name="pn" id="pn" value="'.$pagename.'">';
		echo '<input type="submit" value=" Save " class="button btGray"> <span>ใช้ตัวช่วยสะกดสำหรับ Javascript กด Ctrl+Space </span>';
		echo '</form>';
		echo '<div class="preview">';
		echo '<h2 class="title">แสดงตัวอย่าง</h2>';
		echo '<iframe id="co" src="#" frameborder="0" framespacing="0" scrolling="0" width="100%" height="500"></iframe></div>';

//Config
		echo '<script src="'.conf('url').'library/syntax/complete.js"></script>';

	}else
	if(@$q['edit']=='delete')
	{
        if($q['p']!=''&&$q['p']==$result[0]['url']){
		  $ret = $do->delete(conf('table_prefix').'_page','id='.$pageid);
		  echo '<div class="view" style="text-align:center"><p><i class="fa fa-trash fa-5x"></i></p><h2> ลบหน้า '.$pagename.' แล้ว <br><br><a href="javascript:history.back(-1);" class="btGray">Back</a></h2></div>';
        }
        echo '<div class="view" style="text-align:center"><p><i class="fa fa-exclamation-circle fa-5x"></i></p><h2> ไม่พบหน้าที่ต้องการทำรายการ<br><br><a href="javascript:history.back(-1);" class="btGray">Back</a></h2></div>';
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

	echo $p->bos('bottom-script.js');

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

?>