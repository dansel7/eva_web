<?php
session_start();
include_once("../clases/config.php");

include_once("../clases/conexion.php");

$conexion = new conexion();

$link = $conexion->conectar();
        
if(isset($_SESSION["n_declaracion"])){
    
$existencia= mysql_query("SELECT inciso,descripcion,pais FROM retaceoImpuestos where idRetaceo=".  hideunlock($_SESSION["n_declaracion"]), $link);
if(mysql_num_rows($existencia)<=0){
$qry= "select item.partidaArancelaria,item.descripcion,idItem from item inner join factura on item.idFactura=factura.idFactura where item.idRetaceo=".  hideunlock($_SESSION["n_declaracion"])." group by agrupar,partidaArancelaria order by factura.idFactRetaceo,idItemFactura";
$result = mysql_query($qry, $link);
}else{
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
                  $( "#divcalci" ).dialog( "close" ); 
               });
    });
    //function deteccion(){
       // alert('Estás entrando desde un '+navigator.platform);
//}
//window.onload = setTimeout("deteccion();",1000);

    </script>
     <table width="750px"><tr><td class="tabla_titulo" width="80px">No.Item</td><td class="tabla_titulo" width="170px">Partida Arancelaria</td><td class="tabla_titulo" width="250px">Descripcion</td><td class="tabla_titulo" width="250px">Pais</td></tr>
    <?php
                    while($fila = mysql_fetch_array($result)){  
                        $i++;
//DE QUE MANERA SE PODRA AGRUPAR. Y HACER UN MATCH CON LA TABLA RETACEO IMPUESTOS.                        
                        ?>
          <tr>
      <Td class="tabla_filas"><?=$i?><input id="numeroItem" type="hidden" name="numeroItem" value="<?=$i?>"/></Td>
      <Td class="tabla_filas"><?=$fila[0]?><input id="arancel" type="hidden" name="arancel" value="<?=$fila[0]?>"/></Td>
      <Td class="tabla_filas"><input id="descripcion" name="descripcion" style="width:250px" value="<?=$fila[1]?>" /></Td>
      <Td class="tabla_filas">
          <select name="pais" id="pais" >
                <option value="-1" disabled selected>Seleccione una Pais</option>
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
<input type="submit" id="cimp" name="cimp" value="Calcular Impuestos">
<input type="button" id="cancelcimp" name="cancelcimp" value="Cancelar">
<?php
}else{?>
      <h2>Para Calcular los impuestos debe Abrir una Declaracion.<br> <a style="color:blue" href="declaraciones-listado.php">Abrir</a></h2>    
<?php 
}
?> 