<?
if(!isset($html)) $html = new Html();

$query = "SELECT n.id, n.titulo, n.titular_alt, n.img_cuadrada, n.tags, s.titulo as nombre_subseccion FROM noticias n, secciones s WHERE n.seccion = 12 AND n.activo = 1 AND n.fecha_publicacion < NOW() AND n.img_cuadrada <> '' ORDER BY n.fecha_publicacion DESC LIMIT 6";

//echo $query;

$futbol = mysql_query($query);


$query = "SELECT n.id, n.titulo, n.titular_alt, n.img_cuadrada,n.tags FROM noticias n WHERE n.seccion = 11 AND n.activo = 1 AND n.fecha_publicacion < NOW() AND n.img_cuadrada NOT LIKE '' ORDER BY n.fecha_publicacion DESC LIMIT 6";

$tennis = mysql_query($query);
?>

<div class="iContDest02" align="left">
    <div class="iDest02L">
        <div class="iBckTitDest02">
            <div class="iTitDest02">
                <div class="iTxtTitDest02">European Tour</div>
                <div class="iPuntaDest02"><img src="sitefiles/img/puntaTitDest02.jpg" width="19" alt="" /></div>
                <div class="iVerMas"><a href="/torneos-profesionales/european-tour">ver mas</a></div>
            </div>
        </div>
        
        <div class="iCuerpoDest02">
        	
            <div class="iImgDest02" id="imagen_futbol">
            	
            </div>
            <?
            $i=1;
            $a=0;
			$clase[0] = "iBtnDestA";$clase[1] = "iBtnDestB";
			$primero = 1;
            while ($noticia = mysql_fetch_assoc($futbol)) {
            	$noticia["nombre_seccion"] = "torneos-profesionales";
            	$i = ($i+1)%2;
            	$src_aux = explode("/",$noticia["img_cuadrada"]);
            	$aux = $src_aux[count($src_aux)-1];
            	$src_aux[count($src_aux)-1] = "cuadrada_129";
            	$src_aux[count($src_aux)-1] = "";
            	$src_aux[count($src_aux)] = $aux;
            	$src = implode("/",$src_aux);
            	
            	?>
            	<div style="display:none"><img src="<?=$src?>"></div>
            	<div id="titular_futbol<?=$a?>" class="<?=($primero) ? "iBtnDestAA" : $clase[$i]?>" onmouseover="this.className='iBtnDestAA';mostrar_foto_futbol('<?=$src?>');this.className='iBtnDestAA';" style="overflow:hidden;line-height:18px" ><a href="<?=$html->enlace_sin($noticia)?>"><?=($noticia["titular_alt"]=="") ? $noticia["titulo"] : $noticia["titulo_alt"] ?></a></div>       	
            	<?
            	if($primero){
            		$src_primera = $src;
            		$primero = 0;
            	}            
            	$a++;
            	?>
            <? } ?>           
        </div>
    </div>
    
    <script>
	function mostrar_foto_futbol(src){
		clase = Array;
		clase[0] = "iBtnDestA";clase[1] = "iBtnDestB";
		document.getElementById("imagen_futbol").innerHTML = "<img src='"+src+"'>";	
		for(i=0;i<<?=$a?>;i++){
			document.getElementById("titular_futbol"+i).className = clase[i%2];	
		}
	}
	
	document.getElementById("imagen_futbol").innerHTML = "<img src='<?=$src_primera?>'>";	
    
	function mostrar_foto_tennis(src){
		
	}
</script>

    
    
    <div class="iDest02">
        <div class="iBckTitDest02">
            <div class="iTitDest02">
                <div class="iTxtTitDest02">PGA Tour</div>
                <div class="iPuntaDest02"><img src="sitefiles/img/puntaTitDest02.jpg" width="19" alt="" /></div>
                <div class="iVerMas"><a href="/torneos-profesionales/pga-tour">ver mas</a></div>
            </div>
        </div>
        
        <div class="iCuerpoDest02">
            <div class="iImgDest02" id="imagen_tennis">
            
            </div>
             <?
            $i=1;
            $a=0;
			$clase[0] = "iBtnDestA";$clase[1] = "iBtnDestB";
			$primero = 1;
            while ($noticia = mysql_fetch_assoc($tennis)) {
            	$i = ($i+1)%2;
            	$src_aux = explode("/",$noticia["img_cuadrada"]);
            	$aux = $src_aux[count($src_aux)-1];
            	$src_aux[count($src_aux)-1] = "cuadrada_129";
            	$src_aux[count($src_aux)-1] = "";
            	$src_aux[count($src_aux)] = $aux;
            	$src = implode("/",$src_aux);
            	$noticia["nombre_seccion"] = "torneos-profesionales";	
            	$noticia["nombre_subseccion"] = "tenis";	
            	?>
            	
            	<div style="display:none"><img src="<?=$src?>"></div>
            	<div id="titular_tennis<?=$a?>" class="<?=($primero) ? "iBtnDestAA" : $clase[$i]?>" onmouseover="mostrar_foto_tennis('<?=$src?>');this.className='iBtnDestAA';"  style="overflow:hidden;line-height:18px" ><a href="<?=$html->enlace_sin($noticia)?>"><?=($noticia["titular_alt"]=="") ? $noticia["titulo"] : $noticia["titulo_alt"] ?></a></div>       	
            	<?
            	if($primero){
            		$src_primera = $src;
            		$primero = 0;
            	}            
            	$a++;
            	?>
            <? } ?> 
        </div>
    </div>
</div>
<script>
	function mostrar_foto_tennis(src){
		clase = Array;
		clase[0] = "iBtnDestA";clase[1] = "iBtnDestB";
		document.getElementById("imagen_tennis").innerHTML = "<img src='"+src+"'>";	
		for(i=0;i<<?=$a?>;i++){
			document.getElementById("titular_tennis"+i).className = clase[i%2];	
		}
	}
	
	document.getElementById("imagen_tennis").innerHTML = "<img src='<?=$src_primera?>'>";	
    
	
</script>