<?php

//error_reporting(0);
session_start();

if(!isset($_SESSION["n_declaracion"])){
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

//ARREGLO DE MESES PARA MOSTRAR
$meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
// ---------------INICIO DEL REPORTE-----------------
$result=mysql_query("select r.*,e.nombre from retaceo r inner join empresas e on r.nit=e.nit where r.idRetaceo='".hideunlock($_SESSION["n_declaracion"])."'",$link);

while($rows_e = mysql_fetch_array($result)){ //CONSULTA PARA ENCABEZADO
$pdf->addpage($orientacion,'legal');//AGREGA NUEVA PAGINA POR CADA MES

$rsd='
<table width="100%">
<tr><td colspan="3" style="text-align:center"><b>REPORTE INCISO</b></td></tr>
<tr>
	<td width="300px"><b>No. Retaceo:</b> '.$rows_e["numRegistro"].' </td>
        <td width="200px"><b>Fecha:</b> '.$rows_e["fecha"].'</td>
        <td width="200px"><b>No.Control:</b> '.$rows_e["numero"].'</td> 
</tr>
<tr>
	<td><b>Empresa:</b> '.$rows_e["nombre"].'</td>
	<td><b>NIT:</b> '.$rows_e["NIT"].'</td>
	<td><b>A&Ntilde;O: '.  substr($rows_e["fecha"], 0,4).'</b></td>
</tr>
</table>	
<br>
<table border="0" width="100%" cellpadding="3" cellspacing="0">
<tr>	
		<td style="border:1px solid black;width:35px" >Item</td>
		<td style="border:1px solid black;width:70px" >Factura</td>
		<td style="border:1px solid black;width:70px">Partida</td>
		<td colspan="3" style="border:1px solid black;width:375px" >Descripcion</td>
		<td style="border:1px solid black;width:70px" >Cuantia</td>
		<td style="border:1px solid black;width:70px">FOB</td>
</tr>';
}

//$resultado=mysql_query("select * from factura where numeroretaceo='jor301'",$link);

$resultado=mysql_query("select item.idItemFactura, factura.numero as factura, item.partidaArancelaria as partida, item.descripcion, " .
                " item.cuantia as cuantia, (item.cuantia * item.precioUnitario) as fob  ".
                " from factura inner join item on factura.idFactura=item.idFactura where item.idRetaceo='".hideunlock($_SESSION["n_declaracion"])."' order by factura.idFactura,factura.numero,item.idItemFactura",$link);
$fobSubt=0;
$fobTotal=0;
$temp=0;
while($row_exp = mysql_fetch_array($resultado)) //CONSULTA PARA CADA REGISTRO
{
//IMPRESION DE CADA REGISTRO
//PARA HACER UNA AGRUPACION
$fact=$row_exp[1];//PRIMERO SE GUARDA EL NUMERO DE FACTURA

//La agrupacion se valida desde aca
if($temp==0 && $fobSubt==0){}//se comprueba que es el primer valor de los registros y no imprime nada
 else if($fact!=$temp){//compara si son diferentes los numeros de facturas asi para poder agrupar
		$varr.="<tr>
		<td colspan=\"7\" style=\"border:1px solid black;text-align:center\"><b>Subtotal</b></td>
		<td style=\"border:1px solid black;text-align:right\"><b>$".number_format(round($fobSubt,2),2)."</b></td>
		</tr>";
                $fobTotal+=$fobSubt;
		$fobSubt=0;
	} //hasta aca es la agrupacion, se hace antes porque para el primer registro no hay ninguna agrupacion
 
 $varr.="<tr>
		<td style=\"border-left:1px solid black;border-right:1px solid black\">$row_exp[0]</td>
		<td style=\"border-left:1px solid black;border-right:1px solid black\">$row_exp[1]</td>
		<td style=\"border-left:1px solid black;border-right:1px solid black\">$row_exp[2]</td>
		<td colspan=\"3\" style=\"border-left:1px solid black;border-right:1px solid black\">".htmlentities($row_exp[3])."</td>
		<td style=\"border-left:1px solid black;border-right:1px solid black;text-align:right\">".number_format(round($row_exp[4],2),2)."</td>
		<td style=\"border-left:1px solid black;border-right:1px solid black;text-align:right\">".number_format(round($row_exp[5],2),2)."</td>
	</tr>";

$fobSubt+=$row_exp[5];	
$temp=$row_exp[1];//Guarda un temporal que seria el numero anterior para comparar

}//FIN IMPRESION CADA REGISTRO
$varr.="<tr>
		<td colspan=\"7\" style=\"border:1px solid black;text-align:center\"><b>Subtotal</b></td>
		<td style=\"border:1px solid black;text-align:right\"><b>$".number_format(round($fobSubt,2),2)."</b></td>
		</tr>";
$fobTotal+=$fobSubt;
		$fobSubt=0;
// ---------------PIE DEL REPORTE-----------------
//PIE DE TABLA
$fin=$rsd.$varr."<tr>
		<td colspan=\"7\" style=\"border:1px solid black;text-align:center\">TOTALES</td>

		<td style=\"border:1px solid black\">$".number_format(round($fobTotal,2),2)."</td>
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

