<?
if(!isset($html)) $html = new Html();

$query="SELECT n.id,n.titulo,n.titular_alt, n.entradilla, n.entradilla_alt,n.img_cuadrada,n.antetitulo_alt,n.antetitulo, n.tags,s.titulo as nombre_seccion, sb.titulo as nombre_subseccion,n.seccion,n.subseccion FROM noticias n, secciones s, secciones sb WHERE n.fecha_publicacion < now() AND n.activo = 1 AND n.seccion=s.id AND n.subseccion=sb.id AND n.recomendado = 1 ORDER BY n.fecha_publicacion DESC LIMIT 6";

$r = mysql_query($query);

if(mysql_num_rows($r) > 1){
?>
<div class="iContDest01" align="left">
        <!--DESTACADO01-->
        <? $noticia = mysql_fetch_assoc($r);?>
        <div class="iDest01L" style="height:282px;overflow:hidden">
        	<div class="iDest01Top"><a href="#"><img src="sitefiles/img/topDest01.gif" width="175" alt="" /></a></div>
            <div class="iDest01Bck">
            	<?
            	if(isset($noticia["img_cuadrada"])&&$noticia['img_cuadrada']!=""){
            		$src_aux = explode("/",$noticia["img_cuadrada"]);
            		$aux = $src_aux[count($src_aux) -1];
            		$src_aux[count($src_aux) -1] = "cuadrada_151";
            		$src_aux[count($src_aux)] =  $aux;
            		$src = implode("/",$src_aux);
            		$src = $noticia['img_cuadrada'];
            		?><img src="<?=$src?>" width="151" alt="" /><?
            	}
            	?>
                <div class="iDest01Tit01"><?=$noticia["nombre_subseccion"]?>  |  <?=($noticia["antetitulo_alt"] == "") ? $noticia["antetitulo"] : $noticia["antetitulo_alt"] ?></div>
                <div class="iDest01Tit"   style="height:80px;overflow:hidden"><a href="<?=$html->enlace_sin($noticia)?>">
                <?=($noticia["titular_alt"] == "") ? $noticia["titulo"] : $noticia["titular_alt"] ?>                
                </a></div>
            </div>
            <div class="iDest01bot"><img src="sitefiles/img/botDest01.gif" width="175" alt="" /></div>
        </div>
        <!--//////-->
        <? $noticia = mysql_fetch_assoc($r);?>
        <!--DESTACADO01-->
        <div class="iDest01"  style="height:282px;overflow:hidden">
            <div class="iDest01Top"><a href="#"><img src="sitefiles/img/topDest01.gif" width="175" alt="" /></a></div>
            <div class="iDest01Bck">
                <?
            	if(isset($noticia["img_cuadrada"])&&$noticia['img_cuadrada']!=""){
            		$src_aux = explode("/",$noticia["img_cuadrada"]);
            		$aux = $src_aux[count($src_aux) -1];
            		$src_aux[count($src_aux) -1] = "cuadrada_151";
            		$src_aux[count($src_aux)] =  $aux;
            		$src = implode("/",$src_aux);
            		$src = $noticia['img_cuadrada'];
            		?><img src="<?=$src?>" width="151" alt="" /><?
            	}
            	?>
                <div class="iDest01Tit01"><?=$noticia["nombre_subseccion"]?>  |  <?=($noticia["antetitulo_alt"] == "") ? $noticia["antetitulo"] : $noticia["antetitulo_alt"] ?></div>
                <div class="iDest01Tit"  style="height:80px;overflow:hidden"><a href="<?=$html->enlace_sin($noticia)?>"> <?=($noticia["titular_alt"] == "") ? $noticia["titulo"] : $noticia["titular_alt"] ?>                </a></div>
            </div>
            <div class="iDest01bot"><img src="sitefiles/img/botDest01.gif" width="175" alt="" /></div>
        </div>
        <!--//////-->

</div>
<? } ?>