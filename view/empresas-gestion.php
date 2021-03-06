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

$id_empresa = isset($_GET['id']) ? hideunlock($_GET['id']) : 0;
$opc = isset($_GET['opc']) ? hideunlock($_GET['opc']) : 0;//variable que define la opcion nuevo,actualizar,eliminar

if (isset($_POST['submit'])){
//PARA QUE GUARDE EL NIT SIN ENCRIPTACION.	

//Funcion para poder hacer el insert, o update
if($_POST['submit']=='Actualizar'){
        $_POST["nombre"]=strtoupper($_POST["nombre"]);
        $resultado = $clase_database->formToDB($link,'empresas','post','', 'submit, frm, idEmpresa, ','update','NIT="'.$_POST['idEmpresa'].'"');

}else if($_POST['submit']=='Guardar'){
        $nit=$_POST['NIT'];
        $_POST["nombre"]=strtoupper($_POST["nombre"]);
        $resultado = $clase_database->formToDB($link,'empresas','post','', 'submit, frm, idEmpresa, ','insert','');
        
}

if ($resultado){ 
        $mensaje = "Informacion Almacenada Exitosamente";
        $clase_css = "texto_ok";
}else{
        $mensaje = "Error al Almacenar Informacion";
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
<link href="../css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet">
<script src="../js/jquery-1.8.3.js"></script>
<script src="../js/jquery-ui-1.9.2.custom.js"></script>
<script src="../js/validator.js"></script>
<script src="../js/jquery.maskedinput.js"></script>


<style type="text/css">
        label { width: 10em; float: left; }
        label.error { float: none; color: black; padding-left: .5em; vertical-align: top; border:#C63 thin dashed; background-color:#F9C; }
        .submit { margin-left: 12em; }
        em { font-weight: bold; padding-right: 1em; vertical-align: top; }
</style>
<script>
  $(document).ready(function(){
        $("#frm").validate();
        
        $("#frm :input").tooltip();
        
         $("#submit").click(function(){
       $("#frm").attr("action",$("#frm").attr("action")+hidelockjs($("#NIT").val())); 
        
    });
    
  });

   
    

  jQuery(function($){
   // $("#NIT").mask("9999-999999-999-9");  
});

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

 <form name="frm" id="frm" action="<?=$enlace_gestion.'?id='?>" method="post" style="margin:0px;"> 

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
                            $result = mysql_query("SELECT * FROM empresas WHERE nit ='".$id_empresa."'", $link);
                            
                            while($fila = mysql_fetch_array($result)){
                                    $idEmpresa = $fila['nit'];
                                    $nombre_empresa = $fila['nombre'];
                                    //$nombre_comercial = $fila['Nombre_Comercial'];
                                    //$actividad = $fila['Actividad_Economica'];
                            //	$direccion = $fila['Direccion'];
                                    //$telefono = $fila['Telefono'];
                                    //$municipio = $fila['id_Municipio'];
                                    //$NRC = $fila['NRC'];
                                    $NIT = $fila['nit'];
                            }

                      }
            ?>    
                      
                      <div style="margin-left:35%">
                    <div class="texto_explicacion_formulario">Nombre de la Empresa:</div>
                    <div><br><br>
                            <input name="nombre" id="nombre" style="width: 300px;" type="text" value="<? echo isset($nombre_empresa) ? $nombre_empresa : "";?>" class="required" title="Ingrese el Nombre de la Empresa">
                            <input name="idEmpresa" id="idEmpresa" type="hidden" value="<? echo isset($idEmpresa) ? $idEmpresa : "";?>";>
                    </div>
                    <br>
                    <div class="texto_explicacion_formulario">N&uacute;mero de Identidad Tributaria (NIT):</div>
                    <div><br><br>
                            <input name="NIT" id="NIT" rows="1" style="width: 300px;" type="text" value="<? echo isset($NIT) ? $NIT : "";?>" class="required" title="Ingrese el N&uacute;mero de Identidad Tributario">
                    </div>
                      </div>
                    
                <br>
                <hr>
                <center>
                                    <?php 
        //CONDICION PARA MOSTRAR BOTONES, EN EL CASO DE UN NUEVO REGISTRO O DE UNA ACTUALIZACION
        if(!strcmp($opc, 'nuevo')){	
            ?>

          <div><input name="submit" id="submit" style="text-align: center;font-size: 18px" value="Guardar" type="submit"></div>
        <?php
        } 
        else if(strcmp($id_empresa,0)){
            ?>
          <div><input name="submit" id="submit" style="text-align: center;font-size: 18px" value="Actualizar" type="submit"></div>
        <?php    
        }	
            ?>
          </center>
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