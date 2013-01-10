<?
class calendario{
	
	private $id_evento;
	private $nombre_evento;
	private $lugar_evento;
	private	$cuerpo_descripcion;
	private	$fecha_ini;
	private	$fecha_fin;
	private	$hora_ini;
	private	$hora_fin;

	function __construct()
	{
	  $this->id_evento='';
	  $this->nombre_evento='';
	  $this->lugar_evento='';
	  $this->cuerpo_descripcion='';
	  $this->fecha_ini='';
	  $this->fecha_fin='';
	  $this->hora_ini='';
	  $this->hora_fin='';
	}
	
public function LeerEventos($linker){
		$sql_calendario = "SELECT calendario.id_evento,calendario.titulo,calendario.lugar,calendario.descripcion,calendario.fecha_ini,calendario.fecha_fin,
		calendario.hora_ini,calendario.hora_fin,calendario.id_usuario
		from calendario
		inner join usuario
		on usuario.id_usuario=calendario.id_usuario";
		
		$result=mysql_query($sql_calendario,$linker);
		return $result;
	
	}


public function ActualizarEvento($link,$id_evento,$nombre_evento,$lugar_evento,$cuerpo_descripcion,$fecha_ini,$fecha_fin,$hora_ini,$hora_fin){
	
			 $sql="UPDATE calendario SET
		titulo= '".$nombre_evento."',
		lugar= '".$lugar_evento."',
		descripcion= '".$cuerpo_descripcion."',
		fecha_ini = '".$fecha_ini."',
		fecha_fin = '".$fecha_fin."',
		hora_ini = '".$hora_ini."',
		hora_fin = '".$hora_fin."' WHERE id_evento =".$id_evento;
		echo($sql);
		mysql_query($sql,$link);
		
		}

	public function Crear_Evento($link,$nombre_evento,$lugar_evento,$cuerpo_descripcion,$fecha_ini,$fecha_fin,$hora_ini,$hora_fin,$id_user){
				
		$sql = "INSERT INTO calendario(
		titulo,
		lugar,
		descripcion,
		fecha_ini,
		fecha_fin,
		hora_ini,
		hora_fin,
		id_usuario
		)
		VALUES ('"
		.$nombre_evento."','"
		.$lugar_evento."','"
		.$cuerpo_descripcion."', 
		date_format('".$fecha_ini."','%Y-%m-%d'),
		date_format('".$fecha_fin."','%Y-%m-%d'),
		'".$hora_ini."',
		'".$hora_fin."',"
		.$id_user.")";
		
		echo($sql);
		echo ($link);
		mysql_query($sql,$link);
			}
	
	
//OK
		public function EliminarEvento($id, $link){ //ELIMINACION DE UN EVENTO
			$id_eliminar = $id;
			$sqlb1 = "DELETE FROM calendario WHERE id_evento = ".$id_eliminar;
			mysql_query($sqlb1,$link);
			header($this->enlace_calendario);	//Redireccionando a listado de eventos
		}//OK

		public function ProximosEventos($linker){
			 $sql_calendario = "SELECT calendario.titulo, calendario.lugar,
			 calendario.descripcion,calendario.fecha_ini, calendario.fecha_fin, calendario.hora_ini, 
			 calendario.hora_fin, calendario.id_usuario FROM calendario INNER JOIN usuario ON 
			 usuario.id_usuario = calendario.id_usuario WHERE calendario.fecha_ini = CURDATE( )";
			 
			$resultado= mysql_query($sql_calendario,$linker);
			return $resultado;
		}
		
		public function Agenda($linker){
			
				//Seleccionar los eventos de la semana
				$sql_calendario = "SELECT DATE_FORMAT( SUBDATE( NOW( ) , WEEKDAY( NOW( ) ) ) ,  '%Y-%m-%d' ) AS primer_dia,
				 DATE_FORMAT( ADDDATE( NOW( ) , 6 - WEEKDAY( NOW( ) ) ) ,  '%Y-%m-%d' ) AS ultimo_dia, calendario.titulo, calendario.lugar,
				 calendario.descripcion,calendario.fecha_ini, calendario.fecha_fin, calendario.hora_ini, 
				 calendario.hora_fin, calendario.id_usuario FROM calendario INNER JOIN usuario ON 
				 usuario.id_usuario = calendario.id_usuario HAVING calendario.fecha_ini >=primer_dia && calendario.fecha_ini<=ultimo_dia";
			
			
			$resulter= mysql_query($sql_calendario,$linker);
			return $resulter;
		}
		
		
		
}
?>