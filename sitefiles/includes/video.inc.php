<?php
if (!isset($html))
  $html = new Html();

$query = "SELECT * FROM videos WHERE activo = 1 AND fecha_publicacion < NOW() ORDER BY fecha_publicacion DESC LIMIT 1";

$r = mysql_query($query);
if (mysql_num_rows($r) > 0) : $video = mysql_fetch_assoc($r)
  ?>
  <!-- VIDEO -->
  <div class="gContIncludes">
    <div class="gBckTitIncludes">
      <div class="gTitIncludes">
        <div class="gTxtTitIncludes">video</div>
        <div class="gPuntaIncludes"><img src="/sitefiles/img/puntaTitIncludes.jpg" width="21" alt="" /></div>
        <div class="gfloatRight"><img src="/sitefiles/img/icoyoutube.jpg" width="49" alt="" /></div>
      </div>
    </div>
      <?php echo $html->redimensionaVideo($video["cod_video"], 300, '') ?>
    <div class="gEnRenglonTit"><?php echo $video["titulo"] ?></div>
  </div>
<?php endif; ?>