<div class="iContMas">
     <div class="iBckTitMas" id="menu_ultima">
        <div class="iTitMas">
            <div class="iTxtTitMas">&uacute;ltimas</div>
            <div class="iPuntaMas"><img src="sitefiles/img/puntaTitIncludes.jpg" width="21" alt="" /></div>
            <div class="iTxtTit2" style="cursor:pointer" onclick="ver_lomas(1)">+ leidas</div>
            <div class="iPuntaMas" style="cursor:pointer" onclick="ver_lomas(1)"><img src="sitefiles/img/separaBot.jpg" width="19" alt="" /></div>
            <div class="iTxtTit2" style="cursor:pointer" onclick="ver_lomas(2)">+ comentadas</div>
        </div>
    </div>

    <div class="iBckTitMas" id="menu_leida" style="display:none">
        <div class="iTitMas">
        	<div class="iTxtTit2" style="cursor:pointer" onclick="ver_lomas(0)">&uacute;ltimas</div>
        	<div class="iPuntaMas"><img src="sitefiles/img/puntaTitIncludes2.jpg" width="21" alt="" /></div>
            <div class="iTxtTitMas">+ leidas</div>
            <div class="iPuntaMas"><img src="sitefiles/img/puntaTitIncludes.jpg" width="21" alt="" /></div>
            <div class="iTxtTit2" style="cursor:pointer" onclick="ver_lomas(2)">+ comentadas</div>
        </div>
    </div>
    
    <div class="iBckTitMas" id="menu_comentada"  style="display:none">
        <div class="iTitMas">
            <div class="iTxtTit2" style="cursor:pointer" onclick="ver_lomas(0)">&uacute;ltimas</div>
            <div class="iPuntaMas" style="cursor:pointer" onclick="ver_lomas(0)">
            	<img src="sitefiles/img/separaBot.jpg" width="19" alt="" />
            </div>
            <div class="iTxtTit2" style="cursor:pointer" onclick="ver_lomas(1)">+ leidas</div>
            <div class="iPuntaMas"><img src="sitefiles/img/puntaTitIncludes2.jpg" width="21" alt="" /></div>
            <div class="iTxtTitMas" style="margin-right:2px">+ comentadas&nbsp;</div>
        </div>
    </div>

<?
$query = "SELECT n.id, n.titulo, n.titular_alt,n.tags, s.titulo as nombre_seccion, sb.titulo as nombre_subseccion FROM noticias n, secciones s, secciones sb WHERE n.activo = 1 AND n.fecha_publicacion < NOW() AND ((n.seccion = s.id AND n.subseccion = sb.id AND (n.subseccion <> 0 AND n.seccion<>4 )) OR ((n.seccion=4  OR n.subseccion = 0) AND n.seccion = s.id AND n.seccion = sb.id ))  ORDER BY n.fecha_publicacion DESC LIMIT 8";

$r = mysql_query($query);


$q = "SELECT n.titulo, n.titular_alt,n.id,n.tags, s.titulo as nombre_seccion, sb.titulo as nombre_subseccion  FROM lomas l, noticias n, secciones s, secciones sb WHERE n.id = l.id_noticia AND l.tipo = 'leido'  AND ((n.seccion = s.id AND n.subseccion = sb.id AND ( n.subseccion <> 0 AND n.seccion<>4 )) OR ((n.seccion=4  OR n.subseccion = 0) AND n.seccion = s.id AND n.seccion = sb.id )) AND n.activo=1 ORDER BY l.orden ASC LIMIT 8";
$losmas_leidos = mysql_query($q);

$q = "SELECT n.titulo, n.titular_alt,n.id,n.tags, s.titulo as nombre_seccion, sb.titulo as nombre_subseccion FROM lomas l, noticias n, secciones s, secciones sb WHERE n.id = l.id_noticia AND l.tipo = 'comentado'  AND ((n.seccion = s.id AND n.subseccion = sb.id AND ( n.subseccion <> 0 AND n.seccion<>4 )) OR ((n.seccion=4  OR n.subseccion = 0) AND n.seccion = s.id AND n.seccion = sb.id )) AND n.activo=1 ORDER BY l.orden ASC LIMIT 8";

$losmas_comentados = mysql_query($q);


?><div class="iContRenglon" id="capa_ultima" style="display:block"><?
$ab[0] = "iRenglonMasA";
$ab[1] = "iRenglonMasB";
$i=1;
while($noticia = mysql_fetch_assoc($r)){
	$i = ($i+1) % 2;
	if($noticia["titular_alt"]=="") $titular = $noticia["titulo"];
	else  $titular = $noticia["titular_alt"];
	?>	
            <div class="<?=$ab[$i]?>"><img src="sitefiles/img/icoMas.png" width="16" alt="" /><a href="<?=$this->enlace_sin($noticia)?>"><?=$this->cut_string($titular,30,$this->enlace_sin($noticia),"#636363",1)?></a></div>
	<?	
}
?></div>
    
<div class="iContRenglon" id="capa_leida" style="display:none"><?
$ab[0] = "iRenglonMasA";
$ab[1] = "iRenglonMasB";
$i=1;
while($noticia = mysql_fetch_assoc($losmas_leidos)){
	$i = ($i+1) % 2;
	if($noticia["titular_alt"]=="") $titular = $noticia["titulo"];
	else  $titular = $noticia["titular_alt"];
	?>	
            <div class="<?=$ab[$i]?>"><img src="sitefiles/img/icoMas.png" width="16" alt="" /><a href="<?=$this->enlace_sin($noticia)?>"><?=$this->cut_string($titular,30,$this->enlace_sin($noticia),"#636363",1)?></a></div>
	<?	
}
?></div>

<div class="iContRenglon" id="capa_comentada" style="display:none"><?
$ab[0] = "iRenglonMasA";
$ab[1] = "iRenglonMasB";
$i=1;
while($noticia = mysql_fetch_assoc($losmas_comentados)){
	$i = ($i+1) % 2;
	if($noticia["titular_alt"]=="") $titular = $noticia["titulo"];
	else  $titular = $noticia["titular_alt"];
	?>	
            <div class="<?=$ab[$i]?>"><img src="sitefiles/img/icoMas.png" width="16" alt="" /><a href="<?=$this->enlace_sin($noticia)?>"><?=$this->cut_string($titular,30,$this->enlace_sin($noticia),"#636363",1)?></a></div>
	<?	
}
?></div>
</div>

<script>
 function ver_lomas(num){
 	if(num==0){
 		document.getElementById("menu_ultima").style.display="block";
 		document.getElementById("menu_leida").style.display="none";
 		document.getElementById("menu_comentada").style.display="none";
 		document.getElementById("capa_ultima").style.display="block";
 		document.getElementById("capa_leida").style.display="none";
 		document.getElementById("capa_comentada").style.display="none";
 	}
 	else if(num==1){
 		document.getElementById("menu_ultima").style.display="none";
 		document.getElementById("menu_leida").style.display="block";
 		document.getElementById("menu_comentada").style.display="none";
 		document.getElementById("capa_ultima").style.display="none";
 		document.getElementById("capa_leida").style.display="block";
 		document.getElementById("capa_comentada").style.display="none";
 	}
 	else{
 		document.getElementById("menu_ultima").style.display="none";
 		document.getElementById("menu_leida").style.display="none";
 		document.getElementById("menu_comentada").style.display="block";
 		document.getElementById("capa_ultima").style.display="none";
 		document.getElementById("capa_leida").style.display="none";
 		document.getElementById("capa_comentada").style.display="block";
 	}
 	
 }

</script>