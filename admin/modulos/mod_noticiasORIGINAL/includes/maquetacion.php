<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td>Recomendado:</td>
    <td><input type="checkbox" name="recomendado" value="1" <?php if (isset($noticia) && $noticia['recomendado'] == 1)
  echo "checked"; ?> /></td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <input type="hidden" name="recurso_maquetacion" value="">
  <?php /*
    <tr>
    <td>Recurso de maquetacion:</td>
    <td>
    <select name="recurso_maquetacion" style="width:150px">
    <option value="">Ninguno</option>
    <option value="destacado_verde" <?php if (isset($noticia)&&($noticia['recurso_maquetacion'] == "destacado_verde")) echo "selected";?>>Destacado verde</option>
    <option value="destacado_verde_blanco" <?php if (isset($noticia)&&($noticia['recurso_maquetacion'] == "destacado_verde_blanco")) echo "selected";?>>Destacado verdiblanco</option>
    </select>
    </td>
    </tr>

    <tr>
    <td class="separador" colspan="2"></td>
    </tr> */ ?>
  <tr>
    <td>Tamaño del titular:</td>
    <td>
      <select name="tamanio_titular" id="tamanio_titular" style="width:150px">
        <option value="24" <?php if (isset($noticia) && ($noticia['tamanio_titular'] == "24"))
    echo "selected"; ?>>Mediano</option>
        <option value="20" <?php if (isset($noticia) && ($noticia['tamanio_titular'] == "20"))
    echo "selected"; ?>>Pequeño</option>
        <option value="35" <?php if (isset($noticia) && ($noticia['tamanio_titular'] == "35"))
    echo "selected"; ?>>Grande</option>
      </select>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Posicion de la imagen de portada:</td>
    <td>
      <select name="posicion_imagen_portada">
        <option value="encima" <?php if (isset($noticia) && $noticia['posicion_imagen_portada'] == "encima")
    echo "selected"; ?>>Foto encima titular</option>
        <option value="debajo" <?php if (isset($noticia) && $noticia['posicion_imagen_portada'] == "debajo")
    echo "selected"; ?>>Foto debajo titular</option>
        <option value="izquierda" <?php if (isset($noticia) && $noticia['posicion_imagen_portada'] == "izquierda")
    echo "selected"; ?>>A la izquierda del texto</option>
        <option value="derecha" <?php if (isset($noticia) && $noticia['posicion_imagen_portada'] == "derecha")
    echo "selected"; ?>>A la derecha del texto</option>



      </select>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
          <td>
            Titular alternativo: (max. 38 caracteres)&nbsp;
            <input type="text" name="titular_alt" id="titular_alt" value="<?php echo (isset($noticia)) ? $noticia['titular_alt'] : ""; ?>" maxlength="38" size="56px"><br><br>
          </td>
        </tr>
        <tr>
          <td>
            Antetitulo alternativo:<br />
            <textarea name="antetitulo_alt" style="width:100%"><?php echo (isset($noticia)) ? $noticia['antetitulo_alt'] : ""; ?></textarea>
          </td>
        </tr>
        <tr>
          <td>
            Entradilla alternativa:<br />
            <textarea name="entradilla_alt" style="width:100%"><?php echo (isset($noticia)) ? $noticia['entradilla_alt'] : ""; ?></textarea>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>