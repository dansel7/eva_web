<?
class conexion{

	private $db_usuario;
	private $db_clave;
	private $db_nombre;
	private $db_host;
	
	function __construct()
	{
	  $this->db_host = 'localhost';
	  $this->db_usuario = 'root';
	  $this->db_clave = 'va';
	  $this->db_nombre = 'siade';
	}
	
	public function conectar()
	{
	  $link=mysql_connect($this->db_host, $this->db_usuario, $this->db_clave);
	  mysql_select_db($this->db_nombre);
	  return $link;
	}
	
	public function desconectar()
	{
	  mysql_close();
	}
	
}
	function hidelock($string) {//ENCRYPT ID, CADENAS O CUALQUIER DATO EN STRING
   $key="1v&a1";//llave de encriptacion
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-10*5, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}

function hideunlock($string) {//DECRYPT ID, CADENAS O CUALQUIER DATO EN STRING
	$key="1v&a1";//llave de encriptacion
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-10*5, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   return $result;
}

?>
