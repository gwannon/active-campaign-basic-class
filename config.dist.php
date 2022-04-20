<?php

date_default_timezone_set('Europe/Madrid');

include(dirname(__FILE__)."/classes/user.php");
include(dirname(__FILE__)."/classes/account.php");
include(dirname(__FILE__)."/classes/curl.php");
include(dirname(__FILE__)."/classes/functions.php");

date_default_timezone_set('Europe/Madrid');

//Config
define('AC_API_DOMAIN', 'xxxxxxxxxxxx'); //URL de la API de Active Campaign
define('AC_API_TOKEN', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); //Token de la API de Active Campaign

$serverName = "0.0.0.0"; //serverName\instanceName
$connectionInfo = array(
	"Database"=>"DBNAME",
	"UID"=>"user",
	"PWD"=>"password"
);

//Usuarios
$userFields = [
  "tratamiento" => 40,
  "dni"       => 42,
  "provincia" => 7,
];

$tags = getAllTags(true);

$lists = getAllLists(true);

//Cuentas
$accountFields = [
  "ciudad" => 4,
  "pais" => 7,
  "telefono" => 11,
];
