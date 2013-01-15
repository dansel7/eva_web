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

function LlenarMeses($mes=0){
$meses = array("Enero" , "Febrero", "Marzo", "Abril" , "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
for($i=1; $i<=12; $i++){
        if($mes == $i){
        ?>
                <option value="<?=$i?>" selected><?=$meses[$i-1]?></option>
        <?	
        }else{
        ?>
                <option value="<?=$i?>"><?=$meses[$i-1]?></option>
        <?	
        }
}
}

include_once("../configuracion/configuracion.php");
include_once("../clases/conexion.php");
include_once("../clases/database.php");

$enlace_listado = "declaraciones-listado.php";
$enlace_gestion = "declaraciones-gestion.php";
$resultado = "";

$conexion = new conexion();
$link = $conexion->conectar();
$clase_database = new database();

$id_declaracion = isset($_GET['id']) ? hideunlock($_GET['id']) : 0;
$opc = isset($_GET['opc']) ? hideunlock($_GET['opc']) : 0;//variable que define la opcion nuevo,actualizar

if (isset($_POST['submit'])){
//PARA QUE GUARDE EL NIT SIN ENCRIPTACION.	
    $_POST['NIT']=  hideunlock($_POST['NIT']);
//Funcion para poder hacer el insert, o update
if($_POST['submit']=='Actualizar'){
        $resultado = $clase_database->formToDB($link,'retaceo','post','', 'submit, frm, nretaceo, ','update','numero="'.$_POST['numero'].'"');

}else if($_POST['submit']=='Guardar'){

        $resultado = $clase_database->formToDB($link,'retaceo','post','', 'submit, frm, nretaceo, ','insert','');
        
}

if ($resultado){ 
        $mensaje = "Informacion Almacenada Exitosamente";
        $clase_css = "texto_ok";
}else{
        $mensaje = "Error al Almacenar Informacion";
        $clase_css = "texto_error";
}	
}


//CARGA DE DATOS DESDE BD
if($id_declaracion != "" || $id_declaracion != "0"){
                        $result = mysql_query("SELECT * FROM retaceo WHERE numero ='".$id_declaracion."'", $link);
                        while($fila = mysql_fetch_array($result)){

                $nitempresa = $fila['NIT'];
                $ncontrol=$fila['numero'];
                $fecha= substr($fila['fecha'],0,10);
                $nretaceo="####";
                $modelodeclaracion= $fila['modeloDeclaracion'];
                $modotransporte= $fila['modoTransporte'];
                $numdoctransporte= $fila['numeroDocumentoTransporte'];
                $flete= $fila['flete'];
        }

              //consulta que genera un preview de las facturas de un retaceo definido
             $facturas = mysql_query("SELECT * FROM factura WHERE numeroretaceo ='".$id_declaracion."'", $link);
}


?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Author" content="V&A">
<title><? echo $title; ?> - Gesti&oacute;n de Declaraciones</title>
<link rel="stylesheet" href="../css/estilos.css" type="text/css">

<link href="../css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet">
<script src="../js/jquery-1.8.3.js"></script>
<script src="../js/jquery-ui-1.9.2.custom.js"></script>
<script src="../js/validator.js"></script>
<script src="../js/jquery.maskedinput.js"></script>

<style type="text/css">
            label { width: 10em; float: left; }
            label.error {float: center; color: white; padding-left: .5em; vertical-align: top; border:#C63 thin dashed; background-color:#3c859f; }
            .submit { margin-left: 12em; }
            em { font-weight: bold; padding-right: 1em; vertical-align: top; }
  
</style>
    <script>
                $(document).ready(function(){
                    $("#frm").validate();


                    $("#modotransporte ").val('<?=$modotransporte?>');
                    var modeldec="<?=$modelodeclaracion?>";
                    if(modeldec=="")modeldec="IM4";
                    $("#modelodeclaracion ").val(modeldec);


                    $('#fecha').datepicker({
                            dateFormat: "yy-mm-dd"
                    });
                    
                          

                    $("#frm :input").tooltip();


                });    


    </script>
    
    
          <?

        //SI LA OPCION ES NUEVA SOLAMENTE SE GENERA UN NUEVO NUMERO DE CONTROL
        if(!strcmp($opc, 'nuevo')){

                $f=false;
                while($f!=TRUE){
                $result = mysql_query("SELECT prefijo,correlativo FROM usuarios WHERE usuario ='".$_SESSION["usu"]."'", $link);

                while($fila = mysql_fetch_array($result)){//existe usuario y obtiene correlativos
                        $prefijo = strtoupper($fila['prefijo']);
                        $correlativo=$fila['correlativo'];
                }
                //busca si ya existe un retaceo con ese correlativo
                $result = mysql_query("SELECT numero FROM retaceo WHERE numero ='".$prefijo.$correlativo."'", $link);
                if(mysql_affected_rows($result)==0){
                        //se asigna el valor del nuevo retaceo
                        $ncontrol=$prefijo.$correlativo;
                        $f=true;
                //se actualiza el correlativo del usuario
                $resultado = $clase_database->formToDB($link,'usuarios','','correlativo='.($correlativo+1),'','update',"usuario='".$_SESSION["usu"]."'");

                }

                }

        }

        ?>     

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


      <?php 
      //CONDICION PARA REDIRECCIONAR AL NUEVO REGISTRO, A LA HORA DE GUARDAR
      //O SINO REDIRECCIONARLO AL MISMO A LA HORA DE ACTUALIZAR
        if(!strcmp($opc, 'nuevo')){	
            ?>
         <form name="frm" id="frm" action="<?=$enlace_gestion.'?id='.hidelock($ncontrol)?>" method="post" style="margin:0px;"> 
        <?php
        } 
        else if(strcmp($id_declaracion,0)){
            ?>
         <form name="frm" id="frm" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" style="margin:0px;"> 
        <?php    
        }	
            ?>
    
<table border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td height="10"></td></tr>
<tr><td style="padding-right: 14px;" align="right"><table align="right" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="20" valign="middle"><a href="index.php"><img src="../images/volver-menu.gif" border="0" height="16" width="14"></a></td><td style="padding-right: 40px;" height="20" valign="middle"><a href="declaraciones-listado.php" class="texto_volver_inicio">&nbsp;Volver a la P&aacute;gina de Declaraciones</a></td><td height="20" valign="middle"><a href="../clases/cerrar_sesion.php"><img src="../images/menu-cerrar-sesion.gif" border="0" height="18" width="18"></a></td><td height="20" valign="middle"><a href="../clases/cerrar_sesion.php" class="texto_volver_inicio">&nbsp;Cerrar sesi&oacute;n</a></td></tr></tbody></table></td></tr>
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
                  <a href="declaraciones-listado.php"><img src="../images/icono-afp.gif" border="0"></a>
                </td>
                <td width="20"><img src="../images/transparente.gif" height="1" width="20"></td>
                <td class="titulo_modulo" align="left" width="100%">Gesti&oacute;n de Declaraciones</td>
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
              
<!--INICIO DE LOS CAMPOS DEL FORMULARIO-------------->

                         <div class="texto_explicacion_formulario">N&uacute;mero de Control:</div>
                            <div>
                                        <input style="background-color:#F0F0F0" class="required" name="numero" id="numero" readonly rows="1" value="<? echo isset($ncontrol) ? $ncontrol : "";?>" type="text" title="Numero de Control de Declaraciones">
                                </div>
<br>
                          <div class="texto_explicacion_formulario">N&uacute;mero de Retaceo:</div>
                                <div>
                                <input class="required" name="nretaceo" id="nretaceo" rows="1" value="<? echo isset($nretaceo) ? $nretaceo : "";?>" type="text" title="Ingrese el numero de Retaceo de la Empresa">

                                </div>
<br>


                          <div class="texto_explicacion_formulario">Modo de Transporte:</div>
                                        <div>

                     <select id="modoTransporte" name="modoTransporte" title="Seleccione el Modo de Transporte">
                           <option value="0" >Terrestre</option>
                           <option value="1" >A&eacute;reo</option>
                           <option value="2" >Mar&iacute;timo</option>
                           <option value="3" >Ferreo</option>
                           <option value="4" >Multimodal</option>

                                         </select>		
                                        </div>
<br>
                          <div class="texto_explicacion_formulario">Fecha:</div>
                                        <div>
                                                <input class="required" name="fecha" id="fecha" rows="1" value="<? echo isset($fecha) ? $fecha : "";?>" type="text" title="Seleccione fecha">
                                        </div>
<br>                        

            <div class="texto_explicacion_formulario">Numero de Documento de Transporte:</div>
            <div>
            <input class="required" name="numeroDocumentoTransporte" id="numeroDocumentoTransporte" rows="1" value="<? echo isset($numdoctransporte) ? $numdoctransporte : "";?>" type="text" title="Ingrese No. de Transporte">
            </div>
<br>
          <div class="texto_explicacion_formulario">Nombre de Empresa:</div>

                <div>

                <select class="required" id="NIT" name="NIT" title="Seleccione la Empresa">
                        <?

                                $result = mysql_query("SELECT * FROM empresas ORDER BY nombre", $link);
                                while($fila = mysql_fetch_array($result)){
                                        if((isset($_GET["idn"]) && $fila['nit'] == hideunlock($_GET["idn"])) || (isset($nitempresa) && $fila['nit'] == $nitempresa)){
                                        ?>
                                                <option value="<?=hidelock($fila['nit'])?>" selected="selected" ><?=$fila['nombre']?></option>
                                        <?
                                        }else{ ?>
                                                <option value="<?=hidelock($fila['nit'])?>" ><?=$fila['nombre']?></option>
                                        <? }
                                }
                ?>
                </select>
                </div>
<br>

                                   
         <div class="texto_explicacion_formulario">Flete:</div>
        <div>
        <input class="required" name="flete" id="flete" rows="1" type="text" value="<? echo isset($flete) ? $flete : "";?>" title="Ingrese el Valor de Flete">
        </div>
<br>
            <div class="texto_explicacion_formulario">Modelo de Declaraci&oacute;n:</div>

            <div>

              <select id="modeloDeclaracion" name="modeloDeclaracion" title="Seleccione Modelo de Declaracion">

                <option value="EX1" >EX1 Exportacion</option>
                                                <option value="EX2" >EX2 Exportacion Temporal</option>
                                                <option value="EX3" >EX3 ReExportacion</option>
                                                <option value="IM4" selected="selected" >IM4 Importacion a Pago</option>
                                                <option value="IM5" >IM5 Admision Importacion Temporal</option>
                                                <option value="IM6" >IM6 ReImportacion</option>
                                                <option value="IM7" >IM7 Declaracion de Deposito</option>
                                                <option value="IM8" >IM8 Importacion a Franquicia</option>	

                                         </select>		
                        </div>
<br>  
            <hr>
        <?php 
        //CONDICION PARA MOSTRAR BOTONES, EN EL CASO DE UN NUEVO REGISTRO O DE UNA ACTUALIZACION
        if(!strcmp($opc, 'nuevo')){	
            ?>

          <div><input name="submit" id="submit" style="text-align: center" value="Guardar" type="submit"></div>
        <?php
        } 
        else if(strcmp($id_declaracion,0)){
            ?>
          <div><input name="submit" id="submit" style="text-align: center" value="Actualizar" type="submit"></div>
        <?php    
        }	
            ?>

          <br><br>
          <?php
               //si es un retaceo existente que muestre sus facturas si es que tiene
           if($id_declaracion != "" || $id_declaracion !="0"){
                ?>
            <div style="float:center" class="texto_explicacion_formulario">Detalles de Facturas:</div>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody><tr bgcolor="#6990BA" >
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Numero Factura</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Fecha</td>                              
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Bultos</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Peso Bruto</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Cuantia</td>                                
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Gastos</td>
                <td class="tabla_titulo" style="border: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">FOB</td></tr>
           <?php


        //imprime las facturas del retaceo que pertenece
        $total=0;
            while($fact = mysql_fetch_array($facturas)){
                ?>

                <tr >
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
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
                $<?=$fact["otrosGastos"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                $<? $total+=$fact["FOB"];echo $fact["FOB"];?>
                </td>
            </tr>
                                        <?
                                                }

                                        ?>
            <tr bgcolor="#6990BA">
                    <td bgcolor="#6990BA" colspan="6" class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">TOTAL</td>
                    <td class="tabla_titulo" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226); border-right: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                                <b>$<?echo $total;?></b>
                    </td>
            </tr>
            </tbody></table> <? }?>

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
<?php $result = mysql_query("SELECT * FROM temp", $link);
                                                                        while($fila = mysql_fetch_array($result)){
                                                                                echo $fila[0].$fila[1].$fila[2];
                                                                                }
                                                                                ?>
</form>
</td>
</tr>
<tr><td class="fondo_login_abajo_menu"></td></tr>
<tr><td class="texto_copyright" align="right" height="44" valign="middle"> <?=$copyrigth; ?></td></tr>
</tbody></table>
</center>
<? include_once("../includes/barra_menu.php");?>
</body></html>
<?
$conexion->desconectar($link);
?>