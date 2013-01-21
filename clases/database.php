<?
	class database{
		
	public function obtenerId($link, $idcolumn, $tabla){
		$result = mysql_query("SELECT LAST_INSERT_ID(".$idcolumn.") AS Ultimo FROM ". $tabla ." ORDER BY ".$idcolumn." DESC LIMIT 1", $link);	
		$fila = mysql_fetch_row($result);
		return $fila[0];
	}
        public function GenerarNuevoId($link, $idcolumn, $tabla, $condicion){
		$result = mysql_query("SELECT MAX(".$idcolumn.")+1 AS Ultimo FROM ". $tabla ." ".$condicion." ORDER BY ".$idcolumn." DESC LIMIT 1", $link);
   //echo "SELECT MAX(".$idcolumn.")+1 AS Ultimo FROM ". $tabla ." ".$condicion." ORDER BY ".$idcolumn." DESC LIMIT 1";
		$fila = mysql_fetch_row($result);
		return $fila[0];
	}
        
	
	public function Eliminar($link, $tabla, $condicion){
            //$link,es el objeto de conexion
            //$tabla,nombre de la tabla
            //$sql_condicion="", es la condicion para hacer un update y en ese caso no es opcional
		return mysql_query("DELETE FROM ". $tabla ." WHERE ". $condicion, $link);	
	}
		
		
	public function formToDB($link, $tabla, $recepcionDatos,$valores='',$excepciones, $sql_tipo='',$sql_condicion="")
	{//Diccionario:
	//$link,es el objeto de conexion
	//$tabla,nombre de la tabla
	//$recepcionDatos, la manera en como recibira los datos si por post o directamente a la funcion
	//$valores='',si no es por post
	//$excepciones, son los campos enviados por post, el formato es [campo, ] despues de la coma un espacio
	//$sql_tipo='',es el tipo de accion a ejecutar
	//$sql_condicion="", es la condicion para hacer un update y en ese caso no es opcional
		
	$campos="";//se hace uso de el solo cuando es por recibido por post

	  //$recepcionDatos podra ser post o simplemente "" ya que con ese dependera de que manera reciba los valores.
	  //si es por post, el solo tomara los datos
	  if ($sql_tipo == 'insert')
	  {//ejecucion cuando es un insert
	  	 if($recepcionDatos=="post"){
	  		   foreach ($_POST as $campo => $valor)
	  		{
			if (!preg_match("/$campo, /", $excepciones))
				{
			 	$campos .= "$campo='".trim(addslashes($valor))."', ";
				}
	  		}
	  		$campos = preg_replace('/, $/', '', $campos);//para quitar la coma del final de la cadena

	  		$sql = "INSERT INTO ".$tabla." SET ".$campos;
                        //echo $sql;
                      
	  	 }else{//SINO ES POR POST, SE EJECUTARA CON LOS VALORES QUE SE DEFINIERON DE PARAMETROS
	  		$sql = "INSERT INTO ".$tabla." values (".$valores.")";
	  	 }
	  }
	  else if ($sql_tipo=='update')
	  {//ejecucion cuando es un update
	 	   	if($recepcionDatos=="post"){
	  		   foreach ($_POST as $campo => $valor)
	  		{
			if (!preg_match("/$campo, /", $excepciones))
				{
			 	$campos .= "$campo='".trim(addslashes($valor))."', ";
				}
	  		}
	  		$campos = preg_replace('/, $/', '', $campos);//para quitar la coma del final de la cadena
			
			if ($sql_condicion=="")//por seguridad, si se llegara a dejar vacio que no ejecute la consulta,sino hara un update barrido
			{
		  	return false;
			}
			$sql = "UPDATE ".$tabla." SET ".$campos." WHERE ".$sql_condicion;
                         //echo $sql;
	  
	  	 }else{
	  	 	
	  	 	if ($sql_condicion=="")//por seguridad, si se llegara a dejar vacio que no ejecuta la consulta,sino hara un update barrido
			{
		  	return false;
			} 	
		    $sql = "UPDATE ".$tabla." SET ".$valores." WHERE ".$sql_condicion;
	  }
	}
	  else
	  {
		return 0;
	  }
          
          
	  if (mysql_query($sql,$link))
	  {		
		  return true;		
	  }
	  else
	  {
		return false;
	  }
	}
		
	}

?>
