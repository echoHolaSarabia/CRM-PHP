<script type="text/javascript" src="scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="modulos/mod_autores/scripts/tiny_mce.js"></script>
<?php
if (isset($_GET['id']) && $_GET['id'] != "") {// MODIFICAR
  $r = mysql_query("SELECT * FROM autores_opinion WHERE id=" . $_GET['id']);
  $seccion = mysql_fetch_array($r);
  $accion = "update&id=" . $seccion['id'];
} else {// INSERTAR
  $accion = "insert";
}
?>
<tr>
  <td width="100%">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="botonera" align="right">
          <table border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="botones_botonera"><a href="javascript:document.form_secciones.submit();" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
              <td class="botones_botonera"><a href="index2.php?modulo=mod_autores" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="buscador"><span class="titulo_seccion"><?php if ($accion == "insert")
  echo "Nueva ";else
  echo "Editar "; ?>Autor de opini&oacute;n</span></td>
      </tr>
      <tr>
        <td>
          <form name="form_secciones" method="post" action="?modulo=mod_autores&fns=1&accion=<?php echo $accion ?>" enctype="multipart/form-data">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="titulos" colspan="2">Detalles</td>
              </tr>
              <tr>
                <td class="separador" colspan="2"></td>
              </tr>
              <tr>
                <td class="contenido">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td class="etiqueta_200px">Estado:</td>
                      <td>
                        <select name="activo" style="width:80px">
                          <option value="1" <?php if ($seccion['activo'] == 1)
  echo "selected"; ?>>Activo</option>
                          <option value="0" <?php if ($seccion['activo'] == 0)
  echo "selected"; ?>>Inactivo</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Nombre del autor</td>
                      <td>
                        <input type="text" name="titulo" value="<?php echo htmlentities($seccion['titulo']) ?>" style="width:300px;"/>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Descripcion:</td>
                      <td>
<?php /* <textarea name="entradilla" id="entradilla" class="mceEditor"><?php=(isset($seccion)) ? $seccion['entradilla'] : "";?></textarea> */ ?>
                        <input type="text" name="entradilla" value="<?php echo htmlentities($seccion['entradilla']) ?>" style="width:300px;" />
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">CV:</td>
                      <td>
                        <textarea name="cv" id="cv" class="mceEditor"><?php echo (isset($seccion)) ? $seccion['cv'] : ""; ?></textarea>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Foto del autor</td>
                      <td>
                        <?php
                        if (isset($seccion["foto"]) && $seccion["foto"] != "") {
                          ?>
                          <img src="../userfiles/autores/<?php echo $seccion['foto'] ?>" width="90" alt="boo">&nbsp;&nbsp;<a href="index2.php?modulo=mod_autores&fns=1&accion=borrar_foto&id=<?php echo $seccion["id"] ?>"><img border="0" src="images/eliminar.png" width="25"></a>
                          <?php
                        } else {
                          ?>
                          <input type="file" name="foto" name="foto">
<?php } ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>
  </td>
</tr>