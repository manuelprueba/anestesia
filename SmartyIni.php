<?php

// load Smarty library
require('c:\wamp\Smartylibs\Smarty.class.php');

class SmartyIni extends Smarty {

function SmartyIni() {

// Class Constructor. 
// These automatically get set with each new instance.

  $this->Smarty();

  $this->template_dir = 'C:\wamp\www\templates';
  $this->config_dir = ' C:\wamp\www\Smartyconfig';
  $this->cache_dir = 'C:\wamp\Smartycache';
  $this->compile_dir = 'C:\wamp\Smartytemplates_c';

}

}
?>
