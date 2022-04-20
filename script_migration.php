<?php

/* 
 * TODO:
 * OK - Los ciudadanos tendrán una única cuenta (11312)
 * OK - Los ayuntamientos son los únicos que tienen nombre en euskera
 * OK - Si detectamos subsedes debemos usar la sede principal excepto en el caso de id principal 24000 (gobierno vasco) que nos quedaremos con las subsedes
 */

include_once(dirname(__FILE__)."/config.php");

ini_set("display_errors", 1);

header('Content-type: text/plain; charset=utf-8');

$accounts_migrados = array();

if(!canMigrate()) { showNow(date("Y-m-d H:i:s")." - Migracíon parada ----------------------- \n"); 
} else {
  showNow(date("Y-m-d H:i:s")." - Empezamos migración de bloque ----------------------- \n");

  $conn = sqlsrv_connect( $serverName, $connectionInfo);

  //Conseguimos el listado de provincias y el de municipios
  $listado_provincias = getProvincias();
  $listado_municipios = getMunicipios();

  $sql = "SELECT DISTINCT ". 
    "Email, IdEntidad, IdEntidadContacto ".
    "FROM gEntidadesContactos ".
    "WHERE (PromocionIHOBE = 'S') AND (FechaBaja IS NULL) AND (Email IS NOT NULL) ".
    //" AND (IdEntidad = '34722') ".
    "ORDER BY IdEntidad ASC, IdEntidadContacto ASC ".
    "OFFSET 0 ROWS ".
    //"FETCH NEXT 20 ROWS ONLY".
    "";

  $users = sqlsrv_query($conn, $sql); 
  
  /*if(  $users === false ) {
    if( ($errors = sqlsrv_errors() ) != null) {
      foreach( $errors as $error ) {
        echo "SQLSTATE: ".$error[ 'SQLSTATE']."\n";
        echo "code: ".$error[ 'code']."\n";
        echo "message: ".$error[ 'message']."\n";
      }
    }
  } */

  $limit = 0;
  if(sqlsrv_has_rows($users)) {  
    while( $mainrow = sqlsrv_fetch_array( $users, SQLSRV_FETCH_ASSOC)) {  

      //$mainrow['Email'] = mb_strtolower(str_replace(" ", "", $mainrow['Email']));

      if (filter_var($mainrow['Email'], FILTER_VALIDATE_EMAIL)) {
        
        $sql = "SELECT ". 
          "IdEntidad, IdEntidadContacto, Nombre, Apellido1, Apellido2, Telefono1, Email, Cargo, LogFecha ". 
          "FROM gEntidadesContactos ".
          "WHERE (PromocionIHOBE = 'S') AND (FechaBaja IS NULL) AND (Email = '".$mainrow['Email']."') ".
          "ORDER BY LogFecha DESC ".
          "OFFSET 0 ROWS ".
          "FETCH NEXT 1 ROWS ONLY".
          "";
        
        //showNow($sql."\n");
        $cusers = sqlsrv_query($conn, $sql); 
        if(sqlsrv_has_rows($cusers)) { 
          $count = sqlsrv_num_rows($users); 
          if($count > 1) echo $mainrow['Email']."REPETIDO \n"; 
          while( $row = sqlsrv_fetch_array( $cusers, SQLSRV_FETCH_ASSOC)) {  
            showNow($limit." - ".date("Y-m-d H:i:s")."\n"); 
            $limit++;
            $id_migracion = chop($row['IdEntidad'])."-".chop($row['IdEntidadContacto']);
            $nombre = chop($row['Nombre']);
            $apellidos = chop($row['Apellido1']).($row['Apellido2'] != '' ? " ".chop($row['Apellido2']) : "");
            $email = $row['Email'];
            $telefono = chop($row['Telefono1']);
            $cargo = chop($row['Cargo']);
            $logFecha = json_decode(json_encode($row['LogFecha']), true);
            if(isset($logFecha['date'])) $logfecha = date("Y-m-d", strtotime($logFecha['date']));
            else $logfecha = "";
            showNow("\e[0;31;42m".$id_migracion ." > ". $nombre." ".$apellidos ." > ". $email ." > ".$telefono." > ".$cargo." > ".$logfecha."\e[0m\n"); 

            //Buscamos el usuario y le damos valores
            $user = new UserAC($email);
            $user->setNombre($nombre);
            $user->setApellidos($apellidos);
            $user->setTelefono($telefono);
            $user->setField("id-migracion", $id_migracion);
            $user->setField("log-fecha", $logfecha);
            $user->setField("migracion-ver", "3");
            $user->updateProfileAC();

            if(!$user->hasList(1)) { $user->setList(1, 1); } //Metemeos en la lista 1
            if(!$user->hasTag(58)) { $user->setTag(58); } //Damos la etiqueta via-demeter


            /*
              idioma-es            1
              idioma-eu            2
              idioma-en             59
              boletin-us2030       56
              boletin-ihobe        57

            */

            $usertags = [
              1 => false, //idioma-es
              2 => false, //idioma-eu
              59 => false, //idioma-en
              56 => false, //boletin-us2030
              57 => false, //boletin-ihobe
            ];


            //Boletines IHOBE + Idioma
            $sql = "SELECT ". 
              "IdIdioma, Boletin ".
              "FROM gEntidadesContactosWebs ".
              "WHERE (IdWeb = '6426daa2-983b-4927-b254-9ea4a6e41196') AND (IdEntidad = '".$row['IdEntidad']."') AND (IdEntidadContacto = '".$row['IdEntidadContacto']."') ".
              "";

            $boletines = sqlsrv_query($conn, $sql); 
            if(sqlsrv_has_rows($boletines)) { 
              while( $boletin = sqlsrv_fetch_array($boletines, SQLSRV_FETCH_ASSOC)) { 
                //showNow("Boletín IHOBE\n");
                //print_r ($boletin);
                if ($boletin['IdIdioma'] == 'E' || $boletin['IdIdioma'] == 'e') {
                  $usertags[2] = true;
                } else if ($boletin['IdIdioma'] == 'C' || $boletin['IdIdioma'] == 'c') {
                  $usertags[1] = true;
                } else if ($boletin['IdIdioma'] == 'I' || $boletin['IdIdioma'] == 'i') {
                  $usertags[59] = true;
                }
                
                if ($boletin['Boletin'] == '1') {
                  $usertags[57] = true;
                } 
              }
            }

            //Boletines IHOBE + UDALSAREA30
            $sql = "SELECT ". 
            "IdIdioma, Boletin ".
              "FROM gEntidadesContactosWebs ".
              "WHERE (IdWeb = '94f4643b-ffbf-4f29-8401-949a36835664') AND (IdEntidad = '".$row['IdEntidad']."') AND (IdEntidadContacto = '".$row['IdEntidadContacto']."') ".
              "";

            $boletines = sqlsrv_query($conn, $sql); 
            if(sqlsrv_has_rows($boletines)) { 
              while( $boletin = sqlsrv_fetch_array($boletines, SQLSRV_FETCH_ASSOC)) { 
                //showNow("Boletín UDALSAREA30\n");
                //print_r ($boletin);
                if ($boletin['IdIdioma'] == 'E' || $boletin['IdIdioma'] == 'e') {
                  $usertags[2] = true;
                } else if ($boletin['IdIdioma'] == 'C' || $boletin['IdIdioma'] == 'c') {
                  $usertags[1] = true;
                } else if ($boletin['IdIdioma'] == 'I' || $boletin['IdIdioma'] == 'i') {
                  $usertags[59] = true;
                }
                
                if ($boletin['Boletin'] == '1') {
                  $usertags[56] = true;
                } 
              }
            }

            //Preparamos los tags de idioma y boletín
            //print_r ($usertags);
            $string_tags = array();
            foreach ($usertags as $tagid => $status) {
              if ($status) {
                $string_tags[] = $tagid;
                if(!$user->hasTag($tagid)) {
                  $user->setTag($tagid);
                }
              } else {
                if($user->hasTag($tagid)) $user->deleteTag($tagid);
              }
            }

            if (count($string_tags) > 0) echo "Tags: ".implode(", ", $string_tags)."\n";


            //Buscamos la entidad y le damos valores
            $sql = "SELECT ". 
              "IdEntidad, IdEntidadPadre, IdTipoEntidad, IdSubEntidad, RazonSocial, Telefono1, Web, NombreComercial, CPPostal, IdProvincia, IdMunicipio, LogFecha, NombreSede ".
              "FROM gEntidades ".
              "WHERE (IdEntidad = '".$row['IdEntidad']."') ".
              "";
            $companies = sqlsrv_query($conn, $sql); 
            if(sqlsrv_has_rows($companies)) { 
              while( $companyrow = sqlsrv_fetch_array($companies, SQLSRV_FETCH_ASSOC)) {

                if ($companyrow['IdEntidadPadre'] != '0' && $companyrow['IdEntidadPadre'] != '24000') { //Si la entidad es una sede (IdEntidadPadre] != 0) y metemos los datos la entidad padre, excepto si la entidad padre es el gobierno Vasco(id=24000)
                  $sql = "SELECT ". 
                    "IdEntidad, IdEntidadPadre, IdTipoEntidad, IdSubEntidad, RazonSocial, Telefono1, Web, NombreComercial, CPPostal, IdProvincia, IdMunicipio, LogFecha, NombreSede ".
                    "FROM gEntidades ".
                    "WHERE (IdEntidad = '".$companyrow['IdEntidadPadre']."') ".
                    "";
                  $companiesPadre = sqlsrv_query($conn, $sql);
                  $companyrowPadre = sqlsrv_fetch_array($companiesPadre, SQLSRV_FETCH_ASSOC);
                  showNow("'".chop($companyrow['RazonSocial'])."' es una sede de '".chop($companyrowPadre['RazonSocial'])."' \n");
                  $companyrow = $companyrowPadre;
                }

                $id_migracion = chop($companyrow['IdEntidad']);
                if($companyrow['IdEntidadPadre'] == '24000') {
                  $nombre = chop($companyrow['RazonSocial']. " - ".$companyrow['NombreSede']); 
                } else {
                  $nombre = chop($companyrow['RazonSocial']);
                }
                if($companyrow['IdTipoEntidad'] == 2 && $companyrow['IdSubEntidad'] == 1) { //Es un ayuntamiento y se traduce su nombre usando el campo 'nombreComercial',  si no es ayutamiento simplemente se copia el de castellano
                  $nombre_eus = chop(getDenominacionEntidadById($companyrow['IdEntidad'])); 
                } else {
                  $nombre_eus = $nombre;
                }
                $codigo_postal = chop($companyrow['CPPostal']); 
                $telefono = chop($companyrow['Telefono1']);  
                $url = chop($companyrow['Web']); 
                $entidad = getTipoEntidadById($companyrow['IdTipoEntidad']);
                $subentidad = getSubEntidadById($companyrow['IdTipoEntidad'], $companyrow['IdSubEntidad']);
                $provincia = $listado_provincias[$companyrow['IdProvincia']];
                $municipio = $listado_municipios[$companyrow['IdMunicipio']];
                $logFecha = json_decode(json_encode($companyrow['LogFecha']), true);
                if(isset($logFecha['date'])) $logfecha = date("Y/m/d", strtotime($logFecha['date']));
                else $logfecha = "";
  
                if($companyrow['IdTipoEntidad'] == 5) { //Es un ciudadano y por tanto le asignamos la cuenta de ciudadanos (11312)
                  showNow("'".$user->nombre." ".$apellidos ."' es un ciudadano y lo asociamos a la cuenta genérica\n");
                  $user->setAccount(11312, $cargo);

                } else {
                  if ($nombre != '') {
                    $account = new AccountAC($nombre);
                    if(!in_array($row['IdEntidad'], $accounts_migrados)) {
                      $account->setUrl($url);
                      $account->setField("telefono", $telefono);
                      $account->setField("nombre-eus", $nombre_eus);
                      $account->setField("codigo-postal", $codigo_postal);
                      $account->setField("ciudad", $municipio);
                      $account->setField("estado-provincia", $provincia);
                      $account->setField("ciudad", $municipio);
                      $account->setField("id-migracion_number", $id_migracion);
                      $account->setField("entidad", $entidad);
                      $account->setField("subentidad", $subentidad);
                      $account->setField("log-fecha_date", $logfecha);
                      $account->updateAccountAC();
                      if(!in_array($id_migracion, $accounts_migrados)) $accounts_migrados[] = $id_migracion;
                      showNow("\e[0;31;44m".$id_migracion ." > ". $nombre."/".$nombre_eus ." > ". $codigo_postal ." > ". $telefono ." > ".$municipio." (".$provincia.") > ".$subentidad." (".$entidad.") > ".$logfecha."\e[0m\n"); 
                    } else showNow("No actualizamos '".$account->nombre."'\n");
                    //Asociamos al usuario con la entidad
                    if(!$user->hasAccount($account->id)) {
                      showNow("Asociamos '".$user->nombre." ".$user->apellidos ."' con '".$account->nombre."'\n");
                      $user->setAccount($account->id, $cargo);
                    } else showNow("Ya asociado '".$user->nombre." ".$user->apellidos ."' con '".$account->nombre."'\n");
                  }
                }
              }
            }
          }
          showNow("\e[0;32;40m---\e[0m\n");
        }
      } else showNow($mainrow['Email']." EMAIL ERRONEO\n--------------------------------------------------------------------------------------------\n");
    }  
  }




  showNow(date("Y-m-d H:i:s")." - Terminamos migración de bloque ----------------------- \n");
}
showNow("\n--------------------------------------------------------------------------------------------\n--------------------------------------------------------------------------------------------\n\n");
