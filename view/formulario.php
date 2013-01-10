<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title>Centro de Administración</title>
	<link rel="stylesheet" href="css/estilosForm.css" type="text/css">
	<script type="text/javascript" language="javascript" src="images/jquery.js"></script>
    <link href="css/sample.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="images/fckeditor.js"></script>
    
    <script src="images/jquery.tools.min.js"></script>
    
        <script type="text/javascript" src="images/validator.js"></script>
<style type="text/css">
* { font-family: Verdana; font-size: 96%; }
label { width: 10em; float: left; }
label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
p { clear: both; }
.submit { margin-left: 12em; }
em { font-weight: bold; padding-right: 1em; vertical-align: top; }
</style>
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
    
    
<style id="wrc-middle-css" type="text/css">.wrc_whole_window{	display: none;	position: fixed; 	z-index: 2147483647;	background-color: rgba(40, 40, 40, 0.9);	word-spacing: normal;	margin: 0px;	padding: 0px;	border: 0px;	left: 0px;	top: 0px;	width: 100%;	height: 100%;	line-height: normal;	letter-spacing: normal;}.wrc_middle_main {	font-family: Segoe UI, Arial Unicode MS, Arial, Sans-Serif;	font-size: 14px;	width: 600px;	height: auto;	margin: 0px auto;	margin-top: 15%;    background: url(chrome-extension://icmlaeflemplmjndnaapfdbbnpncnbda/skin/images/background-body.jpg) repeat-x left top;	background-color: rgb(39, 53, 62);}.wrc_middle_logo {    background: url(chrome-extension://icmlaeflemplmjndnaapfdbbnpncnbda/skin/images/logo.jpg) no-repeat left bottom;    width: 140px;    height: 42px;    color: orange;    display: table-cell;    text-align: right;    vertical-align: middle;}.wrc_icon_warning {	margin: 20px 10px 20px 15px;	float: left;	background-color: transparent;}.wrc_middle_title {    color: #b6bec7;	height: auto;    margin: 0px auto;	font-size: 2.2em;	white-space: nowrap;	text-align: center;}.wrc_middle_hline {    height: 2px;	width: 100%;    display: block;}.wrc_middle_description {	text-align: center;	margin: 15px;	font-size: 1.4em;	padding: 20px;	height: auto;	color: white;	min-height: 3.5em;}.wrc_middle_actions_main_div {	margin-bottom: 15px;	text-align: center;}.wrc_middle_actions_blue_button {	-moz-appearance: none;	border-radius: 7px;	-moz-border-radius: 7px/7px;	border-radius: 7px/7px;	background-color: rgb(0, 173, 223) !important;	display: inline-block;	width: auto;	cursor: Pointer;	border: 2px solid #00dddd;}.wrc_middle_actions_blue_button:hover {	background-color: rgb(0, 159, 212) !important;}.wrc_middle_actions_blue_button:active {	background-color: rgb(0, 146, 200) !important;	border: 2px solid #00aaaa;}.wrc_middle_actions_blue_button div {	display: inline-block;	width: auto;	cursor: Pointer;	margin: 3px 10px 3px 10px;	color: white;	font-size: 1.2em;	font-weight: bold;}.wrc_middle_action_low {	font-size: 0.9em;	white-space: nowrap;	cursor: Pointer;	color: grey !important;	margin: 10px 10px 0px 10px;	text-decoration: none;}.wrc_middle_action_low:hover {	color: #aa4400 !important;}.wrc_middle_actions_rest_div {	padding-top: 5px;	white-space: nowrap;	text-align: center;}.wrc_middle_action {	white-space: nowrap;	cursor: Pointer;	color: red !important;	font-size: 1.2em;	margin: 10px 10px 0px 10px;	text-decoration: none;}.wrc_middle_action:hover {	color: #aa4400 !important;}</style><script id="wrc-script-middle_window" type="text/javascript" language="JavaScript">var g_inputsCnt = 0;var g_InputThis = new Array(null, null, null, null);var g_alerted = false;/* we test the input if it includes 4 digits   (input is a part of 4 inputs for filling the credit-card number)*/function is4DigitsCardNumber(val){	var regExp = new RegExp('[0-9]{4}');	return (val.length == 4 && val.search(regExp) == 0);}/* testing the whole credit-card number 19 digits devided by three '-' symbols or   exactly 16 digits without any dividers*/function isCreditCardNumber(val){	if(val.length == 19)	{		var regExp = new RegExp('[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}');		return (val.search(regExp) == 0);	}	else if(val.length == 16)	{		var regExp = new RegExp('[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}');		return (val.search(regExp) == 0);	}	return false;}function CheckInputOnCreditNumber(self){	if(g_alerted)		return false;	var value = self.value;	if(self.type == 'text')	{		if(is4DigitsCardNumber(value))		{			var cont = true;			for(i = 0; i < g_inputsCnt; i++)				if(g_InputThis[i] == self)					cont = false;			if(cont && g_inputsCnt < 4)			{				g_InputThis[g_inputsCnt] = self;				g_inputsCnt++;			}		}		g_alerted = (g_inputsCnt == 4);		if(g_alerted)			g_inputsCnt = 0;		else			g_alerted = isCreditCardNumber(value);	}	return g_alerted;}function CheckInputOnPassword(self){	if(g_alerted)		return false;	var value = self.value;	if(self.type == 'password')	{		g_alerted = (value.length > 0);	}	return g_alerted;}function onInputBlur(self, bRatingOk, bFishingSite){	var bCreditNumber = CheckInputOnCreditNumber(self);	var bPassword = CheckInputOnPassword(self);	if((!bRatingOk || bFishingSite == 1) && (bCreditNumber || bPassword) )	{		var warnDiv = document.getElementById("wrcinputdiv");		if(warnDiv)		{			/* show the warning div in the middle of the screen */			warnDiv.style.left = "0px";			warnDiv.style.top = "0px";			warnDiv.style.width = "100%";			warnDiv.style.height = "100%";			document.getElementById("wrc_warn_fs").style.display = 'none';			document.getElementById("wrc_warn_cn").style.display = 'none';			if(bFishingSite)				document.getElementById("wrc_warn_fs").style.display = 'block';			else				document.getElementById("wrc_warn_cn").style.display = 'block';			warnDiv.style.display = 'block';		}	}}</script></head>
<body>

<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tbody><tr><td height="22">&nbsp;</td></tr>
  
  <tr><td class="cabecera">
  		<div class="titulo_dominio" style="padding-top:5px;padding-right:25px;" align="right">
  			<a href="http://mutigimnasiodb.comyr.com/index.php" target="_blank">CRYSTAL 1.0</a>
        </div>
  </td></tr>
  
  <tr>
    <td valign="top" class="fondo_menu">
    
<form name="frm" id="frm" action="images/Centro de Administración.htm" method="post" style="margin:0px;" novalidate="novalidate">    
      <table border="0" cellspacing="0" cellpadding="0">
        <tbody><tr><td height="10"></td></tr>
        <tr><td align="right" style="padding-right:14px;"><table border="0" cellspacing="0" cellpadding="0" align="right"><tbody><tr><td valign="middle" height="20"><a href="http://mutigimnasiodb.comyr.com/administrator/menu.php"><img src="images/volver-menu.gif" border="0" height="16" width="14"></a></td>
        <td style="padding-right: 40px;" height="20" valign="middle"><a href="http://mutigimnasiodb.comyr.com/administrator/usuarios-listado.php" class="texto_volver_inicio">&nbsp;Volver a listado de usuarios</a></td><td height="20" valign="middle"><a href="http://mutigimnasiodb.comyr.com/administrator/cerrar_sesion.php"><img src="images/menu-cerrar-sesion.gif" border="0" height="18" width="18"></a></td><td height="20" valign="middle"><a href="http://mutigimnasiodb.comyr.com/administrator/cerrar_sesion.php" class="texto_volver_inicio">&nbsp;Cerrar sesión</a></td></tr></tbody></table></td></tr>
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
                                <td width="8"><img src="images/transparente.gif" width="8" height="1"></td>
                                <td valign="middle">
                                  <a href="http://mutigimnasiodb.comyr.com/administrator/usuarios-listado.php"><img src="images/icono-usuarios.gif" border="0"></a>
                                </td>
                                <td width="20"><img src="images/transparente.gif" width="20" height="1"></td>
                                <td width="100%" align="left" class="titulo_modulo">Administración de Usuarios</td>
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
<div class="texto_explicacion_formulario"><span style="color: #F00"> </span> Datos de Usuario:Administrador  &nbsp;<span style="font-weight:normal;color:#000000;"><strong id="usuario"></strong></span></div>  
  <div class="texto_explicacion_formulario">Alias:</div>  
<div><input name="alias_usuario" type="text" class="required valid" style="width:420px;" id="alias_usuario" value="Leo" maxlength="25"></div>
<div class="texto_explicacion_formulario">Nombre:</div>  
<div><input name="nombre_usuario" type="text" class="required" style="width:420px;" id="nombre_usuario" value="Rafael Ernesto" maxlength="25"></div>
<div class="texto_explicacion_formulario">Apellidos:</div>  
<div><input name="apellido_usuario" type="text" class="required" style="width:420px;" id="apellido_usuario" value="De Leon Leon" maxlength="25"></div>
<div class="texto_explicacion_formulario"> E-Mail:<br><span id="mensaje" style="color:#F00"></span></div>  
<div><input name="email_usuario" type="text" class="required" style="width:420px;" id="email_usuario" value="rafael.de.leon@hotmail.com" maxlength="30"></div>
<div class="texto_explicacion_formulario">Dirección:</div>  
<div><textarea name="direccion_usuario" id="direccion_usuario" class="inputbox" rows="2">Calle Montesinos, colonia Florencia, casa #3</textarea></div>
<div class="texto_explicacion_formulario">Password (Máximo 15 caracteres):<br><span id="contador" style="color:#F00"></span></div> 
<div><input type="password" name="password" id="password" class="required" style="width:420px;" maxlength="16" value="123456" onkeypress=" return limita(this, event,15)"> </div>
<div style="height:15px;"></div>
<div class="texto_explicacion_formulario">Tipo de Usuario:</div>
<div class="texto_explicacion_formulario" style="font-weight:normal;color:#000000;">
<div class="texto_explicacion_formulario" style="color:#000000;font-weight:bold;"><input name="id_tipo_usuario" type="radio" id="id_tipo_usuario" value="1" checked="true">&nbsp;Administrador</div><div class="texto_explicacion_formulario" style="color:#000000;font-weight:normal;"><input type="radio" id="id_tipo_usuario" name="id_tipo_usuario" value="2">&nbsp;Registrado</div></div>

			<div class="texto_explicacion_formulario">Permisos:</div>  
			
            <div class="texto_explicacion_formulario" style="color:#000000;"><input name="modulo1" type="checkbox" id="modulo1" value="3" checked="true">&nbsp;Gestion de Empresas</div>						
         <br><div class="texto_explicacion_formulario" style="color:#000000;"><input name="modulo4" type="checkbox" id="modulo4" value="4" checked="true">&nbsp;Gestion de Compra Contribuyentes</div>						
		<br><div class="texto_explicacion_formulario" style="color:#000000;"><input name="modulo5" type="checkbox" id="modulo5" value="5" checked="true">&nbsp;Gestion de Ventas Consumidor Final</div>						
	    <br><div class="texto_explicacion_formulario" style="color:#000000;"><input name="modulo4" type="checkbox" id="modulo4" value="4" checked="true">&nbsp;Gestion de Proveedores</div>						
		<br><div class="texto_explicacion_formulario" style="color:#000000;"><input name="modulo5" type="checkbox" id="modulo5" value="5" checked="true">&nbsp;Gestion de Usuarios</div>						
        <br><div class="texto_explicacion_formulario" style="color:#000000;"><input name="modulo4" type="checkbox" id="modulo4" value="4" checked="true">&nbsp;Gestion de Empleados</div>						
		<br><div class="texto_explicacion_formulario" style="color:#000000;"><input name="modulo5" type="checkbox" id="modulo5" value="5" checked="true">&nbsp;Gestion de Planillas AFP</div>						
	    <br><div class="texto_explicacion_formulario" style="color:#000000;"><input name="modulo4" type="checkbox" id="modulo4" value="4" checked="true">&nbsp;Gestion de Planillas de Salarios</div>						
		<br><div class="texto_explicacion_formulario" style="color:#000000;"><input name="modulo5" type="checkbox" id="modulo5" value="5" checked="true">&nbsp;Gestion de Variables de Entorno</div>						

	<br>	     				                          				
							<div><br><span id="alerta" style="color:#F00"></span><br>
							  <input name="submit" id="submit" value="Actualizar" type="submit">
							</div>
									</td>
                                    <td valign="top">
                                    	<div align="center" style="padding-top:50px">
                                        														
                                    		<img src="images/avatar-usuarios.jpg" width="200" border="0">
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
				//men.innerHTML = "La dirección de email " + valor + " es correcta.";				
			}
			else
			{
				var men = document.getElementById('mensaje');
				men.innerHTML = "La dirección de email " + valor + " no es correcta."
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
  <tr><td height="44" align="right" valign="middle" class="texto_copyright">© CRYSTAL</td></tr>
</tbody></table>
</center>





<!-- Hosting24 Analytics Code -->
<script type="text/javascript" src="images/count.php"></script>
<!-- End Of Analytics Code -->
<div class="tooltip" style="position: absolute; top: 327.5px; left: 259px; opacity: 0.7; display: none; ">Ingrese un alias para iniciar sesión</div></body></html>