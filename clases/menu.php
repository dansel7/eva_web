<?
class menu{
	private $IdMenu; 
	private $NombreMenu;
	private $IDMenuPadre;
	private $DescripcionMenu;
	private $UrlMenu;
	private $enlace_menu; //Enlace web a listado de menu existentes en el sistema
	
	function __construct() //constructor de clase para menu
	{
	  $this->IdMenu = 0;
	  $this->NombreMenu = '';
	  $this->IdMenuPadre = '';
	  $this->DescripcionMenu = '';
	  $this->UrlMenu = '';
	  $this->enlace_menu = "Location: http://mutigimnasiodb.comyr.com/administrator/menu-listado.php";
	}
	
	public function EstablecerId($idu){
		$this->IdMenu = $idu; //Establece Identificador de menu
	}
	public function GenerarId(){ //Genera y retorna identificador de menu		
		return $this->IdMenu; 
	}
	
	public function EliminarMenu($id, $link){ //ELIMINACION O BORRADO DE UN MENU
		$id_eliminar = $id;
	    $sqlb1 = "DELETE FROM menu WHERE id_menu = ".$id_eliminar;
		if(mysql_query($sqlb1,$link)){		
		header($this->enlace_menu);	//Redireccionando a listado de menu
		}
	}
	
	public function NuevoMenu($link,$sql_tipo='')
	{
	  $this->NombreMenu=$_POST['nombre_menu'];
	  $this->IDMenuPadre=$_POST['menu_padre'];
	  $this->DescripcionMenu=$_POST['descripcion_menu'];
	  $sql_id = "SELECT count(*) as conteo FROM menu";
	  $resul_id = mysql_query($sql_id,$link);
	  $row_id = mysql_fetch_array($resul_id);
	  $this->IdMenu = $row_id['conteo']+1;
      
	  if ($sql_tipo == 'insert')
	  {
		  $sql_url = "SELECT url_corta FROM url WHERE id_contenido=".$_POST['menu_url'];
		  $result_url = mysql_query($sql_url,$link);
		  $menu_url = mysql_fetch_array($result_url);
		  		  
		$sql_nuevo = "INSERT INTO menu VALUES(".$this->IdMenu.",'".$this->NombreMenu."',".$this->IDMenuPadre.",'".$this->DescripcionMenu."','".$menu_url['url_corta']."')";
	  }
	  if (mysql_query($sql_nuevo,$link))
	  {	
	   header($this->enlace_menu);	//Redireccionando a listado de menu   		
	  }
	  else
	  {
		return false;
	  }
	}
	public function ActualizarMenu($id,$link,$sql_tipo=''){
	 $this->IdMenu = $id;
	 $sql_url = "SELECT url_corta FROM url WHERE id_contenido=".$_POST['menu_url'];
		  $result_url = mysql_query($sql_url,$link);
		  $menu_url = mysql_fetch_array($result_url);
		  
	 $sql_actualizar = "UPDATE menu SET nombre_menu='".$_POST['nombre_menu']."', id_menu_padre='".$_POST['menu_padre']."', descripcion_menu='".$_POST['descripcion_menu']."', url_menu='".$menu_url['url_corta']."' WHERE id_menu = ".$this->IdMenu;
 
if (mysql_query($sql_actualizar,$link))
	  {	
		header($this->enlace_menu);	//Redireccionando a listado de menu   		
	  }
	  else
	  {
		return false;
	  }  
}
}
?>