<?php
if(!isset($html)) $html = new Html();

$query = "SELECT id, titulo, titular_alt, antetitulo, antetitulo_alt, entradilla, entradilla_alt, img_vertical, img_cuadrada, tags FROM noticias WHERE seccion=5 AND activo=1 AND fecha_publicacion < NOW() ORDER BY fecha_publicacion DESC LIMIT 1";

$r = mysql_query($query);

if(mysql_num_rows($r)>0){
 	$noticia = mysql_fetch_assoc($r);
 	$noticia["nombre_seccion"] = "entrevistas";
 	$noticia["nombre_subseccion"] = "entrevistas";
?>
  <!-- ENTREVISTA -->
  <div class="gContIncludes">
    <div class="gBckTitIncludes">
      <div class="gTitIncludes">
        <div class="gTxtTitIncludes">entrevista</div>
        <div class="gPuntaIncludes"><img src="/sitefiles/img/puntaTitIncludes.jpg" width="21" alt="" /></div>
        <div class="gfloatRight"><a href="/entrevistas"><img src="/sitefiles/img/vermas.jpg" width="38" alt="" /></a></div>
      </div>
    </div>
    <div class="gEntRenglonTit" style="height:100%">
      <?php
      if ($noticia["img_vertical"] != "") {
        $src_aux = explode("/", substr($noticia["img_vertical"], 3));
        $aux = $src_aux[count($src_aux) - 1];
        $src_aux[count($src_aux) - 1] = "vertical_116";
        $src_aux[count($src_aux)] = $aux;
        $src = implode("/", $src_aux);
        $src = substr($noticia["img_vertical"], 3);
        ?>
        <a href="<?php echo $html->enlace_sin($noticia) ?>"><img src="/<?php echo $src ?>"></a>
  <?php } ?>
      <div class="gEntTit01"><?php echo ($noticia["antetitulo_alt"] == "") ? $noticia["antetitulo"] : $noticia["antetitulo_alt"] ?></div>
      <div class="gEntTit"><a href="<?php echo $html->enlace_sin($noticia) ?>" style="color:#6B6959;text-decoration:none"><?php echo ($noticia["titular_alt"] == "") ? $noticia["titulo"] : $noticia["titular_alt"] ?></a></div>
      <div class="gEntTxt"><?php echo $html->escribir_entradilla($noticia, "gris") ?></div>
    </div>
    <div class="gEntBott"><img src="/sitefiles/img/bottomEntrevista.jpg" width="300" alt="" /></div>
  </div>
<?php } ?>