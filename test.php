<?php

include("config.php");


/*$fc = fopen("./Todos.csv", "r");
$ciudades = array();
while (($email = fgetcsv($fc, 0, ',')) !== FALSE) {
	$acemails[] = $email[0];
}
fclose($fc);

echo count($acemails)."Emails desde ACTIVE CAMPAIGN\n";*/


$conn = sqlsrv_connect( $serverName, $connectionInfo);

/*$sql = "SELECT DISTINCT ". 
"Email ".
"FROM gEntidadesContactos ".
"WHERE (PromocionIHOBE = 'S') AND (FechaBaja IS NULL) AND (Email IS NOT NULL) ".
"ORDER BY Email ASC ".
"OFFSET 0 ROWS ".
//"FETCH NEXT 1 ROWS ONLY".
  "";
  $count = 0;
  $users = sqlsrv_query($conn, $sql); 
  if(sqlsrv_has_rows($users)) {  
    while( $row = sqlsrv_fetch_array( $users, SQLSRV_FETCH_ASSOC)) {
      $count++;
      if(!in_array($row['Email'], $acemails)) echo $row['Email']."\n";
    }
  }

echo $count." Emails desde DEMETER\n"; die;*/
/*echo "---------------------------\n";
  $listado_provincias = getProvincias();
  $listado_municipios = getMunicipios();
$sql = "SELECT * ". 
    //"IdEntidad, IdEntidadPadre, IdTipoEntidad, IdSubEntidad, RazonSocial, Telefono1, Web, NombreComercial, CPPostal, IdProvincia, IdMunicipio, LogFecha, NombreSede ".
    //"IdEntidad, NombreSede, RazonSocial, NombreComercial, IdEntidadPadre, IdTipoEntidad ".
    "FROM gEntidades ".
    //"WHERE (RazonSocial =  'Fundación Grupo GSI')".
    "WHERE (IdEntidad = '15623') ".
    //"ORDER BY IdEntidad ASC, IdEntidadContacto ASC ".
    //"OFFSET 0 ROWS ".
    //"FETCH NEXT 200 ROWS ONLY".
    "";
    $users = sqlsrv_query($conn, $sql); 
    if(sqlsrv_has_rows($users)) {  
      $count = sqlsrv_num_rows($users);
  
      while( $row = sqlsrv_fetch_array( $users, SQLSRV_FETCH_ASSOC)) {
        print_r ($row);
        echo getTipoEntidadById($row['IdTipoEntidad'])."\n";
        echo getSubEntidadById($row['IdTipoEntidad'], $row['IdSubEntidad'])."\n";
        echo $listado_provincias[$row['IdProvincia']]."\n";
        echo $listado_municipios[$row['IdMunicipio']]."\n";
      }
  
    }*/



        
/*echo "---------------------------\n";
$sql = "SELECT ". 
    "* ".
    //"IdEntidad, IdEntidadContacto, Nombre, Apellido1, Apellido2, Email, Cargo, PromocionIHOBE, FechaBaja, LogFecha ".
    "FROM gEntidadesContactos ".
    //"WHERE (IdEntidad = '34722') ".
    //"WHERE (PromocionIHOBE = 'S') AND (FechaBaja IS NULL) AND (Email = 'e.alabort@gmail.com') ".
    "WHERE (PromocionIHOBE = 'S') AND (Email = 'ibeobide@transformaconsultoria.es') ".
    "ORDER BY LogFecha DESC ".
    //"OFFSET 0 ROWS ".
    //"FETCH NEXT 1 ROWS ONLY".
    "";
    echo $sql;
    $users = sqlsrv_query($conn, $sql); 
    if(sqlsrv_has_rows($users)) {  
      while( $row = sqlsrv_fetch_array( $users, SQLSRV_FETCH_ASSOC)) {
        print_r ($row);
      }
    }*/

/*$sql = "SELECT ". 
          "* ".
          "FROM gEntidades ";
$users = sqlsrv_query($conn, $sql); 
if(sqlsrv_has_rows($users)) {  
  while( $row = sqlsrv_fetch_array( $users, SQLSRV_FETCH_ASSOC)) {
    print_r ($row);
  }

}*/

/*
26
20-36 --> Marian Tapia Saiz --> marian@zorrotz.com --> 943730500 --> Dirección --> 
2018/11/20---------
20 --> Zorrotz Legazpi, S.L.L./ --> 20230 --> 943730500 --> Legazpi (GIPUZKOA) --> Industrial (Empresa) --> 2018/11/20
Ya están asociado 'Marian Tapia Saiz' con 'Zorrotz Legazpi, S.L.L.'

--------------------------------------------------------------------------------------------
27
6992-2 --> Ana Luengo --> ana@zorrotz.com -->  --> Responsable de calidad y medio ambiente --> 
---------
6992 --> Placas y Moldes, S.A./ --> 20230 --> 943730950 --> Legazpi (GIPUZKOA) --> Industrial (Empresa) --> 
Ya están asociado 'Ana Luengo' con 'Placas y Moldes, S.A.'
*/

/*$sql = "SELECT ". 
    "* ".
    "FROM gEntidadesDenominaciones WHERE (IdEntidadDenominacion = 2) ".
    //"WHERE (PromocionIHOBE = 'S') AND (FechaBaja IS NULL) AND (Email IS NOT NULL) ".
    //"ORDER BY IdEntidad ASC, IdEntidadContacto ASC ".
    //"OFFSET 0 ROWS ".
    //"FETCH NEXT 200 ROWS ONLY".
    "";




  $users = sqlsrv_query($conn, $sql); 
  if(sqlsrv_has_rows($users)) {  
    $count = sqlsrv_num_rows($users);

    while( $row = sqlsrv_fetch_array( $users, SQLSRV_FETCH_ASSOC)) {
      print_r ($row);
    }

  }*/

/*$response = getUsersByStatus(3);
print_r ($response);*/


//Functions --------------------------------
//print_r(getAllTags());
//print_r(getAllContactFields());
//print_r(getAllAccountFields());
//print_r(getAllLists());

//Users ----------------------------------------------------------------------------------------------

//print_r (curlCallGet("/contacts/1/fieldValues")->fieldValues);
//$user = new UserAC(1); //1 jorge@enutt.net
//print_r($user);

//print_r (curlCallGet("/accounts/1/accountCustomFieldData"));
//$account = new AccountAC("Eñutt Comunicación, S.L."); //1 Enutt
//print_r($account);

//Accounts/Users
/*if(!$user->hasAccount(3562)) { echo "set 3562\n"; $user->setAccount(3562); }
else { echo "delete 3562\n"; $user->deleteAccount(3562); }*/

//Tags
/* if(!$user->hasTag(98)) { echo "set 98\n"; $user->setTag(98); }
else { echo "delete 98\n"; $user->deleteTag(98); }
if($user->hasTag(101)) { echo "delete 101\n"; $user->deleteTag(101);}
else  { echo "set 101\n"; $user->setTag(101);} */

//Fields
/*$user->setField("dni", "2222222d");
$user->setField("provincia", "araba");
$user->setField("tratamiento", "HE/HIM");
if($user->updateProfileAC()) echo "Usuario actualizado\n";
else echo "Fallo la actualización\n";*/

//Lists
/*if(!$user->hasList(4)) { echo "set 4\n"; $user->setList(4, 1); }
else { echo "delete 4\n"; $user->setList(4, 2); } */

//Automations
/*if($user->executeAutomation(103)) echo "Automatización lanzada\n";
else echo "Automatización ha fallado\n";*/
//print_r($user);

/*$user = new UserAC(61496); //61496 jorge@enutt.net
print_r($user);*/

/* print_r($account);*/

//Account Fields
/*$account->setUrl("https://google.es");
$account->setField("ciudad", "Toulouse");
$account->setField("pais", "Francia");
$account->setField("telefono", rand(600000000, 699999999));
if($account->updateAccountAC()) echo "Cuenta actualizada\n";
else echo "Fallo la actualización\n";*/

/*$account = new AccountAC("FAGOR ARRASATE USA, INC");
print_r($account);*/

//CreateAccount
/*$account = createAccountAC("Borrar cuenta");
print_r($account);*/ 


//existsContact("monasdasdklwqleqlwekqw@gmail.com");
//existsContact("blagartos@georka.es");

$user = new UserAC("jorge@enutt.net");
$user->deleteAllAccounts();
$user->setAccount(17857); //Eñutt
//$user->setAccount(11312); //Ciudadanos