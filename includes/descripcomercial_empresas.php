<script>
    //BUSQUEDA FILTRO LOCAL
    $.expr[':'].containsIgnoreCase = function(e,i,m){
    return jQuery(e).text().toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

     $("#filtro_txt").keyup(function(){
            
 //Primero oculta todas las filas y luego muestra solo el encabezado
          $("#desc_table").find("tr").hide();
          $("#desc_table").find("tr").eq(0).show();
 //convierte los datos de las filas en un solo arreglo
          var data = this.value.split(" ");
 //crea un objeto con las filas
          var jo = $("#desc_table").find("tr");
 //Filtro recursivo de Jquery para recibir los resultados. 
          $.each(data, function(i, v){
               jo = jo.filter("*:containsIgnoreCase('"+v+"')");
          });
 //Muestra las filas que concuerdan.
          jo.show();

if($("#filtro_txt").val()=="") $("#desc_table").find("tr").show();

      });
      
    </script>
    <label class="texto_ok">Filtro Descripcion, Inciso: </label><input type="text" id="filtro_txt" name="filtro_txt" >
<br><br>
<table id="desc_table" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr bgcolor="#EBEBEB">
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="50px">#</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="200px">DESCRIPCION COMERCIAL</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="300px">PARTIDA ARANCELARIA</td>
    <td class="tabla_titulo" style="border-top: 1px solid rgb(226, 226, 226); border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle" width="75px">EDITAR</td>
    <td class="tabla_titulo" style="border: 1px solid rgb(226, 226, 226);" 																							   align="center" height="34" valign="middle" width="75px">ELIMINAR</td>
  </tr>
<?
	include_once("../clases/conexion.php");
	$conexionCombos = new conexion();
	$linkCombos = $conexionCombos->conectar();
	
	$id = hideunlock($_POST["id"]);
	
        If($id == "") {
            $paramEmp = "'%%' or empresa is null";
        }Else{
            $paramEmp = "'%" . $id . "%'";
        }
		  /*	$sql_empresas =  "select descripcion.inciso , descripcion.descripcion , descripcion.descripcion2, descripcion.precioUnitario, " .
                        " IFNULL(descripcionempresa.empresa,'') nit, IFNULL(empresas.nombre,'') empresa " .
                        " from descripcion left join descripcionempresa on (descripcion.inciso = descripcionempresa.inciso) " .
                        " left join empresas on (descripcionempresa.empresa = empresas.nit) " .
                        " where descripcion like '%%' " .
                        " and tipoDescripcion='N' " .
                        " and descripcionempresa.empresa like " . $paramEmp .
                        " order by descripcion asc, empresa desc ";
		   */

	
  	$sql_empresas =  "select descripcion.inciso , descripcion.descripcion" .
                        " from descripcion left join descripcionempresa on (descripcion.inciso = descripcionempresa.inciso) " .
                        " left join empresas on (descripcionempresa.empresa = empresas.nit) " .
                        " where descripcion like '%%' " .
                        " and tipoDescripcion='N' " .
                        " and descripcionempresa.empresa like " . $paramEmp .
                        " order by descripcion asc, empresa desc ";
                     
	$result = mysql_query($sql_empresas,$linkCombos);
	$contador = 0;
	while($filas = mysql_fetch_array($result)){
		$contador++;
	?>
	
              <tr bgcolor="#FBFBFB">
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo $contador;?></td>
        <td class="tabla_filas" style="padding-left: 20px; border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo htmlentities($filas['descripcion']);?></td>
        <td class="tabla_filas" style="padding-left: 20px; border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><? echo $filas['inciso'];?></td>
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><a href="descripcomercial-gestion.php?id=<? echo $filas['inciso'];?>"><img src="../images/icono-editar.gif" border="0"></a></td>
        <td class="tabla_filas" style="border-left: 1px solid rgb(226, 226, 226); border-bottom: 1px solid rgb(226, 226, 226); border-right: 1px solid rgb(226, 226, 226);" align="center" height="34" valign="middle"><a href="javascript:eliminar('<? echo $filas['inciso'];?>');"><img src="../images/icono-eliminar.gif" border="0"></a></td>
      </tr>
    
	<?
	}
	$conexionCombos->desconectar($linkCombos);
  ?>
      </tbody></table>
      
