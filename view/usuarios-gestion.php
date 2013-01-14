<?php
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


		$direccion = "Location: index.php";
	if(!$_SESSION["autenticado_admin"]){//Solamente si esta debidamente autenticado
		header($direccion);	
	}
	
	
	include_once("../clases/config.php");
	include_once("../clases/conexion.php");
	include_once("../clases/usuario.php");	
	$enlace_listado = "usuarios-listado.php";
	$cerrar = "../clases/cerrar_sesion.php";
	
	$usuario = new usuario();
	$conexion = new conexion();
	$enlace = $conexion->conectar();

	$id_usuario=hideunlock($_GET['id']);	
	$aux_id=$id_usuario;
//SI SE PRESIONA GUARDAR PARA NUEVO USUARIO	
if (($_POST['submit']=='Guardar') && !isset($id_usuario)){	
		$accion="Guardar";
		//$sql_modulo = "SELECT COUNT(*) as total FROM modulo";
		//$row_modulo = mysql_fetch_array(mysql_query($sql_modulo, $enlace));
		//$nchecks = $row_modulo['total'];	

			/*for($i=1;$i<=$nchecks;$i++){
				$nombrech="modulo$i";
       			if(!isset($_POST[$nombrech])){ 		
        			 $modulo[$i] = "";	//NO se selecciono					
				}else{
					 $modulo[$i] = 'Checked="true"';	//SI se selecciono					
				}
				//echo "<br>";
			}*/
			
		if($_POST['id_tipo_usuario'] == '1'){
			$tipoUsuario[1] = 'Checked="true"'; //Si usuario es administrador habilita ch
			$tipoUsuario[2] = '';
			$_POST['tipo_usuario']="1";
		}
		else{
			$tipoUsuario[2] = 'Checked="true"';
			$tipoUsuario[1] = '';
			$_POST['tipo_usuario']="2";
		}		
		if (($_POST['alias_usuario'] == '')||($_POST['nombre_usuario'] == '') || ($_POST['apellido_usuario'] == '') || ($_POST['email_usuario'] == '') || ($_POST['password'] == '')) {		
			
		}
		else{				
			$res=$usuario->NuevoUsuario($enlace,'insert');
			?>
            
	    <script language="javascript">
           	if(<? echo $res; ?>==false) {
                alert("Error: Usuario existente!");
          }
        </script>
			<?
			//$usuario->NuevoPrivilegio($enlace);
		} 
	}
	//SI SE PRESIONA ACTUALIZAR PARA USUARIO EXISTENTE	
elseif (($_POST['submit']=='Actualizar') && isset($id_usuario)){	
	//actualiza datos de usuario predeterminado
	$accion="Actualizar";
	/*$nchecks=6;
	for($i=1;$i<=$nchecks;$i++){
		$nombrech="modulo$i";
       	if(!isset($_POST[$nombrech])){ 		
        		$modulo[$i] = "";	//NO se selecciono					
		}else{
				$modulo[$i] = 'Checked="true"';	//SI se selecciono					
		}
		}*/
		
		if($_POST['id_tipo_usuario'] == '1'){
			$tipoUsuario[1] = 'Checked="true"';
			$tipoUsuario[2] = '';
			$_POST['tipo_usuario']="1";
		}
		else{
			$tipoUsuario[2] = 'Checked="true"';
			$tipoUsuario[1] = '';
			$_POST['tipo_usuario']="2";
		}		
		if (($_POST['alias_usuario'] == '')||($_POST['nombre_usuario'] == '') || ($_POST['apellido_usuario'] == '') || ($_POST['email_usuario'] == '') || ($_POST['password'] == '')) {		
			
		}
		else{	
			$res=$usuario->ActualizarUsuario('usuario',$id_usuario, $enlace);
			?>
            
	    <script language="javascript">
           	if(<? echo $res; ?>==false) {
                alert("Error: Usuario existente con dichos parametros!");
          }
        </script>
			<?	
			//$usuario->ActualizarPrivilegio('privilegio',$enlace);
		} 	
} //CARGA PAGINA PARA ENLISTAR UN NUEVO USUARIO
elseif(!isset($id_usuario) || $aux_id=""){
		$accion = "Guardar";
		$_POST['tipo_usuario']="";
		$tipoUsuario[1] ='Checked="true"';
		$tipoUsuario[2] = '';
		$_POST['alias_usuario']	= "";
		$_POST['nombre_usuario'] == "";
		$_POST['apellido_usuario'] == "";
		$_POST['direccion_usuario'] == "";
		$_POST['email_usuario']	= "";
		$_POST['password'] == "";
		$modulo[]="";			
	}//CARGA PAGINA CON DATOS DE USUARIO A ACTUALIZAR
elseif(isset($id_usuario)){	
		$enlace_gestion = "usuarios-gestion.php?id=".$id_usuario;
		$sql_usuario = "Select usuario Alias,Nombres,Apellidos,clave password from usuarios
where usuario='".$id_usuario."'";
$result = mysql_query($sql_usuario, $enlace);
$filas = mysql_fetch_array($result);
        $_POST['alias_usuario'] = $filas['Alias'];
		$_POST['nombre_usuario'] = $filas['Nombres'];
		$_POST['apellido_usuario'] = $filas['Apellidos'];
		$_POST['email_usuario'] = $filas['Email'];
		$_POST['direccion_usuario'] = $filas['Direccion'];
		$_POST['password'] = $filas['password'];
		$_POST['tipo_usuario'] = $filas['Tipo_Usuario'];
		
		if($_POST['tipo_usuario'] == '1'){
			$tipoUsuario[1] = 'Checked="true"';
			$tipoUsuario[2] = '';
		}else{
			$tipoUsuario[2] = 'Checked="true"';
			$tipoUsuario[1] = '';
		}		
		
		/*$sql_modulo = "SELECT COUNT(*) as total FROM modulo";
		$row_modulo = mysql_fetch_array(mysql_query($sql_modulo, $enlace));
		$n = $row_modulo['total'];
		//MODULOS
			$sql_modulo_padre = "SELECT id_modulo 
FROM  modulo WHERE id_modulo_padre=0";
			$res_modulo_padre = mysql_query($sql_modulo_padre,$enlace);
				  $i=0;*/
				  /*while($modulos_padre = mysql_fetch_array($res_modulo_padre)){
						$i++;
						$modulobd[$i] = $modulos_padre['id_modulo'];
						//Mostrar Modulo padre	
						$sql_modulo_hijo="SELECT id_modulo 
FROM  modulo WHERE id_modulo_padre=".$modulos_padre['id_modulo'];
						$res_modulo_hijo = mysql_query($sql_modulo_hijo,$enlace);	
				  while($modulos_hijo = mysql_fetch_array($res_modulo_hijo)){
						$i++;
						//Mostrar Modulo hijo
						$modulobd[$i]=$modulos_hijo['id_modulo'];
				  }}
				  */
		/*for($k=1;$k<=$n;$k++){
			$sql_permiso = "SELECT permiso FROM privilegio WHERE id_usuario=".$id_usuario." AND id_modulo=".$modulobd[$k];
			$res_permiso = mysql_query($sql_permiso,$enlace);
			while($permiso = mysql_fetch_array($res_permiso)){
	    	if($permiso["permiso"]=='si'){
							$modulo[$k] = 'Checked="true"'; //checkeado
						}else{
							$modulo[$k] = "";
						}
			}
		}*/
		$accion = "Actualizar";
	}
	else{
	//si no actualiza o no presiona boton guardar
		$modulo[] ="";
		$tipoUsuario[1] ='Checked="true"';
		$tipoUsuario[2] = '';
		$accion = "Guardar";
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title><? echo $title; ?></title>
	<link rel="stylesheet" href="../css/estilos.css" type="text/css">
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
    <link href="fckeditor/_samples/sample.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="fckeditor/fckeditor.js"></script>
    
    <script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
    
        <script type="text/javascript" src="fckeditor/validator.js"></script>

  <script>
  $(function(){
       $('#frm').validate({
           rules: {          
           'email_usuario': { required: true, email: true },
		   'password':{ maxlength: 15},
		   'alias_usuario':{ required: true, maxlength: 10}
           },
       messages: {           
          'email_usuario': { required: 'Campo Obligatorio', email: 'Ingrese una direccion de correo valida'  },
		  'password': {maxlength: 'Maximo 15 caracteres'},
		  'alias_usuario': {required: "Campo obligatorio", maxlength: 'Maximo 10 caracteres'}
	   },
    });
});
  </script>
    
    
</head>
<body>

<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tbody><tr><td height="22">&nbsp;</td></tr>
  
  <tr><td class="cabecera">
  		<div class="titulo_dominio" style="padding-top:5px;padding-right:25px;" align="right">
  			<a href="../" target="_blank"><? echo $nombre_institucion;?></a>
        </div>
  </td></tr>
  
  <tr>
    <td valign="top" class="fondo_menu">
    
<form name="frm" id="frm" action="<? echo $enlace_gestion; ?>" method="post" style="margin:0px;">    
      <table border="0" cellspacing="0" cellpadding="0">
        <tbody><tr><td height="10"></td></tr>
        <tr><td align="right" style="padding-right:14px;">
       
        <table align="left" border="0" cellpadding="0" cellspacing="0">
							<tr>
							<td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>
							<td><img src="../images/logo.png" border="0" height="65" width="150"></td>
							<td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>
							<td><img src="../images/va.png" border="0" height="60" width="85"></td>
							</tr>
		</table>
        	
        <table border="0" cellspacing="0" cellpadding="0" align="right"><tbody><tr>
        	<td valign="middle" height="20"><a href="<? echo $enlace_menu; ?>"><img src="../images/volver-menu.gif" border="0" height="16" width="14"></a></td>
        	<td style="padding-right: 40px;" height="20" valign="middle"><a href="<? echo $enlace_listado; ?>" class="texto_volver_inicio">&nbsp;Volver a listado de usuarios</a></td>
        	<td height="20" valign="middle"><a href="<? echo $cerrar; ?>"><img src="../images/menu-cerrar-sesion.gif" border="0" height="18" width="18"></a></td>
        	<td height="20" valign="middle"><a href="<? echo $cerrar; ?>" class="texto_volver_inicio">&nbsp;Cerrar sesi&oacute;n</a></td></tr></tbody>
        </table>
        </td></tr>
        <tr><td class="menu_interior_arriba">&nbsp;</td></tr>
        <tr>
          <td valign="top" align="center">
            <table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
              <tbody><tr>
                <td class="menu_fondo" align="center" valign="top">
                  <table width="930" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tbody><tr>
                      <td valign="top">

                      <table width="928" border="0" cellspacing="0" cellpadding="0" align="center">  
                        <tbody><tr>
                          <td height="12"></td>
                        </tr>
                        <tr>
                          <td valign="middle">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="80">
                              <tbody><tr>
                                <td width="8"><img src="./Panel de Administraci&oacute;n7_files/transparente.gif" width="8" height="1"></td>
                                <td valign="middle">
                                  <a href="<? echo $enlace_listado; ?>"><img src="../images/icono-usuarios.gif" border="0"></a>
                                </td>
                                <td width="20"><img src="./Panel de Administraci&oacute;n7_files/transparente.gif" width="20" height="1"></td>
                                <td width="100%" align="left" class="titulo_modulo">Administraci&oacute;n de Usuarios</td>
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
        <tr><td class="menu_interior_arriba">&nbsp;</td></tr>
        <tr>
          <td valign="top" align="center">
            <table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
              <tbody><tr>
                <td class="menu_fondo" align="center" valign="top">
                  <table width="930" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tbody><tr>
                      <td valign="top">           
                      
                      <table width="928" border="0" cellspacing="0" cellpadding="0" align="center">  
                        <tbody><tr>
                          <td valign="top" style="padding:14px;">
                          	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"> 
                            	<tbody><tr>
                                	<td width="50%">                                   
<div class="texto_explicacion_formulario"><span style="color: #F00"> </span> Datos de Usuario: <? echo hideunlock($_GET['id']); ?>
  &nbsp;<span style="font-weight:normal;color:#000000;"><strong id="usuario"></strong></span></div>  
  <div class="texto_explicacion_formulario">Alias:</div>  
<div><input name="alias_usuario" type="text" class="required" style="width:420px;" id="alias_usuario"  value="<? echo $_POST['alias_usuario']; ?>" maxlength="25" title="Ingrese un alias para iniciar sesi&oacute;n"></div>
<div class="texto_explicacion_formulario">Nombre:</div>  
<div><input name="nombre_usuario" type="text" class="required" style="width:420px;" id="nombre_usuario"  value="<? echo $_POST['nombre_usuario']; ?>" maxlength="25" title="Ingrese el nombre del usuario"></div>
<div class="texto_explicacion_formulario">Apellidos:</div>  
<div><input name="apellido_usuario" type="text" class="required" style="width:420px;" id="apellido_usuario" value="<? echo $_POST['apellido_usuario']; ?>" maxlength="25" title="Ingrese el apellido del usuario"></div>
<div class="texto_explicacion_formulario"> E-Mail:<br /><span id="mensaje" style="color:#F00"></span></div>  
<div><input name="email_usuario" type="text" class="required" style="width:420px;" id="email_usuario"   value="<? echo $_POST['email_usuario']; ?>" maxlength="30" title="Ingrese una direccion de correo valida"></div>
<div class="texto_explicacion_formulario">Direcci&oacute;n:</div>  
<div><textarea name="direccion_usuario" id="direccion_usuario" title="Ingrese direccion asociada" style="width:420px;" rows="2"><? echo $_POST['direccion_usuario']; ?></textarea></div>
<div class="texto_explicacion_formulario" >Password (M&aacute;ximo 15 caracteres):<br /><span id="contador" style="color:#F00"></span></div> 
<div><input type="password" name="password" id="password" class="required" style="width:420px;" maxlength="16" value="<? echo $_POST['password']; ?>" onKeyPress=" return limita(this, event,15)"  title="Ingrese un password para resguardar su cuenta usuario"> </div>
<div style="height:15px;"></div>

<div class="texto_explicacion_formulario" style="font-weight:bold;color:#000000;">Tipo de Usuario:</div>


<div class="texto_explicacion_formulario" style="color:#000000;font-weight:normal;"><input name="id_tipo_usuario" type="radio" id="id_tipo_usuario" title=" " value="1" <? echo $tipoUsuario[1]; ?> >&nbsp;Administrador</div>

<div class="texto_explicacion_formulario" style="color:#000000;font-weight:normal;"><input type="radio" id="id_tipo_usuario" title=" " name="id_tipo_usuario" value="2" <? echo $tipoUsuario[2]; ?> >&nbsp;Registrado</div>

			<div class="texto_explicacion_formulario"></div>
			<div><br />
							  <input name="submit" id="submit" value="<? echo $accion; ?>" type="submit">
							</div>
									</td>
                                    <td valign="top">
                                    	<div align="center" style="padding-top:50px">
                                        														
                                    		<img src="../images/avatar-usuarios.jpg" width="200" border="0">
                                        </div>
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
            </tbody></table> 
          </td>
        </tr>
        <tr><td class="menu_abajo">&nbsp;</td></tr>
      </tbody></table>    
</form>
    
<script type="text/javascript" language="javascript">
   $(document).ready(
   		function(){
			var info = document.getElementById('mensaje'); 	
	 		info.innerHTML = "";
			var cont = document.getElementById('contador');
			cont.innerHTML = "";
				
		}
   );
		function validarEmail(valor) 
		{
			if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(valor))
			{
				var men = document.getElementById('mensaje');
				//men.innerHTML = "La direcci&oacute;n de email " + valor + " es correcta.";				
			}
			else
			{
				var men = document.getElementById('mensaje');
				men.innerHTML = "La direcci&oacute;n de email " + valor + " no es correcta."
				var limp = document.getElementById('email_usuario');
				limp.value = ""; 
			}
		}

		function cuenta(obj,evento,maxi,div)  
		{  
	 		var elem = obj.value;  
	 		var info = document.getElementById(div); 	
	 		info.innerHTML = "Quedan " + (maxi-elem.length) + " caracteres";  
		}
		function limita(obj,elEvento, maxi)  
		{  
   			var elem = obj;  
  		    var evento = elEvento || window.event;  
   			var cod = evento.charCode || evento.keyCode;  
     		// 37 izquierda  
     		// 38 arriba  
     		// 39 derecha  
     		// 40 abajo  
     		// 8  backspace  
     		// 46 suprimir  
   			if(cod == 37 || cod == 38 || cod == 39  || cod == 40 || cod == 8 || cod == 46)  
   			{  
     			return true;  
   			}  
   			if(elem.value.length < maxi )  
   			{  
     			return true;  
   			}  
   				return false;  
		} 
$("#frm :input").tooltip({

      //tip: '.tooltip',
 
          // use the fade effect instead of the default
          effect: 'fade',
 
          // make fadeOutSpeed similar to the browser's default
          //fadeOutSpeed: 100,
 
          // the time before the tooltip is shown
         // predelay: 400,
 
          // tweak the position
          position: "center center",
          offset: [-30, -60],
		  opacity: 0.7
 
      });		
</script>
    </td>
  </tr>
  <tr><td class="fondo_login_abajo_menu"></td></tr>
  <tr><td height="44" align="right" valign="middle" class="texto_copyright"><? echo $copyright; ?></td></tr>
</tbody></table>
</center>



<? include_once("../includes/barra_menu.php");?>
</body></html>