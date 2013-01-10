<?

	

	/*****ENLACES*******************/

	$title = " Panel de Administraci&oacute;n ";

	$home = "../index.php";

	$url_absoluta = "";

	$enlace_menu = "index.php";



	/*****INFORMACION DEL SITIO*****/

	$title = "Centro de Administraci&oacute;n";

	$nombre_institucion = "E-Facil";

$copyright = "&copy; e-Facil";


	error_reporting(0);
	session_start();


	if($_SESSION["autenticado_admin"] == "si"){

		

	}else{

		$direccion = "Location: index.php";

		header($direccion);

	}

?>