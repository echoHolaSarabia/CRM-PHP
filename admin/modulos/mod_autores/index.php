<?php
include("modulos/mod_autores/conf.php");
$funciones = new Funciones;
?>
<tr>
  <td width="100%">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="botonera" align="right">
          <table border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="botones_botonera"><a href="index2.php?modulo=<?php echo MODULO ?>&accion=nuevo" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nuevo</a></td>
              <td class="botones_botonera"><a href="javascript:document.form_listado_seleccion.submit();" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="buscador"><span class="titulo_seccion">Listado de Autores de Opinión</span></td>
      </tr>
      <tr>
        <td class="contenido">
          <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <?php $num_filas = $funciones->get_num_autores(); ?>
            <form name="form_listado_seleccion" method="post" action="index2.php?modulo=<?php echo MODULO ?>&fns=1&accion=delete">
              <tr class="titulos">
                <td>Id</td>
                <td><input type="checkbox" name="seleccion" onclick="checkAll(<?php echo $num_filas + 1 ?>,'');" /></td>
                <td>Nombre</td>
                <td align="center">Artículos</td>
                <td align="center">Activar</td>
                <td align="right">Editar</td>
              </tr>
              <?php
              //Muestra las categorías raiz
              $fila_seccion = 0;
              $secciones = $funciones->get_autores();
              foreach ($secciones as $seccion) {
                $fila_seccion++;
                if (($fila_seccion % 2) == 0)
                  $estilo = "fila_tabla_par";
                else
                  $estilo = "fila_tabla_impar";
                ?>
                <tr>
                  <td class="<?php echo $estilo ?>"><?php echo $seccion['id'] ?><?php if ($num_subsecciones > 0) { ?><img id="mas_menos_<?php echo $seccion['id'] ?>" src="images/mas.png" align="absmiddle" onclick="muestra_oculta('subsecciones_',<?php echo $seccion['id'] ?>)" style="cursor:pointer;"/><?php } ?></td>
                  <td class="<?php echo $estilo ?>"><input type="checkbox" name="seleccion<?php echo $fila_seccion; ?>" value="<?php echo $seccion['id'] ?>" /></td>
                  <td class="<?php echo $estilo ?>"><strong><?php echo stripslashes($seccion['titulo']) ?></strong></td>
                  <td class="<?php echo $estilo ?>" align="center">0</td>
                  <td class="<?php echo $estilo ?>" align="center"><a href="?modulo=mod_autores&fns=1&accion=estado&id=<?php echo $seccion['id'] ?>"><img width="15px" src="<?php if ($seccion["activo"] == 1)
                  echo "images/tick.png"; else
                  echo "images/eliminar.png"; ?>" border="0" /></a></td>
                  <td class="<?php echo $estilo ?>" align="right"><a href="?modulo=<?php echo MODULO ?>&accion=editar&id=<?php echo $seccion['id'] ?>"><img src="images/page_edit.png" border="0" /></a></td>
                </tr>
<?php } ?>
            </form>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>