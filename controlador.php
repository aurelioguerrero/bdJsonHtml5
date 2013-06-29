<?php
if(isset($_GET['accion']))
{
	$con = new mysqli("localhost","qtcfnkg3_adm","zzaaqq11","qtcfnkg3_au");
	if ($con->connect_errno)
	{
		echo "Falla en la conexión a la bd: " . $con->connect_error();
	} 
	else 
	{
		switch($_GET['accion'])
		{
			case 'consultarTodo':
			{				
				$cadena = '{ "datos": [';
				$resultado = $con->query("SELECT * FROM persona ORDER BY nombre");
				for($i = 0; $i<= $resultado->num_rows - 1; $i++)
				{
					$resultado->data_seek($i);
					$fila = $resultado->fetch_assoc();
					$cadena .= '{"id": '.$fila['id'].', "nombre": "'.$fila['nombre'].'", "apellido": "'.$fila['apellido'].'", "telefono": '.$fila['telefono'].'}';
					if($i < $resultado->num_rows - 1)
						$cadena .= ",";
				}
				echo $cadena."]}";						
				break;
			}
			
			case 'consultar':
			{
				$cadena = '{ "respuesta": "exito", "datos": [';
				$resultado = $con->query('SELECT * FROM persona WHERE id = '.$_GET['id']);			
				$resultado->data_seek(0);
				$fila = $resultado->fetch_assoc();
				$cadena .= '{"id": '.$fila['id'].', "nombre": "'.$fila['nombre'].'", "apellido": "'.$fila['apellido'].'", "telefono": '.$fila['telefono'].'}';				
				
				echo $cadena."]}";
				break;
			}
			
			case 'guardar':
			{
				$resultado = $con->query('SELECT * FROM persona WHERE nombre = "'.$_GET['nombre'].'" AND apellido = "'.$_GET['apellido'].'" AND telefono = '.$_GET['telefono'].'');
				if($resultado->num_rows == 0)
				{				
					$resultado = $con->query('INSERT INTO persona(nombre,apellido,telefono) VALUES ("'.$_GET['nombre'].'","'.$_GET['apellido'].'",'.$_GET['telefono'].')');
					$cadena = '{ "respuesta": "exito", "datos": [';
					$resultado = $con->query('SELECT * FROM persona WHERE nombre = "'.$_GET['nombre'].'" AND apellido = "'.$_GET['apellido'].'" AND telefono = '.$_GET['telefono'].'');			
					$resultado->data_seek(0);
					$fila = $resultado->fetch_assoc();
					$cadena .= '{"id": '.$fila['id'].', "nombre": "'.$fila['nombre'].'", "apellido": "'.$fila['apellido'].'", "telefono": '.$fila['telefono'].'}';				
				
					echo $cadena."]}";	
				}
				else
				{
					$cadena = '{ "respuesta": "error", "mensaje": "La persona ya existe"}';
					echo $cadena;
				}
				break;	
			}
			
			case "eliminar":
			{
				$resultado = $con->query('DELETE FROM persona WHERE id = '.$_GET['id']);
				echo '{ "respuesta": "exito"}';
				break;	
			}
			
			case "actualizar":
			{
				$resultado = $con->query('UPDATE persona SET nombre = "'.$_GET['nombre'].'", apellido = "'.$_GET['apellido'].'", telefono = '.$_GET['telefono'].' WHERE id = '.$_GET['id']);
				$cadena = '{ "respuesta": "exito", "datos": [';
				$resultado = $con->query('SELECT * FROM persona WHERE id = '.$_GET['id']);			
				$resultado->data_seek(0);
				$fila = $resultado->fetch_assoc();
				$cadena .= '{"id": '.$fila['id'].', "nombre": "'.$fila['nombre'].'", "apellido": "'.$fila['apellido'].'", "telefono": '.$fila['telefono'].'}';				
				
				echo $cadena."]}";
				break;
			}
		}		
	}
}
?>