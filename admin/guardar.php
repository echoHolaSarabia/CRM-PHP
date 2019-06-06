<?php
//Este script se encarga de actualizar los campos que le pasa XMLhttpRequest para ordenar los listado

 include ("../configuracion.php");
 include ("includes/conexion.inc.php");
 
 $id_ordenadas = explode(",",$_POST['cadena']);
 $num_elementos = count($id_ordenadas);
 $id_ordenadas = array_reverse($id_ordenadas);
 for ($i = $num_elementos; $i > 0; $i--){
 	$c="UPDATE ".$_GET['tabla']." SET orden=".($i)." WHERE id=".$id_ordenadas[$i-1].";";
	mysql_query($c);
 }

 echo "Se ha guardado el nuevo orden";
?>