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

		

	//$link = "Location: ".$enlace_login;

	$direccion = "Location: index.php";

	$cerrar = "../clases/cerrar_sesion.php";

	$enlace_listado = "usuarios-listado.php";

	$enlace_gestion = "usuarios-gestion.php";

	

	if($_SESSION["autenticado_admin"]){

		if($_SESSION["autenticado_admin"]=="si"){ //Solamente si esta debidamente autenticado

			include_once("../clases/config.php");

			include_once("../clases/conexion.php");

			include_once("../clases/usuario.php");

			$conexion = new conexion();

			$enlace = $conexion->conectar();

			$usuario = new usuario();

	

			if($_GET['accion'] == "eliminar"){

				$usuario->EliminarUsuario($_GET['id'],$enlace);

			}

		}else{

			header($direccion);	

			}

	}else{

		header($direccion);	

	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
<meta http-equiv="X-UA-Compatible" content="IE=8" >
		<meta http-equiv="X-UA-Compatible" content="IE=7" >
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

	<title><? echo $title; ?> - Listado de Usuarios</title>

	<link rel="stylesheet" href="../css/estilos.css" type="text/css">

	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>

    <link href="../fckeditor/_samples/sample.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>

</head>

<body>



<center>

<table border="0" cellspacing="0" cellpadding="0">

  <tbody><tr><td height="22">&nbsp;</td></tr>

  

  <tr><td class="cabecera">

  		<div class="titulo_dominio" style="padding-top:5px;padding-right:25px;" align="right">

  			<a href="<? echo $url_absoluta; ?>" target="_blank"><? echo $nombre_institucion; ?></a>

        </div>

  </td></tr>

  

  <tr>

    <td valign="top" class="fondo_menu">

    

<form name="frm" id="frm" action="<? echo $enlace_listado; ?>" method="post" style="margin:0px;">    

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
        	
        	<table border="0" cellspacing="0" cellpadding="0" align="right">
        	<tbody><tr><td valign="middle" height="20">
        		<a href="../<? echo $enlace_menu; ?>"><img src="../images/volver-menu.gif" border="0" height="16" width="14"></a></td>
        		<td valign="middle" height="20" style="padding-right:40px;"><a href="../<? echo $enlace_menu; ?>" class="texto_volver_inicio">&nbsp;Volver al panel de administraci&oacute;n</a></td>
        		<td valign="middle" height="20"><a href="<? echo $cerrar; ?>"><img src="../images/menu-cerrar-sesion.gif" border="0" height="18" width="18"></a></td>
        		<td valign="middle" height="20"><a href="<? echo $cerrar; ?>" class="texto_volver_inicio">&nbsp;Cerrar sesi&oacute;n</a></td></tr></tbody>
        		
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

                                <td width="8"><img src="../noti_files/transparente.gif" height="1" width="8"></td>

                                <td valign="middle">

                                  <a href="../index.php"><img src="../images/icono-usuarios.gif" border="0"></a>

                                  </td>                                  

                                <td width="20"><img src="../noti_files/transparente.gif" height="1" width="20"></td>

                                <td width="100%" align="left" class="titulo_modulo">Administraci&oacute;n de Usuarios</td>

                                <td align="right" valign="middle">   

                                

  <table border="0" cellspacing="0" cellpadding="0">

    <tbody><tr>

      <td align="center" style="padding-left:10px;padding-right:10px;"><a href="usuarios-gestion.php"><img src="../images/menu-crear.gif" border="0"></a></td>

    </tr>

    <tr>

      <td align="center" style="padding-left:10px;padding-right:10px;"><a href="usuarios-gestion.php" class="menu_opcion"><nobr>Nuevo</nobr></a></td>

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

                          <td valign="top">

    

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

  <tbody><tr bgcolor="#EBEBEB">

    <td width="42" class="tabla_titulo" align="center" height="34" valign="middle" style="border-top:1px solid #E2E2E2;border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><strong>#</strong></td>

    <td width="100" class="tabla_titulo" align="center" height="34" valign="middle" style="border-top:1px solid #E2E2E2;border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><strong>USUARIO</strong></td>

    <td width="120" class="tabla_titulo" align="center" height="34" valign="middle" style="border-top:1px solid #E2E2E2;border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><strong>NOMBRES</strong></td>

    <td width="120" class="tabla_titulo" align="center" height="34" valign="middle" style="border-top:1px solid #E2E2E2;border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><strong>APELLIDOS</strong></td>

    <td width="200" class="tabla_titulo" align="center" height="34" valign="middle" style="border-top:1px solid #E2E2E2;border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><strong>DESCRIPCION</strong></td>
    
    <td width="120" class="tabla_titulo" align="center" height="34" valign="middle" style="border-top:1px solid #E2E2E2;border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><strong>PERFIL</strong></td>

    <td width="50" class="tabla_titulo" align="center" height="34" valign="middle" style="border-top:1px solid #E2E2E2;border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><strong>EDITAR</strong></td>

    <td width="68" class="tabla_titulo" align="center" height="34" valign="middle" style="border-top:1px solid #E2E2E2;border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><strong>ELIMINAR</strong></td>

  </tr>

   <?

  	$sql_usuarios = "SELECT usuario,Nombres,Apellidos,usuarios.descripcion,perfiles.nombre perfilado from usuarios inner join perfiles on usuarios.perfil=perfiles.codigo";

	$result = mysql_query($sql_usuarios,$enlace);

	$contador = 0;

	while($filas = mysql_fetch_array($result)){

		$contador++;

	?>

      <tr bgcolor="#FBFBFB">

    <td class="tabla_filas" align="center" height="34" valign="middle" style="border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><? echo $contador;?></td>

    <td class="tabla_filas" align="center" height="34" valign="middle" style="border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><? echo $filas['usuario'];?></td>

    <td class="tabla_filas" align="center" height="34" valign="middle" style="border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><? echo $filas['nombres'];?></td>

    <td class="tabla_filas" align="center" height="34" valign="middle" style="border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><? echo $filas['Apellidos'];?></td>

    <td class="tabla_filas" align="center" height="34" valign="middle" style="border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><? echo $filas['Descripcion'];?></td>
    
    <td class="tabla_filas" align="center" height="34" valign="middle" style="border-left:1px solid #E2E2E2;border-bottom:1px solid #E2E2E2;"><? echo $filas['perfilado'];?></td>

    <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><a href="<?php echo $enlace_gestion?>?id=<?php echo hidelock($filas['usuario']);?>"><img src="../images/icono-editar.gif" border="0"></a></td>

   <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226); border-right: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><a href="javascript:eliminar('<?php echo hidelock($filas['usuario']);?>');"><img src="../images/icono-eliminar.gif" border="0"></a></td>

  </tr>

  <?

	}

	

  ?>

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

<input name="total" id="total" value="8" type="hidden">

<input name="accion" id="accion" value="" type="hidden">

<input id="__EVENTTARGET" name="__EVENTTARGET" type="hidden">      

</form>

<script type="text/javascript">

  function eliminar(id)

  {

	if (confirm('Desea realmente eliminar el registro seleccionado?'))

	{

      //document.location.href='usuarios-listado.php?accion=eliminar&id='+id;

	  document.location.href='<?php echo $enlace_listado?>?accion=eliminar&id='+id;

	}

  }

</script>

<script type="text/javascript">

  var marcado=false;

 

  function marcar()

  {

    var to=document.getElementById('total').value;

 

    for(i=1;i<=to;i++)

	{

      if(marcado)

	  {

        document.getElementById('check_id_'+i).checked=false;

      }

	  else

	  {

        document.getElementById('check_id_'+i).checked=true;

      }

    }

    if(marcado){marcado=false;}else{marcado=true;}

  }

</script>

<script type="text/javascript">

<!--

var theForm=document.getElementById("frm");



function __doSubmit(eventTarget) 

{

   if (!theForm.onsubmit || (theForm.onsubmit() != false))

   {

     theForm.__EVENTTARGET.value = eventTarget;

	 theForm.submit();

   }

}

// -->

</script>

 </td>

  </tr>

  <tr><td class="fondo_login_abajo_menu"></td></tr>

  <tr><td class="texto_copyright" align="right" height="44" valign="middle"><? echo $copyright; ?></td></tr>

</tbody></table>

</center>
<? include_once("../includes/barra_menu.php");?>

</body></html>