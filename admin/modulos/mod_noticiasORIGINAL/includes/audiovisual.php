<style type="text/css">.botones_audiovisual td{background:#989898;margin:2px;color:#FFF;border:1px solid #555;cursor:pointer}</style>
<!-- BOTONERA DE AUDIOVISUAL -->
<table width="100%" border="0" class="botones_audiovisual">
  <tr>
    <td align="center" width="33%" onclick="cambia_etiqueta_audiovisual(1,3)">Imágenes</td>
    <td align="center" width="33%" onclick="cambia_etiqueta_audiovisual(2,3)">Video</td>
    <td align="center" width="33%" onclick="cambia_etiqueta_audiovisual(3,3)">Audio</td>
  </tr>
</table>
<!-- FIN BOTONERA DE AUDIOVISUAL -->

<!-- CONTENIDO IMAGENES -->
<table width="100%" id="audiovideo1" style="display:block">
  <tr>
    <td>
      <div id="content">
        <img id="loading" src="loading.gif" style="display:none;">
        <table cellpadding="0" cellspacing="0" class="tableForm">
          <tr>
            <td>Seleccione la imagen que desea subir:</td>
          </tr>
          <tr>
            <td><input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input">&nbsp;<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();">Upload</button></td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
  <script type="text/javascript">
    function filterURL1(file) {
      window.open('/admin/modulos/mod_noticias/recorte/index.php?tipo=rotador&foto='+file.url,'recorte2','width=1000,height=800,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes');
    }
    function filterURL2(file) {
      window.open('/admin/modulos/mod_noticias/recorte/index.php?tipo=horizontal&foto='+file.url,'recorte2','width=1000,height=800,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes');
    }
    function filterURL3(file) {
      window.open('/admin/modulos/mod_noticias/recorte/index.php?tipo=vertical&foto='+file.url,'recorte2','width=1000,height=800,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes');
    }
    function filterURL4(file) {
      window.open('/admin/modulos/mod_noticias/recorte/index.php?tipo=cuadrada&foto='+file.url,'recorte2','width=1000,height=800,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes');
    }
    function filterURL5(file) {
      window.open('/admin/modulos/mod_noticias/recorte/index.php?tipo=ampliada&foto='+file.url,'recorte2','width=1000,height=800,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes');
    }
    function filterURL6(file) {
      window.open('/admin/modulos/mod_noticias/recorte/index.php?tipo=newsletter&foto='+file.url,'recorte2','width=1000,height=800,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes');
    }
  </script>
  <tr>
    <td>
      <table width="100%" border="1">
        <?php
        $i = 1;
        foreach ($tipos_de_recorte as $recorte) {
          ?>
          <tr>
            <td>
              <a href="javascript:mcImageManager.open('','','','',{relative_urls : true,insert_filter : filterURL<?php echo $i ?>});">Elegir imagen para <?php echo $recorte ?></a>
              <br><input type="radio" name="recorte_ampliada" value="img_<?php echo $recorte ?>" <?php echo ((!isset($noticia) && ($recorte == "ampliada")) || (isset($noticia) && ("img_" . $recorte == $noticia["recorte_ampliada"]))) ? " checked='checked' " : "" ?>>&nbsp;Usar para ampliada
              <br><input type="radio" name="recorte_newsletter" value="img_<?php echo $recorte ?>" <?php echo ((!isset($noticia) && ($recorte == "newsletter")) || (isset($noticia) && ("img_" . $recorte == $noticia["recorte_newsletter"]))) ? " checked='checked' " : "" ?>>&nbsp;Usar para newsletter
            </td>
            <td>
              <div id="miniatura_<?php echo $recorte ?>"><?php if (isset($noticia) && $noticia['img_' . $recorte] != "")
          echo "<img src='" . $noticia['img_' . $recorte] . "' width='100px' onclick='window.open(\"modulos/mod_noticias/verimagen.php?src=\"+this.src,\"Previsualización 1\",\"width=800,height=600,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes\")' />" ?></div>
              <input type="hidden" name="img_<?php echo $recorte ?>" id="<?php echo $recorte ?>" value="<?php if (isset($noticia) && $noticia['img_' . $recorte] != "")
              echo $noticia['img_' . $recorte]; ?>">
            </td>
            <td><img border="0" src="images/cross.png" alt="Eliminar" title="Eliminar" onclick="javascript:eliminarFoto('<?php echo $recorte ?>')"/></td>
          </tr>
          <?php
          $i++;
        }
        ?>
        <tr>
          <td>
            Foto extra:&nbsp;<input type="file" name="extra">
          </td>
          <td>
            <div id="miniatura_foto_extra"><?php if (isset($noticia) && $noticia['foto_extra'] != "")
          echo "<img src='/userfiles/extra/" . $noticia['foto_extra'] . "' width='100px' onclick='window.open(\"modulos/mod_noticias/verimagen.php?src=\"+this.src,\"Previsualización 1\",\"width=800,height=600,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes\")' />" ?></div>
            <input type="hidden" name="foto_extra" id="foto_extra" value="<?php if (isset($noticia) && $noticia['foto_extra'] != "")
              echo $noticia['foto_extra']; ?>">
          </td>
          <td><img border="0" src="images/cross.png" alt="Eliminar" title="Eliminar" onclick="javascript:eliminarFoto('foto_extra')"/></td>
        </tr>
        <tr>
          <td>
            Pie de foto:&nbsp;<input type="text" name="pie_img_ampliada" value="<?php echo (isset($noticia)) ? $noticia["pie_img_ampliada"] : "" ?>" style="width:180px">
          </td>
          <td>
            <div id="miniatura_foto_extra"><?php if (isset($noticia) && $noticia['foto_extra'] != "")
                     echo "<img src='/userfiles/extra/" . $noticia['foto_extra'] . "' width='100px' onclick='window.open(\"modulos/mod_noticias/verimagen.php?src=\"+this.src,\"Previsualización 1\",\"width=800,height=600,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes\")' />" ?></div>
            <input type="hidden" name="foto_extra" id="foto_extra" value="<?php if (isset($noticia) && $noticia['foto_extra'] != "")
              echo $noticia['foto_extra']; ?>">
          </td>
          <td><img border="0" src="images/cross.png" alt="Eliminar" title="Eliminar" onclick="javascript:eliminarFoto('foto_extra')"/></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<!-- FIN CONTENIDO IMAGENES -->

<!-- CONTENIDO VIDEO -->
<?php
$opcion1 = "selected";
$opcion2 = "";
$opcion3 = "";
$estilo1 = "table-row";
$estilo2 = "none";
$estilo3 = "none";
$disable1 = "";
$disable2 = "disable";
$disable3 = "disable";
if (isset($noticia)) {
  if ($noticia["tipo_video"] == "fichero") {
    $opcion1 = "";
    $opcion2 = "selected";
    $opcion3 = "";
    $estilo1 = "none";
    $estilo2 = "table-row";
    $estilo3 = "none";
    $disable1 = "disable";
    $disable2 = "";
    $disable3 = "disable";
  } elseif ($noticia["tipo_video"] == "importar") {
    $opcion1 = "";
    $opcion2 = "";
    $opcion3 = "selected";
    $estilo1 = "none";
    $estilo2 = "none";
    $estilo3 = "table-row";
    $disable1 = "disable";
    $disable2 = "disable";
    $disable3 = "";
  }
}
?>
<table width="100%" id="audiovideo2" style="display:none">
  <?php if (isset($noticia) && $noticia['cod_video'] != "") { ?>
    <tr>
      <td align="center">
        <table>
          <tr>
            <td id="preview">
              <?php
              if ($noticia['tipo_video'] == "cod_video" || $noticia['tipo_video'] == "importar") {
                echo $noticia['cod_video'];
              } else {
                ?>
                <script type='text/javascript' src='/videoplayer/swfobject.js'></script>
                <div id='video'>Video</div>
                <script type='text/javascript'>
                  var s1 = new SWFObject('/videoplayer/player.swf','player','320','250','9','#ffffff');
                  s1.addParam('allowfullscreen','true');
                  s1.addParam('allowscriptaccess','always');
                  s1.addParam('wmode','opaque');
                  s1.addParam('flashvars','file=/videos/<?php echo $noticia['cod_video'] ?>');
                  s1.write('video');
                </script>
                <?php
              }
              ?>
            </td>
            <td><img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar video" title="Eliminar video" onclick="eliminar_video()"></td>
          </tr>
        </table>
      </td>
    </tr>
  <?php } ?>
  <tr>
    <td>
      <table id="capa_11">
        <tr>
          <td>Fuente:</td>
          <td>
            <select name="tipo_video" id="tipo_video" onchange="cambia_fuente_video(this);">
              <option value="cod_video" <?php echo $opcion1 ?>>Código</option>
              <option value="fichero" <?php echo $opcion2 ?>>Fichero</option>
              <option value="importar" <?php echo $opcion3 ?>>Importar de bbdd</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="separador" colspan="2"></td>
        </tr>
        <tr id="tipo1" style="display:<?php echo $estilo1 ?>">
          <td>C&oacute;digo:</td>
          <td>
            <textarea name="cod_video" id="cod_video" cols="40" rows="5" <?php echo $disable1 ?>><?php echo (isset($noticia)) ? htmlentities($noticia["cod_video"]) : ""; ?></textarea>
          </td>
        </tr>
        <tr id="tipo2" style="display:<?php echo $estilo2 ?>">
          <td>Fichero:</td>
          <td>
            <input type="file" name="cod_video" id="fichero" <?php echo $disable2 ?>>
          </td>
        </tr>
        <tr id="tipo3" style="display:<?php echo $estilo3 ?>">
          <td colspan="2">
            <?php $videos = $funciones->importarVideos(); ?>
            <select name='cod_video' id='importar' <?php echo $disable3 ?>>
              <option id='0'>Elegir un video multimedia</option>
              <?php foreach ($videos as $unVideo) { ?>
                <option id='<?php echo $unVideo['id'] ?>' value='<?php echo addslashes(htmlentities($unVideo['codigo'])) ?>'><?php echo $unVideo['titular'] ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="2"><input type="checkbox" name="video_portada" value="1" <?php if (isset($noticia) && $noticia['video_portada'] == 1)
                echo "checked"; ?>>&nbsp;Mostrar video en portada</td>
        </tr>
        <tr>
          <td colspan="2"><input type="checkbox" name="video_seccion" value="1" <?php if (isset($noticia) && $noticia['video_seccion'] == 1)
                                   echo "checked"; ?>>&nbsp;Mostrar video en secci&oacute;n</td>
        </tr>
        <tr>
          <td colspan="2"><input type="checkbox" name="video_ampliada" value="1" <?php if (isset($noticia) && $noticia['video_ampliada'] == 1)
                                   echo "checked"; ?>>&nbsp;Mostrar video en noticia ampliada</td>
        </tr>
        <tr>
          <td colspan="2"><input type="checkbox" name="exportar" value="si">&nbsp;Exportar al archivo de video</td>
        </tr>
      </table>
      
            <script type="text/javascript">
                document.getElementById('tipo1').style.display = '';
                document.getElementById('tipo2').style.display = 'none';
                document.getElementById('tipo3').style.display = 'none';
                document.getElementById('cod_video').disabled=false;
                document.getElementById('fichero').disabled=true;
                document.getElementById('importar').disabled=true;
            </script>
    </td>
  </tr>
</table>
<!-- FIN CONTENIDO VIDEO -->

<!-- CONTENIDO AUDIO -->
<?php
$opcion1 = "selected";
$opcion2 = "";
$opcion3 = "";
$estilo1 = "table-row";
$estilo2 = "none";
$estilo3 = "none";
$disable1 = "";
$disable2 = "disable";
$disable3 = "disable";
if (isset($noticia)) {
  if ($noticia["tipo_audio"] == "fichero_audio") {
    $opcion1 = "";
    $opcion2 = "selected";
    $opcion3 = "";
    $estilo1 = "none";
    $estilo2 = "table-row";
    $estilo3 = "none";
    $disable1 = "disable";
    $disable2 = "";
    $disable3 = "disable";
  } elseif ($noticia["tipo_audio"] == "importar_audio") {
    $opcion1 = "";
    $opcion2 = "";
    $opcion3 = "selected";
    $estilo1 = "none";
    $estilo2 = "none";
    $estilo3 = "table-row";
    $disable1 = "disable";
    $disable2 = "disable";
    $disable3 = "";
  }
}
?>
<table width="100%" id="audiovideo3" style="display:none">
  <?php if (isset($noticia) && $noticia['cod_audio'] != "") { ?>
    <tr>
      <td align="center">
        <table>
          <tr>
            <td id="preview">
              <?php
              if ($noticia['tipo_audio'] == "cod_audio" || $noticia['tipo_audio'] == "importar_audio") {
                echo $noticia['cod_audio'];
              } else {
                ?>
                <script type='text/javascript' src='/videoplayer/swfobject.js'></script>
                <div id='audio'>Audio</div>
                <script type='text/javascript'>
                  var s1 = new SWFObject('/videoplayer/player.swf','player','320','20','9','#ffffff');
                  s1.addParam('allowfullscreen','true');
                  s1.addParam('allowscriptaccess','always');
                  s1.addParam('wmode','opaque');
                  s1.addParam('flashvars','file=/audios/<?php echo $noticia['cod_audio'] ?>');
                  s1.write('audio');
                </script>
                <?php
              }
              ?>
            </td>
            <td><img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar audio" title="Eliminar audio" onclick="eliminar_audio()"></td>
          </tr>
        </table>
      </td>
    </tr>
  <?php } ?>
  <tr>
    <td>
      <table id="capa_11">
        <tr>
          <td><br>Fuente:</td>
          <td>
            <select name="tipo_audio" onchange="cambia_fuente_audio(this);">
              <option value="cod_audio" <?php echo $opcion1 ?>>Código</option>
              <option value="fichero_audio" <?php echo $opcion2 ?>>Fichero</option>
              <option value="importar_audio" <?php echo $opcion3 ?>>Importar de bbdd</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="separador" colspan="2"></td>
        </tr>
        <tr id="tipo1_audio" style="display:<?php echo $estilo1 ?>">
          <td>C&oacute;digo:</td>
          <td>
            <textarea name="cod_audio" cols="40" rows="5" id="cod_audio" <?php echo $disable1 ?>><?php echo (isset($noticia)) ? htmlentities($noticia["cod_audio"]) : ""; ?></textarea>
          </td>
        </tr>
        <tr id="tipo2_audio" style="display:<?php echo $estilo2 ?>">
          <td>Fichero:</td>
          <td>
            <input type="file" name="cod_audio" id="fichero_audio" <?php echo $disable2 ?>>
          </td>
        </tr>
        <tr id="tipo3_audio" style="display:<?php echo $estilo3 ?>">
          <td colspan="2">
            <?php $videos = $funciones->importarVideos(); ?>
            <select name='cod_audio' id='importar_audio' <?php echo $disable3 ?>>
              <option id='0'>Elegir un video multimedia</option>
              <?php foreach ($videos as $unVideo) { ?>
                <option id='<?php echo $unVideo['id'] ?>' value='<?php echo addslashes(htmlentities($unVideo['codigo'])) ?>'><?php echo $unVideo['titular'] ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="2"><input type="checkbox" name="audio_portada" value="1" <?php if (isset($noticia) && $noticia['audio_portada'] == 1)
                echo "checked"; ?>>&nbsp;Mostrar audio en portada</td>
        </tr>
        <tr>
          <td colspan="2"><input type="checkbox" name="audio_seccion" value="1" <?php if (isset($noticia) && $noticia['audio_seccion'] == 1)
                                   echo "checked"; ?>>&nbsp;Mostrar audio en secci&oacute;n</td>
        </tr>
        <tr>
          <td colspan="2"><input type="checkbox" name="audio_ampliada" value="1" <?php if (isset($noticia) && $noticia['audio_ampliada'] == 1)
                                   echo "checked"; ?>>&nbsp;Mostrar audio en noticia ampliada</td>
        </tr>
        <tr>
          <td colspan="2"><input type="checkbox" name="exportar_audio" value="si">&nbsp;Exportar al archivo de video</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<!-- FIN CONTENIDO AUDIO -->