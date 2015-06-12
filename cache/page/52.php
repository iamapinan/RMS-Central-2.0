<?php

if(isset($_GET['edit'])&&$_GET['token']!=$session_token)
{
	echo '<div class="view" style="text-align: center;line-height: 28px;margin-left: 225px;">
	<h3><span class="icx1-lock"></span> การกำหนดค่ารักษาความปลอดภัยไม่ถูกต้องโปรดลองใหม่อีกครั้ง</h3>
	<p><a href="/"><span class="button btGray">ย้อนกลับ</span></a></p>
	</div>';
	exit;
}
$post = mysql_fetch_array(mysql_query('select * from '.conf('table_prefix').'_subject where req_id="'.$_GET['edit'].'"'));
if(isset($_GET['edit'])&&$post['req_id']=='')
{
	echo '<div class="view" style="text-align: center;line-height: 28px;margin-left: 225px;">
	<h3><span class="icx1-sad"></span> ไม่พบเนื้อหาที่คุณต้องการแก้ไข กรุณาตรวจสอบใหม่อีกครั้ง</h3>
	<p><a href="/"><span class="button btGray">ย้อนกลับ</span></a></p>
	</div>';
	exit;
}
else
{
	if($_GET['edit']!=''){
		$title_text = 'แก้ไขบทความ';
		$owner = $post['username'];
	}
	else
	{
		$title_text = 'บทความใหม่';
		$owner = $_SESSION['loginid']['nickname'];
	}
}
$file = conf('url').'data/content/'.$post['req_id'].'/'.$post['req_id'].'.html';
$blog_data = file_get_contents($file);
$blog_data = str_replace('<!DOCTYPE html>','',$blog_data);
$blog_data = str_replace('<html lang="en" class="no-js">','',$blog_data);
$blog_data = str_replace('<head>','',$blog_data);
$blog_data = str_replace('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">','',$blog_data);
$blog_data = str_replace('<link rel="stylesheet" type="text/css" href="/library/blogstyle.css" charset="utf-8"/>','',$blog_data);
$blog_data = str_replace('</head>','',$blog_data);
$blog_data = str_replace('<body>','',$blog_data);
$blog_data = str_replace('</body>','',$blog_data);
$blog_data = str_replace('</html>','',$blog_data);
?>
<form method="post" id="bgp">
<div class="title"><?php echo $title_text;?></div>
<div id="new-form">
<input type="hidden" id="rqk" name="rqk" value="<?php if($_GET['edit']!='') echo $post['req_id']; else echo $p->randomstr(25); ?>">
<input type="hidden" id="edit" name="edit" value="<?php echo $_GET['edit']; ?>">
	<p><b> ชื่อบทความ</b></p>
	<p><span><input type="text"  name="title" class="text-input" id="title" placeholder="ตั้งชื่อบทความ" onChange="repme();$(this).attr('style','background-color: #fff !important');" autocomplete="off" value="<?php echo $post['subject'];?>"> <font color="red">*</font></span> 
	</p>
	<input type="hidden" id="owner" name="owner" value="<?php echo $owner; ?>">
	<input type="hidden" id="token" name="token" value="<?php echo $session_token; ?>">
<select id="gp" data-type="1" name="gp" onchange="getsub($(this).val(),$(this).attr('data-type'));$(this).attr('style','background-color: #fff !important');">
		<option value="" selected disabled class="option-color-disable">กรุณาเลือกกลุ่มรายวิชา</option>
<?php
$sql = mysql_query('select * from '.conf('table_prefix').'_group order by gid asc');
while($gm=mysql_fetch_array($sql))
{
	if($post['group']==$gm['gid']) $select = 'selected'; else $select = '';
	if($gm['gid']<18)  echo '<option value="'.$gm['gid'].'" '.$select.' >'.$gm['name'].'</option>';
	if(($client['role']==8||$client['admin']==1)&&($gm['gid']>=18)) 
	echo '<option value="'.$gm['gid'].'" '.$select.' class="option-color-secret">'.$gm['name'].'</option>';
}
?>
	</select> <font color="red">*</font>

<select id="lev" onchange="$(this).attr('style','background-color: #fff !important');">
<option value=""  selected disabled class="option-color-disable">กรุณาเลือกระดับชั้น</option>
<?php
$sql = mysql_query('select * from '.conf('table_prefix').'_level order by level_id asc');
while($gm=mysql_fetch_array($sql))
{
	if($post['level']==$gm['level_id']) $select = 'selected'; else $select = '';
	
	echo '<option value="'.$gm['level_id'].'" '.$select.'>'.$gm['name'].'</option>';
}
?>
</select> <font color="red">*</font>
<div id="choice"></div>

<script language="javascript">
function getsub(cat,t)
{
     $.post('/social_func.php?g=listsubcat&t='+t+'&id='+cat, function(data)
     {
         if(data==1){  
		 $('#choice').hide();
		 $('#choice').html(''); 
		 return false; 
	 }
         else{ 
		 $('#choice').show();
		 $('#choice').html(data); 
		}
     });
}

</script>
	<p>&nbsp;</p>
	<p><b>แท็ก</b></p>
	<p><input type="text" class="text-input" id="keyword" placeholder="คำค้น1, คำค้น2, คำค้น3,..."  autocomplete="off" value="<?php echo $post['tag'];?>"></p>
	<p>&nbsp;</p>
	<span>
		<div id="content" contenteditable="true"><?php 
		if($_GET['edit']!='') echo $blog_data; else echo 'เขียนเนื้อหาของคุณ';?></div>
	</span>
	
	<p><input type="checkbox" name="allwpb" value="1"> เผยแพร่ในหน้าสาธารณะ</p>
	<p>&nbsp;</p>
	<p><a href="#"><span class="button btPost" id="submitbt">บันทึก</span></a>
	<span><a href="/" class="button btn-link">หรือยกเลิก?</a></span></p>

</div>
</form>