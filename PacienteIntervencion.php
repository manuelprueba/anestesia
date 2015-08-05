<?php

class PacienteIntervencion {


/* variables de la clase PacienteIntervencion */

  var $snombre;

  var $sapellido;

  var $stelefono;

  var $stelefono_1;

  var $id_especialidad;

  var $bactivo;

/* Método Constructor: Cada vez que creemos una variable

de esta clase, se ejecutará esta función */

  function PacienteIntervencion($num_recibo = "", $id_paciente = "", $id_tpoperacion = "", $id_doctor_cirujano = "", 
	                        $id_doctor_anestesia = "", $monto_total = "", $id_responsable = "", $fecha_pago = "", 
	                        $fecha_intervencion = "", $monto_pagado = "", $sobservacion = "" ) {

  $this->num_recibo          = $num_recibo;
  $this->id_paciente         = $id_paciente;
  $this->id_tpoperacion      = $id_tpoperacion;
  $this->id_doctor_cirujano  = $id_doctor_cirujano;
  $this->id_doctor_anestesia = $id_doctor_anestesia;
  $this->monto_total         = $monto_total;
  $this->id_responsable      = $id_responsable;
  $this->fecha_pago          = $fecha_pago;
  $this->fecha_intervencion  = $fecha_intervencion;
  $this->monto_pagado        = $monto_pagado;
  $this->sobservacion        = $sobservacion;
}
/* Ejecuta un consulta */

function consulta($Conexion_ID, $Where = ""){

  $SQL =  " SELECT me.id, me.num_recibo, me.id_paciente,
	           me.id_tpoperacion, me.id_doctor_cirujano,
		   me.id_doctor_anestesia, me.monto_total,
		   me.id_responsable, me.fecha_pago,
		   me.fecha_intervencion, me.monto_pagado,
		   me.sobservacion, pac.snombre AS nombrepac, 
		   pac.sapellido AS apellidopac, ciru.snombre AS nombreciru, 
		   ciru.sapellido AS apellidociru, anes.snombre AS nombreanes, 
		   anes.sapellido AS apellidoanes, tpopera.sdescripcion AS desctpopera, 
		   respon.sdescripcion AS descrespon, est.sdescripcion AS descestatus
              FROM paciente_intervencion me, detalle_paciente_interven deta, 
                   paciente pac, doctor ciru, 
		   doctor anes, tipo_operacion tpopera, 
		   responsable respon, estatus est
             WHERE me.id = deta.id_paciente_intervencion
               AND me.id_paciente = pac.id
               AND me.id_doctor_cirujano = ciru.id
               AND me.id_doctor_anestesia = anes.id
               AND me.id_tpoperacion = tpopera.id
               AND me.id_responsable = respon.id
               AND me.id_estatus = est.id ";

  if (!empty($Where)) {
    $SQL .= " AND " . $Where;
  }

//ejecutamos la consulta

  $this->Consulta_ID = @mysql_query($SQL, $Conexion_ID);

  if (!$this->Consulta_ID) {

    $this->Errno = mysql_errno();

    $this->Error = mysql_error();

  }

/* Si hemos tenido éxito en la consulta devuelve el identificador de la conexión, sino devuelve 0 */

  return $this->Consulta_ID;

}

/* Ejecuta un Insercion */

function create($Conexion_ID, $datos = ""){

  $query = "INSERT INTO paciente_intervencion ( num_recibo, id_tpoperacion, 
		                                id_doctor_cirujano, id_doctor_anestesia, 
						monto_total, id_responsable, 
						fecha_intervencion, sobservacion ) 
			               VALUES ('".$datos['num_recibo']."','".$datos['id_tpoperacion']."','".
                                                  $datos['id_doctor_cirujano']."','".$datos['id_doctor_anestesia']."','".
						  $datos['monto_total']."','".$datos['id_responsable']."','".
						  $datos['fecha']."','".$datos['sobservacion']."')";

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización */

function actualiza($Conexion_ID, $Where = "", $datos = ""){

	$query = "UPDATE paciente_intervencion set snombre = '". $datos["snombre"] .
		                 "', sapellido = '". $datos['sapellido']. 
	                         "', stelefono = '". $datos['stelefono'].
	                         "', stelefono_1 = '". $datos['stelefono_1'].
		                 "', id_especialidad = '". $datos['id_especialidad']."' WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización del campo bactivo */

function elimina($Conexion_ID, $Where = ""){

  $query = "UPDATE paciente_intervencion set bactivo = 0  WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}


/* Devuelve el número de campos de una consulta */

function numcampos() {

  return mysql_num_fields($this->Consulta_ID);

}

/* Devuelve el número de registros de una consulta */

function numregistros(){

  return mysql_num_rows($this->Consulta_ID);

}

/* Devuelve el nombre de un campo de una consulta */

function nombrecampo($numcampo) {

  return mysql_field_name($this->Consulta_ID, $numcampo);

}

}
?>
