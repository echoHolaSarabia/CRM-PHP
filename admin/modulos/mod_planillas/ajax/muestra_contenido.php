<?php
include_once("../../../../configuracion.php");
include_once("../../../includes/conexion.inc.php");
include_once("../conf.php");
if (isset($_POST['tabla']) && isset($_POST['id_elemento'])){
	$tabla = $_POST['tabla'];
	$id_elemento = $_POST['id_elemento'];
	if (in_array($tabla,$tablas_con_previsualizaion)){
		$c = "SELECT entradilla FROM ".$tabla." WHERE id=".$id_elemento;
		$fila = mysql_fetch_assoc(mysql_query($c));
		echo $fila['entradilla'];
	}
}
?>