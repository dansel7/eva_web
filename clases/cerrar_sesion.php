<?
	session_start();
	$_SESSION["autenticado_admin"] = "no";

	session_destroy(); // destruyo la sesión 
    header("Location: ../index.php"); //envío al usuario a la pag. de autenticación 
?>