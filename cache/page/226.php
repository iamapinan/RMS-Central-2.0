<?php
$sql = mysql_query('select * from am_trial15d limit 0,50');
echo '<table>';
echo '<th><tr><td>ชื่อ นามสกุล</td><td>บริษัท</td><td>Username</td><td>Password</td><td>อีเมล์</td><td>เบอร์โทรศัพท์</td><td>วันหมดอายุ</td></tr></th>';
while($result = mysql_fetch_array($sql))
{
	echo '<tr><td>'.$result['fn'].' '.$result['ln'].'</td><td>'.$result['org'].'</td><td>'.$result['user'].'</td><td>'.$result['pwd'].'</td><td>'.$result['email'].'</td><td>'.$result['tel'].'</td><td>'.date('d/m/y',$result['ExpireDate']).'</td></tr>';
}
echo '</table>';
?>