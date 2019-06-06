<?
include_once("../configuracion.php");
include_once("../admin/includes/conexion.inc.php");

$query = "UPDATE ".$_POST["tabla"]." SET hits = (hits + 1) WHERE id=".$_POST["id"];
mysql_query($query);
?>