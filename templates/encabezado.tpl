{include file="doctype.tpl"}
<link rel="stylesheet" href="menu/menu.css">
<LINK HREF="css/anestesia.css" REL="stylesheet" TYPE="text/css"
<!-- INICIO HEADER VENEZUELA -->
<head>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="/imagenes/lhoi.jpg" alt="banner_lhoi_izq" /></td>
    <td width="100%" align="right" class="titulosistema" background="/imagenes/banner_bandes_cen.jpg">Sistema Control de Intervenciones<br/></td>
    <td align="right"><img src="/imagenes/banner_bandes_der.jpg" alt="banner_bandes_der" /></td>
  </tr>
</table>

<link rel="stylesheet" type="text/css" href="css/jquerycssmenu.css" />
{literal}
<link href="menu/estilos.css" rel="stylesheet" type="text/css" /> 
<link href="menu/ADxMenuHoriz.css" rel="stylesheet" type="text/css" media="screen, tv, projection" /> 
<!--[if lte IE 6]>
<link href="ADxMenuHoriz-IE6.css" rel="stylesheet" type="text/css" media="screen, tv, projection" />
<![endif]--> 
 
<script type="text/javascript" src="menu/ADxMenu.js"></script> 

{/literal}

</head>
<!-- FIN HEADER VENEZUELA -->
<!-- INICIO MENU -->
{if $usuario_log}
<div class="ejemplo"> 
<ul class="adxm menu"> 
  {if $badministra}
    <li><a href="javascript:void();">Administración</a>
      <ul> 
        <li><a href="http://localhost/CtrlUsuario.php">Usuarios</a></li> 
        <li><a href="http://localhost/CtrlDoctor.php">Doctores</a></li> 
        <li><a href="http://localhost/CtrlPaciente.php">Pacientes</a></li> 
        <li><a href="http://localhost/CtrlResponsable.php">Responsables</a></li> 
        <li><a href="http://localhost/CtrlIntervencion.php">Tipo de Intervenciones</a></li> 
      </ul> 
    </li> 
  {/if}
  <li><a href="http://localhost/CtrlPacienteIntervencion.php" title="Cargar Intervenciones">Intervenciones</a></li> 
  <li><a href="javascript:void();" title="Salir del Sistema">Salir</a></li> 
</ul> 
</div> 
{/if}


<!-- FIN MENU -->
<br/>
{if $subtitulo }
<div class="titulonegro" align="center">{$subtitulo }</div>
{/if}
<div align="center">
<span class="titulodonfomarillo" id="error_msg">{$error_msg}</span>
</div>
{literal}
<script type="text/javascript" language="Javascript">
//ocultar mensajes de error luego de 10 segundos
if (document.getElementById) {
  if (document.getElementById('error_msg')) {
    window.setTimeout("document.getElementById('error_msg').style.display = 'none'",6000);
  }
}
</script>
<script type="text/javascript" src="/js/tooltip.js"></script>
{/literal}
<div id="cargando" style="display: none">
      <table> 
         <tr> 
            <td> 
               <span style="color: #000000">Cargando</span>
            </td> 
         </tr>
         <tr> 
            <td align="center">
               <img alt="Cargando" src="/imagenes/cargando.gif" /> 
            </td> 
         </tr>  
      </table> 
</div>
