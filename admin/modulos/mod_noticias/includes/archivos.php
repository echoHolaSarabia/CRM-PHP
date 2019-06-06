<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">Documento 1:&nbsp;
      <?php if (isset($noticia) && $noticia['doc_1'] != "") { ?>
        <a href="../docs/<?php echo $noticia['doc_1'] ?>"><?php echo $noticia['doc_1'] ?></a><img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar documento" onclick="borrar_doc('<?php echo $noticia['doc_1'] ?>',<?php echo $noticia['id'] ?>,'doc_1')">
      <?php } else { ?>
        <input type="file" name="doc_1" size="40" value=""/>
      <?php } ?>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"> Nombre Documento 1:&nbsp;
      <input type="text" name="nombre_doc_1" size="30" value="<?php echo (isset($noticia)) ? $noticia['nombre_doc_1'] : ""; ?>"/>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">Documento 2:&nbsp;
      <?php if (isset($noticia) && $noticia['doc_2'] != "") { ?>
        <a href="../docs/<?php echo $noticia['doc_2'] ?>"><?php echo $noticia['doc_2'] ?></a><img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar documento" onclick="borrar_doc('<?php echo $noticia['doc_2'] ?>',<?php echo $noticia['id'] ?>,'doc_2')">
      <?php } else { ?>
        <input type="file" name="doc_2" size="40" value=""/>
      <?php } ?>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"> Nombre Documento 2:&nbsp;
      <input type="text" name="nombre_doc_2" size="30" value="<?php echo (isset($noticia)) ? $noticia['nombre_doc_2'] : ""; ?>"/>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">Documento 3:&nbsp;
      <?php if (isset($noticia) && $noticia['doc_3'] != "") { ?>
        <a href="../docs/<?php echo $noticia['doc_3'] ?>"><?php echo $noticia['doc_3'] ?></a><img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar documento" onclick="borrar_doc('<?php echo $noticia['doc_3'] ?>',<?php echo $noticia['id'] ?>,'doc_3')">
      <?php } else { ?>
        <input type="file" name="doc_3" size="40" value=""/>
      <?php } ?>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"> Nombre Documento 3:&nbsp;
      <input type="text" name="nombre_doc_3" size="30" value="<?php echo (isset($noticia)) ? $noticia['nombre_doc_3'] : ""; ?>"/>
    </td>
  </tr>
</table>