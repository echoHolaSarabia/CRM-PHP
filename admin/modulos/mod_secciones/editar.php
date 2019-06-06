<?
include("modulos/mod_secciones/conf.php");
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM secciones WHERE id=".$_GET['id']);
	$seccion = mysql_fetch_array($r);
	$accion = "update&id=".$seccion['id'];
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
                <td class="botones_botonera"><a href="index2.php?modulo=<?=MODULO?>" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nueva ";else echo "Editar ";?>Secci&oacute;n</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_secciones" method="post" action="?modulo=<?=MODULO?>&fns=1&accion=<?=$accion?>">
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
                      <td class="etiqueta_200px">Secci&oacute;n padre:</td>
                      <td>
                          <select name="id_padre" style="width:150px">
                              <option value="-1" <? if (isset($seccion) && $seccion['id_padre'] == 0) echo "selected";?>>Es categor&iacute;a principal</option>
                              <?
							  $r = mysql_query("SELECT * FROM secciones WHERE id_padre = -1");
							  while ($padre = mysql_fetch_array($r)){
							  ?>
                              <option value="<?=$padre['id']?>" <? if (isset($seccion) && $seccion['id_padre'] == $padre['id']) echo "selected";?>><?=$padre['titulo']?></option>
                              <? } ?>
                          </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Nombre de la secci&oacute;n:</td>
                      <td><input type="text" name="titulo" value="<?=(isset($seccion)) ? $seccion['titulo'] : "";?>" style="width:150px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Tabla:</td>
                      <td>
                          <select name="tabla" style="width:150px">
                              <?foreach ($tablas as $unaTabla){?>
                              <option value="<?=$unaTabla?>" <? if (isset($seccion) && $seccion['tabla'] == $unaTabla) echo "selected";?>><?=$unaTabla?></option>
                              <?}?>
                          </select>
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
