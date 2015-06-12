<?php
if(isset($_GET['param'])) $param = '&user='.$_GET['param'];
if(isset($_SESSION['loginid']['nickname']))
   header('location: /profile/'.$_SESSION['loginid']['nickname'].$param);
else
   header('location: /login');
?>