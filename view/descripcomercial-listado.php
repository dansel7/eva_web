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
	
	$enlace_listado = "descripcomercial-listado.php";
	$enlace_gestion = "descripcomercial-gestion.php";
	$resultado = "";
	
	$conexion = new conexion();
	$link = $conexion->conectar();
	$clase_database = new database();
	
	if(isset($_GET['accion']) && $_GET['accion'] == "eliminar"){
		$resultado = $clase_database->Eliminar($link,'ventas_consumidores',' idConsumidor_Final = ' . $_GET['id']);
		if ($resultado){ 
			$mensaje = "Venta Eliminada Correctamente";
			$clase_css = "texto_ok";
		}else{
			$mensaje = "Error al Eliminar Venta";
			$clase_css = "texto_error";
		}	
	}
	
?>
<html>
	<head><meta http-equiv="X-UA-Compatible" content="IE=8" >
		<meta http-equiv="X-UA-Compatible" content="IE=7" >
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta name="Author" content="Villatoro Asociados">
		<title><? echo $title; ?> - Listado de Ventas</title>
		<link rel="stylesheet" href="../css/estilos.css" type="text/css">
     <link href="../css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<script src="../js/jquery-1.8.3.js"></script>
	<script src="../js/jquery-ui-1.9.2.custom.js"></script>
        
        <script>
        $(document).ready(function(){
       
   				$("#Empresas").change(function(){
					if($("#Empresas").val()=="YWdiZWJoYWNnZ2FhYmQ="){
					$("#logo").html("<center><img src='../images/logos/siman.jpg'></center>");
					}else if($("#Empresas").val()=="YWdiZWJiYWlhYWJhZGM="){
					$("#logo").html("<center><img src='../images/logos/unicomer.jpeg'></center>");
					}else if($("#Empresas").val()=="YWdiZWJnYWdhZmJhZGo="){
					$("#logo").html("<center><img height='60px' src='../images/logos/coprodisa.jpeg'></center><br>");
					}else{$("#logo").html("");}
					
					$("#cargar").html("<center><img width='50px' height='50px' src='../images/load.gif'></center>"); 

					$.post("../includes/descripcomercial_empresas.php",
							{id: $(this).val()},
						   function(data){
						   $("#cargar").html(data);  
					 })
				})  
				     
			})
			
		$(function() {
			function abrir() {
				var selectedEffect = 'bounce';
				var options = {};
				$( "#avanzadas" ).effect( selectedEffect, options, 500 );
			};
			
			$( "#desplegar" ).click(function() {
				abrir();
				return false;
			});
			
			function cerrar() {
				var selectedEffect = 'blind';
				var options = {};
				$( "#avanzadas" ).hide( selectedEffect, options, 1000);
			};

			$( "#cerrar" ).click(function() {
				cerrar();
				return false;
			});
			
			$( "#avanzadas" ).hide();
                        
                        
        
                        
                        
	});
        
       
        </script>
        <style>
			#desplegar { padding: .5em 1em; text-decoration: none; font-size:11px; float:left; }
			#avanzadas { width: 900px; padding: 0.4em;  position: relative;}
			.ui-effects-transfer { border: 2px dotted gray; } 
        </style>
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
                                  <a href="index.php"><img src="../images/icono-menus.gif" border="0"></a>
                                </td>
                                <td width="20"><img src="../images/transparente.gif" height="1" width="20"></td>
                                <td class="titulo_modulo" align="left" width="100%">Gesti&oacute;n de Descripciones Comerciales</td>
                                <td align="right" valign="middle">   
                                
  <table border="0" cellpadding="0" cellspacing="0">
    <tbody><tr>
      <td style="padding-left: 10px; padding-right: 10px;" align="center"><a href="<?=$enlace_gestion?>"><img src="../images/menu-crear.gif" border="0"></a></td>
    </tr>
    <tr>
      <td style="padding-left: 10px; padding-right: 10px;" align="center"><a id="agregar" href="<?=$enlace_gestion?>" class="menu_opcion"><nobr>Agregar Descripci&oacute;n</nobr></a></td>
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
                	
                	<table align="left" border="0" cellpadding="0" cellspacing="0" width="930">
                		<tr><td><div id='logo'></div></td></tr>
                	</table>
                	
                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="930">
                    <tbody><tr>
                      <td valign="top" align="center">
                      	<span class="texto_ok">Seleccione una Empresa</span><br/>
						<select name="Empresas" id="Empresas" >
                        	<option value="-1" disabled selected>Seleccione una Empresa</option>
                        <?
                        	$result = mysql_query("SELECT * FROM empresas ORDER BY nombre", $link);
							while($fila = mysql_fetch_array($result)){ ?>
								<option value="<?=hidelock($fila['nit'])?>" ><?=$fila['nombre']?></option>								
							<? }
						?>
                        </select>
                      <br />
                      	<!--<a href="#" id="desplegar" class="ui-state-default ui-corner-all">.::Opciones Avanzadas ::.</a><br/><br/>
                        <div id="avanzadas" class="ui-widget-content ui-corner-all" style="text-align:left">
                        	<a href="#" id="cerrar" class="ui-state-default ui-corner-all" style="float:right;">&nbsp;x&nbsp;</a><br/><br/>
                        	<input type="text" name="Fdesde" id="Fdesde" style=""/>
						</div>-->
                      </td>
                    </tr>
                  </tbody></table> 
                </td>
              </tr>
            </tbody></table> 
          </td>
        </tr>
        <tr><td class="menu_abajo">&nbsp;</td></tr>
        
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
    						<div id="cargar">

							</div>

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
  <tr><td class="texto_copyright" align="right" height="44" valign="middle"><?= $copyright ?></td></tr>
</tbody></table>

<script type="text/javascript">
  function eliminar(id)
  {
	if (confirm('�Desea realmente eliminar el registro seleccionado?'))
	{
      document.location.href='descripcomercial-listado.php?accion=eliminar&id='+id;
	}
  }
</script>

</center>
<? include_once("../includes/barra_menu.php");?>
</body></html>