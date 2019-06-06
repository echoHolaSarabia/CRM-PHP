<?
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM videos_secciones WHERE id=".$_GET['id']);
	$seccion = mysql_fetch_array($r);
	$accion = "update_seccion&id=".$seccion['id'];
} else {// INSERTAR
	$accion = "insert_seccion";
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
                <td class="botones_botonera"><a href="index2.php?modulo=mod_galerias_video&ver=secciones" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nueva ";else echo "Editar ";?>Secci&oacute;n</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_secciones" method="post" action="?modulo=mod_galerias_video&fns=1&accion=<?=$accion?>">
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
                          	  <option value="1" <? if (isset($seccion) && $seccion['activo'] == 1) echo "selected";?>>Activo</option>
                          	  <option value="0" <? if (isset($seccion) && $seccion['activo'] == 0) echo "selected";?>>Inactivo</option>
                          </select>
                      </td>
                    </tr>
                   
                    
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Nombre de la secci&oacute;n:</td>
                      <td><input type="text" name="nombre" value="<?=(isset($seccion)) ? $seccion['nombre'] : "";?>" style="width:150px;"/></td>
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
