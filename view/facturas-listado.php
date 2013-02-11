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
	
	$enlace_listado = "facturas-listado.php";
	$enlace_gestion = "facturas-gestion.php";
	$resultado = "";
	
	$conexion = new conexion();
	$link = $conexion->conectar();
	$clase_database = new database();
	
       
	
?>
<html>
	<head><meta http-equiv="X-UA-Compatible" content="IE=8" >
		<meta http-equiv="X-UA-Compatible" content="IE=7" >
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta name="Author" content="Villatoro Asociados">
		<title><? echo $title; ?> - Listado de facturas</title>
		<link rel="stylesheet" href="../css/estilos.css" type="text/css">
	<link href="../css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<script src="../js/jquery-1.8.3.js"></script>
	<script src="../js/jquery-ui-1.9.2.custom.js"></script>
</head>
 <? $id_declaracion = isset($_SESSION["n_declaracion"]) ? hideunlock($_SESSION["n_declaracion"]) : 0;
       
 if($id_declaracion=="0"){
     //SE VERIFICA SI NO HAY UN RETACEO ABIERTO, PARA MOSTRAR MENSAJE DE ADVERTENCIA
   
         ?>
<script>
  $(function() {
 
    $( "#errorMSJ" ).dialog({
      height: 180,
      modal: true,
      close: function( event, ui ) {location.href="declaraciones-listado.php";}
    });
  });
  </script>
  
  <body>
<div id="errorMSJ" title="Alerta">
    <center>
  <p>Para poder iniciar la edicion de facturas, debe abrir una Declaracion</p>
  <br> <a style="color:blue" href="declaraciones-listado.php">Abrir</a>
  </center>
</div>
      <? }else{ //SI HAY UNO ABIERTO SE MUESTRA EL CONTENIDO ?>
<body>  
    <? }?>
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
                          <a href="index.php"><img src="../images/icono-tienda.gif" border="0"></a>
                        </td>
                        <td width="20"><img src="../images/transparente.gif" height="1" width="20"></td>
                        <td class="titulo_modulo" align="left" width="100%">Listado de Facturas</td>
                        <td align="right" valign="middle">   


                        </td>
                      </tr>
                    </tbody></table>
                      <br>
                <center>      
                    <table style="border:1px solid #cccccc;text-align:center;font-family: arial;font-size: 14px">
                      
                      <tr style="color: #371e05"><td width="325" colspan="2">Numero de Control: <?=$id_declaracion?></td><td width="325" colspan="3">Numero de Retaceo: <?=$id_declaracion?></td></tr>
                  <tr style="background-color: #785635;color:white"><td colspan="5">Totales</td></tr>
                  
                  <tr style="background-color: #6990BA ;color:white"><td width="130">Bultos</td><td width="130">Peso Bruto</td><td width="130">Cuantia</td><td width="130">FOB</td><td width="130">Otros Gastos</td></tr>
                  <tr><td>0.0</td><td></td><td></td><td></td><td></td></tr>
                  
                  <tr style="background-color: #6990BA ;color:white"><td>Seguro</td><td>CIF</td><td>DAI</td><td>IVA</td><td>A Pago</td></tr>
                  <tr><td>0.0</td><td></td><td></td><td></td><td></td></tr> 
                  
                  </table>
               </center>
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
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="30">Id Factura</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="20">Numero Factura</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="90">Fecha</td>                              
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Bultos</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Peso Bruto</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Cuantia</td>                                
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="90">Gastos</td>
   <td class="tabla_titulo" style="border: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">FOB</td><td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="50">EDITAR</td>
   <td class="tabla_titulo" style="border: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="60">ELIMINAR</td>
  </tr>
<?
  	$sql_facturas = "SELECT * FROM factura WHERE numeroretaceo ='".$id_declaracion."'";
	$result = mysql_query($sql_facturas,$link);
	
	while($fact = mysql_fetch_array($result)){
		
	?>
	
           <tr>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="70">
                <?=$fact["idFactura"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="70">
                <?=$fact["numero"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                <?=substr($fact["fecha"],0,10)?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                <?=$fact["bultos"]?>
                </td>
                 <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                <?=$fact["pesoBruto"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                <?=$fact["cuantia"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                <? $GASTOStotal+=$fact["otrosGastos"];echo $fact["otrosGastos"];?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                <? $FOBtotal+=$fact["FOB"];echo $fact["FOB"];?>
                </td>
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><a href="facturas-gestion.php?id=<? echo hidelock($filas['nit']);?>"><img src="../images/icono-editar.gif" border="0"></a></td>
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
      document.location.href='facturas-listado.php?accion=eliminar&id='+id;
	}
  }
</script>

</center>
<? include_once("../includes/barra_menu.php");?>
</body></html>