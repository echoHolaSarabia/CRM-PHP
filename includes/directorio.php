<?
if(!isset($html)) $html = new Html();

$ids = "";
if(isset($ids_noticias) && (count($ids_noticias) > 0)){
	$ids = " AND n.id NOT IN ( ".$ids_noticias[0];
	for($i=1;$i<count($ids_noticias);$i++)
		$ids .= ", ".$ids_noticias[$i];
	$ids .= " ) ";
}

$query = "SELECT n.id,n.tags, n.antetitulo,n.antetitulo_alt, n.titulo,n.titular_alt,img_cuadrada,n.entradilla,n.entradilla_alt FROM noticias n WHERE n.seccion = 6  AND n.activo=1 AND n.fecha_publicacion < now() ".$ids." ORDER BY n.fecha_publicacion DESC LIMIT 3 ";


$r = mysql_query($query);
if (mysql_num_rows($r) > 0){
?>
<div class="iBckTitPart">
        <div class="iTitPart">
            <div class="iTxtTitPart">Directorio</div>
            <div class="iPuntaPart"><img src="sitefiles/img/puntaTitRotador.jpg" width="21" alt="" /></div>
            <div class="iVerMas"><a href="/directorio">ver mas</a></div>
        </div>
    </div>

    <? 
	while ($noticia = mysql_fetch_assoc($r)) {
		$noticia["nombre_seccion"] = "directorio";
		$noticia["nombre_subseccion"] = "";
		?>			
		<div class="iPart01">
			<div class="iPartT1b"><?=($noticia["antetitulo_alt"]=="") ? $noticia["antetitulo"] : $noticia["antetitulo_alt"] ?></div>
	        <div class="iPartT1bimg"><img src="sitefiles/img/icoSobre.png" width="16" alt="" /></div>
	        <div class="iPartT2b"><a href="<?=$html->enlace_sin($noticia)?>"><?=($noticia["titular_alt"]=="") ? $noticia["titulo"] : $noticia["titular_alt"] ?></a></div>
	</div>
			
		
		<?		
	}
 ?>
<? } ?>