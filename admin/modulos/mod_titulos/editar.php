<?
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM titulos WHERE id_seccion=".$_GET['id']);
	$fila = mysql_fetch_array($r);
	$accion = "update&id=".$fila['id_seccion'];
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
                <td class="botones_botonera"><a href="index2.php?modulo=mod_titulos" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nueva ";else echo "Editar ";?>Secci&oacute;n</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_secciones" method="post" action="?modulo=mod_titulos&fns=1&accion=<?=$accion?>">
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
                      <td class="etiqueta_200px">Sección:</td>
                      <td>
                      	<?
                      		if (isset($fila)){
                      			$seccion = mysql_fetch_assoc(mysql_query("SELECT nombre FROM secciones WHERE id=".$fila['id_seccion']));
                      			echo $seccion['nombre'];
                      		} 
                      	?>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Título:</td>
                      <td><input type="text" name="titulo" value="<?=(isset($fila)) ? $fila['titulo'] : "";?>" style="width:500px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Descripción:</td>
                      <td><textarea name="descripcion" style="width:500px;"><?=(isset($fila)) ? $fila['descripcion'] : "";?></textarea></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Tags:</td>
                      <td><textarea name="tags" style="width:500px;"><?=(isset($fila)) ? $fila['tags'] : "";?></textarea></td>
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
