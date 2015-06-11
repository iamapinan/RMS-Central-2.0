<?php namespace libs;

class http_request{
	public $requests;
	public $get;
	public $post;
	public $files;

	public function __construct(){
		$this->requests = $_REQUEST;
		$this->get = $_GET;
		$this->post = $_POST;
		$this->files = $_FILES;
	}
	public function request(){
		return  (object)$_REQUEST;
	}

	public function courseId(){
		return base64_decode($this->requests['crs']);
	}

	public function get(){
		return (object)$_GET;
	}

	public function post(){
		return (object)$_POST;
	}

	public function server(){
		return (object)$_SERVER;
	}

	public function currents($getvar){
		$filename = str_replace('index.php','session', $this->server()->SCRIPT_NAME);
		return $filename.'?'.$getvar.'='.$this->get()->$getvar;
	}

	public function cookie(){
		return (object)$_COOKIE;
	}

	public function sessions(){
		return (object)$_SESSION;
	}

	public function file(){
		return (object)$_FILE;
	}
}