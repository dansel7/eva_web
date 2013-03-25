<script>
    //SELECCION DE ARANCEL
   $('#TPartidas tr').click(function()
    {

        var tds=$(this).find("td");
     //funcion para actualizar datos iniciales de facturas
        if(tds.eq(0).html()!="DESCRIPCION COMERCIAL"){
         $('#partidaArancelaria').val(tds.eq(1).html());
        }
        $( "#dialogAranc" ).dialog("close");
    });
   //FIN DE SELECCION    
</script>    
    
<table id="TPartidas" name="TPartidas" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr bgcolor="#EBEBEB">
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="75px">DAI</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="50px">INCISO</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="100px">DESCRIPCION ARANCELARIA</td>
  </tr>
<?
	include_once("../clases/conexion.php");
	$conexionCombos = new conexion();
	$link = $conexionCombos->conectar();
	
	$ARANCEL = $_POST["arancel"];
        
  	$sql_incisos = "SELECT dai,inciso,descripcion FROM ARANCEL WHERE INCISO LIKE '%".$ARANCEL."%'";
	$result = mysql_query($sql_incisos,$link);
	$contador = 0;
	while($filas = mysql_fetch_array($result)){ 
		$contador++;
	?>
	
      <tr bgcolor="#FBFBFB" class="flink">
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="RIGHT" height="34" valign="middle"><? echo $filas['dai'];?></td>
        <td class="tabla_filas" style="padding-left: 1px; border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo $filas['inciso'];?></td>
        <td class="tabla_filas" style="padding-left: 1px; border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo htmlentities($filas['descripcion']);?></td>
      </tr>
    
	<?
	}
         echo "- - $contador Resultados Obtenidos - -<br><br>";
	$conexionCombos->desconectar($link);
  ?>
      </tbody></table>
      
