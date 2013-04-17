<?php
//COMPROBACION QUE SE HACE PARA REDIRECCIONAR A ER404.PHP EN EL CASO QUE ESTE EN UN NIVEL INFERIOR DE CARPETAS,
// CUENTA CUANTAS VECES APARECE EL SLASH Y DEPENDIENDO DE ESO REDIRECCIONA Y COMPRUEBA QUE NO ESTA YA EN LA PAGINA DEL ERROR.

if(count(explode("/", $_SERVER["REQUEST_URI"]))>=3 && $_SERVER["REQUEST_URI"]!="/eva_web/error" ){
header("Location: /eva_web/error");
}

?>


<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<head>
	<link rel="stylesheet" href="css/login.css" type="text/css">
	<title>Error 404</title>
</head>

<body>
<center>
<a href="index.php">
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
    <td class="fondo_error" valign="top">
 			            	<table align="left" border="0" cellpadding="0" cellspacing="0">
 			            		<tr><td class="menu_separador_1"><img src="images/transparente.gif" height="5" width="14"></td></tr>
							<tr>
							<td class="menu_separador_1"><img src="images/transparente.gif" height="1" width="14"></td>
							<td><img src="images/logo.jpg" border="0" height="60" width="150"></td>
							<td class="menu_separador_1"><img src="images/transparente.gif" height="1" width="14"></td>
							<td><img src="images/va.png" border="0" height="60" width="85"></td>
							</tr>
							</table>
							<br><br>

     <div style="padding-top:65px;padding-left:485px;"><table border="0" cellpadding="0" cellspacing="0"><tbody></tbody></table></div>
    </td>
  </tr>
  <tr><td class="fondo_login_abajo"></td></tr>
  <tr><td class="texto_copyright" align="right" height="44" valign="middle"> <? echo $copyright; ?></td></tr>
</tbody></table>
</a>
</center>
</body></html>