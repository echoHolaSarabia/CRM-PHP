<?
include("modulos/mod_rosas/conf.php");
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM modulosrosas WHERE id=".$_GET['id']);
	$rosa = mysql_fetch_array($r);
	$accion = "update&id=".$rosa['id'];
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
                <td class="botones_botonera"><a href="javascript:document.form_rosas.submit();" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=<?=MODULO?>" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nuevo ";else echo "Editar ";?> módulo</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_rosas" method="post" action="?modulo=<?=MODULO?>&fns=1&accion=<?=$accion?>">
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
                      <td class="etiqueta_200px">Nombre del modulo:</td>
                      <td><input type="text" name="titulo" value="<?=(isset($rosa)) ? $rosa['titulo'] : "";?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Tipo de módulo:</td>
                      <td>
                      	<select name="tipo">
                      		<option value="banner" <?=(isset($rosa) && $rosa['tipo'] == "banner") ? "selected" : "";?>>Banner</option>
                      		<option value="otros" <?=(isset($rosa) && $rosa['tipo'] == "otros") ? "selected" : "";?>>Otros</option>
                      	</select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Fuente:</td>
                      <td>
					<script>
					function selecciona_fuente(tipo){
						if (tipo == "ruta"){
							document.getElementById('codigo').disabled = true;
							document.getElementById('ruta_archivo').disabled = false;
						}else if (tipo == "codigo"){
							document.getElementById('codigo').disabled = false;
							document.getElementById('ruta_archivo').disabled = true;
						}
					}
					</script>
                      	<select name="fuente" onchange="selecciona_fuente(this.value)">
                      		<option value="ruta" <?=(isset($rosa) && $rosa['fuente'] == "ruta") ? "selected" : "";?>>Archivo</option>
                      		<option value="codigo" <?=(isset($rosa) && $rosa['fuente'] == "codigo") ? "selected" : "";?>>Código</option>
                      	</select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Ruta del archivo:</td>
                      <td><input type="text" name="ruta_archivo" id="ruta_archivo" <?=(isset($rosa) && $rosa['fuente'] == "ruta") ? "" : "disabled";?> value="<?=(isset($rosa)) ? $rosa['ruta_archivo'] : "";?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Código:</td>
                      <td><textarea name="codigo" id="codigo" style="width:300px;" <?=(isset($rosa) && $rosa['fuente'] == "codigo") ? "" : "disabled";?>><?=(isset($rosa)) ? $rosa['codigo'] : "";?></textarea></td>
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
