<?php namespace libs;

use \PDO;

class Storage extends PDO{
	protected $sqlfile;
	public $db;
	protected $schema;

	function __construct($file){
		$this->sqlfile = $file;
		$this->db = new PDO('sqlite:'.$this->sqlfile);
		$this->db->exec("SET NAMES UTF8");
		$this->db->query("SET character_set_results=utf8");
	}	

	public function query($sql)
	{
		return $this->db->query($sql);
	}

	public function select($sql, $arr = array(), $argument = PDO::FETCH_ASSOC)
	{
		$query = $this->db->prepare($sql);
		$query->execute($arr);
		$result = $query->fetchAll($argument);
		return $result;

	}

	public function TableSetup(){
				$this->db->query("CREATE TABLE random (course_id INTEGER PRIMARY KEY, title VARCHAR(50),description text, lastuse VARCHAR(20), image VARCHAR(100));");
				$this->db->query("CREATE TABLE score (id INTEGER PRIMARY KEY AUTOINCREMENT, uid VARCHAR (20), main_score INT (10), crystal INT (10), gold INT (10), coin INT (10), badge VARCHAR (50));");
				$this->db->query("CREATE TABLE student (uid VARCHAR (20) PRIMARY KEY, firstname VARCHAR (50), lastname VARCHAR (50), email VARCHAR (50), photo VARCHAR (100), grade INTEGER (10), class INT (10), status INT (2));");
				$this->db->query("CREATE TABLE session (session_id VARCHAR (50) PRIMARY KEY, course_id INT (10) NOT NULL, timestamp VARCHAR (100), status INT (2));");
				$this->db->query("CREATE TABLE logs (time INTEGER PRIMARY KEY, data VARCHAR (500), type VARCHAR (20));");
				$this->db->query("CREATE TABLE teacher (uid VARCHAR (20) PRIMARY KEY, firstname VARCHAR (50), lastname VARCHAR (50), status INT (2), photo VARCHAR (100));");
				$this->db->query("CREATE TABLE message (msg_id INT (10) PRIMARY KEY, session_id VARCHAR (50), 'to' INT (10), 'from' INT (10), text TEXT);");
				$this->db->query("CREATE TABLE course_meta (course_id INTEGER PRIMARY KEY, title VARCHAR (50), description text, lastuse VARCHAR (20), image VARCHAR (100), type VARCHAR (50));");
				$this->db->query("CREATE TABLE configs (title VARCHAR (50) PRIMARY KEY, value VARCHAR (200));");
				$this->db->query("CREATE TABLE file (id INTEGER PRIMARY KEY AUTOINCREMENT,filename VARCHAR (200),session VARCHAR (100),ftype VARCHAR (50),title VARCHAR (100),accessible INT (2));");
				$this->db->query("CREATE TABLE score_history (id INTEGER PRIMARY KEY AUTOINCREMENT, session_id VARCHAR (100), uid VARCHAR (50), score INT (10), timestamp INT (30), score_type VARCHAR (30) );");

				$this->db->query("INSERT INTO configs (title, value) VALUES ('auto_approved', '1');");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('org', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('course_id', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('course_text', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('class', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('grade', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('allow', '0');");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('type', 0);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('music', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('randomcount', 2);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('random_speed', '1000');");
	
	}
}