<?php
$response = array();
if(file_exists(dirname(__FILE__)."/logs/errors.txt")){
  $response['errors'] = "rotated";
  rename(dirname(__FILE__)."/logs/errors.txt", dirname(__FILE__)."/logs/errors-".date("Y-m-d_H-i-s").".txt");
} else {
  $response['errors'] = "no_exists";
}
if(file_exists(dirname(__FILE__)."/logs/log.txt")){
  $response['log'] = "rotated";
  rename(dirname(__FILE__)."/logs/log.txt", dirname(__FILE__)."/logs/log-".date("Y-m-d_H-i-s").".txt");
} else {
  $response['log'] = "no_exists";
}
if(file_exists(dirname(__FILE__)."/logs/process.txt")){
  $response['process'] = "rotated";
  rename(dirname(__FILE__)."/logs/process.txt", dirname(__FILE__)."/logs/process-".date("Y-m-d_H-i-s").".txt");
} else {
  $response['process'] = "no_exists";
}
echo json_encode($response);
