<?php namespace libs;

class Storage {
	protected $sqlfile;
	public $db;
	protected $schema;
	function __construct($file){
		$this->sqlfile = $file;
		$this->db = new \PDO('sqlite:'.$this->sqlfile);
	}

	public function query($sql)
	{
		return $this->db->query($sql);
	}

	public function TableSetup(){
				$this->db->query("CREATE TABLE random 
								(course_id INTEGER PRIMARY KEY, 
								title VARCHAR(50), 
								description text, 
								lastuse VARCHAR(20), 
								image VARCHAR(100));");

				$this->db->query("CREATE TABLE score (id INTEGER (10) PRIMARY KEY, session_id VARCHAR (50), uid INT (10), main_score INT (10), skill_1 INT (10), skill_2 INT (10), skill_3 INT (10));");
				$this->db->query("CREATE TABLE student (uid INTEGER PRIMARY KEY, firstname VARCHAR (50), lastname VARCHAR (50), photo VARCHAR (100), grade INTEGER (10), class INT (10), status INT (2));");
				$this->db->query("CREATE TABLE session (session_id VARCHAR (50) PRIMARY KEY, timestamp VARCHAR (100));");
				$this->db->query("CREATE TABLE logs (time INTEGER PRIMARY KEY, data VARCHAR (500), type VARCHAR (20));");
				$this->db->query("CREATE TABLE teacher (uid INTEGER (10) PRIMARY KEY, firstname VARCHAR (50), lastname VARCHAR (50), status INT (2), photo VARCHAR (100));");
				$this->db->query("CREATE TABLE message (msg_id INT (10) PRIMARY KEY, session_id VARCHAR (50), 'to' INT (10), 'from' INT (10), text TEXT);");
				$this->db->query("CREATE TABLE course_meta (course_id INTEGER PRIMARY KEY, title VARCHAR (50), description text, lastuse VARCHAR (20), image VARCHAR (100), type INT (1));");
				$this->db->query("CREATE TABLE configs (title VARCHAR (50) PRIMARY KEY, value VARCHAR (200));");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('auto_approved', '1');");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('org', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('class_id', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('class_text', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('class', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('grade', NULL);");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('allow', '0');");
				$this->db->query("INSERT INTO configs (title, value) VALUES ('type', NULL);");
	}
}