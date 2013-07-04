<?php
session_start();
include_once("../clases/config.php");

include_once("../clases/conexion.php");

$conexion = new conexion();

$link = $conexion->conectar();
        
if(isset($_SESSION["n_declaracion"])){//SI ESTA LOGUEADO MUESTRA SUS RESPECTIVOS IMPUESTOS.

    
if(isset($_GET["ins"])){//INSERCION DE CALCULOS DE IMPUESTOS (INSERCION EN CASO ESPECIAL)
    
    
    $cont=  count($_POST["idRetaceo"]);//CUANTAS FILAS DE DEVOLVIO EL ARREGLO
    $items[$cont];
    //print_r($_POST);
    //SE ORDENA LAS FILAS OBTENIDAS Y ENVIADAS DESDE EL FRM. POR MEDIO DE JQUERY.
    foreach ($_POST as $campo => $valor)
    {
        $campos.=$campo.", ";//CAMPOS OBTENIDOS DEL FRM

        $i=0;
        for($n=0;$n<=4;$n++){
            if($campo=='idItemImp')
            $items[$i].=$valor[$n].", ";//SE ALMACENA EN LA MATRIZ CORRESPONDIENTE LOS VALORES
            else
            $items[$i].="'".$valor[$n]."', ";
        $i++;
        }
    }

    $campos= preg_replace('/, $/', '', $campos);//SE QUITA LA COMA DEL FINAL
    $items= preg_replace('/, $/', '', $items);//SE QUITA LA COMA DEL FINAL
    $sql = "INSERT INTO retaceoimpuestos (".$campos.") VALUES "; //INICIO DEL INSERT MULTIPLE, SE AGREGAN LOS CAMPOS.

    for($x=0;$x<$cont;$x++){// SE ADJUNTAN LOS VALORES A INGRESAR
    $sql.="(".$items[$x]."), ";
    }
    
       //LIMPIA LOS DATOS PREVIOS QUE ESTEN.
   $cleanQry="DELETE FROM retaceoimpuestos WHERE idRetaceo = ".  hideunlock($_SESSION["n_declaracion"]);//SE ELIMINA LA COMA DEL FINAL
   mysql_query($cleanQry,$link);//SE EJECUTA LA CONSULTA
   
   
    $sql=preg_replace('/, $/', '', $sql);//SE ELIMINA LA COMA DEL FINAL
    //echo $sql;
    $resultado = mysql_query($sql,$link);//SE EJECUTA LA CONSULTA

        if ($resultado){ 
                $mensaje = "Impuestos Calculados Exitosamente";
                $clase_css = "texto_ok";
        }else{
                $mensaje = "Error al Almacenar Informacion";
                $clase_css = "texto_error";
        }

}

if(isset($_GET["res"])){//REINICIALIZAR LOS DATOS DEL RETACEO
       //LIMPIA LOS DATOS PREVIOS QUE ESTEN.
   $cleanQry="DELETE FROM retaceoimpuestos WHERE idRetaceo = ".  hideunlock($_SESSION["n_declaracion"]);//SE ELIMINA LA COMA DEL FINAL
    $resultado = mysql_query($cleanQry,$link);//SE EJECUTA LA CONSULTA
   
    if ($resultado){ 
                $mensaje = "Datos Reinicializados Exitosamente";
                $clase_css = "texto_ok";
        }else{
                $mensaje = "Error al Almacenar Informacion";
                $clase_css = "texto_error";
        }
    
}
   
    
    
$existencia= mysql_query("SELECT inciso,descripcion,pais FROM retaceoImpuestos where idRetaceo=".  hideunlock($_SESSION["n_declaracion"]), $link);
$msj="";

if(mysql_num_rows($existencia)<=0){//SI NO HAY CALCULO HECHOS EL OBTIENE LOS ITEMS.
$qry= "select item.partidaArancelaria,item.descripcion,idItem,arancel.dai ,item.agrupar 
    from item inner join factura on item.idFactura=factura.idFactura inner join arancel on arancel.inciso=item.partidaArancelaria
    where item.idRetaceo=".  hideunlock($_SESSION["n_declaracion"]) ." 
    group by agrupar,partidaArancelaria 
    order by factura.idFactRetaceo,idItemFactura";

$result = mysql_query($qry, $link);
$msj="<h3 style='color:red'>No se ha realizado ningun calculo de impuestos</h3>";
}else{//SI HAY CALCULO SOLAMENTE MOSTRARA LOS QUE ESTEN EN LA TABLA DE RETACEOIMPUESTO
$result = $existencia;   
}

$paises = mysql_query("SELECT * FROM paises", $link);
$datos = array();
while ($rowp=mysql_fetch_row($paises)){
$datos[]=$rowp;
}

$i=0;
?>
  <script>
    // increase the default animation speed to exaggerate the effect
    $.fx.speeds._default = 1000;
    
    $(function() {
       $( "#cancelcimp" ).click(function() {
                  $( "#divcalci" ).dialog("close"); 
               });
               
       $( "#resetimp" ).click(function() {
               $("#divcalci").html("<center><img width='50px' height='50px' src='../images/load.gif'></center>");
               
              $.post("../includes/calc-impuestos.php?res=1",
                {resetImp:"true"},
               function(data){
               $("#divcalci").html(data);  
               })
            $( "#divcalci" ).dialog( "open" );
            return false;
        });       
        
        
       $( "#cimp" ).click(function() {
           var items = $("#frmImp").serialize();//ITEMS
               $("#divcalci").html("<center><img width='50px' height='50px' src='../images/load.gif'></center>");
               //ENVIARLE POR POST A SCRIPT PHP EL ARRAY DE LA TABLA CON LOS ITEMS PARA SU CALCULO.
               
              $.post("../includes/calc-impuestos.php?ins=1",
                items,
               function(data){
               $("#divcalci").html(data);  
               })
            $( "#divcalci" ).dialog( "open" );
            return false;
        });
               
                          
    });
    
    
//    function deteccion(){
//        alert('Estás entrando desde un '+navigator.platform);
//}
//window.onload = setTimeout("deteccion();",1000);

    </script>
    
<? if($resultado){?>
      <span class="<? echo $clase_css; ?>"><? echo $mensaje; ?></span>
      <br><br>
<? } ?>


    <form id="frmImp" name="frmImp">
      
     <table width="750px"><tr><td class="tabla_titulo" width="80px">No.Item</td><td class="tabla_titulo" width="170px">Partida Arancelaria</td><td class="tabla_titulo" width="250px">Descripcion</td><td class="tabla_titulo" width="250px">Pais</td></tr>
    <?php
    echo $msj;
      while($fila = mysql_fetch_array($result)){  
                        $i++;                        
                        ?>
          <tr>
      <Td class="tabla_filas"><?=$i?>
      <input id="idRetaceo" type="hidden" name="idRetaceo[]" value="<?=hideunlock($_SESSION["n_declaracion"])?>"/>
          <input id="idItemImp" type="hidden" name="idItemImp[]" value="<?=$i?>"/></Td>
      <Td class="tabla_filas"><?=$fila[0]?>
          <input id="inciso" type="hidden" name="inciso[]" value="<?=$fila[0]?>"/></Td>
      <input id="arancel" type="hidden" name="arancel[]" value="<?=$fila[3]?>"/></Td>
     <input id="agrupar" type="hidden" name="agrupar[]" value="<?=$fila[4]?>"/></Td>
      <Td class="tabla_filas">
          <input id="descripcion" name="descripcion[]" style="width:250px" value="<?=htmlentities($fila[1])?>" /></Td>
      <Td class="tabla_filas">
          <select name="pais[]" id="pais" >
                <option value="" selected>Seleccione una Pais</option>
                echo $paises;
                    <? foreach($datos as $val){ ?>
                 <option value="<?=$val[0]?>" <?if($val[0]==$fila[2]) echo "selected";?> ><?=$val[1]?></option>								
                <? }?>
          </select>
      </Td>
          </tr>
    <?php
  }
    ?>
    </table>
      
<br>
<input type="submit" id="cimp" name="cimp" onclick="return false;" value="Calcular Impuestos">
<input type="submit" id="resetimp" onclick="return false;" name="resetimp" value="Reinicializar Datos">
<input type="button" id="cancelcimp" name="cancelcimp" value="Cancelar">
</form>
<?php
}else{?>
      <h2>Para Calcular los impuestos debe Abrir una Declaracion.<br> <a style="color:blue" href="declaraciones-listado.php">Abrir</a></h2>    
<?php 
}
?> 