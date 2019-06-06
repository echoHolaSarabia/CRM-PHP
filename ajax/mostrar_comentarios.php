<? include_once("../configuracion.php"); ?>
<? include_once("../admin/includes/conexion.inc.php"); ?>
<?


$mes = array('01' => "Enero",'02' => "Febrero",'03' => "Marzo",'04' => "Abril",'05' => "Mayo",'06' => "Junio",'07' => "Julio",'08' => "Agosto",'09' => "Septiembre",'10' => "Octubre",'11' => "Noviembre",'12' => "Diciembre");


$id = $_POST["id"];


$aux = explode("-",$_POST["ancla_comentario_buscado"]);
if(isset($aux[1]) && ($aux[1]!="")){
	$id_comentario_buscado = $aux[1];	
}
else $id_comentario_buscado = -1;

if (isset($_POST["tipo"])){
	$tipo = $_POST["tipo"];
	switch ($_POST["tipo"]){
		case 1: $nombre_tipo="noticias";
			break;
		case 2: $nombre_tipo="galerias_imagenes";
			break;
		case 3: $nombre_tipo="videos";
			break;
		case 4: $nombre_tipo="rs_categorias";
			break;
	}
}
else {
	$tipo = "noticias";
	$nombre_tipo="noticias";
}


$c = "SELECT id,fecha,texto,autor FROM comentarios as c WHERE publicado=1 AND id_comentado=".$_POST["id"]." AND tabla_comentado='".$nombre_tipo."' ORDER BY fecha ";
$r = mysql_query($c);

$c= "SELECT count(*) as c FROM comentarios WHERE publicado=1 AND id_comentado=".$_POST["id"]." AND tabla_comentado='".$nombre_tipo."'";
$total = mysql_fetch_array(mysql_query($c));

?>
<div class="fCom">(<?=$total["c"]?>) COMENTARIOS</div>
<?
$cad="";

if(mysql_num_rows($r)>0){
	?>
	<? while($com = mysql_fetch_array($r)){ 
		$fecha_aux = explode(" ",$com["fecha"]);
		$fecha = explode("-",$fecha_aux[0]);
		$hora = explode(":",$fecha_aux[1]);
		if($id_comentario_buscado==$com["id"]) {?><div id="<?=$_POST["ancla_comentario_buscado"]?>"></div><?}
		?>
		<div class="fCom01" style="font-size:12px">
            <div class="fTitCom" style="font-size:12px">POR <span><span style="font-size:13px"><?=utf8_encode($com["autor"])?></span>, <?=$fecha[2]." / ".$fecha[1]." / ".$fecha[0] ?></div>
            <div class="fTxtCom" style="margin-top:10px;font-size:13px"><?=utf8_encode($com["texto"])?></div>
            <div class="fPunteado03"  style="border-bottom: 1px dotted #A0A0A0"></div>
		</div>
		<? } ?>
	<?
}
else { ?><div class="fPunteado03"  style="border-bottom: 1px dotted #A0A0A0"></div><?};



					/*
					<div class="gLineaPuntosHGris"></div>
					<div>
						<a href="#"><img src="/sitefiles/img/mBtFlechaIz_Off.gif" class="gFloatLeft" width="21" height="21" alt="" /></a>
						<div class="gContenedorPaginadorSecciones">
						<a href="#">1</a>&nbsp;&nbsp;|&nbsp;
						<a href="#">2</a>&nbsp;&nbsp;|&nbsp;
						<a href="#">3</a>&nbsp;&nbsp;|&nbsp;
						<a href="#">4</a>&nbsp;&nbsp;|&nbsp;
						<a href="#">5</a>&nbsp;&nbsp;|&nbsp;
						<a href="#">6</a>&nbsp;&nbsp;|&nbsp;
						<a href="#">7</a>&nbsp;&nbsp;|&nbsp;
						<a href="#">8</a>&nbsp;&nbsp;|&nbsp;
						<a href="#">9</a>&nbsp;&nbsp;|&nbsp;
						<a href="#">10</a>
						</div>
						<a href="#"><img src="/sitefiles/img/mBtFlechaDe_Off.gif" class="gFloatLeft" width="21" height="21" alt="" /></a>
						<div class="gClear"></div>
					</div>
					<div class="gLineaPuntosHGris"></div>

*/

//echo $cad;

?>
</div>
</div>