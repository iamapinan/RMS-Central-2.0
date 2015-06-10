<?php
ob_start();
ini_set('display_errors', '0');
error_reporting(E_ALL);
session_start();

//Config
$config = parse_ini_file('./config.ini.php');
//Autoload
include 'autoload.php';

$http = new libs\http_request;
$course = new libs\CourseProc($http->courseId());
$db = new libs\Storage($course->sqlite);

