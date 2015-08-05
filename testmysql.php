<?php 
  $link = mysql_connect('localhost','root','mt2212'); 
  if (!$link) { 
    die('Could not connect to MySQL: ' . mysql_error()); 
  } 
  echo 'Connection OK'; mysql_close($link); 
?> 
