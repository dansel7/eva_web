<?php
session_start();
error_reporting(0);

	if(isset($_POST["cs"])){
	$_SESSION = array();
	session_destroy();
	}
	
	if(isset($_SESSION["usuario"])){
			$link = "Location:index.php";
			header($link);
	}
	
	include_once("clases/config.php");
	$mensaje="";
	
	if(isset($_SESSION["autenticado_admin"])){
		if($_SESSION["autenticado_admin"]=="si"){
			$link = "Location: view/index.php";
			header($link);	
		}
	}else{
		
		include_once("clases/conexion.php");
		include_once("clases/usuario.php");
		
		if (isset($_POST['txt_usuario'])){		
			
			$enlace_index = "../index.php";
			
			$conexion = new conexion();
			$usuario = new usuario();
			$enlace = $conexion->conectar();
			$sq = $usuario->LoginUsuario($_POST['txt_usuario'],$_POST['txt_password']);
			
			$result = mysql_query($sq,$enlace);
			
			$cantidad = mysql_num_rows($result);
			
			if ($cantidad == 1){
				while ($row = mysql_fetch_array($result))			
				{
					 //variables de sesion para control de usuario
					$_SESSION["nombre_usuario"]=$row["nombre"];
					//$_SESSION["apellido"] = $row["apellido"];
					$_SESSION["perfil"]=$row["perfil"];
					//$_SESSION["id_usuario"] = $row["id"];
					//$_SESSION["id_tipo"] = $row["tipo"];
					//$_SESSION["password"]=$_POST['txt_password'];
					$_SESSION["usu"]=$_POST['txt_usuario'];//usuario
					$_SESSION['timeout'] = time();
				}

				if($_SESSION["perfil"]=="17"){
					$_SESSION["autenticado_admin"] = "si";
					$link = "Location: index.php";
					header($link);
				}else{
					$_SESSION["autenticado_admin"] = "no";
					$mensaje="No tiene permisos para acceder.";
					session_destroy();
				}
				
			}else{//ACA ENTRA SI LA CLAVE O USUARIO ES ERRONEA
		
				$mensaje="Usuario o Contrase&ntilde;a invalidos";
				
			}
		}
		else{
	
			$mensaje="";
		
		}
	}
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<head>
	<link rel="stylesheet" href="css/login.css" type="text/css">
	<title><? echo $title; ?></title>
</head>

<body onLoad="document.forms.frm_login.txt_usuario.focus();">

<form name="frm_login" id="frm_login" action="#" method="POST" style="margin:0px;">

<center>
<? //echo $sq; ?>
<table border="0" cellpadding="0" cellspacing="0">
  <tbody>
  	<tr>
    	<td height="22">&nbsp;</td>
    </tr>
    <tr>
    	<td class="cabecera">
        	<div class="titulo_dominio" style="padding-top:5px;padding-right:25px;" align="right">
  				<? echo $nombre_institucion; ?>
  			</div>
        </td>
    </tr>
  	<tr>
    <td class="fondo_login" valign="top">
    
			            	<table align="left" style="margin-left: -14px" border="0" cellpadding="0" cellspacing="0">
							<tr>
							<td class="menu_separador_1"><img src="images/transparente.gif" height="5" width="14"></td>
							</tr>
							<tr>
							<td class="menu_separador_1"><img src="images/transparente.gif" height="1" width="14"></td>
							<td><img src="images/logo.png" border="0" height="90" width="165"></td>
	
							</tr>
							</table>
							
							<table align="right" border="0" cellpadding="0" cellspacing="0">
							<tr>
							<td class="menu_separador_1"><img src="images/transparente.gif" height="5" width="14"></td>
							</tr>
							<tr>
							<td class="menu_separador_1"><img src="images/transparente.gif" height="1" width="14"></td>
							<td><img src="images/va.png" border="0" height="110" width="165"></td>
							</tr>
							</table>
							<br><br>
      <div class="titulo_acceder_panel_admin" style="padding-top:50px;padding-left:450px;">Acceso al Sistema</div>
      <div class="titulo_texto_login" style="padding-top:60px;padding-left:415px;">Usuario</div>
      <div style="padding-top:2px;padding-left:415px;">
        <input name="txt_usuario" id="txt_usuario" class="txt_login" autocomplete="off" type="text" />
      </div>
      <div class="titulo_texto_login" style="padding-top:5px;padding-left:415px;">Contrase&ntilde;a</div>
      <div style="padding-top:2px;padding-left:415px;"><input name="txt_password" id="txt_password" value="" class="txt_login" type="password"><br /><span style="color:#F00;" class="titulo_texto_login"><? echo $mensaje; ?></span></div>

      <div style="padding-top:10px;padding-left:433px;"><a href="" class="texto_recordar">Olvid&oacute; su contrase&ntilde;a?</a></div>
      <?php // <div style="padding-top:2px;padding-left:433px;"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="20" valign="middle"><input name="txt_recordar" id="txt_recordar" value="recordar" type="checkbox"></td><td class="texto_recordar_password" height="20" valign="middle">&nbsp;Recordar Contrase&ntilde;a</td></tr></tbody></table></div> ?>

      <div style="padding-top:95px;padding-left:575px;">
      <input type="image" src="images/boton-acceder-login.gif" value="Acceder" id="image" name="image" >
      </div>
      <div style="padding-top:65px;padding-left:485px;"><table border="0" cellpadding="0" cellspacing="0"><tbody></tbody></table></div>
    </td>
  </tr>
  <tr><td class="fondo_login_abajo"></td></tr>
  <tr><td class="texto_copyright" align="right" height="44" valign="middle"> <? echo $copyright; ?></td></tr>
</tbody></table>
</center>
</form>
</body></html>