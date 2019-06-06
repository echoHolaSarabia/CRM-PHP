<?
include("../../../configuracion.php");
include("../../includes/conexion.inc.php");
$id_pais = $_POST['id_pais'];
$c = "SELECT * FROM provincias WHERE idpais=".$id_pais." ORDER BY provincia";
$r = mysql_query($c);
$sel = "";
if (mysql_num_rows($r) > 0){
	while ($fila = mysql_fetch_assoc($r)){
		echo "<option value='".$fila['idprovincia']."' ".$sel.">".htmlentities($fila['provincia'])."</option>";
	}
}else{
	echo "<option value='0'>Fuera de Espa&ntilde;a</option>";
}
?>