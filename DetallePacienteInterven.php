<?php

class DetallePacienteInterven {


/* variables de la clase DetallePacienteInterven */

  var $id_paciente_intervencion;

  var $id_intervencion;

  var $nmonto;

  var $sobservacion;

/* M�todo Constructor: Cada vez que creemos una variable

de esta clase, se ejecutar� esta funci�n */

function DetallePacienteInterven($id_paciente_intervencion = "", $id_intervencion = "", $nmonto = "", $sobservacion = ""  ) {

  $this->id_paciente_intervencion = $id_paciente_intervencion;
  $this->id_intervencion          = $id_intervencion;
  $this->nmonto                   = $nmonto;
  $this->sobservacion             = $sobservacion;
}

/* Ejecuta un Insercion */

function create($Conexion_ID, $datos = ""){

  $query = "INSERT INTO detalle_paciente_interven ( id_paciente_intervencion, id_intervencion, nmonto, sobservacion ) 
	                                VALUES    ('".$datos["id_paciente_inter"]."','".$datos["id_intervencion"]."','".
	                                              $datos["nmonto"]."','".$datos["sobservacion"]."')";

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualizaci�n del campo bactivo */

function elimina($Conexion_ID, $Where = ""){

  $query = "DELETE FROM detalle_paciente_interven WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Devuelve el n�mero de campos de una consulta */

function numcampos() {

  return mysql_num_fields($this->Consulta_ID);

}

/* Devuelve el n�mero de registros de una consulta */

function numregistros(){

  return mysql_num_rows($this->Consulta_ID);

}

/* Devuelve el nombre de un campo de una consulta */

function nombrecampo($numcampo) {

  return mysql_field_name($this->Consulta_ID, $numcampo);

}

}
?>
