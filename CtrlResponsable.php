<body>

<html>

<?php

  session_start();

  require ("MysqlDB.php");
  require ("Responsable.php");
  require('SmartyIni.php');

  $smarty        = new SmartyIni;
  $miResponsable = new Responsable;

  if (!$_SESSION['usuario_log']){
    header('location: http://localhost/iniciosesion.php');
  }
  $smarty->assign('usuario_log', $_SESSION['usuario_log']);

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miconexion->conectar("", "", "", "");

  $smarty->assign('titulo','Responsables de las Intervenciones');

  if (!empty($_POST['accion']) ) {
    if ($_POST["accion"] == "actualiza" && !empty($_POST["id"]) ){

#Se muestran los datos asociados al id en tratamiento
      $Where = " id = " . $_POST["id"];
      $ConsultaId = $miResponsable->consulta($miconexion->Conexion_ID, $Where);

      $row = mysql_fetch_assoc($ConsultaId);
      if ($row){
        $smarty->assign('id',          $row["id"]);
        $smarty->assign('sdecripcion', $row["sdescripcion"]);
        $smarty->assign('bactivo',     $row["bactivo"]);
      }
    }

#Se hace la inserci�n de los valores de la pantalla

    if ( $_POST["accion"] == "enviar" && empty($_POST["id"])){
      if (crear( $miconexion->Conexion_ID, $miResponsable ) ){
        $smarty->assign('error_msg', 'La creaci�n de los datos se realiz� de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la creaci�n de los datos');
      }
    }
    elseif( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      if (actualiza( $miconexion->Conexion_ID, $miResponsable ) ){
        $smarty->assign('error_msg', 'La acualizaci�n de los datos se realiz� de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la actualizaci�n de los datos');
      }
    }
    elseif( $_POST["accion"] == "elimina" && !empty($_POST["id"]) ){
      if (elimina( $miconexion->Conexion_ID, $miResponsable ) ){
        $smarty->assign('error_msg', 'La Eliminaci�n del registro se realiz� de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de eliminar el registro');
      }
    }
    elseif( $_POST["accion"] == "buscar" ){
      buscar( $smarty, $miconexion->Conexion_ID, $miResponsable );
    }
  }
  else{
    $smarty->assign('id',           "");
    $smarty->assign('sdescripcion', "");
  }

  $Where = " bactivo = 1 ";
  $ConsultaID = $miResponsable->consulta($miconexion->Conexion_ID, $Where);

  verconsulta( $smarty, $ConsultaID );

  $smarty->display('Responsables.tpl');



/* Realiza la insercion de los datos indicados en la forma */
function crear( $Conexion_ID, $miResponsable ){

  $datos = array( "sdescripcion" => $_POST["sdescripcion"] );

  
  $resultado = $miResponsable->create( $Conexion_ID, $datos );

  return $resultado;
}

/* Realiza la actualizac�n de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miResponsable ){

  $Where = " id = " . $_POST["id"];

  $datos = array( "id"             => $_POST["id"],
		  "sdescripcion"   => $_POST["sdescripcion"] );

  
  $resultado = $miResponsable->actualiza( $Conexion_ID, $Where, $datos );

  return $resultado;
}

/* Realiza la actualizac�n de los datos indicados en la forma */
function elimina( $Conexion_ID, $miResponsable ){

  $Where = " id = " . $_POST["id"];

  $resultado = $miResponsable->elimina( $Conexion_ID, $Where );

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
    $Datos['clase']        = $clase;

    $Responsables[$row[0]] = $Datos;
  }

  $smarty->assign('ArrResponsables', $Responsables);

}

function buscar( $smarty, $Conexion_ID, $Responsable ) {

  $Where =  array();
  if ($_POST["id_doctor"]){
    $Where[] = " id_doctor = ". $_POST["id_doctor"];
  }
  if ($_POST["sdescripcion"]){
    $Where[] = " LOWER(sdescripcion) like '%". strtolower($_POST["sdescripcion"])."%'";
  }

  $ConsultaID = $Responsable->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  
  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']           = $row[0];
    $Datos['sdescripcion'] = $row[1];
    $Datos['clase']        = $clase;

    $Responsables[$row[0]] = $Datos;
  }

  $smarty->assign('ArrResponsables', $Responsables);

}

?>

</body>

</html>

