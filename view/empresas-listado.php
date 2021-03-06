<?
session_start();
error_reporting(0);
// 10 mins in seconds
$timeout = 600; 

if(isset($_SESSION['timeout']) ) {
  // Check if session timed out
  $session_time = time() - $_SESSION['timeout'];

  if($session_time > $timeout)
  {  
     // If it did, destroy it and probably logout user
     session_destroy();
     header("Location: ../clases/cerrar_sesion.php");
  }
}
$_SESSION['timeout'] = time();

	include_once("../configuracion/configuracion.php");
	include_once("../clases/conexion.php");
	include_once("../clases/database.php");
	
	$enlace_listado = "empresas-listado.php";
	$enlace_gestion = "empresas-gestion.php";
	$resultado = "";
	
	$conexion = new conexion();
	$link = $conexion->conectar();
	$clase_database = new database();
	
	if(isset($_GET['accion']) && $_GET['accion'] == "eliminar"){
		$resultado = $clase_database->Eliminar($link,'empresas',' NIT = "' . hideunlock($_GET['id']).'"');
		if ($resultado){ 
			$mensaje = "Empresa Eliminada Exitosamente";
			$clase_css = "texto_ok";
		}else{
			$mensaje = "Error al Eliminar Empresa";
			$clase_css = "texto_error";
		}	
	}
	
?>
<html>
	<head><meta http-equiv="X-UA-Compatible" content="IE=8" >
		<meta http-equiv="X-UA-Compatible" content="IE=7" >
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta name="Author" content="Villatoro Asociados">
		<title><? echo $title; ?> - Listado de Empresas</title>
		<link rel="stylesheet" href="../css/estilos.css" type="text/css">
	<link href="../css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<script src="../js/jquery-1.8.3.js"></script>
	<script src="../js/jquery-ui-1.9.2.custom.js"></script>
</head>
	<body>
		<center>
<table border="0" cellpadding="0" cellspacing="0">
  <tbody><tr><td height="22">&nbsp;</td></tr>
  
  <tr><td class="cabecera">
  		<div class="titulo_dominio" style="padding-top:5px;padding-right:25px;" align="right">
  			<? echo $nombre_institucion; ?>
        </div>
  </td></tr>
  
  <tr>
    <td class="fondo_menu" valign="top">
     
<form name="frm" id="frm" action="<? echo $enlace_listado; ?>" method="post" style="margin:0px;" >    
      <table border="0" cellpadding="0" cellspacing="0">
        <tbody><tr><td height="10"></td></tr>
        <tr><td style="padding-right: 14px;" align="right"><table align="right" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="20" valign="middle"><a href="index.php"><img src="../images/volver-menu.gif" border="0" height="16" width="14"></a></td><td style="padding-right: 40px;" height="20" valign="middle"><a href="index.php" class="texto_volver_inicio">&nbsp;Volver al panel de administraci&oacute;n</a></td><td height="20" valign="middle"><a href="../clases/cerrar_sesion.php"><img src="../images/menu-cerrar-sesion.gif" border="0" height="18" width="18"></a></td><td height="20" valign="middle"><a href="../clases/cerrar_sesion.php" class="texto_volver_inicio">&nbsp;Cerrar sesi&oacute;n</a></td></tr></tbody></table></td></tr>
        <tr><td class="menu_interior_arriba">&nbsp;</td></tr>
        <tr>
          <td align="center" valign="top">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="950">
              <tbody><tr>
                <td class="menu_fondo" align="center" valign="top">
                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="930">
                    <tbody><tr>
                      <td valign="top">

                      <table align="center" border="0" cellpadding="0" cellspacing="0" width="928">  
                        <tbody><tr>
                          <td height="12"></td>
                        </tr>
                        <tr>
                          <td valign="middle">
                            <table border="0" cellpadding="0" cellspacing="0" height="80" width="100%">
                              <tbody><tr>
                                <td width="8"><img src="../images/transparente.gif" height="1" width="8"></td>
                                <td valign="middle">
                                  <a href="index.php"><img src="../images/icono-empresas.gif" border="0"></a>
                                </td>
                                <td width="20"><img src="../images/transparente.gif" height="1" width="20"></td>
                                <td class="titulo_modulo" align="left" width="100%">Listado de Empresas</td>
                                <td align="right" valign="middle">   
                                
  <table border="0" cellpadding="0" cellspacing="0">
    <tbody><tr>
      <td style="padding-left: 10px; padding-right: 10px;" align="center"><a id="agreg1" href="<?=$enlace_gestion."?opc=".hidelock("nuevo")?>"><img src="../images/menu-crear.gif" border="0"></a></td>
    </tr>
    <tr>
      <td style="padding-left: 10px; padding-right: 10px;" align="center"><a id="agreg2" href="<?=$enlace_gestion."?opc=".hidelock("nuevo")?>" class="menu_opcion"><nobr>Agregar Nuevo</nobr></a></td>
    </tr>
  </tbody></table>
  
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                        <tr>
                          <td height="12"></td>
                        </tr>
                      </tbody></table>

                      </td>
                    </tr>
                  </tbody></table> 
                </td>
              </tr>
            </tbody></table> 
          </td>
        </tr>
        <tr><td class="menu_abajo">&nbsp;</td></tr>
        
        <? if($resultado){?>
        <tr><td class="menu_interior_arriba">&nbsp;</td></tr>
        <tr>
          <td align="center" valign="top">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="950">
              <tbody><tr>
                <td class="menu_fondo" align="center" valign="top">
                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="930">
                    <tbody><tr>
                      <td valign="top">
                      <br />
						<span class="<? echo $clase_css; ?>"><? echo $mensaje; ?></span>
                      <br />
                      </td>
                    </tr>
                  </tbody></table> 
                </td>
              </tr>
            </tbody></table> 
          </td>
        </tr>
        <tr><td class="menu_abajo">&nbsp;</td></tr>
        <? } ?>
        
        
        <tr><td class="menu_interior_arriba">&nbsp;</td></tr>
        <tr>
          <td align="center" valign="top">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="950">
              <tbody><tr>
                <td class="menu_fondo" align="center" valign="top">
                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="930">
                    <tbody><tr>
                      <td valign="top">           
                      
                      <table align="center" border="0" cellpadding="0" cellspacing="0" width="928">  
                        <tbody><tr>
                          <td valign="top">
    
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr bgcolor="#EBEBEB">
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="50">#</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="365">NOMBRE</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="50">NIT</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="65">EDITAR</td>
    <td class="tabla_titulo" style="border: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="75">ELIMINAR</td>
  </tr>
<?
  	$sql_empresas = "SELECT nombre,nit FROM empresas ORDER BY nombre";
	$result = mysql_query($sql_empresas,$link);
	$contador = 0;
	while($filas = mysql_fetch_array($result)){
		$contador++;
	?>
	
              <tr bgcolor="#FBFBFB">
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo $contador;?></td>
        <td class="tabla_filas" style="padding-left: 20px; border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="left" height="34" valign="middle"><? echo $filas['nombre'];?></td>
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo $filas['nit'];?></td>
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><a href="empresas-gestion.php?id=<? echo hidelock($filas['nit']);?>"><img src="../images/icono-editar.gif" border="0"></a></td>
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226); border-right: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><a href="javascript:eliminar('<? echo hidelock($filas['nit']);?>');"><img src="../images/icono-eliminar.gif" border="0"></a></td>
      </tr>
    
	<?
	}
	
  ?>
      </tbody></table>
    </td></tr>
</tbody></table>
    
                          </td>
                        </tr>
                      </tbody></table>
                      </td>
                    </tr>
                  </tbody></table> 
                 </td>
        </tr>
        <tr><td class="menu_abajo">&nbsp;</td></tr>
      </tbody></table>
    </td>
  </tr>
  <tr><td class="fondo_login_abajo_menu"></td></tr>
  <tr><td class="texto_copyright" align="right" height="44" valign="middle"><?=$copyrigth; ?></td></tr>
</tbody></table>
<input name="total" id="total" value="8" type="hidden">
<input name="accion" id="accion" value="" type="hidden">
<input id="__EVENTTARGET" name="__EVENTTARGET" type="hidden">      
</form>

<script type="text/javascript">
  function eliminar(id)
  {
	if (confirm('�Desea realmente eliminar el registro seleccionado?'))
	{
      document.location.href='empresas-listado.php?accion=eliminar&id='+id;
	}
  }
</script>

</center>
<? include_once("../includes/barra_menu.php");?>
</body></html>