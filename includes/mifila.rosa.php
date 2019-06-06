<?
if(!isset($html)) $html = new Html();

$ids = "";
if(isset($ids_noticias) && (count($ids_noticias) > 0)){
	$ids = " AND n.id NOT IN ( ".$ids_noticias[0];
	for($i=1;$i<count($ids_noticias);$i++)
		$ids .= ", ".$ids_noticias[$i];
	$ids .= " ) ";
}

$query = "SELECT n.id,n.tags, n.antetitulo,n.antetitulo_alt, n.titulo,n.titular_alt,img_cuadrada,n.entradilla,n.entradilla_alt FROM noticias n WHERE n.seccion = 9  AND n.activo=1 AND n.fecha_publicacion < now() ".$ids." ORDER BY n.fecha_publicacion DESC LIMIT 3 ";


$r = mysql_query($query);
if (mysql_num_rows($r) > 0){
?>
<div class="iBckTitPart">
        <div class="iTitPart">
            <div class="iTxtTitPart">Mi fila.com a fondo</div>
            <div class="iPuntaPart"><img src="sitefiles/img/puntaTitRotador.jpg" width="21" alt="" /></div>
            <div class="iVerMas"><a href="/mifila.com-a-fondo">ver mas</a></div>
        </div>
    </div>
<? 
	while ($noticia = mysql_fetch_assoc($r)) {
		$noticia["nombre_seccion"] = "mifila.com a fondo";
		$noticia["nombre_subseccion"] = "mifila.com a fondo";
		?>			
		<div class="iPart03">
		<? if($noticia["img_cuadrada"]!=""){ 
			$src_aux = explode("/",substr($noticia["img_cuadrada"],3));
			$aux = $src_aux[count($src_aux)-1];
	    	$src_aux[count($src_aux)-1] = "cuadrada_54";
	    	$src_aux[count($src_aux)] = $aux;
	    	$src = implode("/",$src_aux);
			?>			
			<div class="iPartImg"><a href="<?=$html->enlace_sin($noticia)?>"><img src="/<?=$src?>" width="54" alt="" /></a></div>
		<? } ?>        
        <div class="iPartT1"><?=($noticia["antetitulo_alt"]=="") ? $noticia["antetitulo"] : $noticia["antetitulo_alt"] ?></div>
        <div class="iPartT2"><a href="<?=$html->enlace_sin($noticia)?>"><?=($noticia["titular_alt"]=="") ? $noticia["titulo"] : $noticia["titular_alt"] ?></a></div>
        <div class="iPartT3"><img src="sitefiles/img/icoMic.png" width="15" alt="" />
        <?=$html->escribir_entradilla($noticia,"gris_corto")?>
        </div>
	</div>
			
		
		<?		
	}
 ?>
<? } ?>