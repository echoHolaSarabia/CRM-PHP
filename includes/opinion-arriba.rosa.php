<?

if(!isset($html)) $html = new Html();

$ids = "";
if(isset($ids_noticias) && (count($ids_noticias) > 0)){
	$ids = " AND n.id NOT IN ( ".$ids_noticias[0];
	for($i=1;$i<count($ids_noticias);$i++)
		$ids .= ", ".$ids_noticias[$i];
	$ids .= " ) ";
}

$query = "SELECT n.id,n.tags,n.autor as nombre, n.entradilla, n.entradilla_alt, n.img_horizontal, n.img_vertical, n.img_cuadrada, n.img_ampliada, n.titulo,n.titular_alt FROM noticias n WHERE n.seccion = 3 or n.seccion = 31 AND n.fecha_publicacion < now() ".$ids." ORDER BY n.fecha_publicacion DESC LIMIT 3 ";


$r = mysql_query($query);
if (mysql_num_rows($r) > 0){
?>
<div class="iContOpinion">
    <div class="iBckTitOpinion">
        <div class="iTitOpinion" >
            <div class="iTxtTitOpinion">opinión</div>
            <div class="iPuntaOpinion"><img src="sitefiles/img/puntaTitIncludes.jpg" width="21" alt="" /></div>
            <div class="gfloatRight"><a href="/opinion"><img src="/sitefiles/img/vermas.jpg" width="38" alt="" /></a></div>
        </div>
    </div>
    <? 
	while ($noticia = mysql_fetch_assoc($r)) {
		$noticia["nombre_seccion"] = "opinion";
		$noticia["nombre_subseccion"] = "opinion";
		/*
		if($noticia["foto"]!="") $src = "/userfiles/autores/".$noticia["foto"];
		else $src = "/userfiles/autores/90_general.jpg";*/
		if($noticia['img_ampliada']!="") $src = $noticia['img_ampliada'];
		if($noticia['img_horizontal']!="") $src = $noticia['img_horizontal'];
		if($noticia['img_vertical']!="") $src = $noticia['img_vertical'];
		if($noticia['img_cuadrada']!="") $src = $noticia['img_cuadrada'];
		
		?>	
		<div class="iOpinion01" style="overflow:hidden;height:76px">
	        <div class="iOpinionImg"><img src="<?=$src?>" width="65" alt="" /></div>
	        <div class="iOpinionT1"><?=$noticia["nombre"]?></div>
	        <div class="iOpinionT2"><a href="<?=$html->enlace_sin($noticia)?>" style="text-decoration:none;color:#ffffff"><?=($noticia["titular_alt"]=="") ? $noticia["titulo"] : $noticia["titular_alt"]?></a></div>
		</div>		
		<?		
	}
 ?>
</div>
<? } ?>