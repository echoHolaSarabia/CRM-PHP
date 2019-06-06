<?
include("../../configuracion.php");
include("../includes/conexion.inc.php");
if ($_POST['id_pais'] != ""){
$id_pais = mysql_real_escape_string($_POST['id_pais']);
$c = "SELECT * FROM provincias WHERE idpais=".$id_pais." ORDER BY provincia";
$r = mysql_query($c);
if (mysql_num_rows($r) > 0){
	echo '<option value="">Todas</option>';
	while ($fila = mysql_fetch_assoc($r)){
		echo "<option value='".$fila['idprovincia']."' >".htmlentities($fila['provincia'])."</option>";
	}
}else{
	echo "<option value='0'>Fuera de Espa&ntilde;a</option>";
}
}else echo '<option value="">Todas</option>';
?>
