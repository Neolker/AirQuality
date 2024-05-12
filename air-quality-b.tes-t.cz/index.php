<?php
if(isset($_SERVER['HTTP_ORIGIN'])){ // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one you want to allow, and if so:  	
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");    
	}
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Max-Age: 86400'); // cache for 1 day
if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){  // may also be using PUT, PATCH, HEAD etc
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	}
if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
  header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  } 
//curl -si -H "Origin: 111" -H "Access-Control-Request-Headers: 222" -H "Access-Control-Request-Method: 333" https://www.air-quality-b.tes-t.cz  
require_once('engine/jadro.php');
$jadro=new Jadro();
$jadro->spustModulDleURL();
