<script>
           //SELECCION DE DESCRIPCION
               $('#Tdesc tr').click(function()
                {
                        
                    var tds=$(this).find("td");
                 //funcion para actualizar datos iniciales de facturas
                    if(tds.eq(0).html()!="DESCRIPCION COMERCIAL"){
                     $('#descripcion').val(tds.eq(0).html());
                     $('#partidaArancelaria').val(tds.eq(1).html());
                     if($('#precioUnitario').val()=="0.00" || $('#precioUnitario').val()==""){
                     $('#precioUnitario').val(parseFloat(tds.eq(2).html()).toFixed(2));
                     }
                    }
                    $( "#dialogDesc" ).dialog("close");
                });
               //FIN DE SELECCION 
                  
</script>    

<table id="Tdesc" name="Tdesc" align="center" border="0" cellpadding="0" cellspacing="0" width="390px">
  <tbody><tr bgcolor="#EBEBEB">
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="100px">DESCRIPCION COMERCIAL</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="100px">INCISO</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="80px">PRECIO UNITARIO</td>
  </tr>
<?

	include_once("../clases/conexion.php");
	$conexionCombos = new conexion();
	$link = $conexionCombos->conectar();
	$DESCRIPCION = $_POST["descripcion"];
        $NIT_EMPRESA = hideunlock($_POST["nit_empresa"]);
        
  	$sql_descrip = "SELECT D.descripcion,D.inciso,D.precioUnitario FROM DESCRIPCION D INNER JOIN DESCRIPCIONEMPRESA DE ON D.INCISO=DE.INCISO WHERE DE.EMPRESA ='".$NIT_EMPRESA."' AND D.DESCRIPCION  LIKE '%".$DESCRIPCION."%'";

        $result = mysql_query($sql_descrip,$link);
	$contador = 0;
	while($filas = mysql_fetch_array($result)){
	$contador++;
	?>
	
      <tr bgcolor="#FBFBFB" class="flink">
        <td class="tabla_filas" style="font-size:12px;padding-left: 1px; border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo htmlentities($filas['descripcion']);?></td>
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo $filas['inciso'];?></td>
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo $filas['precioUnitario'];?></td>
      </tr>
    
	<?
	}
        echo "- - $contador Resultados Obtenidos - -<br><br>";
	$conexionCombos->desconectar($link);
  ?>
      </tbody></table>
      
