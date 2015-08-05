<body>

<html>

<?php

  session_start();

  require ("MysqlDB.php");
  require ("Paciente.php");
  require("SmartyIni.php");

  $smarty  = new SmartyIni;

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miPaciente  = new Paciente;

  $smarty->assign('titulo','Pacientes del Sistema de Control de Intervenciones');
  if (!$_SESSION['usuario_log']){
    header('location: http://localhost/iniciosesion.php');
  }
  $smarty->assign('usuario_log', $_SESSION['usuario_log']);

  $miconexion->conectar("", "", "", "");

  if (!empty($_POST['accion']) ) {
    if ($_POST["accion"] == "actualiza" && !empty($_POST["id"]) ){

#Se muestran los datos asociados al id en tratamiento
      $Where = " id = " . $_POST["id"];
      $ConsultaId = $miPaciente->consulta($miconexion->Conexion_ID, $Where);

      $row = mysql_fetch_assoc($ConsultaId);
      if ($row){
	      
        $smarty->assign('id',          $row["id"]);
        $smarty->assign('shistoria',   $row["shistoria"]);
        $smarty->assign('snombre',     $row["snombre"]);
	$smarty->assign('sapellido',   $row["sapellido"]);

      }
    }

#Se hace la inserciòn de los valores de la pantalla

    if ( $_POST["accion"] == "enviar" && empty($_POST["id"])){
      if (crear( $miconexion->Conexion_ID, $miPaciente )){
        $smarty->assign('error_msg', 'La creación de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la creación de los datos');
      }
    }
    elseif( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      if (actualiza( $miconexion->Conexion_ID, $miPaciente )){
        $smarty->assign('error_msg', 'La acualización de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la actualización de los datos');
      }
    }
    elseif( $_POST["accion"] == "elimina" && !empty($_POST["id"]) ){
      if (elimina( $miconexion->Conexion_ID, $miPaciente )){
        $smarty->assign('error_msg', 'La Eliminación del registro se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de eliminar el registro');
      }
    }
    elseif( $_POST["accion"] == "buscar" ){
      buscar( $smarty, $miconexion->Conexion_ID, $miPaciente );
    }
  }
  else{
    $smarty->assign('shistoria','');
    $smarty->assign('snombre', '');
    $smarty->assign('sapellido', '');
  }

  if (empty($_POST['accion']) || $_POST["accion"] != "buscar" ){
    $Where = " bactivo = 1 ";
    $ConsultaID = $miPaciente->consulta($miconexion->Conexion_ID, $Where);
    verconsulta( $smarty, $ConsultaID );
  }

  $smarty->display('Pacientes.tpl');


/* Realiza la insercion de los datos indicados en la forma */
function crear( $Conexion_ID, $miPaciente ){

  $datos = array( "shistoria"   => $_POST["shistoria"],
	          "snombre"     => $_POST["snombre"],
		  "sapellido"   => $_POST["sapellido"] );

  
  $resultado = $miPaciente->create( $Conexion_ID, $datos );

  return $resultado;

}

/* Realiza la actualizacón de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miPaciente ){

  $Where = " id = " . $_POST["id"];

  $datos = array( "shistoria"   => $_POST["shistoria"],
		  "snombre"     => $_POST["snombre"],
		  "sapellido"   => $_POST["sapellido"] );
  
  $resultado = $miPaciente->actualiza( $Conexion_ID, $Where, $datos );

  return $resultado;
}

/* Realiza la actualizacón de los datos indicados en la forma */
function elimina( $Conexion_ID, $miPaciente ){

  $Where = " id = " . $_POST["id"];

  $resultado = $miPaciente->elimina( $Conexion_ID, $Where );

  return $resultado;
}

/* Muestra los datos de una consulta */

function verconsulta( $smarty, $ConsultaID ) {

// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_object($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']          = $row->id;
    $Datos['shistoria']   = $row->shistoria;
    $Datos['snombre']     = strtoupper($row->snombre);
    $Datos['sapellido']   = strtoupper($row->sapellido);
    $Datos['clase']       = $clase;

    $Pacientes[$row->id] = $Datos;
  }

  $smarty->assign('ArrPacientes', $Pacientes);

}

/* Muestra los datos de una consulta con un criterio de busqueda indicado */

function buscar( $smarty, $Conexion_ID, $Paciente ) {

  $Where =  array();
  if ($_POST["shistoria"]){
    $Where[] = " LOWER(shistoria) like '%". strtolower($_POST["shistoria"])."%'";
  }
  if ($_POST["snombre"]){
    $Where[] = " LOWER(snombre) like '%". strtolower($_POST["snombre"])."%'";
  }
  if ($_POST["sapellido"]){
    $Where[] = " LOWER(sapellido) like '%". strtolower($_POST["sapellido"])."%'";
  }

  $ConsultaID = $Paciente->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']          = $row[0];
    $Datos['shistoria']   = $row[1];
    $Datos['snombre']     = $row[2];
    $Datos['sapellido']   = $row[3];
    $Datos['clase']       = $clase;

    $Pacientes[$row[0]] = $Datos;
  }

  $smarty->assign('ArrPacientes', $Pacientes);

}

?>

</body>

</html>

