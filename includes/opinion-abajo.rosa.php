<?php
if (!isset($html))
  $html = new Html();

$query = "
  SELECT n.id, n.tags, n.autor AS nombre, n.entradilla, n.entradilla_alt, n.img_horizontal, n.img_vertical, n.img_cuadrada, n.img_ampliada, n.titulo, n.titular_alt
  FROM noticias n
  WHERE n.seccion =58
  AND n.fecha_publicacion <= now( )
  ORDER BY n.fecha_publicacion DESC
  LIMIT 2";

$r = mysql_query($query);
if (mysql_num_rows($r) >= 0) : ?>
<div style="border:1px solid #E5E5E5;margin:10px 0 0;padding:4px;width:300px">
  <div class="iBckTitPart">
    <div class="iTitPart">
      <div class="iTxtTitPart">BLOGS</div>
      <div class="iPuntaPart"><img src="sitefiles/img/puntaTitRotador.jpg" width="21" alt="" /></div>
      <div class="iVerMas"><a href="/opinion">+ OPINIONES</a></div>
    </div>
  </div>
  <?php while ($noticia = mysql_fetch_assoc($r)) :
    $noticia["nombre_seccion"] = "opinion";
    $noticia["nombre_subseccion"] = "opinion";
    if ($noticia['img_ampliada'] != "")
      $src = $noticia['img_ampliada'];
    if ($noticia['img_horizontal'] != "")
      $src = $noticia['img_horizontal'];
    if ($noticia['img_vertical'] != "")
      $src = $noticia['img_vertical'];
    if ($noticia['img_cuadrada'] != "")
      $src = $noticia['img_cuadrada'];
    ?>
    <div class="iPart02" style="overflow:hidden">
      <div class="iPartImg"><img src="<?php echo $src ?>" width="54" alt="" /></div>
      <div class="iPartT1"><?php echo $noticia["nombre"] ?></div>
      <div class="iPartT2"><a href="<?php echo $html->enlace_sin($noticia) ?>"><?php echo ($noticia["titular_alt"] == "") ? $noticia["titulo"] : $noticia["titular_alt"] ?></a></div>
      <div class="iPartT3"><img src="sitefiles/img/icoGlobo.png" width="15" alt="" /><?php echo $html->escribir_entradilla($noticia, "gris_corto") ?></div>
    </div>
  <?php endwhile; ?>
</div>
<?php endif; ?>