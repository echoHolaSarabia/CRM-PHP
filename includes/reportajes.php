<?php
if (!isset($html))
  $html = new Html();

$query = "
  SELECT `id` , `titulo`
  FROM `secciones`
  WHERE `id_padre` =53";
$result = mysql_query($query);
?>
<div class="iContActualidad">
  <div class="iActualidad" align="left">
    <div style="float:left; color:#FFF; font-size:25px; padding-top:20px;"><img src="/img/Imagen1.png" alt="Padel & Style"/></div>
    <div style="height:50px;margin:20px;text-align:right;margin-bottom:0">
      <a href="/reportajes" style="color:#FFF;font-size:16px;text-decoration:none"><img src="/img/reportajes.jpg" alt="repor"/></a>
    </div>
    <?php
    while ($reportaje = mysql_fetch_object($result)) :
      $query = "
        SELECT n.id, n.titulo, n.titular_alt, n.entradilla, n.entradilla_alt, n.img_horizontal, n.num_comentarios, n.tags, n.modulo_gris, s.titulo AS nombre_subseccion
        FROM noticias n, secciones s
        WHERE n.subseccion = " . $reportaje->id . "
        AND n.subseccion = s.id
        AND n.activo =1
        AND n.fecha_publicacion < NOW( )
        ORDER BY n.fecha_publicacion DESC
        LIMIT 1";
      $result2 = mysql_query($query);
      if (mysql_num_rows($result2) > 0) {
        $noticia = mysql_fetch_assoc($result2);
        $noticia["nombre_seccion"] = "reportajes";
        $src_aux = explode("/", substr($noticia["img_horizontal"], 3));
        $aux = $src_aux[count($src_aux) - 1];
        $src_aux[count($src_aux) - 1] = "horizontal_220";
        $src_aux[count($src_aux) - 1] = "";
        $src_aux[count($src_aux)] = $aux;
        $src = implode("/", $src_aux);
      ?>
        <div class="iActualidad01">
          <a href="<?php echo $html->enlace_sin($noticia) ?>"><div style="background-image: url('<?php echo $src ?>');
background-size: cover; width:220px; height:100px;" /></div></a>
          <div class="iActTit01"><?php echo (isset($noticia["modulo_gris"]) && $noticia["modulo_gris"] != "") ? $noticia["modulo_gris"] : $noticia["nombre_subseccion"]; ?></div>
          <div class="iActTit"><a href="<?php echo $html->enlace_sin($noticia) ?>"><?php echo ($noticia["titular_alt"] == "") ? $noticia["titulo"] : $noticia["titular_alt"] ?></a></div>
          <div class="iActTxt"><?php echo $html->escribir_entradilla($noticia, "blanco") ?></div>
        </div>
      <?php
      } else {
        $sinreportajes = " sin reportajes ";
      }
    endwhile; ?>
  </div>
</div>