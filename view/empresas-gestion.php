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

	include_once("../configuracion/configuracion.php");
	include_once("../clases/conexion.php");
	include_once("../clases/database.php");
	
	$enlace_listado = "empresas-listado.php";
	$enlace_gestion = "empresas-gestion.php";
	$resultado = "";
	
	$conexion = new conexion();
	$link = $conexion->conectar();
	$clase_database = new database();
	
	$id_empresa = isset($_GET['id']) ? hideunlock($_GET['id']) : 0;
	
	if (isset($_POST['submit']) && $_POST['submit']=='Guardar'){
		
		if($_POST['idEmpresa'] != "" || $_POST['idEmpresa'] != "0"){
			$id_empresa = $_POST['idEmpresa'];
		}
		
		if($_POST['idEmpresa'] != ""){
			$resultado  = $clase_database->formToDB($link,'entidad_mercantil','submit, idEmpresa, id_Departamento, ','','update',' nit = ' . $id_empresa);
		}else{
			$_POST['VCONS'] = '0';
			$_POST['VCONT'] = '0';
			$_POST['COM'] = '0';
			$resultado = $clase_database->formToDB($link,'entidad_mercantil','submit, idEmpresa, id_Departamento, ','','insert','');
			$id_empresa = $clase_database->obtenerId($link,'nit','entidad_mercantil');
		}
		
		if ($resultado){ 
			$mensaje = "Empresa Almacenada Correctamente";
			$clase_css = "texto_ok";
		}else{
			$mensaje = "Error al Almacenar Empresa";
			$clase_css = "texto_error";
		}	
	}
	
	function escribir($var){ echo $varr = isset($var) ? $var : ""; }
	
?>
<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<meta name="Author" content="Universidad Don Bosco">
	<title><? echo $title; ?> - Gesti&oacute;n de Empresas</title>
	<link rel="stylesheet" href="../css/estilos.css" type="text/css">
	<script src="../js/avg_ls_dom.js" type="text/javascript"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
    <script src="../http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
	<script src="../js/jquery.maskedinput.js" type="text/javascript"></script>
    <script type="text/javascript" language="javascript" src="../js/validator.js"></script>
    
    <style type="text/css">
		label { width: 10em; float: left; }
		label.error { float: none; color: black; padding-left: .5em; vertical-align: top; border:#C63 thin dashed; background-color:#F9C; }
		.submit { margin-left: 12em; }
		em { font-weight: bold; padding-right: 1em; vertical-align: top; }
    </style>
  <script>
	  $(document).ready(function(){
		$("#frm").validate();
	  });
	  
	  jQuery(function($){
	   $("#Telefono").mask("9999-9999");
	   $("#NIT").mask("9999-999999-999-9");  
	});
	
	$(document).ready(function(){
		$("#Departamentos").change(function(){
			$("#Municipios").html('<option selected="selected" value="0"> ..:: Cargando ::..</option>')
			$.post("includes/combos.php",{
				   id:$(this).val()
			 },function(data){
				   $("#Municipios").html(data);    
			 })
		})       
	})
</script>
    
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
    
<form name="frm" id="frm" action="<?=$enlace_gestion;?>" method="post" style="margin:0px;">    
      <table border="0" cellpadding="0" cellspacing="0">
        <tbody><tr><td height="10"></td></tr>
        <tr><td style="padding-right: 14px;" align="right"><table align="right" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="20" valign="middle"><a href="index.php"><img src="../images/volver-menu.gif" border="0" height="16" width="14"></a></td><td style="padding-right: 40px;" height="20" valign="middle"><a href="empresas-listado.php" class="texto_volver_inicio">&nbsp;Volver a la p&aacute;gina de Listado de Empresas</a></td><td height="20" valign="middle"><a href="../clases/cerrar_sesion.php"><img src="../images/menu-cerrar-sesion.gif" border="0" height="18" width="18"></a></td><td height="20" valign="middle"><a href="../clases/cerrar_sesion.php" class="texto_volver_inicio">&nbsp;Cerrar sesi&oacute;n</a></td></tr></tbody></table></td></tr>
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
                                  <a href="empresas-listado.php"><img src="../images/icono-empresas.gif" border="0"></a>
                                </td>
                                <td width="20"><img src="../images/transparente.gif" height="1" width="20"></td>
                                <td class="titulo_modulo" align="left" width="100%">Gesti&oacute;n de Empresas</td>
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
						  
						  <?
							if($id_empresa != "" || $id_empresa != "0"){
								$result = mysql_query("SELECT * FROM empresas WHERE nit = ".$id_empresa, $link);
								while($fila = mysql_fetch_array($result)){
									$idEmpresa = $fila['nit'];
									$contribuyente = $fila['nombre'];
									//$nombre_comercial = $fila['Nombre_Comercial'];
									//$actividad = $fila['Actividad_Economica'];
								//	$direccion = $fila['Direccion'];
									//$telefono = $fila['Telefono'];
									//$municipio = $fila['id_Municipio'];
									//$NRC = $fila['NRC'];
									$NIT = $fila['nit'];
								}
								
								$result = mysql_query("SELECT a.id_departamento FROM departamentos a 
									INNER JOIN municipios b ON a.id_departamento=b.id_Departamento WHERE b.id_Municipio = ".$municipio, $link);
								while($fila = mysql_fetch_array($result)){
									$departamento = $fila['id_departamento'];
								}
							}
						?>     
					  
							<div class="texto_explicacion_formulario">Nombre de Contribuyente:</div>
							<div>
								<input name="nombre" id="" style="width: 928px;" type="text" value="<? echo isset($contribuyente) ? $contribuyente : "";?>" class="required" title="Ingrese el Nombre del Contribuyente">
								<input name="idEmpresa" id="" type="hidden" value="<? echo isset($idEmpresa) ? $idEmpresa : "";?>";>
							</div>
							<?php 
							 //MIENTRAS NO SE GUARDAN MAS DATOS SE OCULTARAN DEL FORMULARIO 
							 /*<div class="texto_explicacion_formulario">Nombre Comercial:</div>
							<div>
								<input name="Nombre_Comercial" id="" style="width: 928px;" type="text" value="<? echo isset($nombre_comercial) ? $nombre_comercial : "";?>" class="required" title="Ingrese el Nombre Comercial de la Empresa">
							</div>
							<div class="texto_explicacion_formulario">N&uacute;mero de Registro de Contribuyente (NRC):</div>
							<div>
								<input name="NRC" id="" rows="1" style="width: 928px;" value="<? echo isset($NRC) ? $NRC : "";?>" type="text" class="required" title="Ingrese el N&uacute;mero de Registro de Contribuyente en formato ######-#">
							</div>
							 
							 */ 
							 ?>
							<div class="texto_explicacion_formulario">N&uacute;mero de Identidad Tributaria (NIT):</div>
							<div>
								<input name="NIT" id="NIT" rows="1" style="width: 928px;" type="text" value="<? echo isset($NIT) ? $NIT : "";?>" class="required" title="Ingrese el N&uacute;mero de Identidad Tributario de Contribuyente en formato ####-######-###-#">
							</div>
							<?php 
							 //MIENTRAS NO SE GUARDAN MAS DATOS SE OCULTARAN DEL FORMULARIO 
							 /*
							<div class="texto_explicacion_formulario">Actividad Econ&oacute;mica:</div>
							<div> 
								<textarea name="Actividad_Economica" rows="2" style="width: 928px;" id="" class="required" title="Ingrese la Actividad Econ&oacute;mica de la Empresa"><? echo isset($actividad) ? $actividad : "";?></textarea>
							</div>
                            <div class="texto_explicacion_formulario">Departamento:</div>
							<div>
                            	<select id="Departamentos" name="id_Departamento" class="required" title="Ingrese departamento">
                                	<?
                                    	$result = mysql_query("SELECT * FROM departamentos ORDER BY Nombre_Departamento", $link);
										while($fila = mysql_fetch_array($result)){
											if(isset($departamento) && $fila['id_departamento'] == $departamento){
											?>
												<option value="<?=$fila['id_departamento']?>" selected="selected" ><?=$fila['Nombre_Departamento']?></option>
											<?
											}else{ ?>
												<option value="<?=$fila['id_departamento']?>" ><?=$fila['Nombre_Departamento']?></option>
											<? }
										}
									?>
                                </select>
							</div>
							<div class="texto_explicacion_formulario">Municipio:</div>
							<div>
                            	<select id="Municipios" name="id_Municipio" class="required" title="Ingrese municipio">
                                	<?
										if(isset($departamento)){
											$result = mysql_query("SELECT * FROM municipios WHERE id_Departamento = ".$departamento."  ORDER BY Nombre_Municipio", $link);
											while($fila = mysql_fetch_array($result)){
												if(isset($municipio) && $fila['id_Municipio'] == $municipio){
												?>
													<option value="<?=$fila['id_Municipio']?>" selected="selected" ><?=$fila['Nombre_Municipio']?></option>
												<?
												}else{ ?>
													<option value="<?=$fila['id_Municipio']?>" ><?=$fila['Nombre_Municipio']?></option>
												<? }
											}
										}
									?>
                                </select>
							</div>
							<div class="texto_explicacion_formulario">Direcci&oacute;n:</div>
							<div>
								<input name="Direccion" id="" rows="1" style="width: 928px;" type="text" value="<? echo isset($direccion) ? $direccion : "";?>" class="required" title="Ingrese la direcci&oacute;n de la Empresa">
							</div>
							<div class="texto_explicacion_formulario">Tel&eacute;fono:</div>
							<div>
								<input name="Telefono" id="Telefono" rows="1" style="width: 928px;" type="text" value="<? echo isset($telefono) ? $telefono : "";?>" class="required" title="Ingrese el N&uacute;mero de tel&eacute;fono de la Empresa">
							</div>
							 
							  */
							  ?>
							<div><input name="submit" id="submit" value="Guardar" type="submit"></div>
                          </td>
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
      </tbody></table>    
</form>
 <script type="text/javascript" language="javascript">
$("#frm :input").tooltip({
          effect: 'fade',
          fadeOutSpeed: 100,
         predelay: 400,
          position: "center center",
          offset: [-30, -60],
		  opacity: 0.9
 
      });
 
</script>

    </td>
  </tr>
  <tr><td class="fondo_login_abajo_menu"></td></tr>
  <tr><td class="texto_copyright" align="right" height="44" valign="middle"><?=$copyright; ?></td></tr>
</tbody></table>
</center>
<? include_once("../includes/barra_menu.php");?>
</body></html>
<?
	$conexion->desconectar($link);
?>