<?php namespace core;
// Written by Apinan
// Create date 5/1/2558
// License GPL


class rmsldap
{

	public $LDAP_Host;
	public $LDAP_Version;
	public $LDAP_PORT;
	public $LDAP_Encoding;
	public $LDAP_DC;
	public $DN;
	public $PS;
	public $USR;
	public $OU;
	public $LDAP_ADMIN;
	public $ADMIN_PS;
	public $connect;


	function __construct(){

		$this->LDAP_Host = getConf('ldap-host');
		$this->LDAP_Version = getConf('ldap-version');
		$this->LDAP_PORT = getConf('ldap-port');
		$this->LDAP_Encoding = getConf('ldap-encode');
		$this->LDAP_DC = getConf('ldap-dc');
		$this->LDAP_ADMIN = getConf('ldap-cn-admin');
		$this->ADMIN_PS = getConf('ldap-ps-admin');
	}

	public function rms_ldap_start()
	{
		try{
			$this->connect = ldap_connect($this->LDAP_Host, $this->LDAP_PORT);
			ldap_set_option($this->connect, LDAP_OPT_PROTOCOL_VERSION, $this->LDAP_Version);
		} catch(Exception $e){
			die('Caught exception: '.  $e->getMessage(). "\n");
		}
		return $this->connect;
	}

	public function rms_ldap_search()
	{
		$adminbind = ldap_bind($this->connect, $this->LDAP_ADMIN, $this->ADMIN_PS);
		$systemou = getConf('ldap-ou');

		$splite = explode(';', $systemou);

		for($x=0;$x<count($splite);$x++){
			if(!empty($this->OU)){
				$splite1 = explode(',', $splite[$x]);
				if($splite1[0]=="OU=".$this->OU){
					$SearchOU = $splite[$x].','.$this->LDAP_DC;
				}

			}
		}

		$searchname = $this->USR;
		$search = ldap_search($this->connect,$SearchOU,"(|(mail=$searchname)(givenname=$searchname))");
		$result =  ldap_get_entries($this->connect,$search);
		return $result;

	}

	public function rms_ldap_authen()
	{
		$ldapbind = ldap_bind($this->connect, $this->DN, $this->PS);
		return $ldapbind;
	}
	public function rms_role_gen()
	{
		switch($this->OU)
		{
			case 'Teachers':
				return 5;
			break;
			case 'Students':
				return 3;
			break;
			default:
				return 1;
			break;
		}
	}

	public function rms_member_register($Q = array())
	{

		$role = $this->rms_role_gen();

		$qr = "INSERT INTO ".conf('table_prefix')."_profile
		(`user`, `provider`, `password`, `fullname`, `email`, `role`, `org`, `gender`, `language`, `citizen_id`, `verify`, `status`)
		VALUES (
		'".$Q['username']."',
		'ldap',
		'".$Q['ps']."',
		'".$Q['fullname']."',
		'".$Q['mail']."',
		'".$role."',
		'".getConf('ldap-org')."',
		'f',
		'th',
		'".$Q['username']."',
		1,1
		)";

		$run = mysql_query($qr);
		return $run;
	}

	public function rms_am_register($Q = array())
	{
		$idm = new IDM2API;
		$chkg = $idm->check_group(getConf('ldap-org'));

		$sql_school = mysql_query('select * from '.conf('table_prefix').'_school where sid='.getConf('ldap-org'));
		$school = mysql_fetch_array($sql_school);

		if($chkg==false) $add = $idm->add_group(getConf('ldap-org'), $school['sname']);
		$create = $idm->create_user($Q['username'], $Q['password'], $Q['fullname'], $this->rms_role_gen(), getConf('ldap-org'));
		return $create;
	}
}


?>