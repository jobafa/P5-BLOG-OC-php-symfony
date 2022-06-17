<?php

$server = new \Inc\Server();


// CONTACT EMAIL 
define('CF_EMAIL', 'contact@capdeco.com');
define('BLOG_AUTHOR', 'A F');


// Database connection
$servername = $server->get_SERVER('SERVER_NAME');

if (stristr($servername , "ocblog.capdeco.com")) {
	
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'poo_ocp5');
  define('DB_USER', 'poo_ocp5_usr');
  define('DB_PASSWORD', '*O15p16');

	$URL = "https://ocblog.capdeco.com/poo/";
 
} else {/* DEV config home */
	define('DB_HOST', 'localhost');
  define('DB_NAME', 'test');
  define('DB_USER', 'dbuser');
  define('DB_PASSWORD', '');

	$URL = "http://localhost/POO-TP-OC-P5-BLOG/";
}