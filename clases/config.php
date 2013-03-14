<?php

/*****ENLACES*******************/
$home = "index.php";
$url_absoluta = "index.php";
$enlace_menu = "view/";
$enlace_salir = "view/";

/*****INFORMACION DEL SITIO BACK-END*****/
$title = "Panel de Administraci&oacute;n";
$nombre_institucion = "e-Facil";
$copyright = "&copy; e-Facil";
$meta_keywords = "e-Facil,Facilitadora Aduanera,Importaciones El Salvador,Salvador,Aduanas,Villatoro y Asociados,Declaraciones,Centro America,aduana,declaraciones rapidas,sistemas aduanas,automatizacion declaraciones.";
$meta_description = "Facilitadora Aduanera,Declaraciones Rapidaz A traves de sistemas de automatizacion, para todo tipo de Importaciones.";

/*****INFORMACION DEL SITIO EN FRONT-END*****/
$titulo = " ..:: e-Facil ::..";


//FUNCIONES ANTI INYECCION DE SCRIPT, SQL, O HTML
//ESTAN COMENTADAS PORQUE HACIAN LENTO EL LOGIN AL COMPROBAR TODAS LAS VARIABLES
/*
foreach( $_POST as $variable ){
$_POST [ $variable ] = mysql_real_escape_string($variable);
$_POST [ $variable ] = str_replace ( array("<",">","[","]","*","^"), "" , $_POST[ $variable ]);
//$variable=$_POST [ $variable ];
//echo "POST:$variable <br>";
}
foreach( $_REQUEST as $variable){
$_REQUEST [ $variable ] = mysql_real_escape_string($variable);
$_REQUEST [ $variable ] = str_replace ( array("<",">","[","]","*","^"), "" , $_REQUEST[ $variable ]);
//$variable=$_REQUEST [ $variable ];
//echo "REQUEST:$variable <br>";
}
foreach( $_GET as $variable){
$_GET [ $variable ] = mysql_real_escape_string($variable);
$_GET [ $variable ] = str_replace ( array("<",">","[","]","*","^"), "" , $_GET[ $variable ]);
//$variable=$_GET [ $variable ];
//echo "GET:$variable <br>";
}*/



?>