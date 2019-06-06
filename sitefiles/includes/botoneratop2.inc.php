<?php
$query = "
  SELECT `id`,`titulo`,`tabla`
  FROM `secciones`
  WHERE `id_padre` = " . $id_seccion . "
    OR id=".$id_seccion."
    AND `activo` =1 ";

$result = mysql_query($query);

//$comunidades = array( 'MADRID', 'ANDALUCÍA', 'ARAGÓN', 'ASTURIAS', 'BALEARES', 'CANARIAS', 'CANTABRIA', 'CASTILLA LA MANCHA', 'CASTILLA LEÓN', 'CATALUÑA', 'CEUTA Y MELILLA', 'COMUNIDAD VALENCIANA', 'EXTREMADURA', 'GALICIA', 'LA RIOJA', 'MURCIA', 'NAVARRA', 'PAÍS VASCO' );

if ( mysql_num_rows($result) > 1 ) : ?>
    
    <div class="gSubbotonera">
  <ul>
    <li class="fyl">[</li>
    
    <?php $seccion; $flag=true; while($subseccion = mysql_fetch_object($result)) : if($subseccion->id==$id_seccion && $flag) : $seccion = $subseccion; $titulo_seccion["nombre_seccion"] = $subseccion->titulo; $flag=false; else : ?>
    
     <?php  
	 
	 if ($subseccion->id == 53){
	 }else{
	 
	 if ($subseccion->titulo == "Encuentros digitales"){
    }else{ ?>
    
    <li<?php $subseccion->id==$id_subseccion and print ' class="activa"' ?>>
      <?php if ($seccion->id==1 && $subseccion->tabla == 'noticias') : ?>
      <a href="<?php echo getSlugify($subseccion->titulo) ?>"><?php echo $subseccion->titulo ?></a>
      <?php else : ?>
      <a href="<?php echo ($subseccion->tabla == 'noticias') ? ('/' . (getSlugify($seccion->titulo) . '/' . getSlugify($subseccion->titulo))) : $subseccion->tabla ?>"><?php echo $subseccion->titulo ?></a>
      <?php endif; ?>
    </li>
    <?php }} endif; endwhile; ?>
    <?php 
	if($id_seccion == 1){
	?>
    <li class="fyl"> <a href="/encuentros-digitales">Encuentros digitales</a> ]</li>
    <?php 
	}else{
	?>
    <li class="fyl">]</li>
    <?php 
	}
	?>
    
    
  </ul>
</div>
<?php  endif; ?>