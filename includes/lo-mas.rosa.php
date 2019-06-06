<?
if(!isset($html)) $html = new Html();

$mes = date('n');
$ano = date('Y');
$dia = date("d");
if ($mes==1) {
	$mes=12;
	$ano--;
}
else $mes=$mes-1;
$fecha = $ano."-%".$mes."-".$dia." 00:00:00";


//$q = "SELECT n.titulo,n.id, seccion.titulo as nombre_seccion, subseccion.titulo as nombre_subseccion FROM lomas l, noticias n, secciones seccion, secciones subseccion WHERE n.id = l.id_noticia AND n.seccion=seccion.id AND l.tipo = 'leido'  AND ((n.subseccion = subseccion.id) OR (n.subseccion=0 AND  n.seccion = subseccion.id) OR (n.subseccion='' AND  n.seccion = subseccion.id)) ORDER BY l.orden ASC LIMIT 3";

$q = "SELECT n.titulo,n.id,n.tags, seccion.titulo as nombre_seccion FROM lomas l, noticias n, secciones seccion WHERE n.id = l.id_noticia AND l.tipo = 'leido'  AND ((n.subseccion=0 AND  n.seccion = seccion.id) OR (n.subseccion = seccion.id)) AND n.activo=1 ORDER BY l.orden ASC LIMIT 7";
$losmas_leidos = mysql_query($q);

//$q = "SELECT n.*, seccion.titulo as nombre_seccion, subseccion.titulo as nombre_subseccion FROM lomas l, noticias n, secciones seccion, secciones subseccion WHERE n.id = l.id_noticia AND n.seccion=seccion.id AND l.tipo = 'comentado'  AND ((n.subseccion = subseccion.id) OR (n.subseccion=0 AND  n.seccion = subseccion.id) OR (n.subseccion='' AND  n.seccion = subseccion.id)) ORDER BY l.orden ASC LIMIT 3";
$q = "SELECT n.titulo,n.id,n.tags, seccion.titulo as nombre_seccion FROM lomas l, noticias n, secciones seccion WHERE n.id = l.id_noticia AND l.tipo = 'comentado'  AND ((n.subseccion=0 AND  n.seccion = seccion.id) OR (n.subseccion = seccion.id)) AND n.activo=1 ORDER BY l.orden ASC LIMIT 7";

$losmas_comentados = mysql_query($q);

?>
			
<div class="gBordesGrises">
	<div class="gContenedorLateralVerdeConSolapas">
		<div class="gLateralSolapas_On" id="botmasleido" onclick="rollLatOn('masleido')">LO MÁS LEÍDO</div>
		<div class="gLateralSolapas_Off" id="botmascomentado" onclick="rollLatOn('mascomentado')">LO MÁS COMENTADO</div>
	</div>
	
	<?//MAS LEIDO?>
	<div id="masleido" style="display:block">
		<?
		if (mysql_num_rows($losmas_leidos)>0){
			$j=0;
			while($n=mysql_fetch_assoc($losmas_leidos)){
				$j++;
				?>
				<div class="gNoticiasGris<?if(is_int($j/2)){?>Claro<?}else{?>Oscuro<?if($j==1){?>_SinBorde<?}?><?}?>">
				<a href="<?=$html->enlace_sin($n);?>">
					<?=$n["titulo"]?>
				</a>
			</div>
				<?
			}
		}
		?>
	</div>
	
	<?//MAS COMENTADO?>
	<div id="mascomentado" style="display:none">
		<?
		if (mysql_num_rows($losmas_leidos)>0){
			$j=0;		
			while($n=mysql_fetch_assoc($losmas_comentados)){
				$j++;
				?>
				<div class="gNoticiasGris<?if(is_int($j/2)){?>Claro<?}else{?>Oscuro<?if($j==1){?>_SinBorde<?}?><?}?>">
				<a href="<?=$html->enlace_sin($n);?>">
					<?=$n["titulo"]?>
				</a>
			</div>
				<?
			}
		}
		?>
	</div>
	
</div>
<!--
<div class="gContenedorBoton">
	<div class="gContenedorBotones"><a href="#"><img src="sitefiles/img/esp/eBtVerTodasNoticias_Off.gif" width="280" height="20" title="Ver todas las Noticias" alt="Ver todas las Noticias" /></a></div>
</div>
-->
<div class="gSeparaContenidos10px"></div>