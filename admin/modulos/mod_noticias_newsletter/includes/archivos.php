  <div id="opcion2" style="display:none">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">Documento 1:&nbsp;
      <? if (isset($noticia) && $noticia['doc_1'] != ""){?>
        <a href="../docs/newsletter/<?=$noticia['doc_1']?>"><?=$noticia['doc_1']?></a><img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar documento" onclick="borrar_doc('<?=$noticia['doc_1']?>',<?=$noticia['id']?>,'doc_1')">
      <? } else{ ?>
        <input type="file" name="doc_1" size="40" value=""/>
      <? } ?>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"> Nombre Documento 1:&nbsp;
        <input type="text" name="nombre_doc_1" size="30" value="<?=(isset($noticia)) ? $noticia['nombre_doc_1'] : "";?>"/>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">Documento 2:&nbsp;
      <? if (isset($noticia) && $noticia['doc_2'] != ""){?>
        <a href="../docs/newsletter/<?=$noticia['doc_2']?>"><?=$noticia['doc_2']?></a><img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar documento" onclick="borrar_doc('<?=$noticia['doc_2']?>',<?=$noticia['id']?>,'doc_2')">
      <? } else{ ?>
        <input type="file" name="doc_2" size="40" value=""/>
      <? } ?>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"> Nombre Documento 2:&nbsp;
        <input type="text" name="nombre_doc_2" size="30" value="<?=(isset($noticia)) ? $noticia['nombre_doc_2'] : "";?>"/>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">Documento 3:&nbsp;
      <? if (isset($noticia) && $noticia['doc_3'] != ""){?>
        <a href="../docs/newsletter/<?=$noticia['doc_3']?>"><?=$noticia['doc_3']?></a><img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar documento" onclick="borrar_doc('<?=$noticia['doc_3']?>',<?=$noticia['id']?>,'doc_3')">
      <? } else{ ?>
        <input type="file" name="doc_3" size="40" value=""/>
      <? } ?>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"> Nombre Documento 3:&nbsp;
        <input type="text" name="nombre_doc_3" size="30" value="<?=(isset($noticia)) ? $noticia['nombre_doc_3'] : "";?>"/>
    </td>
  </tr>
</table>
</div>