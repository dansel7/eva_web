<?
class usuario{
	private $AliasUsuario; //Alias para inicio de sesion de usuario
	private $NombreUsuario; // nombre de usuario
	private $ApellidoUsuario; //apellido de usuario
	private $DireccionUsuario; //direccion de usuario
	private $EmailUsuario; //email de usuario
	private $Password; //password o contraseña de acceso para usuario
	private	$Permiso; //permiso para cada modulo respecto a usuario
	private $id_modulo; // identificacion de modulo de acceso para usuario
	private $id_tipo_usuario; //identificador de si es usuario registrado o de mas privilegios
	private $id_usuario; //identificador de usuario
	private $enlace_usuario; //Enlace web a listado de usuarios existentes en el sistema
		
	function __construct() //constructor de clase para usuario
	{
	  $this->AliasUsuario = '';
	  $this->NombreUsuario = '';
	  $this->ApellidoUsuario = '';
	  $this->DireccionUsuario = '';
	  $this->EmailUsuario = '';
	  $this->Password = '';
	  $this->Permiso = '';
	  //$this->id_modulo=array();
	  $this->id_tipo_usuario = '';
	  $this->id_usuario = 0;
	  $this->enlace_usuario = "usuarios-listado.php";
	}
	public function LoginUsuario($alias,$password){
		//insert into usuarios values(1,'Admin','Admin','Admin',AES_ENCRYPT('123456',1),'admin@despacho.com',NULL,1,1)
		//$sql_login = "SELECT Tipo_Usuario as tipo, idUsuarios as id, Nombres as nombre, Apellidos as apellido, Alias, Password FROM usuarios WHERE Alias='".$alias."' and Password=AES_ENCRYPT('".$password."','1')";
		$sql_login = "SELECT * FROM usuarios WHERE usuario='".$alias."' and clave='".$password."'";
		return $sql_login;
	}
	
	public function EstablecerId($idu){
		$this->id_usuario = $idu; //Establece Identificador de usuario
	}
	public function GenerarId(){ //Genera y retorna identificador de usuario		
		return $this->id_usuario; 
	}
	public function EstablecerIdTipoUsuario($idtu){ //Establece identificador de tipo de usuario
		$this->id_tipo_usuario = $idtu;
	}
	public function GenerarIdTipoUsuario($link){		
		$this->id_tipo_usuario = mysql_result(mysql_query("SELECT MAX(id_usuario) as mayor FROM usuario"),0,'mayor');
		$this->id_tipo_usuario = $this->id_usuario + 1;
		return $this->id_tipo_usuario; 
	}

public function NuevoUsuario($link,$sql_tipo='')
	{
	  $this->AliasUsuario = $_POST["alias_usuario"];		  
	  $this->NombreUsuario = $_POST['nombre_usuario'];
	  $this->ApellidoUsuario = $_POST['apellido_usuario'];

		  if ($sql_tipo == 'insert')
		  {
			  //insert into usuarios values(1,'Admin','Admin','Admin',AES_ENCRYPT('123456',1),'admin@despacho.com',NULL,1,1)
			$sql_nuevo = "INSERT INTO usuarios(idUsuarios,Nombres,Apellidos,Direccion,Email,Password,Tipo_Usuario,
			Alias,Privilegios) VALUES(0,'".$_POST['nombre_usuario']."','".$_POST['apellido_usuario']."','"
			.$_POST['direccion_usuario']."','".$_POST['email_usuario']."',AES_ENCRYPT('".$_POST['password']."','1'),"
			.$_POST['tipo_usuario'].",'".$_POST['alias_usuario']."',1)";
		  }
		  else
		  {
			return 0;
		  }
	  	  
		  $sqlu = "SELECT * FROM usuarios WHERE Alias ='".$_POST['alias_usuario']."' or Email='".$_POST['email_usuario']."'";
		  if(mysql_num_rows(mysql_query($sqlu,$link))){
		  	//El elemento ya existe!
			return "false";
		  }else{
			  if (mysql_query($sql_nuevo,$link))
			  {	    
				header("Location:".$this->enlace_usuario);   		
			  }
		  }
	}
public function TotalModulos($link){
	$sql_modulo = "SELECT COUNT(*) as total FROM modulo";
	$row_modulo = mysql_fetch_array(mysql_query($sql_modulo, $link));
	return $row_modulo['total'];
}
public function NuevoPrivilegio($link)
	{
		$sql_privilegio= "SELECT id_usuario as id FROM usuario WHERE nombre_usuario='".$this->NombreUsuario.
		"' AND apellido_usuario='".$this->ApellidoUsuario."' AND alias='".$this->AliasUsuario."'";
		$row_privilegio = mysql_fetch_array(mysql_query($sql_privilegio, $link));
		$id = $row_privilegio['id']; 
		
		$sql_modulo = "SELECT COUNT(*) as total FROM modulo";
		$row_modulo = mysql_fetch_array(mysql_query($sql_modulo, $link));
		$nchecks = $row_modulo['total'];
        $modulo[]="";
	
		//MODULOS
			$sql_modulo_padre = "SELECT id_modulo,nombre_modulo
FROM modulo WHERE id_modulo_padre=0";
			$res_modulo_padre = mysql_query($sql_modulo_padre,$link);
				  $i=0;
				  while($modulos_padre = mysql_fetch_array($res_modulo_padre)){
						$i++;
						//Mostrar Modulo padre						
						$modulo[$i]=$modulos_padre['id_modulo'];
						$sql_modulo_hijo="SELECT id_modulo,nombre_modulo
FROM modulo WHERE id_modulo_padre=".$modulos_padre['id_modulo']."";
						$res_modulo_hijo = mysql_query($sql_modulo_hijo,$link);
				  		
				  while($modulos_hijo = mysql_fetch_array($res_modulo_hijo)){
						$i++;
						//Mostrar Modulo hijo
						$modulo[$i]=$modulos_hijo['id_modulo'];
				  }}
						
   			for($i=1;$i<=$nchecks;$i++)
   			{
       			$nombrec="modulo$i";
       			if(!isset($_POST[$nombrec])){ 
					$permiso = "no";
					//echo "No se presionó el ".$nombrec."<br>";
				}else{
					$permiso= "si";
					//echo "Se presionó el ".$nombrec."<br>";
				}
				$sql_insercion_privilegio = "INSERT INTO privilegio VALUES('".$permiso."',".$modulo[$i].",".$id.")";
				
				if (mysql_query($sql_insercion_privilegio,$link)){				  
					//return true; 	
				}
	  			else{//return false;
				}	
				 	
   			}			
			header($this->enlace_usuario);
			
	}
		public function EliminarUsuario($id, $link){ //ELIMINACION O BORRADO DE UN USUARIO
		$id_eliminar = $id;
	    $sqlb1 = "DELETE FROM usuarios WHERE idUsuarios = ".$id_eliminar;
		if(mysql_query($sqlb1,$link)){
			
		}		
		header($this->enlace_usuario);	//Redireccionando a listado de usuarios
	}
	public function LLamado($id,$link){
		$sqlu = "SELECT FROM usuario WHERE id_usuario = ".$id_eliminar;
	}
public function ActualizarUsuario($tabla,$id,$link){
	
	 $this->id_usuario = $id;
	 $this->NombreUsuario = $_POST['nombre_usuario'];
	 $this->ApellidoUsuario = $_POST['apellido_usuario'];
	 $this->AliasUsuario = $_POST['alias_usuario'];
	 $sql4 = "UPDATE usuarios SET Alias='".$_POST['alias_usuario']."', Nombres='".$_POST['nombre_usuario'].
	 "', Apellidos='".$_POST['apellido_usuario']."', Email='".$_POST['email_usuario']."', Direccion='"
	 .$_POST['direccion_usuario']."', Password=AES_ENCRYPT('".$_POST['password']."','1'),Tipo_usuario="
	 .$_POST['tipo_usuario']."  WHERE idUsuarios = ".$this->id_usuario;
	  					
	  $sqlu = "SELECT idUsuarios FROM usuarios WHERE Alias ='".$_POST['alias_usuario']."' or Email='".$_POST['email_usuario']."'";
	  $view = mysql_query($sqlu,$link);
	  
		  if(mysql_result($view,0)!=$this->id_usuario){
		  	//El elemento ya existe!
			return "false";
		  }else{
				  if (mysql_query($sql4,$link)){	   
					header("Location:".$this->enlace_usuario);   		
				  }
		  }
				
				
					  
}
public function ActualizarPrivilegio($tabla,$link){
	$sql = "DELETE FROM privilegio WHERE id_usuario=".$this->id_usuario;
	if (mysql_query($sql,$link)){
					$sql_modulo = "SELECT COUNT(*) as total FROM modulo";
		$row_modulo = mysql_fetch_array(mysql_query($sql_modulo, $link));
		$nchecks = $row_modulo['total'];
        $modulo[]="";
	
		//MODULOS
			$sql_modulo_padre = "SELECT id_modulo,nombre_modulo
FROM modulo WHERE id_modulo_padre=0";
			$res_modulo_padre = mysql_query($sql_modulo_padre,$link);
				  $i=0;
				  while($modulos_padre = mysql_fetch_array($res_modulo_padre)){
						$i++;
						//Mostrar Modulo padre						
						$modulo[$i]=$modulos_padre['id_modulo'];
						$sql_modulo_hijo="SELECT id_modulo,nombre_modulo
FROM modulo WHERE id_modulo_padre=".$modulos_padre['id_modulo']."";
						$res_modulo_hijo = mysql_query($sql_modulo_hijo,$link);
				  		
				  while($modulos_hijo = mysql_fetch_array($res_modulo_hijo)){
						$i++;
						//Mostrar Modulo hijo
						$modulo[$i]=$modulos_hijo['id_modulo'];
				  }}
						
   			for($i=1;$i<=$nchecks;$i++)
   			{
       			$nombrec="modulo$i";
       			if(!isset($_POST[$nombrec])){ 
					$permiso = "no";
					//echo "No se presionó el ".$nombrec."<br>";
				}else{
					$permiso= "si";
					//echo "Se presionó el ".$nombrec."<br>";
				}
				$sql_insercion_privilegio = "INSERT INTO privilegio VALUES('".$permiso."',".$modulo[$i].",".$this->id_usuario.")";
				//echo $sql_insercion_privilegio."<br>";
				
				if (mysql_query($sql_insercion_privilegio,$link)){				  
					//return true; 	
					header($this->enlace_usuario);	
				}
	  			else{//return false;
				}	
				 	
   			}			
							  
					//return true;					
				}
	  			else{//return false; 					
				}
}
}// fin de clase Usuario
?>
