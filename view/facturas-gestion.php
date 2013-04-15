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


$id_factura = isset($_REQUEST['id']) ? hideunlock($_REQUEST['id']) : 0;

$opc = isset($_GET['opc']) ? hideunlock($_GET['opc']) : 0;//variable que define la opcion nuevo,actualizar


//CALCULO DE BULTOS Y PESOS
if (isset($_POST['submit'])){
if($_POST['submit']=='Guardar'){
        $_POST["idRetaceo"]=$clase_database->GenerarNuevoId($link, "idRetaceo", "retaceo", "");
        $_POST["usuario"]=$_SESSION["usu"];
        $_POST["estado"]="0";
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
if(isset($_POST['addItem'])){
    
    $idItemFact=$clase_database->GenerarNuevoId($link, "idItemFactura", "item","where idFactura='".$id_factura."' and idRetaceo='".hideunlock($_SESSION["n_declaracion"])."'");
    $_POST["idFactura"]=$id_factura;
    $_POST["idRetaceo"]=  hideunlock($_SESSION["n_declaracion"]);
    $_POST["idItemFactura"]=$idItemFact;
    $_POST["num_item_declaracion"]=0;
    $_POST["nuevoUsado"]="N";
    $_POST["tipoDescripcion"]="N";
    //SE CALCULA EL PRECIO TOTAL POR SI EL JAVASCRIPT ESTA DESACTIVADO
    $_POST["precioTotal"]=round($_POST["cuantia"], 2) * round($_POST["precioUnitario"], 2);
    
       $resultado = $clase_database->formToDB($link,'item','post','','addItem, idItem, ','insert','');
        if ($resultado){ 
            $mensaje = "Informacion Almacenada Exitosamente";
            $clase_css = "texto_ok";
        }else{
            $mensaje = "Error al Almacenar Informacion";
            $clase_css = "texto_error";
        }
 
         //CALCULA LOS VALORES DE LA FACTURA.

$resultado = $clase_database->formToDB($link,'factura f,(SELECT SUM(precioTotal) pt,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(pesoNeto) pn,SUM(bultos) b from item 
where idFactura='.$id_factura.' and idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') i','','f.FOB=i.pt,f.cuantia=i.c,f.pesoBruto=i.pb,f.pesoNeto=i.pn,f.bultos=i.b,f.total=f.otrosGastos+i.pt','','update','idFactura="'.$id_factura.'"');
  //CALCULA LOS VALORES DEL RETACEO.
//PRIMERO COMPRUEBA SI SE DEBE CALCULAR EL SEGURO O NO.
if(strtoupper($_SESSION["calculoseguro"])=="S")
{
$resultado = $clase_database->formToDB($link,'retaceo r,(SELECT SUM(otrosGastos) og,SUM(FOB) fob,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(bultos) b 
from factura where idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') f','','r.FOB=f.fob,r.cuantia=f.c,r.pesoBruto=f.pb,r.bultos=f.b,r.otrosGastos=f.og,r.seguro=(f.fob+f.og+r.flete)*0.00275,r.cif=(f.fob+f.og+r.flete)+((f.fob+f.og+r.flete)*0.00275)','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
}else{
$resultado = $clase_database->formToDB($link,'retaceo r,(SELECT SUM(otrosGastos) og,SUM(FOB) fob,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(bultos) b 
from factura where idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') f','','r.FOB=f.fob,r.cuantia=f.c,r.pesoBruto=f.pb,r.bultos=f.b,r.otrosGastos=f.og,r.cif=(f.fob+f.og+r.flete)+((f.fob+f.og+r.flete)*0.00275)','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');  
}


header("Location:".$_SERVER['REQUEST_URI']);
}

//-----MODIFICAR DATOS NUEVOS DE FACTURAS
if(isset($_POST['updItem'])){
$_POST["precioTotal"]=round($_POST["cuantia"], 2) * round($_POST["precioUnitario"], 2);
$resultado = $clase_database->formToDB($link,'item','post','','updItem, idItem, ','update','idItem='.$_POST["idItem"]);
 
     if ($resultado){ 
            $mensaje = "Informacion Almacenada Exitosamente";
            $clase_css = "texto_ok";
        }else{
            $mensaje = "Error al Almacenar Informacion";
            $clase_css = "texto_error";
      }
      
    //CALCULA LOS VALORES DE LA FACTURA.
$resultado = $clase_database->formToDB($link,'factura f,(SELECT SUM(precioTotal) pt,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(pesoNeto) pn,SUM(bultos) b from item 
where idFactura='.$id_factura.' and idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') i','','f.FOB=i.pt,f.cuantia=i.c,f.pesoBruto=i.pb,f.pesoNeto=i.pn,f.bultos=i.b,f.total=f.otrosGastos+i.pt','','update','idFactura="'.$id_factura.'"');
  //CALCULA LOS VALORES DEL RETACEO.
//PRIMERO COMPRUEBA SI SE DEBE CALCULAR EL SEGURO O NO.
if(strtoupper($_SESSION["calculoseguro"])=="S")
{
$resultado = $clase_database->formToDB($link,'retaceo r,(SELECT SUM(otrosGastos) og,SUM(FOB) fob,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(bultos) b 
from factura where idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') f','','r.FOB=f.fob,r.cuantia=f.c,r.pesoBruto=f.pb,r.bultos=f.b,r.otrosGastos=f.og,r.seguro=(f.fob+f.og+r.flete)*0.00275,r.cif=(f.fob+f.og+r.flete)+((f.fob+f.og+r.flete)*0.00275)','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
}else{
$resultado = $clase_database->formToDB($link,'retaceo r,(SELECT SUM(otrosGastos) og,SUM(FOB) fob,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(bultos) b 
from factura where idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') f','','r.FOB=f.fob,r.cuantia=f.c,r.pesoBruto=f.pb,r.bultos=f.b,r.otrosGastos=f.og,r.cif=(f.fob+f.og+r.flete)+((f.fob+f.og+r.flete)*0.00275)','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');  
}

header("Location:".$_SERVER['REQUEST_URI']);


}

//ELIMINACION MULTIPLE
if(isset($_POST['opdet'])){
			$cntDel = 0;
			$selDels = $_POST['idsimps'];
            
			foreach($selDels as $idSel) {
			 	
				if($idSel != '') {
                                        $result = $clase_database->Eliminar($link,'item','idItem =' . $idSel);
					if($result) 
					$cntDel++;
				}
			}
                        $resultado=true;
                    if($cntDel > 0) { 
                        $mensaje = "Se eliminaron $cntDel registros";
                        $clase_css = "texto_ok";
                    }else{
                        $mensaje = "No se seleccionaron registros para eliminar";
                        $clase_css = "texto_error";
                    }
				
} 
//FIN ELIMINACION MULTIPLE

//CARGA DE DATOS DE LA FACTURA
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
             $items = mysql_query("SELECT * FROM item WHERE idRetaceo='".hideunlock($_SESSION["n_declaracion"])."' and idFactura =".$id_factura." order by idItemFactura", $link);
             
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
                    
                    //CALCULOS
                    $("#frmf #pesoBruto").blur(function(){
                      $("#frmf #pesoNeto").focus();
                      $("#frmf #pesoNeto").val($(this).val());
                    });
                    
                    $("#busqDescripcion").keypress(function(e) {
                        if(e.which == 13) {
                        $("#btnBusqDesc").click();  
                        }
                     });
                     
                     $("#busqArancel").keypress(function(e) {
                        if(e.which == 13) {
                        $("#btnBusqAranc").click();  
                        }
                     });
                    
                    //Al dar enter en descripcion abra ventana de busqueda
                     $("#descripcion").keypress(function(e) {
                         
                        if(e.which == 13) {
                        $("#btnBuscarD").click();  
                        }
  
                     });
                    
                    //AL DAR DOBLE CLICK EN EL REGISTRO SE MODIFICARA EL VALOR
                    $('#factini tr').dblclick(function()
                    {
                        
                    var tds=$(this).find("td");
                 //funcion para actualizar datos iniciales de facturas
                    if(tds.eq(0).html()!="Id Item" && tds.eq(0).html()!="TOTAL"){
                     $('#frmf #idItem').val(tds.eq(12).html().trim())
                     $('#frmf #descripcion').val(tds.eq(1).html().trim());
                     $('#frmf #bultos').val(tds.eq(2).html().trim());
                     $('#frmf #pesoBruto').val(tds.eq(3).html().trim());
                     $('#frmf #cuantia').val(tds.eq(4).text().trim());
                     $('#frmf #precioUnitario').val(tds.eq(5).html().trim());
                     $('#frmf #precioTotal').val(tds.eq(6).html().trim());
                     $('#frmf #pesoNeto').val(tds.eq(7).html().trim());
                     $('#frmf #partidaArancelaria').val(tds.eq(8).html().trim());
                     $('#frmf #referencia').val(tds.eq(9).html().trim());
                     $('#frmf #unidades').val(tds.eq(10).html().trim());
                     $('#frmf #pagFactura').val(tds.eq(11).html().trim());
                     
                     $('#frmf #addItem').attr("value","Actualizar Item");
                     $('#frmf #addItem').attr("name","updItem");
                     $('#frmf #addItem').attr("id","updItem");
                     $('#cancel').css("display","block");
                     //SOLO FALTA QUE ACTUALICE EN LA FUNCION DE PHP
                     }   
                     
                    });  
                    
                    //CALCULO DE TOTAL
                    $('#cuantia').blur(function(){
                        $('#precioTotal').val((parseFloat($('#cuantia').val()) * parseFloat($('#precioUnitario').val())).toFixed(2));
                    });
                    $('#precioUnitario').blur(function(){
                        $('#precioTotal').val((parseFloat($('#cuantia').val()) * parseFloat($('#precioUnitario').val())).toFixed(2));
                    });
                    
                    //DIV DE BUSQUEDAS
                    $( "#dialogDesc" ).dialog({
                        autoOpen: false,
                        show: "slide",
                        hide: "slide",
                        width:'500',
                        position: "top",
                        height: '350',
                    close: function( event, ui ) {$("#resDescrip").html("");$("#busqDescripcion").val("")}
                    });

                    $( "#dialogAranc" ).dialog({
                        autoOpen: false,
                        show: "slide",
                        hide: "slide",
                        width:'500',
                        position: "top",
                        height: '350',
                    close: function( event, ui ) { $("#resArancel").html("");$("#busqArancel").val("")}
                    });
                    
                    //ABREN LOS CUADROS DE DIALOGO DE BUSQUEDAS
                    
                    $( "#btnBuscarD" ).click(function() {
//SI TXTBOX DESCRIPCION ESTA CON UN VALOR EL LO BUSCA INMEDIATAMENTE ANTES DE ABRIR EL CUADRO DE BUSQUEDA   
                        if($("#descripcion").val()!=""){
                         $("#busqDescripcion").val($("#descripcion").val());
                         $("#btnBusqDesc").click(); 
                        }

                        $( "#dialogDesc" ).dialog("open");
                        return false;
                    });
                    
                    $( "#btnBuscarPA" ).click(function() {
                        
//SI TXTBOX PARTIDAARANCELARIA ESTA CON UN VALOR EL LO BUSCA INMEDIATAMENTE ANTES DE ABRIR EL CUADRO DE BUSQUEDA   
                        if($("#partidaArancelaria").val()!=""){
                         $("#busqArancel").val($("#partidaArancelaria").val())
                         $("#btnBusqAranc").click(); 
                        }
                        
                        $( "#dialogAranc" ).dialog("open");
                        return false;
                    });
                    //FIN DIV DE BUSQUEDAS
                    
                    
                //RESULTADO BUSQUEDAS                    
                 $("#btnBusqDesc").click(function(){
                    if($("#busqDescripcion").val()==""){ $("#resDescrip").html("Ingrese una Descripcion");}
                    else{
                    $("#resDescrip").html("<center><img width='50px' height='50px' src='../images/load.gif'></center>"); 
                    $.post("../includes/descripciones-items.php",
                    {descripcion: $("#busqDescripcion").val(),nit_empresa: '<?php echo hidelock($_SESSION["NIT_EMPRESA"]);?>'},
                           function(data){
                           $("#resDescrip").html(data);  
                           })
                    }                     
                    
                });
                
                
                $("#btnBusqAranc").click(function(){
                    if($("#busqArancel").val()=="") { $("#resArancel").html("Ingrese un numero de Partida Arancelaria");}
                    else{
                    $("#resArancel").html("<center><img width='50px' height='50px' src='../images/load.gif'></center>"); 
                    $.post("../includes/partidasArancel-items.php",
                    {arancel: $("#busqArancel").val()},
                           function(data){
                           $("#resArancel").html(data);  
                           })
                    }
               
                });
                //FIN OBTENCION RESULTADOS BUSQUEDAS
});    

function delRow(cheque,idsimps) {//funcion para eliminacion multiple
		if(cheque)
			document.getElementById('idsimps'+idsimps).value = idsimps;
		else
			document.getElementById('idsimps'+idsimps).value = '';
	}

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
    <tbody>
    <tr>
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
              

<form name="frm" id="frm" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" style="margin:0px;"> 
<!------------INICIO DE LOS CAMPOS DEL FORMULARIO-------------->


<CENTER>
    <h4 style="font-family:helvetica">Datos de Factura</h4>
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
        <input class="required" name="bultos" id="bultos" width rows="1" type="text" value="<? echo isset($bultos) ? $bultos : "";?>" title="Ingrese el Valor de Flete">
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
        <input class="required read" name="cuantiaP" id="cuantiaP" readonly rows="1" type="text" value="<? echo isset($cuantiaT) ? $cuantiaT : "";?>" title="Ingrese el Valor de Flete">
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
<br>
 <center>
     <input type="hidden" id="id" name="id" value="<?=  hidelock($id_factura)?>">
<div><input name="submit" id="submit" style="float: center" value="Calcular Pesos y Bultos" type="submit"></div>

</center> 
        </form>
        <br>
<hr>
             
<!---------------------------FIN DEL FORMULARIO facturas-------------------------------------->


<!--------------------------INCIIO DEL FORM PARA INGRESAR DATOS INICIALES DE FACTURAS ----------------------->             
<form class="frmspecial" name="frmf" id="frmf" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" style="margin:0px;"> 
        <input type="hidden" id="idItem" name="idItem" value="">   
           <h4 style="font-family:helvetica">Agregar Items a la Factura</h4>
             <center>
             <table><tr>
                <td style="width:120px">
                <div class="texto_explicacion_formulario">Cantidad de Bultos:</div>
                <div>
                <input class="required" name="bultos" id="bultos" style="width:120px" type="text" value="0" title="Ingrese Bultos">
                </div>
                </td>
                 
                <td style="width:120px">
                <div class="texto_explicacion_formulario">Peso Bruto:</div>
                <div>
                <input class="required" name="pesoBruto" id="pesoBruto" style="width:120px" type="text" value="0.0" title="Ingrese Peso Bruto">
                </div>
                </td>
                <td style="width:120px">
                <div class="texto_explicacion_formulario">Peso Neto:</div>
                <div>
                <input class="required" name="pesoNeto" id="pesoNeto" style="width:120px" type="text" value="0.0" title="Ingrese Peso Neto">
                </div>
                </td >
                <td style="width:120px">
                <div class="texto_explicacion_formulario">Cuantia:</div>
                <div>
                <input class="required" name="cuantia" id="cuantia" style="width:120px" type="text" value="0.0" title="Ingrese Cuantia">
                </div>
                </td>
                
                <td rowspan="3"> &nbsp</td>
                
                <td style="width:120px">
                <div class="texto_explicacion_formulario">Precio Unitario:</div>
                <div>
                <input class="required" name="precioUnitario" id="precioUnitario" style="width:120px" type="text" value="0.00" title="Ingrese Precio">
                </div>
                </td> 
                
                
                </tr>
                <tr>
                
                <td colspan="2">
                <div class="texto_explicacion_formulario">Descripcion:&nbsp</div>
                <div>
                <input class="required" name="descripcion" id="descripcion" style="width:250px" type="text" value="" title="Requerido">
                <input class="required" name="descripcion2" id="descripcion2" style="width:100px" type="hidden" value="" title="">
                </div>
                </td>  
                
              
                <td>
                     <div class="texto_explicacion_formulario">&nbsp</div>
                    <div style="padding-top:30px">
                    <input style="width:100px" type="button" id="btnBuscarD" name="btnBuscarD" value="Buscar" />
                    </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">Partida Arancelaria:</div>
                <div>
                <input class="required" name="partidaArancelaria" id="partidaArancelaria" style="width:110px" type="text" value="" title="Requerido">
                </div>
                </td> 
            
            <td>    
                <div class="texto_explicacion_formulario">&nbsp</div>
                    <div style="padding-top:30px">
                    <input style="width:100px" type="button" id="btnBuscarPA" name="btnBuscarPA" value="Buscar" />
                    </div>
            </td>
            
            
            </tr>
            <tr>
            <td >
                <div class="texto_explicacion_formulario">Referencia:&nbsp</div>
                <div>
                <input name="referencia" id="referencia" style="width:100px" type="text" value="" title="Requerido">
                </div>
                </td> 

                <td>
                <div class="texto_explicacion_formulario" >Total:&nbsp</div>
                <div>
                <input readonly="readonly" class="required read" name="precioTotal" id="precioTotal" style="width:110px" type="text" value="0.00" title="">
                </div>
                </td> 
                
                <td>
                <div class="texto_explicacion_formulario">Unidades:</div>
                <div style="padding-top:10px">
                    <select name="unidades" id="unidades" title="Seleccione una Unidad">
                <?php
                        $result = mysql_query("SELECT * from unidades order by codigo desc", $link);
                while($fila = mysql_fetch_array($result)){
                
                      echo  '<option value="'.$fila["codigo"].'"  >'.$fila["codigo"].' '.$fila["cantidad"].'</option>';       
                 }
                ?>
                    </select> </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">No. Pagina:</div>
                <div style="padding-top:10px">
                    <select name="pagFactura" id="pagFactura" title="Seleccione una Unidad">
                    <?php
                    $result = mysql_query("SELECT paginas from factura where idFactura=".$id_factura." and idRetaceo=".hideunlock($_SESSION["n_declaracion"]), $link);
                    while($fila = mysql_fetch_array($result)){
                     $npg=$fila["paginas"];
                    }
                    for($i=1;$i<=$npg;$i++) 
                    echo  '<option value="'.$i.'">Pagina '.$i.'</option>';       
                     
                    ?>
                    </select>
                </div>  
                </td>                
                
                <td colspan="2">
                    <div class="texto_explicacion_formulario">&nbsp</div>
                    <div style="padding-top: 30px;text-align: LEFT;">
                        <input name="addItem" id="addItem" value="Agregar Item" type="submit">
                    </div>
                </td>
                
                </tr>
             </table>
             </center>
          </form>
<form method="post" style="padding-left: 500px">
<input name="cancel" id="cancel" style="display:none;float: center;" value="Cancelar" type="submit">
</form>
<br>
<!---------------------------FIN DEL FORMULARIO-------------------------------------->


            <div style="float:LEFT" class="texto_explicacion_formulario">Detalles de Items: (De doble click para editar)</div>
           <br>
            <!---Eliminacion Multiple-->
            <form method="post" action="facturas-gestion.php">
            <input style="margin-left:100px" type="submit" name="opdet" value="Eliminar Seleccionados" onclick="return confirm('Esta seguro que desea Eliminar los registros seleccionados?') ;" />
            <input type="hidden" id="id" name="id" value="<?=  hidelock($id_factura)?>">
           
        <table style="margin-top:5px" id="factini" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody><tr bgcolor="#6990BA">
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="40">Id Item</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="350">Descripcion</td>                            
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Bultos</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Peso Bruto</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Cuantia</td>                                
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Precio Unitario</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="70">Total</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="70">Eliminar</td>
           <?php


        //IMPRIME LOS ITEMS DE LA DECLARACION A QUIEN PERTENECE

$total=0;
            while($fact = mysql_fetch_array($items)){
                ?>

                <tr class="flink">
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
                <td style="display:none">
                   <?=$fact["pesoNeto"]?>
                </td>
                <td style="display:none">
                  <?=$fact["partidaArancelaria"]?>
                </td>
                <td style="display:none">
                  <?=$fact["referencia"]?> 
                </td>
                <td style="display:none">
                   <?=$fact["unidades"]?> 
                </td>
                <td style="display:none">
                  <?=$fact["pagFactura"]?> 
                </td>
                <td style="display:none">
                  <?=$fact["idItem"]?> 
                </td>
              <td ><center><input type="checkbox" onclick="delRow(this.checked, '<? echo  $fact["idItem"]; ?>')" /><input type="hidden" name="idsimps[]" id="idsimps<? echo  $fact["idItem"];; ?>" /></center></td>
            </tr>
                                        <?
                                         $total+=round($fact["precioTotal"],2);
                                                }

                                        ?>
            <tr bgcolor="#6990BA">
                    <td bgcolor="#6990BA" colspan="6" class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">TOTAL</td>
                    <td class="tabla_titulo" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226); border-right: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                                <b>$<?echo number_format(round($total,2),2);?></b>
                    </td>
                    <td></td>
            </tr>
            </tbody></table>
            </form>    
            <!---Eliminacion Multiple--->
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


<!------DIV DE DESCRIPCIONES-------->
  <div id="dialogDesc" title="Buscar Descripciones" align="center" style="overflow:hidden">
  <table align="center" border="0" cellpadding="0" cellspacing="0">
        <tbody><tr>
          <td valign="top" align="left">
          <span class="texto_ok">Ingrese una Descripcion:</span><br/>
          <input type="text" id="busqDescripcion" name="busqDescripcion">
          <input type="button" id="btnBusqDesc" name="btnBusqDesc" value="Buscar">                      
          </td>
        </tr>
  </tbody></table>  
      <b>Seleccione una Descripcion</b>
      <br>
<div ID="resDescrip" style="overflow: auto;height: 230px;width:480px">
</div>
      
</div>
<!---------------------------->
<!-- DIV DE ARANCELES -->
  <div id="dialogAranc" title="Buscar Partidas Arancelarias" align="center">
      <table align="center" border="0" cellpadding="0" cellspacing="0">
        <tbody><tr>
          <td valign="top" align="left">
          <span class="texto_ok">Ingrese Inciso:</span><br/>
          <input type="text" id="busqArancel" name="busqArancel">
          <input type="button" id="btnBusqAranc" name="btnBusqAranc" value="Buscar">                      
          </td>
        </tr>
        </tbody></table>
      <b>Seleccione una Partida Arancelaria</b>
      <br>
      <div ID="resArancel" style="overflow: auto;height: 230px;width:480px">
      </div>
      
  </div>
<!---------------------->
<? include_once("../includes/barra_menu.php");?>
</body></html>
<?
$conexion->desconectar($link);
?>