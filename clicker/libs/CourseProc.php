<?php namespace libs;

use libs\http_request;
use libs\Storage;

class CourseProc {
	public  $cdp;
	protected  $CourseGet;
	public 	$coursedir;
	public $sqlite;
	public $meta;
	public $image;
	protected $RmsSource;
	protected $storage;
	protected $session;
	public $install_path;
	public function __construct($course_id){
		global $config;
		$http = new http_request;

		if(empty($course_id)){
			exit('Invalid incomming requested. Please try again from course menu.');
		}

		$this->CourseGet = $course_id;
		$this->coursedir = $config['data_path'].$this->CourseGet;
		$this->sqlite = $this->coursedir.'/database.sqlite';
		$this->meta = $this->coursedir.'/info.json';
		$this->image = $this->coursedir.'/default_thumbnail.jpg';
		$this->RmsSource = $config['rms_pulling_url'];
		$this->install_path = $config['address'].$config['install_path'];
		$this->cdp = $this->CallDataAPI();
		
		
		if(!is_file($this->sqlite)){
			$this->CreateCourseData();
		}
		$this->storage = new Storage($this->sqlite);
	}
	public function imageSetup($img,$size='100x100', $t='File', $crop="1:1"){
		$imgtype = array('jpg','png','bmp','jpeg','gif');
		$imgp = pathinfo($img);

		$s = explode('x', $size);

		if(@in_array($imgp['extension'], $imgtype))
		{
			return 'img.php?&width='.$s[0].'&height='.$s[1].'&cropratio='.$crop.'&image='.$img;
		}
		else{
			return $this->install_path.'holder.png/'.$size.'/'.$t;
		}
	}

	public function getStdAll(){
		$std = $this->storage->select("SELECT * FROM student WHERE status=1");
		return $std;
	}

	public function courseFiles($sesid = null){
		if($sesid!=null){ $sesionfile = $sesid; }
		else{ 
			$ses = $this->createSession($this->CourseGet); 
			$sesionfile = $ses->session;
		}

		$file = $this->storage->select("SELECT * FROM `file` WHERE `session`='".$sesionfile."'");
		return $file;
	}

	public function CallDataAPI(){

		if(!is_file($this->meta))
			return json_decode(file_get_contents($this->RmsSource.$this->CourseGet), true);
		else
			return json_decode(file_get_contents($this->meta), true);
	}

	public function getCourseConfig($f){
		$courseconfig = $this->storage->select("SELECT `value` FROM configs WHERE `title`='$f'");
		return $courseconfig[0]['value'];
	}

	public function sessionStatus($sessionid){
		$status = $this->storage->select("SELECT status FROM session WHERE session_id='$sessionid'");

		return $status[0]['status'];
	}

	public function courseSync(){
		$http = new http_request;
		$courseData = $this->CallDataAPI();
		file_put_contents($this->meta, json_encode($courseData));
		//Check data
		$test = $this->storage->select("SELECT course_id FROM course_meta");
		
		if(empty($test[0]['course_id']))
		{
			$this->CreateCourseData();
			//Insert new data.
			$this->storage->query("INSERT INTO course_meta (course_id,title,description,lastuse,image,type) VALUES(".$courseData['course_id'].",
				'".$courseData['cname']."','".$courseData['cdetail']."','".time()."','default_thumbnail.jpg','".$courseData['type']."')");

			//configs
			$this->storage->query("UPDATE configs SET `value`='".$courseData['public']."' 				WHERE `title`='auto_approved'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['sid']."' 					WHERE `title`='org'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['course_id']."' 			WHERE `title`='course_id'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['cname']."' 				WHERE `title`='course_text'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['class']['room_number']."' WHERE `title`='class'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['class']['grade']."' 		WHERE `title`='grade'");

			//Teacher
			foreach($courseData['teacher'] as $ta){
				if($ta['photo']!='') {
					@$fileinfo = pathinfo($ta['photo']);
					copy($ta['photo'], $this->coursedir.'/'.$fileinfo['basename']);
				}
				$ta['status'] = ($http->request()->tid==$ta['user']) ? 1 : 0;
				$this->storage->query("INSERT INTO teacher (uid,firstname,lastname,status,photo) VALUES('".$ta['user']."','".$ta['firstname']."'
				,'".$ta['lastname']."',".$ta['status'].",'".@$fileinfo['basename']."')");
			}

			//Student
			foreach($courseData['student'] as $st){
				if($st['photo']!='') {
					@$stdfileinfo = pathinfo($st['photo']);
					copy($st['photo'], $this->coursedir.'/'.@$stdfileinfo['basename']);
				}

				$this->storage->query("INSERT INTO student (uid,firstname,lastname,email,photo,grade,class,status) VALUES('".$st['citizid']."','".$st['firstname']."'
				,'".$st['lastname']."','".$st['email']."','".@$stdfileinfo['basename']."',".$st['grade'].",".$st['class'].",1)");

				//$uscr = $this->storage->select("SELECT uid FROM score WHERE uid='".$st['citizid']."'");
				//if(@$uscr[0]['uid']==''){
				$this->storage->query("INSERT INTO score (uid,main_score,crystal,gold,coin) VALUES
					('".$st['citizid']."',0,0,0,0)");
				//}
			}
			
		}
		else
		{
			//Metadata
			$this->storage->query("UPDATE course_meta SET course_id='".$courseData['course_id']."', title='".$courseData['cname']."',
			description='".$courseData['cdetail']."',lastuse='".time()."', type='".$courseData['type']."'");

			//Teacher
			//Delete teacher data.
			$this->storage->query("DELETE FROM teacher");
			//Insert new
			foreach($courseData['teacher'] as $ta){
				if($ta['photo']!='') {
					@$fileinfo = pathinfo($ta['photo']);
					copy($ta['photo'], $this->coursedir.'/'.@$fileinfo['basename']);
				}
				$ta['status'] = ($http->request()->tid==$ta['user']) ? 1 : 0;
				
				$this->storage->query("INSERT INTO teacher (uid,firstname,lastname,status,photo) VALUES('".$ta['user']."','".$ta['firstname']."'
				,'".$ta['lastname']."',".$ta['status'].",'".@$fileinfo['basename']."')");
			}

			//Student
			$this->storage->query("DELETE FROM student");
			foreach($courseData['student'] as $st){
				if($st['photo']!='') {
					@$stdfileinfo = pathinfo($st['photo']);
					copy($st['photo'], $this->coursedir.'/'.@$stdfileinfo['basename']);
				}

				$this->storage->query("INSERT INTO student (uid,firstname,lastname,email,photo,grade,class,status) VALUES('".$st['citizid']."','".$st['firstname']."'
				,'".$st['lastname']."','".$st['email']."','".@$stdfileinfo['basename']."',".$st['grade'].",".$st['class'].",1)");

				$uscr = $this->storage->select("SELECT uid FROM score WHERE uid='".$st['citizid']."'");
				if(@$uscr[0]['uid']==''){
					$this->storage->query("INSERT INTO score (uid,main_score,crystal,gold,coin) VALUES
					('".$st['citizid']."',0,0,0,0)");
				}
			}

			//configs
			$this->storage->query("UPDATE configs SET `value`='".$courseData['public']."' 				WHERE `title`='auto_approved'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['sid']."' 					WHERE `title`='org'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['course_id']."' 			WHERE `title`='course_id'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['cname']."' 				WHERE `title`='course_text'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['class']['room_number']."' WHERE `title`='class'");
			$this->storage->query("UPDATE configs SET `value`='".$courseData['class']['grade']."' 		WHERE `title`='grade'");

			$this->CreateCourseData();
		}

	}
	public function sessionInfo($ses){
		$result = $this->storage->select("SELECT * FROM session WHERE session_id=?", array($ses));
		return $result[0];
	}

	public function courseInfo(){
		$result = $this->storage->select("SELECT * FROM course_meta");
		return $result[0];
	}

	public function createSession($c)
	{
		$http = new http_request;
		$check = $this->storage->select("SELECT session_id FROM session WHERE status=1");

		if(@$check[0]['session_id']!=''||isset($http->request()->sessionid)){
			$res['status'] = 1;
			$res['session'] = $check[0]['session_id'];
			$this->CreateCourseData();
			return (object)$res;
		}
		else{
			$sessionid = sha1($c.time());
			$qr = $this->storage->query("INSERT INTO session (session_id, course_id, timestamp, status) VALUES('$sessionid',$c,".time().", 1)");
			if($qr)
			{
				$this->courseSync();

				$res['status'] = 1;
				$res['session'] = $sessionid;
				return (object)$res;
			}
		}
	}

	public function endClass($sessionid){
		$update  = $this->storage->query("UPDATE session SET status=0 WHERE session_id='$sessionid'");
		@unlink($this->meta);
		return $update;
	}

	public function MediaFile($session){
		$file = $this->storage->query("SELECT * FROM file WHERE session=".$session);
		return $$file;
	}

	public function CreateCourseData(){
		//if(_API_ === true) exit(200);

		if(!is_dir($this->coursedir)){
			mkdir($this->coursedir);
			mkdir($this->coursedir.'/files');
		}
		
		if(!is_file($this->meta)){
			file_put_contents($this->meta, json_encode($this->cdp));
		}

		if(!is_file($this->image)) 
			copy($this->cdp['img'], $this->image);

		if(!file_exists($this->sqlite)){
			touch($this->sqlite);

			$db = new Storage($this->sqlite);
			$db->TableSetup();
		}


	}


}