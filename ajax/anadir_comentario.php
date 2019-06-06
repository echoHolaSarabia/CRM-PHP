<? session_start()?>
<? include_once("../configuracion.php"); ?>
<? include_once("../admin/includes/conexion.inc.php"); ?>

<?
if((isset($_POST["email"]))&&(isset($_POST["nombre"]))&&(isset($_POST["coment"]))){
	
	if($_POST["tipo"]==1) $tabla_comentado = "noticias";
	else if($_POST["tipo"]==2) $tabla_comentado = "galerias_imagenes";
	else if($_POST["tipo"]==3) $tabla_comentado = "videos";
	else if($_POST["tipo"]==4) $tabla_comentado = "rs_categorias";
	
	$query = "INSERT INTO comentarios (id_comentado,tabla_comentado,texto,autor,fecha,publicado,email) VALUES ";
	$query .= " (".$_POST["id"].", '".$tabla_comentado."', '".utf8_decode(mysql_real_escape_string(strip_tags($_POST["coment"])))."', '".utf8_decode(mysql_real_escape_string(strip_tags($_POST["nombre"])))."', NOW(), 0 , '".utf8_decode(mysql_real_escape_string(strip_tags($_POST["email"])))."')";
	
	mysql_query($query);
	
	
	mysql_query("UPDATE ".$tabla_comentado." SET num_comentarios=num_comentarios+1 WHERE id=".$_POST["id"]);
}
//echo "<br><span style='font-size:11px;color:#ff0000'>Su comentario ha sido recibido y ser&aacute; publicado en breve.<br>Muchas gracias</span>";
?>