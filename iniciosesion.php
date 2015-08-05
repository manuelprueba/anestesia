<body>

<html>

<?php

  require ("MysqlDB.php");
  require ("Usuario.php");
  require('SmartyIni.php');

  $smarty  = new SmartyIni;

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miUsuario  = new Usuario;

  $miconexion->conectar("", "", "", "");

  if (!empty($_POST['susuario']) ) {
#Se Busca la información relacionada con el usuario
    $Where = " susuario = '" . $_POST["susuario"]."' AND bactivo = 1";
    $ConsultaId = $miUsuario->consulta($miconexion->Conexion_ID, $Where);

    if ($ConsultaId){
      $row = mysql_fetch_object($ConsultaId);
      if ($row &&  (sha1($_POST['scontrasena']) == $row->scontrasena)){
	session_start();
	$usuario_log  = $_POST['susuario'];
	$nombre_log   = $row->snombre;
	$apellido_log = $row->sapellido;
	$badministra  = ($row->badministrador == 1) ? 1 : 0;
        session_register("usuario_log");
        session_register("nombre_log");
        session_register("apellido_log");
        session_register("badministra");
	
        header( 'Location: http://localhost/Home.php' ) ;
      }
      else{
        $smarty->assign('error_msg','Las credenciales indicadas no corresponden con ningún usuario registrado');
      }
    }
    else{
      $smarty->assign('error_msg','Las credenciales indicadas no corresponden con ningún usuario registrado');
    }
  }

  $smarty->display('iniciosesion.tpl');

?>

</body>

</html>

