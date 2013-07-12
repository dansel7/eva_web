<?

session_start();
//error_reporting(0);
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


$id_declaracion = isset($_REQUEST['id']) ? hideunlock($_REQUEST['id']) : 0;

//SE CREA UNA VARIABLE DE SESION PARA LA DECLARACION CON LA QUE SE ESTARA TRABAJANDO        
if(isset($_REQUEST['id']) && $_REQUEST['id']!="" && !isset($_SESSION["n_declaracion"])){
    $_SESSION["n_declaracion"]=$_REQUEST['id'];
}


$opc = isset($_GET['opc']) ? hideunlock($_GET['opc']) : 0;//variable que define la opcion nuevo,actualizar


//ACCION AL CERRAR UNA DECLARACION, ELIMINA DE LA SESION Y REDIRECCIONA PARA ABRIR OTRO.
if (isset($_POST['cerrar'])){
   unset($_SESSION["n_declaracion"]);
   unset($_SESSION["NIT_EMPRESA"]);
   header ("Location: ../view/".$enlace_listado);
}

if (isset($_POST['submit'])){
//PARA QUE GUARDE EL NIT SIN ENCRIPTACION.	
    $_POST['NIT']=  hideunlock($_POST['NIT']);
    
// -------------------------UPDATE & INSERT--------------------------------//
//Funcion para poder hacer el insert, o update de declaracion
if($_POST['submit']=='Actualizar'){/////-------ACTUALIZAR-----------/////
    
    
        $_POST["fechaModificado"]=date("Y-m-d H:i:s");
        $resultado = $clase_database->formToDB($link,'retaceo','post','', 'submit, frm, ','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');

//CALCULA LOS VALORES DEL RETACEO.
//PRIMERO COMPRUEBA SI SE DEBE CALCULAR EL SEGURO O NO.
if(strtoupper($_POST["calcularSeguro"])=="S")
{

    $porcent=0.0;
    if(strtoupper($_POST["TipoCalculoSeguro"])=="E"){$porcent=0.015;}
    else if(strtoupper($_POST["TipoCalculoSeguro"])=="I"){$porcent=0.0125;}
$resultado = $clase_database->formToDB($link,'retaceo r,(SELECT SUM(otrosGastos) og,SUM(FOB) fob,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(bultos) b 
from factura where idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') f','','r.FOB=f.fob,r.cuantia=f.c,r.pesoBruto=f.pb,r.bultos=f.b,r.otrosGastos=f.og,r.seguro=(f.fob*'.$porcent.'),r.cif=(f.fob+f.og+r.flete)+(f.fob*'.$porcent.')','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
}else{
$resultado = $clase_database->formToDB($link,'retaceo r,(SELECT SUM(otrosGastos) og,SUM(FOB) fob,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(bultos) b 
from factura where idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') f','','r.FOB=f.fob,r.cuantia=f.c,r.pesoBruto=f.pb,r.bultos=f.b,r.otrosGastos=f.og,r.cif=(f.fob+f.og+r.flete)+r.seguro','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');  
}
//------FIN--CALCULA LOS VALORES DEL RETACEO-----
        
        
        if ($resultado){ 
            $mensaje = "Informacion Almacenada Exitosamente";
            $clase_css = "texto_ok";
        }else{
            $mensaje = "Error al Almacenar Informacion";
            $clase_css = "texto_error";
        }
}   
else if($_POST['submit']=='Guardar'){///////----------GUARDAR NUEVO--------/////////
    
        $_POST["usuario"]=$_SESSION["usu"];
        $_POST["estado"]="0";
        $_POST["fechaCreado"]=date("Y-m-d H:i:s");
        $_POST["fechaModificado"]=date("Y-m-d H:i:s");
        $_POST["bultos"]=0.0;
        $_POST["pesoBruto"]=0.0;
        $_POST["cuantia"]=0.0;
        $_POST["FOB"]=0.0;
        $_POST["otrosGastos"]=0.0;
        if($_POST["seguro"]=="")$_POST["seguro"]=0.0;
        $_POST["CIF"]=0.0;
        $_POST["DAI"]=0.0;
        $_POST["IVA"]=0.0;
        $_POST["aPago"]=0.0;
        $_POST["total"]=0.0;
        $resultado = $clase_database->formToDB($link,'retaceo','post','', 'submit, frm, ','insert',''); 
        $idRetNuevo=hidelock(mysql_insert_id());
 
if ($resultado){ 
        header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]."?id=".$idRetNuevo."&xm=1");
       
}else{
        header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]."?xm=2");
}       
}
	
}//LUEGO DE INGRESADO LA NUEVA DECLARACION SE REDIRECCIONA Y SE COMPRUEBA PARA MOSTRAR MSJ
if(isset($_GET["xm"])){
   if($_GET["xm"]==1 && isset($_GET["id"])) {  
       $resultado=true;
        $mensaje = "Informacion Almacenada Exitosamente";
        $clase_css = "texto_ok";
        }else if($_GET["xm"]==2){
        $mensaje = "Error al Almacenar Informacion";
        $clase_css = "texto_error";
        }
}
// ------------------------- FIN UPDATE & INSERT FIN --------------------------------//

//-------------------------------------------------------------------------------
//----AGREGAR DATOS NUEVOS DE FACTURAS
if(isset($_POST['addf'])){
    //SE GENERA NUEVO IDFACTRETACEO
    $idFacNuevo=$clase_database->GenerarNuevoId($link, "idFactRetaceo", "factura","where idRetaceo='".hideunlock($_SESSION["n_declaracion"])."'");
    $_POST["idFactRetaceo"]=($idFacNuevo=="") ? 1 : $idFacNuevo;    
 
    $_POST["idRetaceo"]=hideunlock($_SESSION["n_declaracion"]);
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

            $resultado = $clase_database->formToDB($link,'datosIniciales','post','', 'addf, frmf, paginas, fechaf, ','insert','');
            $_POST["bultos"]=0.0;
            $_POST["pesoBruto"]=0.0;
            $_POST["cuantia"]=0.0;
            $_POST["fob"]=0.0;
            $_POST["total"]=0.0;
            $_POST["pesoNeto"]=0.0;
            $resultado = $clase_database->formToDB($link,'factura','post','','fechaf, addf, frmf, ','insert','');

            if ($resultado){ 
                $mensaje = "Informacion Almacenada Exitosamente";
                $clase_css = "texto_ok";
            }else{
                $mensaje = "Error al Almacenar Informacion";
                $clase_css = "texto_error";
            }

             //ACTUALIZA EL VALOR DE OTROSGASTOS DE LA TABLA DE RETACEO Y EL CIF
           $resultado = $clase_database->formToDB($link,'retaceo','','otrosGastos=(SELECT SUM(otrosGastos) from factura where idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'")','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
          $resultado = $clase_database->formToDB($link,'retaceo','','CIF=flete+otrosGastos+seguro', '','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');

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
 $resultado = $clase_database->formToDB($link,'datosIniciales','post','', 'paginas, fechaf, idFactRetaceo, updf, paginas, ','update','idFactRetaceo="'.trim($_POST['idFactRetaceo']).'" and idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
 $resultado = $clase_database->formToDB($link,'factura','post','', 'fechaf, idFactRetaceo, updf, bultos, cuantia, pesoBruto, fob, ','update','idFactRetaceo="'.trim($_POST['idFactRetaceo']).'" and idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
  
     if ($resultado){ 
            $mensaje = "Informacion Almacenada Exitosamente";
            $clase_css = "texto_ok";
        }else{
            $mensaje = "Error al Almacenar Informacion";
            $clase_css = "texto_error";
      } 
      //ACTUALIZA EL VALOR DE OTROSGASTOS DE LA TABLA DE RETACEO Y EL CIF
      $resultado = $clase_database->formToDB($link,'retaceo','','otrosGastos=(SELECT SUM(otrosGastos) from factura where idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'")','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
      $resultado = $clase_database->formToDB($link,'retaceo','','CIF=flete+otrosGastos+seguro', '','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');

   }

   
   
   
}
//
////------OBTIENE LOS DATOS DE LA DECLARACION DESDE LA BD PARA ABRIRLO-------
//VARIABLE QUE VERIFICA SI LA DECLARACION EXISTE Y ES VALIDA.
$dec_valid=0;
//CARGA DE DATOS DESDE BD
if($id_declaracion != "" || $id_declaracion != "0"){
        $result = mysql_query("SELECT * FROM retaceo WHERE idRetaceo='".$id_declaracion."'", $link);
        $dec_valid= mysql_num_rows($result);
       
        while($fila = mysql_fetch_array($result)){
                
                $nitempresa = $fila['NIT'];
                $ncontrol=$fila['numero'];
                $fecha= substr($fila['fecha'],0,10);
                $nretaceo=$fila['numRegistro'];
                $modelodeclaracion= $fila['modeloDeclaracion'];
                $modotransporte= $fila['modoTransporte'];
                $numdoctransporte= $fila['numeroDocumentoTransporte'];
                $flete= $fila['flete'];
                $TipoCalcSeguro= $fila['TipoCalculoSeguro'];
                $CalcSeguro= $fila['calcularSeguro'];
                $seguro=$fila['seguro'];
                $porcentSeguro=$fila['porcentSeguro'];
                
        }
        
        //CREA VARIABLE DE SESSION PARA COMPROBAR SI SE DEBE CALCULAR EL SEGURO O NO
        if(!isset($_SESSION["calculoseguro"]) || isset($_POST['submit'])){
            $_SESSION["calculoseguro"]=$CalcSeguro;
        }
        
        if($CalcSeguro=="N"){
         $_SESSION["porcentSeguro"]=$porcentSeguro;  
        }else if($CalcSeguro=="S"){
         $_SESSION["TPSeguro"]=$TipoCalcSeguro;  
        }
        
        //CREA UNA VARIABLE DE SESION DE EL NIT DE LA EMPRESA
        if(!isset($_SESSION["NIT_EMPRESA"])){
        $_SESSION["NIT_EMPRESA"]=$nitempresa;
        }
        
        
                	
       //ELIMINACION MULTIPLE
if(isset($_POST['opdet'])){
			$cntDel = 0;
			$selDels = $_POST['idsimps'];
            
			foreach($selDels as $idSel) {
			 	
				if($idSel != '') {
                                    $val=explode(",", $idSel);

                                     $result = $clase_database->Eliminar($link,'datosIniciales','idDatosIniciales =' . $val[0]);
                                     $result = $clase_database->Eliminar($link,'factura','idFactura =' . $val[1]);
                                       if($result) 
					$cntDel++;
				}
			}
                        
//CALCULA LOS VALORES DEL RETACEO.
//PRIMERO COMPRUEBA SI SE DEBE CALCULAR EL SEGURO O NO.
if(strtoupper($_SESSION["calculoseguro"])=="S")
{
    $porcent=0.0;
    if(strtoupper($_SESSION["TPSeguro"])=="E"){$porcent=0.015;}
    else if(strtoupper($_SESSION["TPSeguro"])=="I"){$porcent=0.0125;}
$resultado = $clase_database->formToDB($link,'retaceo r,(SELECT SUM(otrosGastos) og,SUM(FOB) fob,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(bultos) b 
from factura where idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') f','','r.FOB=f.fob,r.cuantia=f.c,r.pesoBruto=f.pb,r.bultos=f.b,r.otrosGastos=f.og,r.seguro=(f.fob*'.$porcent.'),r.cif=(f.fob+f.og+r.flete)+(f.fob*'.$porcent.')','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');
}else{
$resultado = $clase_database->formToDB($link,'retaceo r,(SELECT SUM(otrosGastos) og,SUM(FOB) fob,SUM(cuantia) c,SUM(pesoBruto) pb,SUM(bultos) b 
from factura where idRetaceo='.hideunlock($_SESSION["n_declaracion"]).') f','','r.FOB=f.fob,r.cuantia=f.c,r.pesoBruto=f.pb,r.bultos=f.b,r.otrosGastos=f.og,r.cif=(f.fob+f.og+r.flete)+r.seguro','','update','idRetaceo="'.hideunlock($_SESSION["n_declaracion"]).'"');  
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
        
              //consulta que genera un preview de las facturas de un retaceo definido
             $facturas = mysql_query("SELECT d.*,f.paginas,f.idFactura FROM datosIniciales d,factura f WHERE f.idFactRetaceo=d.idFactRetaceo and f.idRetaceo=".$id_declaracion." and d.idRetaceo =".$id_declaracion, $link);

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
                    $("#frmf").validate();   
//funcion para poner el valor retornado de la BD
                    $("#modotransporte ").val('<?=$modotransporte?>');
                    var modeldec="<?=$modelodeclaracion?>";
                    //y sino deja seleccionado por default im4
                    if(modeldec=="")modeldec="IM4";
                    $("#modelodeclaracion ").val(modeldec);

                    //FUNCION QUE QUITE MODO LECTURA AL SEGURO AL DESCHEQUEAR CHECK CALCULARSEGURO
                    $("#calcularSeguro").click(function(){
                        
                       if($("#calcularSeguro").is(':checked')) { 
                           $("#seguro").attr("readonly",true);
                           $("#seguro").attr("class","read");
                           $("#TipoCalculoSeguroE").removeAttr("disabled");
                           $("#TipoCalculoSeguroI").removeAttr("disabled");
                           $("#tdPorc").css("display","none");
                          
                       }else{
                            $("#seguro").attr("readonly",false);
                            $("#seguro").attr("class","required");
                            $("#TipoCalculoSeguroE").attr("disabled", true);
                            $("#TipoCalculoSeguroI").attr("disabled", true);
                            $("#tdPorc").css("display","block");
                       }
                       
                    });
                    if($("#calcularSeguro").is(':checked')) { 
                           $("#seguro").attr("readonly",true);
                           $("#seguro").attr("class","read");
                           $("#TipoCalculoSeguroE").removeAttr("disabled");
                           $("#TipoCalculoSeguroI").removeAttr("disabled");
                           $("#tdPorc").css("display","none");
                          
                       }else{
                            $("#seguro").attr("readonly",false);
                            $("#seguro").attr("class","required");
                            $("#TipoCalculoSeguroE").attr("disabled", true);
                            $("#TipoCalculoSeguroI").attr("disabled", true);
                            $("#tdPorc").css("display","block");
                       }
                    
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
                    if(tds.eq(0).html()!="Id Factura" && tds.eq(0).html()!="TOTAL"){
                    
                     $('#frmf #idFactRetaceo').val(tds.eq(0).html().trim());
                     $('#frmf #numero').val(tds.eq(1).html().trim());
                     $('#frmf #fechaf').val(tds.eq(2).html().trim());
                     $('#frmf #paginas').val(tds.eq(3).html().trim());
                     $('#frmf #bultos').val(tds.eq(4).html().trim());
                     $('#frmf #pesoBruto').val(tds.eq(5).html().trim());
                     $('#frmf #cuantia').val(tds.eq(6).html().trim());
                     $('#frmf #otrosGastos').val(tds.eq(7).html().trim());
                     $('#frmf #fob').val(tds.eq(8).html().trim());
                     
                     $('#frmf #addf').attr("value","Actualizar Datos");
                     $('#frmf #addf').attr("name","updf");
                     $('#frmf #addf').attr("id","updf");
                     $('#cancel').css("display","block");
                     //SOLO FALTA QUE ACTUALICE EN LA FUNCION DE PHP
                     }   
                     
                    });                    

                });    

 function delRow(cheque,idsimps,iddatos){//funcion para eliminacion multiple
       var facts = new Array();
       facts[0] =idsimps ;
       facts[1] = iddatos;
       
       
		if(cheque)
			document.getElementById('idsimps'+idsimps).value = facts;
		else
			document.getElementById('idsimps'+idsimps).value = '';
	}
    </script>
    
    
          <?
        //SI LA OPCION ES NUEVA SOLAMENTE 
        //------SE GENERA UN NUEVO NUMERO DE CONTROL------
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
                  <a href="index.php"><img src="../images/icono-afp.gif" border="0"></a>
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

<? if($resultado && isset($_REQUEST["submit"])){?>
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
         <form name="frm" id="frm" action="<?=$enlace_gestion?>" method="post" style="margin:0px;"> 
        <?php
        } 
        else if(strcmp($dec_valid,0)){
            ?>
         <form name="frm" id="frm" action="<?=$_SERVER['REQUEST_URI'];?>" method="post" style="margin:0px;"> 
        <?php    
        }	
       ?>
<!------------INICIO DE LOS CAMPOS DEL FORMULARIO-------------->
<CENTER>
<TABLE cellpadding="5">
<TR><TD WIDTH="150">
                         
        <div class="texto_explicacion_formulario">N&uacute;mero de Control:&nbsp;</div><br><br>
               <div>
               <input style="background-color:#F0F0F0;width:80px" class="required" name="numero" id="numero" readonly rows="1" value="<? echo isset($ncontrol) ? $ncontrol : "";?>" type="text" title="Numero de Control de Declaraciones">
               </div>
</TD><TD WIDTH="200">
                          <div class="texto_explicacion_formulario">N&uacute;mero de Retaceo:&nbsp;</div><br><br>
                          <div>
                               <input class="required" name="numRegistro" id="numRegistro" rows="1" value="<? echo isset($nretaceo) ? $nretaceo : "";?>" type="text" title="Ingrese el numero de Retaceo de la Empresa">

                                </div>
</TD><TD WIDTH="250">
                <div class="texto_explicacion_formulario">Nombre de Empresa:&nbsp;</div><br><br>

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


</TD></TR>
<TR><TD>
                          <div class="texto_explicacion_formulario">Fecha:&nbsp;</div><br><br>
                                        <div>
                                                <input class="required" name="fecha" readonly id="fecha" rows="1" value="<? echo isset($fecha) ? $fecha : date("Y-m-d");?>" type="text" title="">
                                        </div>
</TD><TD>                      

            <div class="texto_explicacion_formulario">Numero de Documento de Transporte:&nbsp;</div><br><br>
            <div>
            <input class="required" name="numeroDocumentoTransporte" id="numeroDocumentoTransporte" rows="1" value="<? echo isset($numdoctransporte) ? $numdoctransporte : "";?>" type="text" title="Ingrese No. de Transporte">
            </div>
</TD><TD>
         <div class="texto_explicacion_formulario">Flete:&nbsp;</div><br><br>
        <div>
        <input class="required" name="flete" id="flete" rows="1" type="text" value="<? echo isset($flete) ? $flete : "";?>" title="Ingrese el Valor de Flete">
        </div>
         
</TD></TR>
<TR><TD>
                                   
         <div class="texto_explicacion_formulario">Modo de Transporte:&nbsp;</div><br><br>
         <div>

         <select id="modoTransporte" name="modoTransporte" title="Seleccione el Modo de Transporte">
               <option value="0" >Terrestre</option>
               <option value="1" >A&eacute;reo</option>
               <option value="2" >Mar&iacute;timo</option>
               <option value="3" >Ferreo</option>
               <option value="4" >Multimodal</option>

         </select>		
        </div>
         
</TD><TD>
            <div class="texto_explicacion_formulario">Modelo de Declaraci&oacute;n:&nbsp;</div><br><br>

            <div>

              <select id="modeloDeclaracion" name="modeloDeclaracion" title="Seleccione Modelo">

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
 </TD>
 <TD>
     <table>
      <tr>
         <td>
            <div class="texto_explicacion_formulario">Seguro:&nbsp;</div><br><br>
            <div>
            <input class="required read" name="seguro" id="seguro" readonly rows="1" type="text" value="<? echo isset($seguro) ? $seguro : "";?>" title="">
            </div>
        </td>
        <td id="tdPorc" style="display:none">
            <div class="texto_explicacion_formulario">Porcentaje de Seguro:&nbsp;</div><br><br>
            <div>
            <input class="required" name="PorcentSeguro" id="PorcentSeguro"  rows="1" type="text" value="<? echo isset($porcentSeguro) ? $porcentSeguro : "";?>" title="">%
            </div>
        </td>
      </tr>
     </table>
        
</TD</TR>
<TR>
    <TD colspan="3">
        <div style="margin-left:220px;margin-top: -15px">
          <div class="texto_explicacion_formulario">Calcular Seguro:&nbsp;</div>
             <div class="texto_explicacion_formulario">
                  <Input type='hidden' Name='calcularSeguro' value="N">
                  <Input id='calcularSeguro' type='Checkbox' <? if(isset($CalcSeguro)){if($CalcSeguro=="S")echo "Checked";}?> Name='calcularSeguro' value="S">
                 
             </div>
          <br>
        <div style="margin-top:15px;margin-left: -175px" class="texto_explicacion_formulario">Tipo de Calculo de Seguro:</div>
        <br><br>
        <div style="margin-top:-25px;margin-left:5px" class="texto_explicacion_formulario">
            <b alt="Transporte Internacional">Externo:
            <Input  type = 'Radio' id ='TipoCalculoSeguroE' Name ='TipoCalculoSeguro' <? if(isset($TipoCalcSeguro)){if($TipoCalcSeguro=="E")echo "Checked";}else{echo "Checked";} ?> value= 'E'></b>
            <b alt="Transporte Regional">Interno:
            <Input  type = 'Radio' id ='TipoCalculoSeguroI' Name ='TipoCalculoSeguro' <? if(isset($TipoCalcSeguro)){if($TipoCalcSeguro=="I")echo "Checked";} ?> value= 'I'></b>
        </div>
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
        else if(strcmp($dec_valid,0)){
            ?>
          <div><input name="submit" id="submit" style="float: center" value="Actualizar" type="submit"></div>
        
        </form>
         <!--FORMULARIO, AL HACER SUBMIT CIERRA LA DECLARACION    -->
        <form action="<?=$enlace_gestion.'?id='.hidelock($ncontrol)?>" method="post">
        <div><input name="cerrar" id="cerrar" style="background:#785635;float: center" value="Cerrar Declaracion" type="submit"></div>
        </form>
               </center>   
      <?php    
        }	
      ?>   
             
<!---------------------------FIN DEL FORMULARIO DECLARACIONES-------------------------------------->

          <?php
           //si es un retaceo existente que muestre sus facturas si es que tiene
           if($dec_valid != "" || $dec_valid !="0")
               {
                ?>

<!--------------------------INCIIO DEL FORM PARA INGRESAR DATOS INICIALES DE FACTURAS ----------------------->             
<a name="FactInic"></a>
<? if($resultado && (isset($_REQUEST["updf"]) || isset($_REQUEST["updf"]))){?>

  <table align="center" border="0" cellpadding="0" cellspacing="0" width="930">
    <tbody><tr>
      <td valign="top">
      <br />
      <span class="<? echo $clase_css; ?>" style="color: #ff3300"><? echo $mensaje; ?></span>
      <br />
      </td>
    </tr>
  </tbody></table> 

<? } ?>
<form class="frmspecial" name="frmf" id="frmf" action="<?=$_SERVER['REQUEST_URI'];?>#FactInic" method="post" style="margin:0px;"> 
                 
              <h4 style="font-family:helvetica">Agregar Datos Iniciales de Factura <br><b style="font-size:12px;color: #785635">(Utilice la Tecla Tab para pasar de un campo a otro)</b></h4>
              <input class="required" name="idFactRetaceo" id="idFactRetaceo" type="hidden" value="" title="">
                
             <table><tr>
                <td>
                <div class="texto_explicacion_formulario">Numero Factura:</div>
                <div>
                <input class="required" name="numero" id="numero" type="text" value="" title="Ingrese No. De Factura">
                </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">Fecha:</div>
                <div>
                <input class="required" name="fechaf" id="fechaf" type="text" value="<?=date("Y-m-d");?>" title="Ingrese Fecha Factura">
                </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">Bultos:</div>
                <div>
                <input class="" name="bultos" id="bultos" type="text" value="0.0" title="Ingrese Bultos">
                </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">Peso Bruto(lbs):</div>
                <div>
                <input class="" name="pesoBruto" id="pesoBruto" type="text" value="0.0" title="Ingrese Peso Bruto">
                </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">Cuantia:</div>
                <div>
                <input class="" name="cuantia" id="cuantia" type="text" value="0.0" title="Ingrese Cuantia">
                </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">Gastos:</div>
                <div>
                <input class="required" name="otrosGastos" id="otrosGastos" type="text" value="0.0" title="Ingrese Otros Gastos">
                </div>
                </td>
                
                <td>
                <div class="texto_explicacion_formulario">FOB:</div>
                <div>
                <input class="required" name="fob" id="fob" type="text" value="0.0" title="Ingrese FOB">
                </div>
                </td>  
                </tr>
            <tr>
                <td colspan="5"></td>
                <td><div class="texto_explicacion_formulario">No. Paginas:</div>
                <div>
                <input class=""  name="paginas" id="paginas" type="text" value="1" title="Ingrese Numero Paginas">
                </div></td>
                
                <td><div class="texto_explicacion_formulario">&nbsp</div><div><input name="addf" id="addf" style="float: right;" value="Agregar Factura" type="submit"></div>
                  
                </td> 
            </tr>
             </table>
          </form>
<form method="Post">
<input name="cancel" id="cancel" style="display:none;float: right;" value="Cancelar" type="submit">
</form>
<br>
<!---------------------------FIN DEL FORMULARIO-------------------------------------->


            <div style="float:center" class="texto_explicacion_formulario">Detalles de Facturas: (Doble click para editar) - Para Ingresar Items Seleccione la opcion Facturas <img src="../images/icono-tienda.gif" width="25" height="20" border="0"></div>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>"> 

    <input style="margin-left:45%;margin-bottom:5px" type="submit" name="opdet" value="Eliminar Seleccionados" onclick="return confirm('Esta seguro que desea Eliminar los registros seleccionados?') ;" />
    
            <table id="factini" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody><tr bgcolor="#6990BA" >
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Id Factura</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Numero Factura</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Fecha</td>                              
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Paginas</td> 
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Bultos</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Peso Bruto(lbs)</td>
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Cuantia</td>                                
                <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Gastos</td>
                <td class="tabla_titulo" style="border: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">FOB</td>
                <td class="tabla_titulo" style="border: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">Eliminar</td>
                  </tr>
           <?php


        //imprime las facturas del retaceo que pertenece
        $FOBtotal=0;
        $GASTOStotal=0;

            while($fact = mysql_fetch_array($facturas)){
                ?>

                <tr class="flink">
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="70">
                <?=$fact["idFactRetaceo"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="70">
                <?=$fact["numero"]?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                <?=substr($fact["fecha"],0,10)?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                <?=$fact["paginas"]?>
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
                <? $GASTOStotal+=$fact["otrosGastos"];echo $fact["otrosGastos"];?>
                </td>
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">
                <? $FOBtotal+=$fact["FOB"];echo $fact["FOB"];?>
                </td>
                
                <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226); border-right: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                <center><input type="checkbox" onclick="delRow(this.checked,'<? echo  $fact["idDatosIniciales"]; ?>', '<? echo  $fact["idFactura"]; ?>')" /><input type="hidden" name="idsimps[]" id="idsimps<? echo  $fact["idDatosIniciales"];; ?>" /></center>
                </td>
                
            </tr>
                                        <?
                                                }

                                        ?>
            <tr bgcolor="#6990BA">
                    <td bgcolor="#6990BA" colspan="7" class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80">TOTAL</td>
                    <td class="tabla_titulo" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226); border-right: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                                <b>$<?echo number_format(round($GASTOStotal,2),2);?></b>
                    </td>
                    <td class="tabla_titulo" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226); border-right: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle">
                                <b>$<?echo number_format(round($FOBtotal,2),2);?></b>
                    </td>
                    <td>
                        
                    </td>
            </tr>
            </tbody></table> <? }?>
         </form>
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