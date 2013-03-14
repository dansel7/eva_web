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
$enlace_listado = "facturas-listado.php";
$enlace_gestion = "facturas-gestion.php";
$resultado = "";

$conexion = new conexion();
$link = $conexion->conectar();
$clase_database = new database();


$id_factura = isset($_GET['id']) ? hideunlock($_GET['id']) : 0;

$opc = isset($_GET['opc']) ? hideunlock($_GET['opc']) : 0;//variable que define la opcion nuevo,actualizar

//ACCION AL CERRAR UNA DECLARACION, ELIMINA DE LA SESION Y REDIRECCIONA PARA ABRIR OTRO.
if (isset($_POST['cerrar'])){
   unset($_SESSION["n_declaracion"]);
   header ("Location: ../view/".$enlace_listado);
}

if (isset($_POST['submit'])){
//PARA QUE GUARDE EL NIT SIN ENCRIPTACION.	
    $_POST['NIT']=  hideunlock($_POST['NIT']);
    
//Funcion para poder hacer el insert, o update de declaracion
if($_POST['submit']=='Actualizar'){
        $_POST["fechaModificado"]=date("Y-m-d H:i:s");
        $resultado = $clase_database->formToDB($link,'retaceo','post','', 'submit, frm, ','update','numero="'.$_POST['numero'].'"');

}else if($_POST['submit']=='Guardar'){
        $_POST["idRetaceo"]=$clase_database->GenerarNuevoId($link, "idRetaceo", "retaceo", "");
        $_POST["usuario"]=$_SESSION["usu"];
        $_POST["estado"]="0";
        $_POST["fechaCreado"]=date("Y-m-d H:i:s");
        $_POST["fechaModificado"]=date("Y-m-d H:i:s");
        $_POST["bultos"]=0.0;
        $_POST["pesoBruto"]=0.0;
        $_POST["cuantia"]=0.0;
        $_POST["FOB"]=0.0;
        $_POST["otrosGastos"]=0.0;
        $_POST["seguro"]=0.0;
        $_POST["CIF"]=0.0;
        $_POST["DAI"]=0.0;
        $_POST["IVA"]=0.0;
        $_POST["aPago"]=0.0;
        $_POST["total"]=0.0;
        $resultado = $clase_database->formToDB($link,'retaceo','post','', 'submit, frm, ','insert','');       
}

if ($resultado){ 
        $mensaje = "Informacion Almacenada Exitosamente";
        $clase_css = "texto_ok";
}else{
        $mensaje = "Error al Almacenar Informacion";
        $clase_css = "texto_error";
}	
}

//----AGREGAR DATOS NUEVOS DE FACTURAS
if(isset($_POST['addf'])){
    
    $idFacNuevo=$clase_database->GenerarNuevoId($link, "idFactRetaceo", "factura","where idRetaceo='".hideunlock($_SESSION["n_declaracion"])."'");
    $_POST["idFactRetaceo"]=($idFacNuevo=="") ? 1 : $idFacNuevo;    
 
    $_POST["idRetaceo"]=  hideunlock($_SESSION["n_declaracion"]);
    $_POST["fecha"]=  $_POST["fechaf"];//SE LE PUSO OTRO NOMBRE A LOS DATOS, YA QUE EL FORM RETACEO POSEE UN CAMPO LLAMADO FECHA.
    $_POST["numero"]=strtoupper($_POST["numero"]);
    
    //COMPROBAR SI EL NUMERO DE FACTURA YA EXISTE.
    $result = mysql_query("SELECT * FROM factura WHERE numero ='".$_POST["numero"]."' and idRetaceo='".hideunlock($_SESSION["n_declaracion"])."'", $link);
  if(mysql_affected_rows()==1){
      
        $resultado=true; 
        $mensaje = "Numero de Factura Ya Existe";
        $clase_css = "texto_error";
        
   }
  else
   { //SINO EXISTE LO INGRESA    
  
        $resultado = $clase_database->formToDB($link,'datosIniciales','post','', 'addf, frmf, npag, fechaf, ','insert','');
        $_POST["bultos"]=0.0;
        $_POST["pesoBruto"]=0.0;
        $_POST["cuantia"]=0.0;
        $_POST["fob"]=0.0;
        $_POST["total"]=0.0;
        $_POST["pesoNeto"]=0.0;
        $resultado = $clase_database->formToDB($link,'factura','post','','npag, fechaf, addf, frmf, npag, ','insert','');

        if ($resultado){ 
            $mensaje = "Informacion Almacenada Exitosamente";
            $clase_css = "texto_ok";
        }else{
            $mensaje = "Error al Almacenar Informacion";
            $clase_css = "texto_error";
        }
        
         //ACTUALIZA EL VALOR DE OTROSGASTOS DE LA TABLA DE RETACEO Y EL CIF
       $resultado = $clase_database->formToDB($link,'retaceo','','otrosGastos=(SELECT SUM(otrosGastos) from factura where idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'")','','update','numero="'.hideunlock($_SESSION["n_declaracion"]).'"');
      $resultado = $clase_database->formToDB($link,'retaceo','','CIF=flete+otrosGastos+seguro', '','update','numero="'.hideunlock($_SESSION["n_declaracion"]).'"');
  }
  
}


//-----MODIFICAR DATOS NUEVOS DE FACTURAS
if(isset($_POST['updf'])){
 //COMPROBAR SI EL NUMERO DE FACTURA YA EXISTE.
    //PROGRAMACION CON LOGICA NEGADA
    //FUNCION TRIM UTILIZADA PARA QUITAR LOS ESPACIOS 
    //AL OBTENER LOS VALORES DE LA TABLA QUE SE INSERTARON EN LOS CAMPOS INPUT
    $idf=0;
    $valid=true;
    $result = mysql_query("SELECT idFactRetaceo FROM factura WHERE numero ='". trim($_POST["numero"])."' and idRetaceo='".hideunlock($_SESSION["n_declaracion"])."'", $link);   
    if(mysql_affected_rows()>=1){
       $idf = mysql_fetch_row($result);      
        if($idf[0]!=trim($_POST['idFactRetaceo'])){
            $valid=false;

        } 
   }
   if(!$valid){
    $resultado=true; 
    $mensaje = "Numero de Factura Ya Existe";
    $clase_css = "texto_error";    
    
  }else{ //SINO EXISTE LO INGRESA 
      
 $_POST["numero"]=strtoupper($_POST["numero"]);
 $_POST["fecha"]=  $_POST["fechaf"];
 $resultado = $clase_database->formToDB($link,'datosIniciales','post','', 'fechaf, idFactRetaceo, updf, npag, ','update','idFactRetaceo="'.trim($_POST['idFactRetaceo']).'" and idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
 $resultado = $clase_database->formToDB($link,'factura','post','', 'fechaf, idFactRetaceo, updf, npag, bultos, cuantia, pesoBruto, fob, ','update','idFactRetaceo="'.trim($_POST['idFactRetaceo']).'" and idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
  
     if ($resultado){ 
            $mensaje = "Informacion Almacenada Exitosamente";
            $clase_css = "texto_ok";
        }else{
            $mensaje = "Error al Almacenar Informacion";
            $clase_css = "texto_error";
      } 
      //ACTUALIZA EL VALOR DE OTROSGASTOS DE LA TABLA DE RETACEO Y EL CIF
      $resultado = $clase_database->formToDB($link,'retaceo','','otrosGastos=(SELECT SUM(otrosGastos) from factura where idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'")','','update','numero="'.hideunlock($_SESSION["n_declaracion"]).'"');
      $resultado = $clase_database->formToDB($link,'retaceo','','CIF=flete+otrosGastos+seguro', '','update','numero="'.hideunlock($_SESSION["n_declaracion"]).'"');

   }

}

//VARIABLE QUE VERIFICA SI LA DECLARACION EXISTE Y ES VALIDA.
$fac_valid=0;
//CARGA DE DATOS DESDE BD
if($id_factura != "" || $id_factura != "0"){
        $result = mysql_query("SELECT * FROM factura WHERE idRetaceo='".hideunlock($_SESSION["n_declaracion"])."' and idFactura ='".$id_factura."'", $link);
        $fac_valid= mysql_num_rows($result);
        
        while($fila = mysql_fetch_array($result)){

                $nfact = $fila['numero'];
                $fecha= substr($fila['fecha'],0,10);
                $bultos=$fila['bultos'];
                $pesoBruto= $fila['pesoBruto'];
                $pesoNeto=$fila['pesoNeto'];
                $cuantiaT= $fila['cuantia'];
                $fob= $fila['FOB'];
                $otrosGastos= $fila['otrosGastos'];
                $total= $fila['total'];
        }

              //consulta que genera un preview de los items de un retaceo definido
             $items = mysql_query("SELECT * FROM item WHERE idRetaceo='".hideunlock($_SESSION["n_declaracion"])."' and idFactura =".$id_factura, $link);
echo "SELECT * FROM item WHERE idRetaceo='".hideunlock($_SESSION["n_declaracion"])."' and idFactura =".$id_factura; 
             
}


?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Author" content="V&A">
<title><? echo $title; ?> - Gesti&oacute;n de facturas</title>
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
                    $("#frmf").validate();   
//funcion para poner el valor retornado de la BD
                    $("#modotransporte ").val('<?=$modotransporte?>');
                    var modeldec="<?=$modelodeclaracion?>";
                    //y sino deja seleccionado por default im4
                    if(modeldec=="")modeldec="IM4";
                    $("#modelodeclaracion ").val(modeldec);
                                       
                    $('#fechaf').datepicker({
                            dateFormat: "yy-mm-dd"
                    });
                    $('#fechaf').mask("9999-99-99")
                    

                    $("#frm :input").tooltip();
                    $("#frmf :input").tooltip();
                    
                    
                    $("#fob").focus(function(){
                        if($(this).val()=="" || $(this).val()=="0.0") $(this).val("")
                    }); 
                    $("#fob").blur(function(){
                        if($(this).val()=="") $(this).val("0.0")
                    });
                    $("#otrosGastos").focus(function(){
                       if($(this).val()=="" || $(this).val()=="0.0") $(this).val("")
                    });
                    $("#otrosGastos").blur(function(){
                       if($(this).val()=="") $(this).val("0.0")
                    });
                    
                    
                    //AL DAR DOBLE CLICK EN EL REGISTRO SE MODIFICARA EL VALOR
                    $('#factini tr').dblclick(function()
                    {
                        
                    var tds=$(this).find("td");
                 //funcion para actualizar datos iniciales de facturas
                    if(tds.eq(0).html()!="Id Item" && tds.eq(0).html()!="TOTAL"){
                    
                     $('#frmf #idFactRetaceo').val(tds.eq(0).html())
                     $('#frmf #numero').val(tds.eq(1).html());
                     $('#frmf #fechaf').val(tds.eq(2).html());
                     $('#frmf #bultos').val(tds.eq(3).html());
                     $('#frmf #pesoBruto').val(tds.eq(4).html());
                     $('#frmf #cuantia').val(tds.eq(5).html());
                     $('#frmf #otrosGastos').val(tds.eq(6).html());
                     $('#frmf #fob').val(tds.eq(7).html());
                     
                     $('#frmf #addf').attr("value","Actualizar Datos");
                     $('#frmf #addf').attr("name","updf");
                     $('#frmf #addf').attr("id","updf");
                     $('#cancel').css("display","block");
                     //SOLO FALTA QUE ACTUALICE EN LA FUNCION DE PHP
                     }   
                     
                    });                    

                });    


    </script>
    
    
          <?
        //SI LA OPCION ES NUEVA SOLAMENTE SE GENERA UN NUEVO NUMERO DE CONTROL
        if(!strcmp($opc, 'nuevo')){

                $f=false;
                
                while($f!=TRUE){
                $result = mysql_query("SELECT prefijo,correlativo FROM usuarios WHERE usuario ='".$_SESSION["usu"]."'", $link);

                while($fila = mysql_fetch_array($result)){
                      //existe usuario y obtiene correlativos
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

  
<table border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td height="10"></td></tr>
<tr><td style="padding-right: 14px;" align="right"><table align="right" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="20" valign="middle"><a href="index.php"><img src="../images/volver-menu.gif" border="0" height="16" width="14"></a></td><td style="padding-right: 40px;" height="20" valign="middle"><a href="facturas-listado.php" class="texto_volver_inicio">&nbsp;Volver a la P&aacute;gina de facturas</a></td><td height="20" valign="middle"><a href="../clases/cerrar_sesion.php"><img src="../images/menu-cerrar-sesion.gif" border="0" height="18" width="18"></a></td><td height="20" valign="middle"><a href="../clases/cerrar_sesion.php" class="texto_volver_inicio">&nbsp;Cerrar sesi&oacute;n</a></td></tr></tbody></table></td></tr>
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
                  <a href="index.php"><img src="../images/icono-tienda.gif" border="0"></a>
                </td>
                <td width="20"><img src="../images/transparente.gif" height="1" width="20"></td>
                <td class="titulo_modulo" align="left" width="100%">Gesti&oacute;n de facturas</td>
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
              
      <?php 
      //CONDICION PARA REDIRECCIONAR AL NUEVO REGISTRO, A LA HORA DE GUARDAR
      //O SINO REDIRECCIONARLO AL MISMO A LA HORA DE ACTUALIZAR
        if(!strcmp($opc, 'nuevo')){	
            ?>
         <form name="frm" id="frm" action="<?=$enlace_gestion.'?id='.hidelock($ncontrol)?>" method="post" style="margin:0px;"> 
        <?php
        } 
        else if(strcmp($fac_valid,0)){
            ?>
         <form name="frm" id="frm" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" style="margin:0px;"> 
        <?php    
        }	
       ?>
<!------------INICIO DE LOS CAMPOS DEL FORMULARIO-------------->


<CENTER>
<TABLE cellpadding="0" cellspacing="0" >
<TR><TD WIDTH="100" rowspan="4">
&nbsp;
</TD>
<TD WIDTH="150">
                         
        <div class="texto_explicacion_formulario">N&uacute;mero de Factura:&nbsp;</div><br><br>
        <div>
        <input class="required read" name="numero" id="numero" readonly rows="1" value="<? echo isset($nfact) ? $nfact : "";?>" type="text" title="Numero de Control de facturas">
        </div>
</TD>
<TD WIDTH="200">
&nbsp;
</TD>
<TD width="250px">
       <div class="texto_explicacion_formulario">Fecha:&nbsp;</div><br><br>
       <div>
       <input class="required read" name="fecha" readonly id="fecha" rows="1" value="<? echo isset($fecha) ? $fecha : date("Y-m-d");?>" type="text" title="">
       </div>
</TD></TR>

<TR><TD>
         <div class="texto_explicacion_formulario">Bultos:&nbsp;</div><br><br>
        <div>
        <input class="required" name="bultos" id="bultos" rows="1" type="text" value="<? echo isset($bultos) ? $bultos : "";?>" title="Ingrese el Valor de Flete">
        </div>
         
</TD>
<TD>
         <div class="texto_explicacion_formulario">Peso Bruto:&nbsp;</div><br><br>
        <div>
        <input class="required" name="pesoBruto" id="pesoBruto" rows="1" type="text" value="<? echo isset($pesoBruto) ? $pesoBruto : "";?>" title="Ingrese el Valor de Flete">
        </div>
         
</TD>
<TD>
         <div class="texto_explicacion_formulario">Peso Neto:&nbsp;</div><br><br>
        <div>
        <input class="required" name="pesoNeto" id="pesoNeto" rows="1" type="text" value="<? echo isset($pesoNeto) ? $pesoNeto : "";?>" title="Ingrese el Valor de Flete">
        </div>
         
</TD>
</TR>

<TR>
<TD>
         <div class="texto_explicacion_formulario">Cuantia:&nbsp;</div><br><br>
        <div>
        <input class="required read" name="cuantia" id="cuantia" readonly rows="1" type="text" value="<? echo isset($cuantiaT) ? $cuantiaT : "";?>" title="Ingrese el Valor de Flete">
        </div>
         
</TD>
<TD>
         <div class="texto_explicacion_formulario">FOB:&nbsp;</div><br><br>
        <div>
        <input class="required read" name="fob" id="fob" rows="1" readonly type="text" value="<? echo isset($fob) ? $fob : "";?>" title="Ingrese el Valor de Flete">
        </div>
         
</TD>
<TD>
         <div class="texto_explicacion_formulario">Otros Gastos:&nbsp;</div><br><br>
        <div>
        <input class="required read" name="otrosGastos" id="otrosGastos" readonly rows="1" type="text" value="<? echo isset($otrosGastos) ? $otrosGastos : "";?>" title="Ingrese el Valor de Flete">
        </div>
         
</TD>
</TR>  
<TR>
<TD>
&nbsp;
</TD>
<TD>
        <div class="texto_explicacion_formulario">Total:&nbsp;</div><br><br>
        <div>
        <input class="required read" name="total" id="total" rows="1" readonly type="text" value="<? echo isset($total) ? $total : "";?>" title="Ingrese el Valor de Flete">
        </div>

         
</TD>
</TR> 
</TABLE>
</CENTER>        

<hr>
            
            
        <center>
        <?php 
        //CONDICION PARA MOSTRAR BOTONES, EN EL CASO DE UN NUEVO REGISTRO O DE UNA ACTUALIZACION
        if(!strcmp($opc, 'nuevo')){	
            ?>
          <div><input name="submit" id="submit" style="float: center" value="Guardar" type="submit"></div>
        <?php
        } 
        else if(strcmp($fac_valid,0)){
            ?>
          <div><input name="submit" id="submit" style="float: center" value="Actualizar" type="submit"></div>
        
        </form>
               </center>   
      <?php    
        }	
      ?>   

             
<!---------------------------FIN DEL FORMULARIO facturas-------------------------------------->

          <?php
           //si es un retaceo existente que muestre sus facturas si es que tiene
           if($fac_valid != "" || $fac_valid !="0")
               {
                ?>

<!--------------------------INCIIO DEL FORM PARA INGRESAR DATOS INICIALES DE FACTURAS ----------------------->             
<form class="frmspecial" name="frmf" id="frmf" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" style="margin:0px;"> 
                 
              <h4 style="font-family:helvetica">Agregar Items a la Factura</h4>
              <input class="required" name="idFactRetaceo" id="idFactRetaceo" type="hidden" value="" title="">
                
             <table><tr>
                <td>
                <div class="texto_explicacion_formulario">Cantidad de Bultos:</div>
                <div>
                <input class="required" name="bultos" id="bultos" style="width:100px" type="text" value="0.0" title="Ingrese Bultos">
                </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">Peso Bruto:</div>
                <div>
                <input class="required" name="pesoBruto" id="pesoBruto" style="width:100px" type="text" value="0.0" title="Ingrese Peso Bruto">
                </div>
                </td>
                <td>
                <div class="texto_explicacion_formulario">Peso Neto:</div>
                <div>
                <input class="required" name="pesoNeto" id="pesoNeto" style="width:100px" type="text" value="0.0" title="Ingrese Peso Neto">
                </div>
                </td>
                <td>
                <div class="texto_explicacion_formulario">Cuantia:</div>
                <div>
                <input class="required" name="cuantia" id="cuantia" style="width:100px" type="text" value="0.0" title="Ingrese Cuantia">
                </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">Unidades:</div>
                <div>
                    <select name="unidades" id="unidades" title="Seleccione una Unidad">
                <?php
                        $result = mysql_query("SELECT * from unidades", $link);
                while($fila = mysql_fetch_array($result)){
                
                      echo  '<option value="'.$fila["codigo"].'"  >'.$fila["codigo"].' '.$fila["cantidad"].'</option>';       
                 }
                ?>
                    </select> </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">FOB:</div>
                <div>
                <input class="required" name="fob" id="fob" style="width:100px" type="text" value="0.0" title="Ingrese FOB">
                </div>
                </td>  
                </tr>
            <tr>
                <td colspan="6"></td>
                
                <td>
                    <div class="texto_explicacion_formulario">&nbsp</div>
                    <div><input name="addf" id="addf" style="float: right;" value="Agregar Factura" type="submit">
                    </div>
                  
                </td> 
            </tr>
             </table>
          </form>
<form method="Post">
<input name="cancel" id="cancel" style="display:none;float: right;" value="Cancelar" type="submit">
</form>
<br>
<!---------------------------FIN DEL FORMULARIO-------------------------------------->


            <div style="float:center" class="texto_explicacion_formulario">Detalles de Facturas: (De doble click para editar)</div>
            <table id="factini" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody><tr bgcolor="#6990BA" >
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="40">Id Item</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="350">Descripcion</td>                            
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Bultos</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Peso Bruto</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Cuantia</td>                                
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Precio Unitario</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="70">Total</td>
           <?php


        //IMPRIME LOS ITEMS DE LA DECLARACION A QUIEN PERTENECE


            while($fact = mysql_fetch_array($items)){
                ?>

                <tr>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                <?=$fact["idItemFactura"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                <?=$fact["descripcion"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                <?=$fact["bultos"]?>
                </td>
                 <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" >
                <?=$fact["pesoBruto"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                <?=$fact["cuantia"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" >
                <?=number_format(round($fact["precioUnitario"],2),2)?>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" >
                <?=number_format(round($fact["precioTotal"],2),2)?>
                </td>
            </tr>
                                        <?
                                                }

                                        ?>
            <tr bgcolor="#6990BA">
                    <td bgcolor="#6990BA" colspan="6" class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">TOTAL</td>
                    <td class="tabla_titulo" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226); border-right: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                                <b>$<?echo number_format(round($FOBtotal,2),2);?></b>
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

</td>
</tr>
<tr><td class="fondo_login_abajo_menu"></td></tr>
<tr><td class="texto_copyright" align="right" height="44" valign="middle"> <?=$copyrigth;?></td></tr>
</tbody></table>
</center>
<? include_once("../includes/barra_menu.php");?>
</body></html>
<?
$conexion->desconectar($link);
?>