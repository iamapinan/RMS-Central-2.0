<?php namespace libs;

use libs\CourseProc;
use libs\Storage;

class UserProc extends Storage{
	public $sqlite;
	public $CourseGet;
	public $coursedir;
	protected $storage;

	function __construct($course_id){
		global $config;
		$http = new http_request;

		if(empty($http->courseId())){
			exit('Invalid incomming requested. Please try again from course menu.');
		}

		$this->CourseGet = $course_id;
		$this->coursedir = $config['data_path'].$this->CourseGet;
		$this->sqlite = $this->coursedir.'/database.sqlite';
		$this->storage = new Storage($this->sqlite);
	}

	public function UserList(){
		$result = $this->storage->select("SELECT * FROM student");
		return $result;
	}

	public function TeacherList(){
		$result = $this->storage->select("SELECT * FROM teacher");
		return $result;
	}

	public function userProfile($u){
		$result = $this->storage->select("SELECT * FROM student WHERE uid=?", array($u));
		return @$result[0];
	}

	public function Score($u){
		$result = $this->storage->select("SELECT * FROM score WHERE uid=?", array($u));
		return @$result[0];
	}

	public function teacherProfile(){
		$result = $this->storage->select("SELECT * FROM teacher WHERE status=1");
		return @$result[0];
	}

	public function avatar($user,$s='100x100')
	{
		global $config;
		$u = $this->userProfile($user);
		if($u['photo']==''){
			$url = $config['address'].$config['install_path'].'holder.png/'.$s.'/Avatar';
			return $url;
		}
		else{
			return '/img.php?width='.$s.'&height='.$s.'&cropratio=1:1&image='.$u['photo'];
		}
	}
}