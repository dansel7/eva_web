<?php

//error_reporting(0);
session_start();

if(!isset($_SESSION['usu'])){
		$direccion = "Location: ../index.php";
		header($direccion);
	}else{
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
require_once('../clases/conexion.php');

$db= new conexion();
$link = $db->conectar();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Daniel Diaz');
$pdf->SetTitle('V&A');
$pdf->SetSubject('Control');
$pdf->SetKeywords('TCPDF, PDF, reporte, control, MANTENIMIENTO');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '1', 90));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(10, 10, 10);
//$pdf->SetHeaderMargin();
//$pdf->SetFooterMargin(15);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 5);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
//$pdf->setLanguageArray($l); 
// ---------------------------------------------------------
// set font
$pdf->SetFont('helvetica', '', 10);

// add a page

$orientacion="vertical";

//ARREGLO DE MESES PARA MOSTRAR
$meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
// ---------------INICIO DEL REPORTE-----------------

$impuestos = mysql_query("SELECT inciso,descripcion,pais FROM retaceoImpuestos where idRetaceo=".  hideunlock($_SESSION["n_declaracion"]), $link);
$itemImpuestos = array();
while ($rowp=mysql_fetch_row($impuestos)){
$itemImpuestos[]=$rowp;
}
    
$result=mysql_query("select r.*,e.nombre from retaceo r inner join empresas e on r.nit=e.nit where r.idRetaceo='".hideunlock($_SESSION["n_declaracion"])."'",$link);

while($rows_e = mysql_fetch_array($result)){ //CONSULTA PARA ENCABEZADO
$pdf->addpage($orientacion,'legal');//AGREGA NUEVA PAGINA POR CADA MES

$rsd='
<table width="100%">
<tr><td colspan="3" style="text-align:center"><b>REPORTE PARTIDAS ARANCELARIAS AGRUPADAS</b><br></td></tr>
<tr>
	<td width="100px"><b>No. Retaceo:</b> </td>
        <td width="225px" style="text-align:right">'.$rows_e["numRegistro"].'&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="100px"><b>FOB:</b></td> 
        <td width="100px" style="text-align:right">'.$rows_e["FOB"].'&nbsp;&nbsp;&nbsp;&nbsp;</td> 
        <td width="100px"><b>DAI:</b> </td> 
        <td width="60px" style="text-align:right">'.$rows_e["DAI"].'</td>     
</tr>
<tr>
	<td width="100px"><b>NIT:</b> </td>
        <td width="225px" style="text-align:right">'.$rows_e["NIT"].'&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="100px"><b>Flete:</b></td> 
        <td width="100px" style="text-align:right">'.$rows_e["flete"].'&nbsp;&nbsp;&nbsp;&nbsp;</td> 
        <td width="100px"><b>IVA:</b> </td> 
        <td width="60px" style="text-align:right">'.$rows_e["IVA"].'</td>     
</tr>
<tr>
	<td width="100px"><b>Fecha:</b> </td>
        <td width="225px" style="text-align:right">'.date("d-m-Y", strtotime($rows_e["fecha"])).'&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="100px"><b>O.Gastos:</b></td> 
        <td width="100px" style="text-align:right">'.$rows_e["otrosGastos"].'&nbsp;&nbsp;&nbsp;&nbsp;</td> 
        <td width="100px"><b>A Pago:</b> </td> 
        <td width="60px" style="text-align:right">'.$rows_e["aPago"].'</td>     
</tr>
<tr>
	<td width="100px"><b>Consignatario:</b> </td>
        <td width="225px" style="text-align:right">'.$rows_e["nombre"].'&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="100px"><b>Seguro:</b></td> 
        <td width="100px" style="text-align:right">'.$rows_e["seguro"].'&nbsp;&nbsp;&nbsp;&nbsp;</td> 
        <td colspan="2"></td> 
</tr>
<tr>
	<td width="100px"><b>Doc.Transporte:</b> </td>
        <td width="225px" style="text-align:right">'.$rows_e["numeroDocumentoTransporte"].'&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="100px"><b>CIF:</b></td> 
        <td width="100px" style="text-align:right">'.$rows_e["CIF"].'&nbsp;&nbsp;&nbsp;&nbsp;</td> 
        <td colspan="2"></td>   
</tr>

</table>	
<br>


<table border="0" width="100%" cellpadding="1" cellspacing="0" >
<tr>	
		<td style="width:255px" ><b>Descripcion</b></td>
		<td style="text-align:right;width:55px"><b>Bultos</b></td>
		<td style="text-align:right;width:80px"><b>Peso Bruto</b></td>
		<td style="text-align:right;width:60px"><b>Cuantia</b></td>
		<td style="text-align:right;width:80px"><b>Valor</b></td>
		<td style="text-align:right;width:65px"><b>Factura</b></td>
                <td style="text-align:right;width:30px"><b>ODF</b></td>
                <td style="text-align:center;width:80px"><b>TLC</b></td>
</tr>';
}

//$resultado=mysql_query("select * from factura where numeroretaceo='jor301'",$link);


$resultado=mysql_query("select item.descripcion,item.bultos,item.pesoBruto,item.cuantia as cuantia,(item.cuantia * item.precioUnitario) as fob," .
            " factura.numero as factura,factura.idFactRetaceo, agrupar,partidaArancelaria  ".
            //se puso esta linea.
" from item inner join factura on item.idFactura=factura.idFactura where item.idRetaceo=".  hideunlock($_SESSION["n_declaracion"]).
            " order by agrupar desc,item.idItem asc,factura.idFactRetaceo"
            //    " from factura inner join item on factura.idFactura=item.idFactura where item.idRetaceo='".hideunlock($_SESSION["n_declaracion"])."' order by factura.idFactura,factura.numero,item.idItemFactura"
            ,$link);


$fobSubt=0;
$fobTotal=0;
$tempA=0;
$tempB=0;
$NumItem=1;
while($row_exp = mysql_fetch_array($resultado)) //CONSULTA PARA CADA REGISTRO
{
//IMPRESION DE CADA REGISTRO
//PARA HACER UNA AGRUPACION
$FlagAgrupado=$row_exp[7];//PRIMERO SE GUARDA LA BANDERA DE AGRUPADO
$PartidaAgrupada=$row_exp[8];//SEGUNDO SE GUARDA LA PARTIDA ARANCELARIA QUE AGRUPA

//La agrupacion se valida desde aca
if($tempA==0 && $fobSubt==0){}//se comprueba que es el primer valor de los registros y no imprime nada
 else  if($PartidaAgrupada!=$tempB || $FlagAgrupado!=$tempA){//compara si son diferentes las banderas de agrupados asi para poder Agrupar
            $varr.="<tr>
<td style=\"text-align:left\"><b>".$tempB."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>DAI:". 0.0 ."</b></td>
                <td style=\"text-align:right\"><b>". 0 ."</b></td>                    
		<td style=\"text-align:right\"><b>". 0 ."</b></td>
                <td style=\"text-align:right\"><b>". 0 ."</b></td>
		<td style=\"text-align:right\"><b>".number_format(round($fobSubt,2),2)."</b></td>
                <td colspan=\"3\" style=\"text-align:center\"><b>Item: &nbsp;&nbsp;". $NumItem ."</b></td>
		</tr><tr><td colspan=8></td></tr>";
                $fobTotal+=$fobSubt;
		$fobSubt=0;
                $NumItem++;
                
             //}
	} //hasta aca es la agrupacion, se hace antes porque para el primer registro no hay ninguna agrupacion
 
 $varr.="<tr>
		<td >".htmlentities($row_exp[0])."</td>
		<td style=\"text-align:right\">$row_exp[1]</td>
		<td style=\"text-align:right\">$row_exp[2]</td>
		<td style=\"text-align:right\">$row_exp[3]</td>
		<td style=\"text-align:right\">".number_format(round($row_exp[4],2),2)."</td>
		<td style=\"text-align:right\">$row_exp[5]</td>
                <td style=\"text-align:right\">$row_exp[6]</td>
                <td style=\"text-align:right\">&nbsp;</td>
                </tr>";

$fobSubt+=$row_exp[4];	
$tempA=$row_exp[7];//Guarda un temporal que seria la bandera de agrupado anterior para comparar
$tempB=$row_exp[8];//Guarda un temporal que seria la partida arancelaria anterior para comparar


}//FIN IMPRESION CADA REGISTRO
$varr.="<tr>
		<td><b>".$tempB."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>DAI:". 0.0 ."</b></td>
                <td style=\"text-align:right\"><b>". 0 ."</b></td>                    
		<td style=\"text-align:right\"><b>". 0 ."</b></td>
                <td style=\"text-align:right\"><b>". 0 ."</b></td>
		<td style=\"text-align:right\"><b>".number_format(round($fobSubt,2),2)."</b></td>
                <td colspan=\"3\" style=\"text-align:center\"><b>Item: &nbsp;&nbsp;". $NumItem ."</b></td>
		</tr><tr><td colspan=8></td></tr></table>";
$fobTotal+=$fobSubt;
		$fobSubt=0;
// ---------------PIE DEL REPORTE-----------------
//PIE DE TABLA
$fin=$rsd.$varr."<b>
<table border=\"0\" >              
        <tr>
        <td>&nbsp;</td>
        <td colspan=\"2\" style=\"text-align:center\">Bultos</td>
        <td colspan=\"2\" style=\"text-align:center\">Peso (Kgs)</td>
        <td colspan=\"2\" style=\"text-align:center\">Cuantia</td>
        <td colspan=\"2\" style=\"text-align:center\">Valor</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <td>&nbsp;</td>
        <td colspan=\"2\" style=\"text-align:center\">". 0 ."</td>
        <td colspan=\"2\" style=\"text-align:center\">". 0 ."</td>
        <td colspan=\"2\" style=\"text-align:center\">". 0 ."</td>
        <td colspan=\"2\" style=\"text-align:center\">".number_format(round($fobTotal,2),2)."</td>
        <td>&nbsp;</td>
        </tr>
</table></b>
<br><br>
";

$pdf->writeHTML($fin, true, false, false, false, '');




/////////////////////////////////////////////////////////////////////
   
$db->desconectar();	 
//Close and output PDF document
$pdf->Output('reporte.pdf', 'I');
}

?>

