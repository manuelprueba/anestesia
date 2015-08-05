<body>

<html>

<?php

  session_start();

  require('SmartyIni.php');

  $smarty  = new SmartyIni;

  if (!$_SESSION['usuario_log']){
    header('location: http://localhost/iniciosesion.php');
  }
  $smarty->assign('usuario_log', $_SESSION['usuario_log']);
  $smarty->assign('badministra', $_SESSION['badministra']);

  $smarty->display('Home.tpl');

?>

</body>

</html>

