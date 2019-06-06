<input type="hidden" name="fecha_creacion" id="fecha_creacion" value="<?php echo (isset($noticia)) ? $noticia['fecha_creacion'] : ""; ?>" />
<input type="hidden" name="fecha_modificacion" id="fecha_modificacion" value="<?php echo date("Y-m-d H:i:s") ?>" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td>Publicado:</td>
    <td><input type="checkbox" name="activo" value="1" <?php if (isset($noticia) && $noticia['activo'] == 1)
  echo "checked"; ?> /></td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Seccion:</td>
    <td>
  <!--    	<select name="seccion" id="seccion" style="width:150px" onchange="recarga_secciones(this);">-->
      <select name="seccion" id="seccion" style="width:150px" onclick="recarga_secciones(this.value);">
        <option value="">Elija una ...</option>
        <?php
        $array_secciones = $funciones->get_secciones($funciones->tabla);
        foreach ($array_secciones as $unaSeccion) {
          ?>
          <option value="<?php echo $unaSeccion['id'] ?>" <?php if (isset($noticia) && $unaSeccion['id'] == $noticia['seccion'])
          echo "selected"; ?> onclick="recarga_secciones(this);"><?php echo $unaSeccion['titulo'] ?></option>
<?php } ?>
      </select>
    </td>
  </tr>
  <tr class="tr_subseccion">
    <td class="separador" colspan="2"></td>
  </tr>
  <tr class="tr_subseccion">
    <td>Subsección:</td>
    <td>
      <?php
      if (isset($noticia))
        
        $r = $funciones->get_subsecciones($noticia['seccion']);
      else
        $r = array();
      ?>
      <select name="subseccion" id="subseccion" style="width:150px"  onchange="recargar_planillas(this,document.form_noticia.subseccion.value);" >
        <?php
        foreach ($r as $subseccion) {
          ?>
          <option value="<?php echo $subseccion['id'] ?>" <?php if ($subseccion['id'] == $noticia['subseccion'])
            echo "selected"; ?>>
          <?php echo $subseccion['titulo'] ?>
          </option>
  <?php
}
?>
      </select>
    </td>
  </tr>
  <tr id="separador" style="display: none;">
    <td class="separador" colspan="2"></td>
  </tr>
  <tr id="inferior" style="display: none;">
    <td>En modulo inferior:</td>
    <td><input type="checkbox" id="modulo_inferior" name="modulo_inferior" value="1" <?php if (isset($noticia) && $noticia['modulo_inferior'] == 1)
  echo 'checked="checked"'; ?> /></td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Planilla de portada:</td>
    <td>
      <?php
      if (isset($noticia))
        $planillas_portada = $funciones->get_planillas_de_elemento($noticia['id'], 1, $funciones->tabla);
      else
        $planillas_portada = array();
      ?>
      <select multiple name="planillas_portada[]" style="width:150px;height:50px;">
        <?php
        $planillas = $funciones->get_planillas(1);
        foreach ($planillas as $unaPlanilla) {
          ?>
          <option value="<?php echo $unaPlanilla['id'] ?>" <?php if (in_array($unaPlanilla['id'], $planillas_portada))
            echo "selected"; ?>><?php echo $unaPlanilla['fecha_publicacion'] ?></option>
  <?php } ?>
      </select>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Planilla de secci&oacute;n:</td>
    <td>
      <select multiple name="planillas_seccion[]" id="planillas_seccion" style="width:150px;height:50px;">
          <?php
          if (isset($noticia)) {
            $planillas_seccion = $funciones->get_planillas_de_elemento($noticia['id'], $noticia['seccion'], $funciones->tabla);
            ?>
          <optgroup label="Secci&oacute;n">
            <?php
            if (isset($noticia)) {
              $planillas = $funciones->get_planillas($noticia['seccion']);
              foreach ($planillas as $unaPlanilla) {
                ?>
                <option value="<?php echo $unaPlanilla['id'] ?>" <?php if (in_array($unaPlanilla['id'], $planillas_seccion))
          echo "selected"; ?>><?php echo $unaPlanilla['fecha_publicacion'] ?> - <?php echo $unaPlanilla['id'] ?></option>
                <?php
              }
              ?>
            </optgroup>
              <?php if (($noticia['subseccion'] != 0) && ($noticia["seccion"] != 4)) { ?>
              <optgroup label="Subsecci&oacute;n">
                <?php
                $planillas = $funciones->get_planillas($noticia['subseccion']);
                foreach ($planillas as $unaPlanilla) {
                  ?>
                  <option value="<?php echo $unaPlanilla['id'] ?>" <?php if (in_array($unaPlanilla['id'], $planillas_seccion))
          echo "selected"; ?>><?php echo $unaPlanilla['fecha_publicacion'] ?> - <?php echo $unaPlanilla['id'] ?></option>
        <?php } ?>
              </optgroup>
    <?php } ?>

  <?php }
} ?>
      </select>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Fecha de publicaci&oacute;n:</td>
    <td><input type="text" name="fecha_publicacion" id="fecha_publicacion" value="<?php echo (isset($noticia)) ? $noticia['fecha_publicacion'] : ""; ?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador2" value="..." /></td>
  </tr>
  <script type="text/javascript">
    Calendar.setup({
      inputField     :    "fecha_publicacion",     // id del campo de texto
      ifFormat       :    "%Y-%m-%d %H:%M",
      showsTime      :    true,
      button     :    "lanzador2"     // el id del botón que lanzará el calendario
    });
  </script>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Numero de veces leida:</td>
    <td><input type="text" name="hits" value="<?php echo (isset($noticia)) ? $noticia['hits'] : ""; ?>" disabled/></td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Titulo en Padel & Style:</td>
    <td><input type="text" name="modulo_gris" value="<?php echo (isset($noticia)) ? $noticia['modulo_gris'] : ""; ?>" /></td>
  </tr>
</table>
<script type="text/javascript">
  <!--
  var sub = document.getElementById("seccion");
  sub.onchange = function() {
    if(sub.options[sub.selectedIndex].text == "Agenda" || sub.options[sub.selectedIndex].text == "Golf Solidario" || sub.options[sub.selectedIndex].text == "Tu Ryder") {
      document.getElementById("separador").style.display = "";
      document.getElementById("inferior").style.display = "";
    } else {
      document.getElementById("separador").style.display = "none";
      document.getElementById("inferior").style.display = "none";
      document.getElementById("modulo_inferior").checked = "";
    }
  }
  if(sub.options[sub.selectedIndex].text == "Agenda" || sub.options[sub.selectedIndex].text == "Golf Solidario" || sub.options[sub.selectedIndex].text == "Tu Ryder") {
    document.getElementById("separador").style.display = "";
    document.getElementById("inferior").style.display = "";
  }
  //-->
</script>