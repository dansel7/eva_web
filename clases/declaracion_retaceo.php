<?php
class declaracion_retaceo  {//clase Retaceo
   var $id;
    var $numero = "";
    var $usuario = "";
    var $n_emp = "";
    var $estado;
    var $fechaCreado ;
    var $fechaModificado ;
    var $fechaFinalizado ;
    var $modeloDeclaracion = "";
    var $fecha ;
    var $modoTransporte_;
    var $numeroDocumentoTransporte = "";
    var $NIT = "";
    var $flete;
    var $bultos;
    var $pesoBruto;
    var $cuantia;
    var $FOB;
    var $otrosGastos;
    var $seguro;
    var $CIF;
    var $DAI;
    var $IVA;
    var $aPago;
    var $total;
    var $calcularSeguro  = True;
    var $tipoCalculoSeguro = "E";
    var $esNuevo ;
		
		function __construct() {
		$id = 1;
        $numero = id;
        $estado = 0;
        $fecha = date("d-m-Y");
        $fechaCreado =  date("d-m-Y H:i:s");;
        $fechaModificado = date("d-m-Y H:i:s");
        $fechaFinalizado = "";
        $modoTransporte_ = 0 ; 
        $esNuevo = true ;
		}
}
  ?>