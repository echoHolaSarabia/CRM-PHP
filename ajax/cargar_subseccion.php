<? include_once("../configuracion.php"); ?>
<? include_once("../admin/includes/conexion.inc.php"); ?>


<?
if($_POST["padre"]!=""){
	$query = "SELECT * FROM secciones WHERE id_padre=".$_POST["padre"];
	$r = mysql_query($query);
	?>
	<select name="subseccion" class="rbSelectSebSeccion">
	<option value="" selected >Todas</option>
		<?
		if(mysql_num_rows($r) > 0){
			while($fila = mysql_fetch_assoc($r)){
				?><option value="<?=$fila["id"]?>" <?=($fila["id"]==$_POST["subseccion"]) ? "selected" : ""?> ><?=htmlentities($fila["titulo"])?></option><?
			}
		}
	?></select><?
}else{
	?>
	<select name="subseccion" class="rbSelectSebSeccion" >
	<option value="" selected ></option>
	</select>
	<?
}
?>