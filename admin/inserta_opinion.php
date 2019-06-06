<?
include("../configuracion.php");
include("../includes/conexion.inc.php");


$c = "SELECT id_noticia1,id_noticia2,id_noticia3,id_noticia4 FROM modulos_cuatro WHERE id = 1";
$r = mysql_query($c);
$modulo = mysql_fetch_array($r);
print_r($modulo);
echo "<br>";

$c = "SELECT * FROM noticias WHERE seccion = 4 AND recurso_maquetacion = 'modulo_cuatro' AND fecha_publicacion <= NOW() ORDER BY fecha_publicacion DESC";
echo $c;
$r = mysql_query($c);
$cont = 1;
while (($fila = mysql_fetch_assoc($r)) && ($cont <= 4)){
	$c = "UPDATE modulos_cuatro SET id_noticia".$cont."=".$fila['id']." WHERE id = 1";
	mysql_query($c);
	echo $c."<br>";
	$cont++;
}
$cont2 = 0;
for ($i=$cont; $i<=4; $i++){
	$c = "UPDATE modulos_cuatro SET id_noticia".$i."=".$modulo[$cont2]." WHERE id = 1";
	mysql_query($c);
	echo $c."<br>";
	$cont2 ++;
}
?>