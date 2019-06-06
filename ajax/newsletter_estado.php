<?php
include_once("../configuracion.php");
include_once("../admin/includes/conexion.inc.php");

if( isset($_GET["estado"]) && isset($_GET["newsletter"]) && $_GET["newsletter"] != "" )
{
	$query = "UPDATE `newsletters` SET `enviada` = ";
	$query .= ($_GET["estado"] == "true" ) ? "'0'" : "'1'" ;
	$query .= " WHERE `newsletters`.`id` = " . $_GET["newsletter"];
	mysql_query($query);
	header( 'Location: /admin/index2.php?modulo=mod_newsletter' );
}
?>