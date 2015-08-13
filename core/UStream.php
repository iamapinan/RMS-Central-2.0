<?php

class UStream extends common
{
	var $ret = '';

	public function getStream($q=null,$rang='0,20')
	{
		$i = UserInfo();
		$my = new mysql;
		$q = ($q!=null) ? 'WHERE '.$q : '';
		$qr = 'SELECT * FROM tc_subject '.$q.' ORDER BY bid DESC LIMIT '.$rang;
		$res = $my->query($qr);

		$c = count($res);
		$allowtype = array('pdf'=>'-pdf-o',
			'mp3'=>'-audio-o',
			'ppt'=>'-powerpoint-o',
			'pptx'=>'-powerpoint-o',
			'wav'=>'-audio-o',
			'mp4'=>'-video-o',
			'zip'=>'-zip-o',
			'rar'=>'-zip-o',
			'txt'=>'-text-o',
			'jpg'=>'-image-o',
			'png'=>'-image-o',
			'bmp'=>'-image-o',
			'doc'=>'-word-o',
			'docx'=>'-word-o',
			'xls'=>'-excel-o',
			'xlsx'=>'-excel-o');

		$this->ret = '<ul class="slist" id="slist">';
		for($x=0;$x<$c;$x++)
		{
			$aut = UserInfo($res[$x]['username']);
			if($aut['role']>=5)
				$alt = '<sup><i class="fa fa-check-circle-o text-warn" title="ครู"></i></sup>';
			else
				$alt = '';
			//Check user avatar.
			$avatar = image_resize('/user/'.$aut['user'].'/'.$aut['avatar'],50,50);

			if($allowtype[$res[$x]['filetype']]=='-image-o')
				$srcfile = '<div class="fileSRC"><a href="/data/content/'.$res[$x]['section'].'/'.$res[$x]['ref'].'/'.$res[$x]['file'].'" target="_blank" class="icon-thumbnail">
				<img src="'.image_resize('/data/content/'.$res[$x]['section'].'/'.$res[$x]['ref'].'/'.$res[$x]['file'],80,60,'1.3:1').'" class="img-thumbnail"></a><span class="postfilename">'.$res[$x]['file'].'</span></div>';
			else if($res[$x]['filetype']!='')
				$srcfile = '<div class="fileSRC"><a href="/data/content/'.$res[$x]['section'].'/'.$res[$x]['ref'].'/'.$res[$x]['file'].'" target="_blank" class="icon-thumbnail"><i class="fa fa-file'.$allowtype[$res[$x]['filetype']].' fa-4x"></i></a><span class="postfilename">'.$res[$x]['file'].'</span></div>';

			//Stream ouput start here.
				$this->ret .= '
				<li  class="post-'.$res[$x]['bid'].' content_line"  position-id="'.$res[$x]['bid'].'">
					<div class="thumb-wrap"><a href="/profile/'.$aut['user'].'" class="uthumb img-radius" style="background: url('.$avatar.');"></a></div>';
					$this->ret .= '<div class="desc-wrap">';
					$this->ret .= '<span class="postfullname">
						<a href="/profile/'.$aut['user'].'">'.str_replace("|"," ",$aut['fullname']).'</a> '.$alt.'
					</span>';

					$this->ret .= '<div class="post_contents">';
					$this->ret .= ''.nl2br($res[$x]['subject']).'';
					$this->ret .= $srcfile;
					$this->ret .= '</div>';

			//End Details
					$this->ret .= '
					<div id="post-fnc-'.$res[$x]['bid'].'">
						<span class="linkoption">
							<a href="javascript:;" onclick="liked('.$res[$x]['bid'].')">
							<i class="fa fa-thumbs-up"></i>
							<span class="liked_'.$res[$x]['bid'].'"> '.$res[$x]['like'].'</span> Like
							</a>
						</span>
						';

						//Delete button
						if($res[$x]['username']==$i['user']||$i['role']>=5){
							$this->ret .= '
							<span class="linkoption">
							&nbsp;<a href="javascript:;" class="p'.$res[$x]['bid'].'" onClick="delete_post('.$res[$x]['bid'].')">
							<i class="fa fa-trash-o"></i> ลบ</a>
							</span>';
						}
						if($res[$x]['response_need']==1){
							//For student
							if($i['role']<=3){
								$replylookup[$x] = mysql_query("SELECT * FROM tc_comment WHERE uid=".$i['ssid']." AND bid=".$res[$x]['bid']." ORDER BY cid DESC LIMIT 0,3");
							}
							else{
								$replylookup[$x] = mysql_query("SELECT * FROM tc_comment WHERE bid=".$res[$x]['bid']." ORDER BY cid DESC LIMIT 0,3");
							}
							$replyCount[$x] = mysql_num_rows($replylookup[$x]);
							
							if($replyCount[$x]>=1){
								$rep_text[$x] = "เคยตอบแล้วแล้ว ".$replyCount[$x]." ครั้ง";
								$replyBox[$x] .= '<div class="replyContainer">';
								while($replyresult[$x] = mysql_fetch_array($replylookup[$x])){
									if($replyresult[$x]['file']!='') $filetext[$x] = '<div class="fileSRC"><a href="'.$replyresult[$x]['file'].'" target="_blank"><i class="fa fa-file fa-2x"></i> &nbsp;<span class="inlinepos">'.basename($replyresult[$x]['file']).'</span></a></div>';
									else $filetext[$x] = '';
									$replyUser = UserById($replyresult[$x]['uid']);
									$replyBox[$x] .= '<div class="replyListbox" id="rep-'.$replyresult[$x]['cid'].'">
									<div class="inlinepos"><img src="'.image_resize('/user/'.$replyUser['user'].'/'.$replyUser['avatar'] ,30,30).'" class="img-circle"></div>
									<div class="inlinepos"><p class="marginbottom5">
									<a href="/profile/'.$replyUser['user'].'" class="inlinepos">'.str_replace('|',' ',$replyUser['fullname']).'</a>
									 
									<span class="inlinepos"> &nbsp;'.$replyresult[$x]['msg'].'</span></p>
									'.$filetext[$x].'
									<span class="inlinepos text-gray">'.relativeTime($replyresult[$x]['timestamp']).'</span>';
						if($i['role']>=5){
						if($i['ssid']!=$replyresult[$x]['uid']){
						$replyBox[$x] .= '		
									· <span class="inlinepos"><a href="javascript:;" onclick="delreply('.$replyresult[$x]['cid'].')"><i class="fa fa-trash"></i> ลบ</a></span>';
						}
						if($replyresult[$x]['status']==0&&$i['ssid']!=$replyresult[$x]['uid']){
						$replyBox[$x] .= '· <span class="inlinepos text-gray point-'.$replyresult[$x]['cid'].'"><a href="javascript:;" onclick="addPoint('.$replyresult[$x]['cid'].')"><i class="fa fa-plus-circle"></i> ให้คะแนน</a></span>';
						}else if($i['ssid']!=$replyresult[$x]['uid']){
							$replyBox[$x] .= '· <span class="inlinepos text-gray point-'.$replyresult[$x]['cid'].'">มี 1 คะแนน</span>';
						}
						
						if($replyresult[$x]['show']==0&&$i['ssid']!=$replyresult[$x]['uid']){
						$replyBox[$x] .= '· <span class="inlinepos text-gray pb-'.$replyresult[$x]['cid'].'"><a href="javascript:;" onclick="showreply('.$replyresult[$x]['cid'].')"><i class="fa fa-eye"></i> เปิดเผย</a></span>';
						}else if($i['ssid']!=$replyresult[$x]['uid']){
						$replyBox[$x] .= '· <span class="inlinepos text-gray pb-'.$replyresult[$x]['cid'].'">สาธารณะ</span>';
						}
						$replyBox[$x] .= '</div></div>';
						}
								}
								$replyBox[$x] .= '</div>';
							}
							else $rep_text[$x] = "";
							$this->ret .= '&nbsp;<a href="javascript:;" onclick="replybtn('.$res[$x]['bid'].')" class="replybutton" data-id="'.$res[$x]['bid'].'"> <span class="linkoption" >
						<i class="fa fa-reply"></i> ตอบกลับ</span></a> <span class="linkoption text-gray">'.$rep_text[$x].'</span>';
							$this->ret .= '<div class="replybox rep-'.$res[$x]['bid'].'">
								<span class="replyinput">
									<input type="text" name="replymsg" class="replymsg" id="replymsg-'.$res[$x]['bid'].'" placeholder="ข้อความของท่าน">
									<div class="rpbutton">
									<a href="javascript:;" onclick="addreplyfile('.$res[$x]['bid'].')" class="attf replyoptionbt"><i class="fa fa-paperclip"></i> แนบไฟล์</a>&nbsp;&nbsp;
									<a href="javascript:;" onclick="savereply('.$res[$x]['bid'].')" class="repsa replyoptionbt"><i class="fa fa-check-circle"></i> ตอบ</a>
									<a href="javascript:;" onclick="$(\'.replybox\').hide()" class="repsa replyoptionbt"><i class="fa fa-times"></i> ยกเลิก</a>
									</div>
								</span>
								<input type="file" name="replyFile" class="hidden" id="repf-'.$res[$x]['bid'].'" onchange="$(\'.attf\').html(\'มี 1 ไฟล์\');$(\'.repsa\').addClass(\'text-green\')">
							</div>';
						}
						$this->ret .= '&nbsp;&nbsp;<span class="linkoption"><i class="fa fa-clock-o"></i> '.relativeTime($res[$x]['timestamp']).'</span>';

						$this->ret .= '</div>'; //Close post-fnc
					$this->ret .= '</div>'; //Close desc-wrap
					$this->ret .= $replyBox[$x];
					$this->ret .= '</li>';
			//End stream

		}//End for loops.
		$this->ret .= '</ul>';

		if($c>20)
		{
			$this->ret .= '<div class="btmore" onclick="morefeed()"><i class="fa fa-chevron-circle-down"></i> แสดงเพิ่มเติม </div>';
		}

		if($c==0)
		{
			$this->ret .= '<h2 class="btmore"><i class="fa fa-comment-o fa-2x"></i> เริ่มโพสต์เป็นคนแรก </h2>';
		}
		return $this->ret;
	}

}
