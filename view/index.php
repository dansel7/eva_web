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


	if($_SESSION["autenticado_admin"] == "si"){

		
	}else{

		$direccion = "Location: ../";

		header($direccion);

	}

	

	include_once("../clases/config.php");

	include_once("../clases/conexion.php");

	

	$conexion = new conexion();

	$link = $conexion->conectar();

	$cerrar = "../clases/cerrar_sesion.php";



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

	

	<title>Centro de Administraci&oacute;n</title>

	<link rel="stylesheet" href="../css/estilos.css" type="text/css">
<link href="../css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<script src="../js/jquery-1.8.3.js"></script>
	<script src="../js/jquery-ui-1.9.2.custom.js"></script>
    

        <script>
    // increase the default animation speed to exaggerate the effect
    $.fx.speeds._default = 1000;
    $(function() {
        $( "#dialog" ).dialog({
            autoOpen: false,
            show: "blind",
            hide: "explode",
            width:'700',
            height: '300'
        });
 
        $( "#opener1" ).click(function() {
            $( "#dialog" ).dialog( "open" );
            return false;
        });
        $( "#opener2" ).click(function() {
            $( "#dialog" ).dialog( "open" );
            return false;
        });
    });
    </script>
</head>

<body>
  <div id="dialog" title="Reportes" align="center">
<?php
if(isset($_SESSION["n_declaracion"])){
?>

<table align="center" border="0" cellpadding="0" cellspacing="0">

    		<tbody>
			<tr>
				
              <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			   <td align="center" valign="top"><div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div align="center"><a target="_blank" href="../reports/factura_diniciales.php"><img src="../images/icono-menus.gif" border="0"></a></div>

				<div style="height:30px;"><a target="_blank" href="../reports/factura_diniciales.php" class="modulo_titulo">Facturas Datos Iniciales</a></div>

			  </td>

		  	  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			                    

              <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>
                         
                         <td align="center" valign="top">
			  	<div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div align="center"><a target="_blank" href="../reports/factura_ordenadas.php"><img src="../images/icono-articulos.gif" border="0"></a></div>
				
				<div style="height:30px;"><a target="_blank" href="../reports/factura_ordenadas.php" class="modulo_titulo">Facturas Ordenadas</a></div>
			  </td>
			 

		  	  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			                    

			  </tr>
			  </tbody></table>

<?php
}else{?>
      <h2>Para visualizar un Reporte debe Abrir una Declaracion.<br> <a href="declaraciones-listado.php">Abrir</a></h2>    
<?php 
}
?>
</div>


<center>

<table border="0" cellpadding="0" cellspacing="0">

  <tbody><tr><td height="22">&nbsp;</td></tr>

  

  <tr><td class="cabecera">

  		<div class="titulo_dominio" style="padding-top:5px;padding-right:25px;" align="right">

  			<a href="" target="_blank"><? echo $nombre_institucion; ?></a>

        </div>

  </td></tr>

  

  <tr>

    <td class="fondo_menu" valign="top">

      <table border="0" cellpadding="0" cellspacing="0">

        <tbody>

        	<tr><td height="10"></td></tr>

        	<tr><td style="padding-right: 14px;" align="right">

			            	<table align="left" border="0" cellpadding="0" cellspacing="0">
							<tr>
							<td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>
							<td><img src="../images/logo.png" border="0" height="65" width="150"></td>
							<td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>
							<td><img src="../images/va.png" border="0" height="60" width="85"></td>
							</tr>
							</table>
            	<table align="right" border="0" cellpadding="0" cellspacing="0">

                <tbody>

                		<tr>

                        <form method="post" action="#">

                        	<td height="20" valign="middle">

							<a href="<? echo $cerrar; ?>">

                                `<img src="../images/menu-cerrar-sesion.gif" border="0" height="18" width="18">

                                </a>

                    		</td>

                            <td height="20" valign="middle">

                            	<a href="<? echo $cerrar; ?>" class="texto_volver_inicio">

                            		&nbsp;Cerrar sesi&oacute;n

                            	</a>

                            </td>

                            </form>

                        </tr>

                     </tbody>

                </table>

         </td>

       </tr>

       <tr><td class="menu_arriba">&nbsp;</td></tr>

       <tr>

          <td align="center" valign="top">

            <table align="center" border="0" cellpadding="0" cellspacing="0" width="950">

              <tbody>

              <tr><td class="menu_fondo" align="center" valign="top">

                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="930">

                    <tbody><tr>

                      <td class="menu_fondo_2" valign="top">   
<!--INICIO DE MENU-->
   						 <table align="center" border="0" cellpadding="0" cellspacing="0">

    			  			<tbody>

                  				<tr>

                                    

                  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			  <td align="center" valign="top">
			  	<div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div align="center"><a href="empresas-listado.php"><img src="../images/icono-empresas.gif" border="0"></a></div>

				<div style="height:30px;"><a href="empresas-listado.php" class="modulo_titulo">Gesti&oacute;n de<br>Empresas</a></div>

			  </td>

		  	  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			                    

                <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			  <td align="center" valign="top"><div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div align="center"><a href="descripcomercial-listado.php"><img src="../images/icono-menus.gif" border="0"></a></div>

				<div style="height:30px;"><a href="descripcomercial-listado.php" class="modulo_titulo">Gesti&oacute;n de<br>Descripciones Comerciales</a></div>

			  </td>

		  	  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			                    

                  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			  <td align="center" valign="top"><div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div align="center"><a href="facturas-listado.php"><img src="../images/icono-tienda.gif" border="0"></a></div>

				<div style="height:30px;"><a href="facturas-listado.php" class="modulo_titulo">Gesti&oacute;n de <br>Facturas</a></div>

			  </td>

		  	  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			                    

                  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			  <td align="center" valign="top">
			  	<div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div id="opener1" align="center"><a href="#"><img src="../images/icono-contenidos.gif" border="0"></a></div>

				<div id="opener2" style="height:30px;"><a href="#" class="modulo_titulo">Reportes</a></div>

			  </td>

		  	  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			                    

                  

			  </tr><tr>                  

                  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			  <td align="center" valign="top"><div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div align="center"><a href="usuarios-listado.php"><img src="../images/icono-empleados.gif" border="0"></a></div>

				<div style="height:30px;"><a href="usuarios-listado.php" class="modulo_titulo">Gesti&oacute;n de <br>Usuarios</a></div>

			  </td>

		  	  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			                    

               <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			  <td align="center" valign="top"><div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div align="center"><a href="declaraciones-listado.php"><img src="../images/icono-afp.gif" border="0"></a></div>

				<div style="height:30px;"><a href="declaraciones-listado.php" class="modulo_titulo">Gesti&oacute;n de <br>Declaraciones</a></div>


			  </td>

		  	  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			                    


			<td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			  <td align="center" valign="top"><div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div align="center"><a href="variables-entorno-gestion.php"><img src="../images/icono-eventos.gif" border="0"></a></div>

				<div style="height:30px;"><a href="variables-entorno-gestion.php" class="modulo_titulo">Gesti&oacute;n de <br>Variables del Sistema</a></div>

			  </td>

		  	  <td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

			<td class="menu_separador_1"><img src="../images/transparente.gif" height="1" width="14"></td>

		<td align="center" valign="top"><div><img src="../images/transparente(1).gif" height="8" width="1"></div>

				<div align="center"><a href="partidasarancelarias-listado.php"><img src="../images/icono-libro.png" border="0"></a></div>

				<div style="height:30px;"><a href="partidasarancelarias-listado.php" class="modulo_titulo">Partidas Arancelarias</a></div>

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

    </td>

  </tr>

  <tr><td class="fondo_login_abajo_menu"></td></tr>

  <tr><td class="texto_copyright" align="right" height="44" valign="middle"><? echo $copyright; ?></td></tr>

</tbody></table>

</center>



<!-- End Of Analytics Code -->

</body></html>