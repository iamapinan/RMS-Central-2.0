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

	public function __construct($course_id){
		global $config;
		$http = new http_request;

		if(empty($http->courseId())){
			exit('Invalid incomming requested. Please try again from course menu.');
		}

		$this->CourseGet = $course_id;
		$this->coursedir = $config['data_path'].$this->CourseGet;
		$this->sqlite = $this->coursedir.'/database.sqlite';
		$this->meta = $this->coursedir.'/info.json';
		$this->image = $this->coursedir.'/default_thumbnail.jpg';
		$this->RmsSource = $config['rms_pulling_url'];
		$this->cdp = $this->CallDataAPI();

		if(!is_file($this->sqlite)){
			$this->CreateCourseData();
		}
	}

	public function CallDataAPI(){
		if(!is_file($this->meta))
			return json_decode(file_get_contents($this->RmsSource.$this->CourseGet), true);
		else
			return json_decode(file_get_contents($this->meta), true);
	}

	public function CreateCourseData(){
		global $config;

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