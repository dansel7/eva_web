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
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(10, 20, 10);
//$pdf->SetHeaderMargin(0);
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

// ---------------INICIO DEL REPORTE-----------------
$result=mysql_query("select numRegistro,usuario,flete,numero from retaceo  where idRetaceo='".hideunlock($_SESSION["n_declaracion"])."'",$link);

while($rows_e = mysql_fetch_array($result)){ //CONSULTA PARA ENCABEZADO
$pdf->addpage($orientacion,'legal');//AGREGA NUEVA PAGINA POR CADA MES
$flete=$rows_e[2];
$rsd='
<table width="100%">
<tr><td colspan="3" style="text-align:center"><b>REPORTE INICIALES DE FACTURAS</b><br></td></tr>
<tr>
	<td ><b>No. Retaceo:</b> '.$rows_e[0].' </td>
<td></td>	
<td ><b>No.Control:</b> '.$rows_e["numero"].'</td>
	
</tr>
</table>	
<br>
<table border="0" width="100%" cellpadding="3" cellspacing="0">
<tr>	
		<td align="right"><b>Numero Factura</b></td>
                <td></td>
		<td align="right"><b>Otros Gastos</b></td>
                <td></td>
		<td align="right"><b>FOB</b></td>
                <td></td>
</tr>';
}

//$resultado=mysql_query("select * from factura where numeroretaceo='jor301'",$link);

$resultado=mysql_query("select * from datosIniciales where idRetaceo=".hideunlock($_SESSION["n_declaracion"]),$link);
$fobTotal=0;
$gastosTotal=0;

while($row_exp = mysql_fetch_array($resultado)) //CONSULTA PARA CADA REGISTRO
{
//IMPRESION DE CADA REGISTRO

 $varr.="<tr>
		<td align=\"right\">".htmlentities($row_exp[3])."</td>
                    <td></td>
		<td align=\"right\">".number_format(round($row_exp[9],2),2)."</td>
                    <td></td>
		<td align=\"right\">".number_format(round($row_exp[8],2),2)."</td>
                    <td></td>
	</tr>";

$fobTotal+=$row_exp[8];	
$gastosTotal+=$row_exp[9];	

}//FIN IMPRESION CADA REGISTRO

// ---------------PIE DEL REPORTE-----------------
// 
$subTotal=$flete+$fobTotal+$gastosTotal;
$montoAsegurado=$subTotal*1.1;
if(strtoupper($_SESSION["calculoseguro"])=="S")
{
    $porcent=0.0;
    if(strtoupper($_SESSION["TPSeguro"])=="E"){$porcent=0.015;}
    else if(strtoupper($_SESSION["TPSeguro"])=="I"){$porcent=0.0125;}
    $seguro=$montoAsegurado*$porcent;
}else if(strtoupper($_SESSION["calculoseguro"])=="N"){
    $seguro=$montoAsegurado*($_SESSION["porcentSeguro"]/100);
}

//PIE DE TABLA
$fin=$rsd.$varr."
    <tr>
		<td ></td>
                <td></td>
                <td ></td>
		<td ></td>
                <td></td>
	</tr>
        <tr>
		<td colspan=\"6\"><hr></td>
                
	</tr>
        <tr>
		<td ><b>TOTAL FOB:</b></td>
                <td></td>
                <td ></td>
                <td></td>
		<td align=\"right\">".number_format(round($fobTotal,2),2)."</td>
                <td></td>
	</tr>
        <tr>
		<td ><b>TOTAL GASTOS:</b></td>
                <td></td>
                <td align=\"right\">".number_format(round($gastosTotal,2),2)."</td>
                <td></td>
		<td ></td>
                <td></td>
	</tr>
        <tr>
		<td ><b>FLETE:</b></td>
                <td></td>
                <td align=\"right\">".number_format(round($flete,2),2)."</td>
                <td></td>
		<td ></td>
                <td></td>
	</tr>
        <tr>
		<td ><b>SUB-TOTAL:</b></td>
                <td></td>
                <td ></td>
                <td></td>
		<td align=\"right\">".number_format(round($subTotal,2),2)."</td>
                <td></td>
	</tr>
        <tr>
		<td ><b>MONTO ASEGURADO:</b></td>
                <td></td>
                <td ></td>
                <td></td>
		<td align=\"right\">".number_format(round($montoAsegurado,2),2)."</td>
                <td></td>
	</tr>
        <tr>
		<td ><b>SEGURO:</b></td>
                <td></td>
                <td ></td>
                <td></td>
		<td align=\"right\">".number_format(round($seguro,2),2)."</td>
                <td></td>
	</tr>
</table>
<br><br>
";

$pdf->writeHTML($fin, true, false, false, false, '');




/////////////////////////////////////////////////////////////////////
   
$db->desconectar();	 
//Close and output PDF document
$pdf->Output('reporte.pdf', 'I');
}

?>

