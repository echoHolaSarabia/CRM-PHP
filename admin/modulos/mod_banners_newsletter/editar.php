<?
if (isset($_GET['id']) && $_GET['id']!=""){
  $r = mysql_query("SELECT * FROM banners_newsletter WHERE id = ".$_GET['id']);
  $banner = mysql_fetch_array($r);
  $accion = "update&id=".$banner['id'];
}else{
  $accion = "insert";
}
?>
<script>
  function cambiar_recomendacion(valor){
    if(valor=="Megabanner"){
      document.getElementById("capa_megabanner").style.display="inline";
      document.getElementById("capa_recomendado").style.display="none";
    }
    else{
      document.getElementById("capa_megabanner").style.display="none";
      document.getElementById("capa_recomendado").style.display="inline";
    }
  }
  
  function cambiar_fuente(valor){
    if(valor=="codigo"){
      document.getElementById("fuente_codigo").disabled=false;
      document.getElementById("fuente_imagen").disabled=true;
      document.getElementById("fuente_codigo").style.display="inline";
      document.getElementById("fuente_imagen").style.display="none";
    }
    else{
      document.getElementById("fuente_codigo").disabled=true;
      document.getElementById("fuente_imagen").disabled=false;
      document.getElementById("fuente_codigo").style.display="none";
      document.getElementById("fuente_imagen").style.display="inline";
    }
  }
</script>
<tr>
  <td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="botonera" align="right">
            <table border="0" cellpadding="0" cellspacing="0" >
            <tr>
                <td class="botones_botonera"><a href="javascript:document.form_banners_newsletter.submit();" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_banners_newsletter" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Editar Banner</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_banners_newsletter" method="post" action="?modulo=mod_banners_newsletter&fns=1&accion=<?=$accion?>" enctype="multipart/form-data">
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
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Nombre:</td>
                      <td><input type="text" name="titulo" value="<?=(isset($banner)) ? $banner["titulo"] : ""?>" style="width:250px" ></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr style="display: none;">
                      <td class="etiqueta_200px">Tipo:</td>
                      <td>
                        <select name="tipo" style="width:254px" onchange="cambiar_recomendacion(this.value)" >
                          <!--<option value="Megabanner" <?//echo (isset($banner) && $banner["tipo"]=="Megabanner") ? "SELECTED" : ""?>>Megabanner</option>-->
                          <option value="Recomendado" <?=(isset($banner) && $banner["tipo"]=="Recomendado") ? "SELECTED" : ""?>>Recomendado</option>
                        </select>
                    </tr>
         
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Link: (incluir http://)</td>
                      <td>
                        <input type="text" name="link" value="<?=(isset($banner)) ? $banner["link"] : ""?>" style="width:250px" >                      
                        </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Fuente: </td>
                      <td>
                        <select name="fuente" style="width:254px" onchange="cambiar_fuente(this.value)" >
                          <option value="imagen" <?=(isset($banner) && $banner["fuente"]=="imagen") ? "SELECTED" : ""?>>Imagen</option>
                          <option value="codigo" <?=(isset($banner) && $banner["fuente"]=="codigo") ? "SELECTED" : ""?>>C�digo</option>
                        </select>               
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Banner:
<!--                        <div id="capa_megabanner" style="display:<?=(!(isset($banner) && $banner["tipo"]=="Recomendado")) ? "inline" : "none"?>">(Tama�o recomendado 460x90)</div>-->
                        <div id="capa_megabanner" style="display:<?=(!(isset($banner) && $banner["tipo"]=="Recomendado")) ? "inline" : "none"?>">(Ancho recomendado 300)</div>
<!--                        <div id="capa_recomendado" style="display:<?=(isset($banner) && $banner["tipo"]=="Recomendado") ? "inline" : "none"?>">(Tama�o recomendado 60x55)</div>-->
                      </td>
                      <td>            
                      <?
                        if (isset($banner["imagen"]) && $banner["imagen"]!=""){
                          if(isset($banner['fuente']) && $banner['fuente'] == "codigo"){
                          ?>
                          <?=$banner['imagen']?>&nbsp;&nbsp;<a href="index2.php?modulo=mod_banners_newsletter&accion=borrar_foto&fns=1&&id=<?=$banner["id"]?>">
                            <img border="0" src="images/eliminar.png" >
                          </a>
                          <?}else{?>
                          <img src="../userfiles/banners/<?=$banner['imagen']?>" alt="boo">&nbsp;&nbsp;<a href="index2.php?modulo=mod_banners_newsletter&accion=borrar_foto&fns=1&&id=<?=$banner["id"]?>">
                            <img border="0" src="images/eliminar.png" >
                          </a>
                          <?}?>
                          
                          <?
                          if(isset($banner['fuente']) && $banner['fuente'] == "codigo"){
                            $atributos_textarea = "style='display:block'";
                            $atributos_file = "style='display:none' disabled";
                          }else{
                            $atributos_textarea = "style='display:none' disabled";
                            $atributos_file = "style='display:block'";
                          }
                          ?>
                          <textarea name="imagen" id="fuente_codigo" <?=$atributos_textarea?> cols="35"><?=(isset($banner['imagen'])) ? $banner['imagen'] : "";?></textarea>
                          <input type="file" name="imagen" id="fuente_imagen" <?=$atributos_file?>>
                          
                        <? } else {?>
                          <?
                          if(isset($banner['fuente']) && $banner['fuente'] == "codigo"){
                            $atributos_textarea = "style='display:block'";
                            $atributos_file = "style='display:none' disabled";
                          }else{
                            $atributos_textarea = "style='display:none' disabled";
                            $atributos_file = "style='display:block'";
                          }
                          ?>
                          <textarea name="imagen" id="fuente_codigo" <?=$atributos_textarea?> cols="35"><?=(isset($banner['imagen'])) ? $banner['imagen'] : "";?></textarea>
                          <input type="file" name="imagen" id="fuente_imagen" <?=$atributos_file?>>
                        <? } ?>

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
