<?php
include_once 'initial.min.php';
$id = $_GET['id'];

	$sql = mysql_query('select * from '.conf('table_prefix').'_profile where vc = "'.$id.'"');
	$res = @mysql_fetch_array($sql);

if($res['user']!=''){
if(isset($_SESSION['loginid']['nickname']))
	$button = '<p><a href="/my" class="button btPost"> ดูโปรไฟล์ </a></p>';
else
	$button = '<p><a href="/login" class="button btPost"> เข้าสู่ระะบบ </a></p>';

$print = '<div class="resf">
			<div class="view">
				<h2>ยืนยันสำเร็จแล้ว</h2>
				<p><img src="/library/images/_0007_Tick.png"></p>
				'.$button.'
			</div>
	</div>';
	mysql_query('UPDATE '.conf('table_prefix').'_profile SET status=1 WHERE user="'.$res['user'].'"');
}
else
{
	$print = '<div class="resf">
			<div class="view">
				<h2>รหัสยืนยันไม่ถูกต้อง</h2>
				<p><img src="/library/images/_0005_Delete.png"></p>
			</div>
	</div>';
}
		echo $p->globalheader();
		eval('?>' . $p->getC('header'). '<?php ');
		echo $p->toolbar()."\n";
		echo  '<div id="container">
		<div id="ContentBody">
		<center>';
		echo $print;
		echo '</center>
		</div>
		</div>
		</div>';

//display footer
	$ft_wrapper = '<div class="clear"></div>';
	$ft_wrapper .= '<div id="footer-wrapper">'."\n";
	$ft_wrapper .= '<div class="ft_link">'.GetConf('footer_link').'</div>';
	$ft_wrapper .= '<div class="footer-text">'.GetConf('footer_text').'</div>';
	$ft_wrapper .= '</div>'."\n";
	$p->privatefooter = $ft_wrapper;
	eval('?>' . $p->footer(). '<?php ');
?>