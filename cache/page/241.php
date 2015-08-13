<div class="feedview">
<?php
switch($_GET['section']){
		case 'cc':
			$getinfo = explode(' ', $_REQUEST['ref']);
			$class = classinfo($getinfo[1]);
			$course = CourseInfo($getinfo[0]);
			$title = '<p class="title"><i class="fa fa-pencil-square-o"></i> '.$course['cname'].' ·  '.$class['title'].'</p>';
		break;
		case 'course':
			$course = CourseInfo($_REQUEST['ref']);
			$title = '<p class="title"><i class="fa fa-pencil-square-o"></i> '.$course['cname'].' </p>';
		break;
		case 'classroom':
			$course = CourseInfo($_REQUEST['ref']);
			$title = '<p class="title"><i class="fa fa-pencil-square-o"></i> '.$class['title'].' </p>';
		break;
}
echo '<div class="postcontainer">
'.$title.'
<textarea id="postmsgbox" class="postmsgbox"></textarea>
<div class="postbuttoncontainer">
			<input type="file" id="postfile" name="postfile" class="postfilebox" onchange="$(\'.postfiletext\').html($(this).val())">
			<p class="postfiletext"></p>
			<button class="button"><input type="checkbox" name="response" id="resp" value="1"> ให้ตอบกลับ</button>
			<button class="button btGray inlinepos" onclick="addPostFile()"><i class="fa fa-paperclip"></i> แนบไฟล์</button>
			<button class="button btGreen inlinepos" id="sharedata"><i class="fa fa-share-alt"></i> แชร์</button>
		</div>';
$query = "section='".$_GET['section']."' AND ref='".$_GET['ref']."'";
echo $show->getStream($query);
?>
</div>