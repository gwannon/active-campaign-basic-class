<?php

ini_set("display_errors", 1);
header('Content-type: application/json; charset=utf-8');
include_once("./config.php");
$items = array();


if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'check-status') {
  if(curlCall("/lists")) $items['api'] = true;
  else $items['api'] = false;
  $items['migration'] = canMigrate();
  $conn = sqlsrv_connect( $serverName, $connectionInfo);
  if( $conn ) {
  
  	$server_info = sqlsrv_server_info( $conn);
		if( $server_info ) {
			$items['mssql'] = true;
		} else {
			$items['mssql'] = false;
		}
  } else $items['mssql'] = false;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'change-status-migration') {
  if(canMigrate()) { 
    $write = "0";
    $items['migration'] = false;
  } else {
     $write = "1";
     $items['migration'] = true;
  }
  $f = fopen("./migration.txt", "w+");
  fwrite($f, $write);
  fclose($f);
  sleep(1);
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'info-tags') {
  //$tags = array(58,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,1,2,56,57);
  $zonahoraria = (60*60*7);
  $items[] = [
    "id" => "ID",
    "tag" => "ETIQUETA",
    "count" => "CONTACTOS",
    "date" => "FECHA",
  ];
  foreach ($tags as $tag_id) {
    $tag = curlCall("/tags/".$tag_id)->tag;
    $items[] = [
      "id" => $tag->id,
      "tag" => $tag->tag,
      "count" => $tag->subscriber_count,
      "date" => date("Y-m-d H:i:s", (strtotime($tag->updated_timestamp) + $zonahoraria)),
    ];
  } 
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'info-logs') {
  if(file_exists("./logs/log.txt")) {
    $logs = explode("\n", file_get_contents("./logs/log.txt"));
    foreach ($logs as $log) {
      $log = explode("|", $log);
      if($log[0] != '') {
        $items[] = [
          "date" => $log[0],
          "apicall" => $log[1],
          "method" => $log[2],
          "payload" => ($log[3] ? $log[3] : "")
        ];
      }
    }
  }
  $items[] = [
    "date" => "Fecha",
    "apicall" => "Llamada API",
    "method" => "Método",
    "payload" => "Payload"
  ];
  $items = array_reverse($items);
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'info-errors') {

  if(file_exists("./logs/errors.txt")) {
    $logs = explode("\n", file_get_contents("./logs/errors.txt"));
    foreach ($logs as $log) {
      $log = explode("|", $log);
      if($log[0] != '') {
        $items[] = [
          "date" => $log[0],
          "apicall" => $log[1],
          "method" => $log[2],
          "payload" => ($log[3] ? $log[3] : ""),
          "httpcode" => $log[4],
          "response" => $log[5],
        ];
      }
    }
  }
  $items[] = [
    "date" => "Fecha",
    "apicall" => "Llamada API",
    "method" => "Método",
    "payload" => "Payload",
    "httpcode" => "Código HTTP",
    "response" => "Respuesta"
  ];
  $items = array_reverse($items);
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'info-general') { 
  $items[] = [
    "field" => "Dato",
    "value" => "Valor"
  ];

  $items[] = [
    "field" => "Contactos activos",
    "value" => curlCall("/contacts?status=1&limit=1")->meta->total,
  ];

  $items[] = [
    "field" => "Contactos desuscritos",
    "value" => curlCall("/contacts?status=2&limit=1")->meta->total,
  ];

  $items[] = [
    "field" => "Contactos rebotados",
    "value" => curlCall("/contacts?status=3&limit=1")->meta->total,
  ];

  $items[] = [
    "field" => "Cuentas",
    "value" => curlCall("/accounts?limit=10")->meta->total,
  ];
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'info-crons') {
  $items[] = [
    "line" => "Cronjobs"
  ];
  foreach (explode("\n", shell_exec('cat ./crons.txt')) as $line) {
    if($line != '') {
      $items[] = [
        "line" => $line
      ];
    }
  }
} else die;

echo json_encode($items);
