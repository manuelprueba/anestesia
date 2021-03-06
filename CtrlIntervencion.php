<body>

<html>

<?php

  session_start();

  require ("MysqlDB.php");
  require ("Intervencion.php");
  require('SmartyIni.php');

  $smarty   = new SmartyIni;

  if (!$_SESSION['usuario_log']){
    header('location: http://localhost/iniciosesion.php');
  }
  $smarty->assign('usuario_log', $_SESSION['usuario_log']);

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miIntervencion  = new Intervencion;

  $miconexion->conectar("", "", "", "");

  $smarty->assign('titulo','Tipo de Intervenciones');

  if (!empty($_POST['accion']) ) {
    if ($_POST["accion"] == "actualiza" && !empty($_POST["id"]) ){

#Se muestran los datos asociados al id en tratamiento
      $Where = " id = " . $_POST["id"];
      $ConsultaId = $miIntervencion->consulta($miconexion->Conexion_ID, $Where);

      $row = mysql_fetch_assoc($ConsultaId);
      if ($row){
        $smarty->assign('id',          $row["id"]);
        $smarty->assign('sdescripcion',$row["sdescripcion"]);
        $smarty->assign('nmonto_ref',  $row["nmonto_ref"]);
      }
    }

#Se hace la inserci�n de los valores de la pantalla

    if ( $_POST["accion"] == "enviar" && empty($_POST["id"])){
      if (crear( $miconexion->Conexion_ID, $miIntervencion ) ){
        $smarty->assign('error_msg', 'La creaci�n de los datos se realiz� de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la creaci�n de los datos');
      }
    }
    elseif( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      if (actualiza( $miconexion->Conexion_ID, $miIntervencion ) ){
        $smarty->assign('error_msg', 'La acualizaci�n de los datos se realiz� de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la actualizaci�n de los datos');
      }
    }
    elseif( $_POST["accion"] == "elimina" && !empty($_POST["id"]) ){
      if (elimina( $miconexion->Conexion_ID, $miIntervencion ) ){
        $smarty->assign('error_msg', 'La Eliminaci�n del registro se realiz� de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de eliminar el registro');
      }
    }
    elseif( $_POST["accion"] == "buscar" ){
      buscar( $smarty, $miconexion->Conexion_ID, $miIntervencion );
    }
  }
  else{
    $smarty->assign('id',           "");
    $smarty->assign('sdescripcion', "");
    $smarty->assign('nmonto_ref', "");
  }

  $Where = " bactivo = 1 ";
  $ConsultaID = $miIntervencion->consulta($miconexion->Conexion_ID, $Where);

  verconsulta( $smarty, $ConsultaID );

  $smarty->display('Intervenciones.tpl');



/* Realiza la insercion de los datos indicados en la forma */
function crear( $Conexion_ID, $miIntervencion ){

  $datos = array( "sdescripcion" => $_POST["sdescripcion"],
                  "nmonto_ref"   => $_POST["nmonto_ref"] );
  
  $resultado = $miIntervencion->create( $Conexion_ID, $datos );

  return $resultado;
}

/* Realiza la actualizac�n de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miIntervencion ){

  $Where = " id = " . $_POST["id"];

  $datos = array( "sdescripcion" => $_POST["sdescripcion"],
                  "nmonto_ref"   => $_POST["nmonto_ref"] );

  
  $resultado = $miIntervencion->actualiza( $Conexion_ID, $Where, $datos );

  return $resultado;
}

/* Realiza la actualizac�n de los datos indicados en la forma */
function elimina( $Conexion_ID, $miIntervencion ){

  $Where = " id = " . $_POST["id"];

  $resultado = $miIntervencion->elimina( $Conexion_ID, $Where );

  return $resultado;
}

/* Muestra los datos de una consulta */

function verconsulta( $smarty, $ConsultaID ) {

// mostrarmos los registros

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']           = $row[0];
    $Datos['sdescripcion'] = $row[1];
    $Datos['nmonto_ref']   = $row[2];
    $Datos['clase']        = $clase;

    $Intervenciones[$row[0]] = $Datos;
  }

  $smarty->assign('ArrIntervenciones', $Intervenciones);

}

function buscar( $smarty, $Conexion_ID, $Intervencion ) {

  $Where =  array();
  if ($_POST["snombre"]){
    $Where[] = " LOWER(sdescripcion) like '%". strtolower($_POST["sdescripcion"])."%'";
  }
  if ($_POST["nmonto_ref"]){
    $Where[] = " nmonto_ref = ". $_POST["nmonto_ref"];
  }

  $ConsultaID = $Intervencion->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']           = $row[0];
    $Datos['sdescripcion'] = $row[1];
    $Datos['nmonto_ref']   = $row[2];
    $Datos['clase']        = $clase;

    $Intervenciones[$row[0]] = $Datos;
  }

  $smarty->assign('ArrIntervenciones', $Intervenciones);

}

?>

</body>

</html>

