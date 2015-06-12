<div class="view">
<h2 class="title">LDAP Connect tester</h2>
<?php
$LDAP_Host = 'ldap://bll-dc01.bll.in.th';
$LDAP_Version = 3;
$LDAP_PORT = 389;
$LDAP_Encoding = 'utf-8';
$LDAP_DC = 'DC=bll,DC=in,DC=th';
$LDAP_DN = 'CN=Administrator,CN=Users';

//$user = 'ou=teachers,ou=rms,CN=teacher01,CN=Users,DC=bll,DC=in,DC=th';
$user = 'CN=Administrator,CN=Users,DC=bll,DC=in,DC=th';
$pass = 'P@$$w0rdbll2013';
$ldapou = 'OU=teachers,OU=rms,DC=bll,DC=in,DC=th';
$ldapoustd = 'OU=students,OU=rms,DC=bll,DC=in,DC=th';
$ldapoummt = 'OU=MMT,OU=RMS,DC=bll,DC=in,DC=th';
// Connecting to LDAP
$ldapconn = ldap_connect($LDAP_Host, $LDAP_PORT) or die("<p>[".date('H:i:s:u',time())."] Could not connect to $ldaphost</p>");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, $LDAP_Version);

if($ldapconn)
{
	echo "<p>[".date('H:i:s:u',time())."] Connect to $LDAP_Host:\t succuessfully.\n</p>";
	//$ldapbind = ldap_bind($ldapconn, $user, $pass);

	echo "<p>[".date('H:i:s:u',time())."] Bind DN: $binddn \n</p>";
        $ldapbind = ldap_bind($ldapconn, $user, $pass);
    if($ldapbind)
	{
		echo "<p>[".date('H:i:s:u',time())."] LDAP bind:\t\t successful...</p>";
		echo "<p>[".date('H:i:s:u',time())."] Search OU:\t\t $ldapou</p>";
		$result = ldap_search($ldapconn, $ldapou,"(cn=*)"); 
		$rec = ldap_get_entries($ldapconn,$result);
		$ce = ldap_count_entries($ldapconn,$result);
		echo "<p>[".date('H:i:s:u',time())."] Count Entries: ".$ce."</p>";
		/*
		for($x=0;$x<$ce;$x++){
		echo "<p>[".date('H:i:s:u',time())."] Test name ".($x+1).": 
		CN=".$rec[$x]['cn'][0].",
		SN=".$rec[$x]['sn'][0].",
		GN=".$rec[$x]['givenname'][0].",
		Mail=".$rec[$x]['mail'][0].",
		Name=".$rec[$x]['name'][0]."</p>";
		}
		*/
		
		echo '<pre>';
		print_r($rec);
		echo '</pre>';
		
		echo "<p>[".date('H:i:s:u',time())."] Search OU:\t\t $ldapoustd</p>";
		$resultstd = ldap_search($ldapconn, $ldapoustd,"(cn=*)"); 
		$recstd = ldap_get_entries($ldapconn,$resultstd);
		$sce = ldap_count_entries($ldapconn,$resultstd);
		echo "<p>[".date('H:i:s:u',time())."] Count Entries: ".$sce."</p>";
		/*
		for($s=0;$s<$sce;$s++){
		echo "<p>[".date('H:i:s:u',time())."] Test name ".($s+1).": 
		CN=".$recstd[$s]['cn'][0].",
		SN=".$recstd[$s]['sn'][0].",
		GN=".$recstd[$s]['givenname'][0].",
		Mail=".$recstd[$s]['mail'][0].",
		Name=".$recstd[$s]['name'][0]."</p>";
		}
		*/
		/*
		//Search for user teacher01
		$searchname = (isset($_POST['user'])) ? $_POST['user'] : 'Teacher01';
		$resultsh = ldap_search($ldapconn, $ldapou,"(|(mail=$searchname)(givenname=$searchname))"); 
		$recsh = ldap_get_entries($ldapconn,$resultsh);
		echo '<hr><br>';
		echo '<form method="POST">GivenName or Email <input type="text" name="user" value="'.$searchname.'" placeholder="Given name" class="text-input"><input type="submit" value=" Search " class="button btPost"></form>';
		echo '<h3>Searching result for user "'.$searchname.'" on "OU=Teacher,OU=RMS"</h3>';
		echo "<p>[".date('H:i:s:u',time())."] Result: 
		CN=".$recsh[0]['cn'][0].",
		SN=".$recsh[0]['sn'][0].",
		GN=".$recsh[0]['givenname'][0].",
		Mail=".$recsh[0]['mail'][0].",
		Name=".$recsh[0]['cn'][0]."</p>";
		
		//Search for user Student
		$searchname2 = (isset($_POST['user'])) ? $_POST['user'] : 'Student01';
		$resultsh2 = ldap_search($ldapconn, $ldapoustd,"(|(mail=$searchname2)(givenname=$searchname2))"); 
		$recsh2 = ldap_get_entries($ldapconn,$resultsh2);
		echo '<hr>';
		echo '<h3>Searching result for user "'.$searchname2.'" on "OU=Students,OU=RMS"</h3>';
		echo "<p>[".date('H:i:s:u',time())."] Result: 
		CN=".$recsh2[0]['cn'][0].",
		SN=".$recsh2[0]['sn'][0].",
		GN=".$recsh2[0]['givenname'][0].",
		Mail=".$recsh2[0]['mail'][0].",
		Name=".$recsh2[0]['cn'][0]."</p>";
		
		//Search for user MMT
		$searchname3 = (isset($_POST['user'])) ? $_POST['user'] : 'Apinan';
		$resultsh3 = ldap_search($ldapconn, $ldapoummt,"(|(mail=$searchname3)(givenname=$searchname3))"); 
		$recsh3 = ldap_get_entries($ldapconn,$resultsh3);
		echo '<hr>';
		echo '<h3>Searching result for user "'.$searchname3.'" on "OU=MMT,OU=RMS"</h3>';
		echo "<p>[".date('H:i:s:u',time())."] Result: 
		CN=".$recsh3[0]['cn'][0].",
		SN=".$recsh3[0]['sn'][0].",
		GN=".$recsh3[0]['givenname'][0].",
		Mail=".$recsh3[0]['mail'][0].",
		Name=".$recsh3[0]['cn'][0]."</p>";
		echo '<pre>';
		print_r($recsh3);
		echo '</pre>';
		*/
	}else{
		echo "<p>[".date('H:i:s:u',time())."] LDAP bind:\t\t failed.</p>";
    }
ldap_unbind($ldapconn);
}

?>
</div>