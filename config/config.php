<?php

// Database connection

/*define('DB_HOST', 'localhost');
define('DB_USER', 'oc_p5blog_usr');
define('DB_PASS', '*O15c3p16');
define('DB_NAME', 'oc_p5blog');
*/
// Contact form email 
//define('DOCUMENT_ROOT', dirname(dirname(__FILE__)));
//require_once(__ROOT__.'/config.php');
define('CF_EMAIL', 'contact@capdeco.com');
define('BLOG_AUTHOR', 'A F');

$servername = $_SERVER["SERVER_NAME"];

if (stristr($servername , "ocblog.capdeco.com")) {
	//echo $_SERVER["SERVER_NAME"].'ere';
	//exit;
define('DB_HOST', 'localhost');
define('DB_NAME', 'ocp5blog');
define('DB_USER', 'oc_p5_blog_usr');
define('DB_PASSWORD', 'O15c3p16');

	$URL = "https://ocblog.capdeco.com/";
  /* PREPROD config  
  $GLB_SYSTYPE="unix";
  $s_DbHost = "localhost";
  $s_DbName = "oc_p5blog";
  $s_DbUser = "oc_p5blog_usr";
  $s_DbPass = "O15c3p16";*/
} else {
	define('DB_HOST', 'localhost');
define('DB_NAME', 'test');
define('DB_USER', 'dbuser');
define('DB_PASSWORD', '');

	$URL = "http://localhost/TP-OC-P5-BLOG/";

  /* DEV config home */
  $GLB_SYSTYPE="win";
  $s_DbHost = "localhost";
  $s_DbName = "test";
  
  $s_DbUser = "root";
  $s_DbPass = "root";
}

/*
$root = $_SERVER['DOCUMENT_ROOT'];
$host = $_SERVER['HTTP_HOST'];

define('ROOT', $root.'/Blog-mvc/app/');
define('HOST', 'http://'.$host.'/Blog-mvc/');

define('CONTROLLER', ROOT.'controller/');
define('MODEL', ROOT.'model/');
define('VIEW', ROOT.'view/');
define('PUBLICS', HOST.'public/');
*/