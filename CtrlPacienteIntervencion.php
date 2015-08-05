<?php

  session_start();

  require ("MysqlDB.php");
  require ("Doctor.php");
  require ("Paciente.php");
  require ("TipoOperacion.php");
  require ("Responsable.php");
  require ("Intervencion.php");
  require ("PacienteIntervencion.php");
  require ("DetallePacienteInterven.php");
  require ("Fecha.php");
  require('SmartyIni.php');

  $smarty  = new SmartyIni;

  $smarty->assign('usuario_log', $_SESSION['usuario_log']);

  $smarty->assign('titulo','Intervención por Paciente');

  if(!$_SESSION['usuario_log']){
    header('location: http://localhost/iniciosesion.php');
  }

  $ConsultaId;


  $miconexion = new DB_mysql ;

  $miDoctor   = new Doctor;

  $miPaciente = new Paciente;

  $miTipoOperacion = new TipoOperacion;

  $miResponsable = new Responsable;

  $miIntervencion = new Intervencion;

  $miPacienteIntervencion = new PacienteIntervencion;

  $miDetallePacienteInterven = new DetallePacienteInterven;

  $miconexion->conectar("", "", "", "");

  $Paciente = $miPaciente->listarPaciente( $miconexion->Conexion_ID );
  $smarty->assign('pacien_options', $Paciente);

  $TipoOperacion = $miTipoOperacion->listarTipoOperacion( $miconexion->Conexion_ID );
  $smarty->assign('tpopera_options', $TipoOperacion);

  $DoctorCiru = $miDoctor->listarDoctores( $miconexion->Conexion_ID, " id_especialidad = 1 " );
  $smarty->assign('doctorCiru_options', $DoctorCiru);

  $DoctorAnes = $miDoctor->listarDoctores( $miconexion->Conexion_ID, " id_especialidad = 2 " );
  $smarty->assign('doctorAnes_options', $DoctorAnes);

  $Responsable = $miResponsable->listarResponsable( $miconexion->Conexion_ID );
  $smarty->assign('respon_options', $Responsable);

  $Intervencion = $miIntervencion->listarIntervencion( $miconexion->Conexion_ID );
  $smarty->assign('interven_options', $Intervencion);

  if (!empty($_POST['accion']) && $_POST['accion'] != "buscarPacientes" && $_POST['accion'] != "buscarIntervenciones" ) {
    if ($_POST["accion"] == "actualiza" && !empty($_POST["id"]) ){

#Se muestran los datos asociados al id en tratamiento
      $Where = " me.id = " . $_POST["id"];
      $ConsultaId = $miPacienteIntervencion->consulta($miconexion->Conexion_ID, $Where);

      $row = mysql_fetch_assoc($ConsultaId);
      if ($row){
	         $smarty->assign('id',                  $row["id"]);
                 $smarty->assign('num_recibo',          $row["num_recibo"]);
                 $smarty->assign('id_paciente',         $row["id_paciente"]);
                 $smarty->assign('fecha',               $row["fecha"]);
                 $smarty->assign('id_tpoperacion',      $row["id_tpoperacion"]);
	         $smarty->assign('id_doctor_cirujano',  $row["id_doctor_cirujano"]);
                 $smarty->assign('id_doctor_anestesia', $row["id_doctor_anestesia"]);
		 $smarty->assign('monto_total',         $row["monto_total"]);
		 $smarty->assign('id_responsable',      $row["id_responsable"]);
		 $smarty->assign('sobservacion',        $row["sobservacion"]);
      }
    }

#Se hace la inserciòn de los valores de la pantalla

    if ( $_POST["accion"] == "enviar" && empty($_POST["id"])){
      if (crear( $miconexion->Conexion_ID, $miPacienteIntervencion, $miDetallePacienteInterven )){
        $smarty->assign('error_msg', 'La creación de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la creación de los datos');
      }
    }
    elseif( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      if (actualiza( $miconexion->Conexion_ID, $miPacienteIntervencion )){
        $smarty->assign('error_msg', 'La acualización de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la actualización de los datos');
      }
    }
    elseif( $_POST["accion"] == "elimina" && !empty($_POST["id"]) ){
      if (elimina( $miconexion->Conexion_ID, $miPacienteIntervencion )){
        $smarty->assign('error_msg', 'La Eliminación del registro se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de eliminar el registro');
      }
    }
    elseif( $_POST["accion"] == "buscar" ){
      buscar( $smarty, $miconexion->Conexion_ID, $miPacienteIntervencion );
    }

    $smarty->display('Paciente_Intervencion.tpl');
  }
  else{

    if (!empty($_POST["accion"]) && ($_POST["accion"] == "buscarPacientes" || $_POST["accion"] == "buscarIntervenciones") &&
        !empty($_POST["criterio"]) ){
      if ($_POST["accion"] == "buscarPacientes"){
        buscarPacientes( $miconexion->Conexion_ID, $miPaciente );
      }
      elseif ($_POST["accion"] == "buscarIntervenciones"){
        buscarIntervenciones( $miconexion->Conexion_ID, $miIntervencion );
      }
      exit;
    }
    else{
      $smarty->assign('id',                  '');
      $smarty->assign('num_recibo',          '');
      $smarty->assign('id_paciente',         '');
      $smarty->assign('fecha',               '');
      $smarty->assign('id_tpoperacion',      '');
      $smarty->assign('id_doctor_cirujano',  '');
      $smarty->assign('id_doctor_anestesia', '');
      $smarty->assign('monto_total',         '');
      $smarty->assign('id_responsable',      '');
      $smarty->assign('sobservacion',        '');
      
      $smarty->display('Paciente_Intervencion.tpl');
    }
  }


/* Realiza la insercion de los datos indicados en la forma */
function crear( $Conexion_ID, $miPacienteIntervencion, $miDetallePacienteInter ){

  $miFecha = new Fecha;

  $datos = array( "num_recibo"          => $_POST["num_recibo"],
	          "id_paciente"         => $_POST["id_paciente"],
		  "fecha"               => $miFecha->formatoDbFecha($_POST["fecha"]),
		  "id_tpoperacion"      => $_POST["id_tpoperacion"],
		  "id_doctor_cirujano"  => $_POST["id_doctor_cirujano"],
		  "id_doctor_anestesia" => $_POST["id_doctor_anestesia"],
		  "monto_total"         => $_POST["monto_total"],
		  "id_responsable"      => $_POST["id_responsable"],
	          "sobservacion"        => $_POST["sobservacion"] );
  
  $resultado = $miPacienteIntervencion->create( $Conexion_ID, $datos );

  if ($resultado){
    for ($i=0;$i<=4;$i++){
      $nombre_inter = "id_intervencion_".$i;
      $nombre_monto = "nmonto_".$i;
      $nombre_sobse = "sobservacion_".$i;

      if (isset($_POST[$nombre_inter]) && !empty($_POST[$nombre_inter]) ){

	$datosDE = array( "id_paciente_inter" => mysql_insert_id(),
	                  "id_intervencion"   => $_POST[$nombre_inter],
	                  "nmonto"            => $_POST[$nombre_monto],
	                  "sobservacion"      => $_POST[$nombre_sobse] );

        $response = $miDetallePacienteInter->create( $Conexion_ID, $datosDE );
  
      }
    }
  }
  return $response;

}

/* Realiza la actualizacón de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miPacienteIntervencion ){

  $Where = " id = " . $_POST["id"];

  $datos = array( "num_recibo"          => $_POST["num_recibo"],
	          "id_paciente"         => $_POST["id_paciente"],
		  "fecha"               => $_POST["fecha"],
		  "id_tpoperacion"      => $_POST["id_tpoperacion"],
		  "id_doctor_cirujano"  => $_POST["id_doctor_cirujano"],
		  "id_doctor_anestesia" => $_POST["id_doctor_anestesia"],
		  "monto_total"         => $_POST["monto_total"],
		  "id_responsable"      => $_POST["id_responsable"] );
  
  $resultado = $miPacienteIntervencion->actualiza( $Conexion_ID, $Where, $datos );

  return $resultado;
}

/* Realiza la actualizacón de los datos indicados en la forma */
function elimina( $Conexion_ID, $miPacienteIntervencion ){

  $Where = " id = " . $_POST["id"];

  $resultado = $miPaienteIntervencion->elimina( $Conexion_ID, $Where );

  return $resultado;
}


function buscar( $smarty, $Conexion_ID, $PacienteIntervencion ) {

  $Where =  array();
  

  $ConsultaID = $PacienteIntervencion->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos["id"]                  = $row[0];
    $Datos['id_paciente']         = strtoupper($row[1]);
    $Datos['fecha']               = strtoupper($row[2]);
    $Datos['id_tpoperacion']      = $row[3];
    $Datos['id_doctor_cirujano']  = $row[4];
    $Datos['id_doctor_anestesia'] = $row[5];
    $Datos['monto_total']         = $row[6];
    $Datos['id_responsable']      = $row[7];
    $Datos['clase']             = $clase;

    $Recibos[$row[0]] = $Datos;
  }

  $smarty->assign('ArrRecibos', $Recibos);


}

function buscarPacientes( $Conexion_ID, $Paciente ) {

  $Where =  array();
  if ($_POST["criterio"]){
    $Where[] = " LOWER(shistoria) like '%". strtolower($_POST["criterio"])."%'";
    $Where[] = " LOWER(snombre) like '%". strtolower($_POST["criterio"])."%'";
    $Where[] = " LOWER(sapellido) like '%". strtolower($_POST["criterio"])."%'";
  }

  $ConsultaID = $Paciente->consulta($Conexion_ID, join(" OR ", $Where));
// Retornamos los registros

  $Retorno = "";
  while ($row = mysql_fetch_row($ConsultaID)) {
    $Retorno .= $row[0].":".$row[1]." - ".$row[3].", ".$row[2]."|";
  }

  echo $Retorno;

}

function buscarIntervenciones( $Conexion_ID, $Intervencion ) {

  $Where =  array();
  if ($_POST["criterio"]){
    $Where[] = " LOWER(sdescripcion) like '%". strtolower($_POST["criterio"])."%'";
	$Where[] = " bactivo = 1 ";
  }

  $ConsultaID = $Intervencion->consulta($Conexion_ID, join("AND", $Where));
// Retornamos los registros

  $Retorno = "";
  while ($row = mysql_fetch_row($ConsultaID)) {
    $Retorno .= $row[0].":".$row[1].":".$row[2]."|";
  }

  echo $Retorno;

}

?>
