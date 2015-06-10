<?php
	include 'initial.php';
	echo $p->globalheader();
	echo '<div id="container">'."\n";
	eval('?>' . $p->getC('header'). '<?php ');
	echo $p->toolbar()."\n";
	$iam = UserInfo();
	header('location: /my');
//index page.
	echo '<div id="ContentBody">';
	echo '<div id="middleContainer">';
	echo '<b>ยินดีต้อนรับ '.str_replace('|',' ',$iam['fullname']).'</b>';
	echo '<div class="menu">';
	echo '<ul>';
	echo  '<li><a href="/my">โปรไฟล์ของฉัน</a></li>';
	echo  '<li><a href="/my_privacy">ตั้งค่าความเป็นส่วนตัว</a></li>';
	echo  '<li><a href="/go/logout">ออกจากระบบ</a></li>';
	echo '</ul>';
	echo '</div>';
	echo '</div>';
	echo '<div id="bottom-link"></div>';
	echo '</div>'."\n";
	echo '</div>'."\n";
	echo $p->bos('bottom-script.js');
	//display footer
	$ft_wrapper = '<div class="clear"></div>';
	$ft_wrapper .= '<div id="footer-wrapper">'."\n";
	$ft_wrapper .= '<div class="ft_link">'.GetConf('footer_link').'</div>';
	$ft_wrapper .= '<div class="footer-text">'.GetConf('footer_text').'</div>';
	$ft_wrapper .= '</div>'."\n";
	$p->privatefooter = $ft_wrapper;
	eval('?>' . $p->footer(). '<?php ');
?>