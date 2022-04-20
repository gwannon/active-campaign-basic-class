<?php

function getAllTags ($single_ids = false, $max = 100) {
  $tags = array();
  foreach (curlCallGet("/tags?limit={$max}")->tags as $tag) {
    if($single_ids) $tags[] = $tag->id;
    else $tags[$tag->id] = $tag->tag;
  }
  return $tags;
}

function getAllContactFields ($max = 100) {
  $fields = array();
  foreach (curlCallGet("/fields?limit={$max}")->fields as $field) {
    $fields[$field->id] = $field->title;
  }
  return $fields;
}

function getAllAccountFields ($max = 100) {
  $fields = array();
  foreach (curlCallGet("/accountCustomFieldMeta?limit={$max}")->accountCustomFieldMeta as $field) {
    $fields[$field->id] = $field->fieldLabel;
  }
  return $fields;
}

function getAllLists ($single_ids = false, $max = 100) {
  $lists = array();
  foreach (curlCallGet("/lists?limit={$max}")->lists as $list) {
    if($single_ids) $lists[] = $list->id;
    else $lists[$list->id] = $list->name;
  }
  return $lists;
}

/*
  User Status:
    -1	Any
    0	Unconfirmed
    1	Active
    2	Unsubscribed
    3	Bounced
*/

function getUsersByStatus($status) {
  $response = curlCallGet("/contacts?status=".$status)->contacts;
  return $response;
}

function canMigrate() {
  if(file_get_contents("/var/www/html/migration.txt") == "1") return true;
  return false;
}

function showNow($string) {
  echo $string;
  $f = fopen("/var/www/html/logs/process.txt", "a+");
  fwrite($f, utf8_decode($string));
  fclose($f);
}



function getTipoEntidadById($entidadId) {
  global $conn;
  $entidades = sqlsrv_query($conn, "SELECT * FROM gTiposEntidades WHERE (IdTipoEntidad = '".$entidadId."')");
  if(sqlsrv_has_rows($entidades)) {  
    while( $entidadrow = sqlsrv_fetch_array($entidades, SQLSRV_FETCH_ASSOC)) {
      return $entidadrow['TipoEntidad'];
    }
  }
  return false;
}

function getSubEntidadById($entidadId, $subEntidadId) {
  global $conn;
  $entidades = sqlsrv_query($conn, "SELECT * FROM gSubentidades WHERE (IdTipoEntidad = '".$entidadId."') AND  (IdSubEntidad = '".$subEntidadId."')");
  if(sqlsrv_has_rows($entidades)) {  
    while( $entidadrow = sqlsrv_fetch_array($entidades, SQLSRV_FETCH_ASSOC)) {
      return $entidadrow['SubEntidad'];
    }
  }
  return false;
}


function getDenominacionEntidadById($entidadId) {
  global $conn;
  $sql = "SELECT ". 
    "* ".
    "FROM gEntidades ".
    "WHERE (IdEntidad = ".$entidadId.") ".
    //"OFFSET 0 ROWS ".
    //"FETCH NEXT 200 ROWS ONLY".
    "";

  $entidades = sqlsrv_query($conn, $sql); 
  if(sqlsrv_has_rows($entidades)) {  
     while( $row = sqlsrv_fetch_array($entidades, SQLSRV_FETCH_ASSOC)) {
      return $row['NombreComercial'];
    }
  }
  return "";
}

function getProvincias() {  //Conseguimos el listado de provincias
  global $conn;
  $listado_provincias = array();
  $provincias = sqlsrv_query($conn, "SELECT * FROM gProvincias");
  if(sqlsrv_has_rows($provincias)) {  
    while( $provinciarow = sqlsrv_fetch_array($provincias, SQLSRV_FETCH_ASSOC)) {
      $listado_provincias[$provinciarow['IdProvincia']] = $provinciarow['Provincia'];
    }
  }
  return $listado_provincias;
}

function getMunicipios() {  //Conseguimos el listado de municipios
  global $conn;
  $listado_municipios = array();
  $municipios = sqlsrv_query($conn, "SELECT * FROM gMunicipios");
  if(sqlsrv_has_rows($municipios)) {  
    while( $municipiorow = sqlsrv_fetch_array($municipios, SQLSRV_FETCH_ASSOC)) {
      $listado_municipios[$municipiorow['IdMunicipio']] = $municipiorow['Municipio'];
    }
  }
  return $listado_municipios;
}