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
		return @base64_decode($this->requests['crs']);
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
  
	private function generate_xml_from_array($array, $node_name) {
		$xml = '';

		if (is_array($array) || is_object($array)) {
			foreach ($array as $key=>$value) {
				if (is_numeric($key)) {
					$key = $node_name;
				}

				$xml .= '<' . $key . '>' . "\n" . $this->generate_xml_from_array($value, $node_name) . '</' . $key . '>' . "\n";
			}
		} else {
			$xml = htmlspecialchars($array, ENT_QUOTES) . "\n";
		}

		return $xml;
	}

	private function generate_valid_xml_from_array($array, $node_block='nodes', $node_name='node') {
		$xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";

		$xml .= '<' . $node_block . '>' . "\n";
		$xml .= $this->generate_xml_from_array($array, $node_name);
		$xml .= '</' . $node_block . '>' . "\n";

		return $xml;
	}

	public function r_implode( $glue, $pieces )
	{
		foreach( $pieces as $r_pieces )
		{
	    		if( is_array( $r_pieces ) )
	    		{
	      			$retVal[] = $this->r_implode( $glue, $r_pieces );
	    		}
	    		else	
	    		{
	      			$retVal[] = $r_pieces;
	    		}
	  	}
	  	return implode( $glue, $retVal );
	} 

	public function data_response(array $data, $type="json")
	{
		if(!empty($data)){
			switch($type){
				case 'json':
					ob_start();
					header('Content-Type: application/json');
					return json_encode($data);
				break;
				case 'html':
					return $this->r_implode(',', $data);
				break;
				case 'array':
					return var_export($data);
				break;
				case 'url':
					return http_build_query($data);
				break;
				case 'serialize':
					return serialize($data);
				break;
				case 'unserialize':
					return @unserialize($data);
				break;
				case 'base64':
					return base64_encode($this->r_implode(',',$data));
				break;
				case 'xml':
					ob_start();
					header('Content-type: application/xml');

					return $this->generate_valid_xml_from_array($data);
				break;
			}

		}else
		{
			return;
		}
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

	public function file($f){
		return @(object)$_FILES[$f];
	}
}