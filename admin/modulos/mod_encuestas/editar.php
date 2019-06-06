<?
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM encuestas WHERE id=".$_GET['id']);
	$encuesta = mysql_fetch_array($r);
	$accion = "update&id=".$encuesta['id'];
} else {// INSERTAR
	$accion = "insert";
}
?>
<script type="text/javascript">
function set_respuestas(){
	num_total_campos = 6;
	num_respuestas = document.form_encuestas.num_respuestas.value;
	for (i = 1; i <=num_total_campos; i++){
		document.getElementById('capa'+i).style.display = "none";
	}
	for (i = 1; i <=num_respuestas; i++){
		document.getElementById('capa'+i).style.display = "";
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
                <td class="botones_botonera"><a href="javascript:document.form_encuestas.submit();" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_encuestas" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nueva ";else echo "Editar ";?>Encuesta</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_encuestas" method="post" action="?modulo=mod_encuestas&fns=1&accion=<?=$accion?>">
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
                      <td class="etiqueta_200px">Publicada en portada:</td>
                      <td>
                          <select name="activa" style="width:80px">
                          	  <option value="0" <? if (isset($encuesta) && $encuesta['activa'] == 0) echo "selected";?>>No</option>
                          	  <option value="1" <? if (isset($encuesta) && $encuesta['activa'] == 1) echo "selected";?>>Si</option>
                          </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Mostrar:</td>
                      <td>
                          <select name="mostrar" style="width:80px">
                          	  <option value="0" <? if (isset($encuesta) && $encuesta['mostrar'] == 0) echo "selected";?>>No</option>
                          	  <option value="1" <? if (isset($encuesta) && $encuesta['mostrar'] == 1) echo "selected";?>>Si</option>
                          </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Número de respuestas:</td>
                      <td>
                          <select name="num_respuestas" style="width:40px" onchange="set_respuestas()">
                          	  <option value="1" <? if (isset($encuesta) && $encuesta['num_respuestas'] == 1) echo "selected";?>>1</option>
                          	  <option value="2" <? if (isset($encuesta) && $encuesta['num_respuestas'] == 2) echo "selected";?>>2</option>
                          	  <option value="3" <? if (isset($encuesta) && $encuesta['num_respuestas'] == 3) echo "selected";?>>3</option>
                          	  <option value="4" <? if (isset($encuesta) && $encuesta['num_respuestas'] == 4) echo "selected";?>>4</option>
                          	  <option value="5" <? if (isset($encuesta) && $encuesta['num_respuestas'] == 5) echo "selected";?>>5</option>
                          	  <option value="6" <? if (isset($encuesta) && $encuesta['num_respuestas'] == 6) echo "selected";?>>6</option>
                          </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Pregunta:</td>
                      <td><input type="text" name="pregunta" value="<?=(isset($encuesta)) ? $encuesta['pregunta'] : "";?>" style="width:400px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr id="capa1" style="display:table-row">
                      <td class="etiqueta_200px">Respuesta 1:</td>
                      <td><input type="text" name="r1" value="<?=(isset($encuesta)) ? $encuesta['r1'] : "";?>" style="width:200px;"/>&nbsp;Hits:&nbsp;<input type="text" name="num_r1" value="<?=(isset($encuesta)) ? $encuesta['num_r1'] : "";?>" ></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr id="capa2" style="display:<? if ((isset($encuesta)) && $encuesta['num_respuestas'] >= 2) echo "table-row";else echo "none";?>">
                      <td class="etiqueta_200px">Respuesta 2:</td>
                      <td><input type="text" name="r2" value="<?=(isset($encuesta)) ? $encuesta['r2'] : "";?>" style="width:200px;"/>&nbsp;Hits:&nbsp;<input type="text" name="num_r2" value="<?=(isset($encuesta)) ? $encuesta['num_r2'] : "";?>" ></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr id="capa3" style="display:<? if ((isset($encuesta)) && $encuesta['num_respuestas'] >= 3) echo "table-row";else echo "none";?>">
                      <td class="etiqueta_200px">Respuesta 3:</td>
                      <td><input type="text" name="r3" value="<?=(isset($encuesta)) ? $encuesta['r3'] : "";?>" style="width:200px;"/>&nbsp;Hits:&nbsp;<input type="text" name="num_r3" value="<?=(isset($encuesta)) ? $encuesta['num_r3'] : "";?>" ></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr id="capa4" style="display:<? if ((isset($encuesta)) && $encuesta['num_respuestas'] >= 4) echo "table-row";else echo "none";?>">
                      <td class="etiqueta_200px">Respuesta 4:</td>
                      <td><input type="text" name="r4" value="<?=(isset($encuesta)) ? $encuesta['r4'] : "";?>" style="width:200px;"/>&nbsp;Hits:&nbsp;<input type="text" name="num_r4" value="<?=(isset($encuesta)) ? $encuesta['num_r4'] : "";?>" ></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr id="capa5" style="display:<? if ((isset($encuesta)) && $encuesta['num_respuestas'] >= 5) echo "table-row";else echo "none";?>">
                      <td class="etiqueta_200px">Respuesta 5:</td>
                      <td><input type="text" name="r5" value="<?=(isset($encuesta)) ? $encuesta['r5'] : "";?>" style="width:200px;"/>&nbsp;Hits:&nbsp;<input type="text" name="num_r5" value="<?=(isset($encuesta)) ? $encuesta['num_r5'] : "";?>" ></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr id="capa6" style="display:<? if ((isset($encuesta)) && $encuesta['num_respuestas'] >= 6) echo "table-row";else echo "none";?>">
                      <td class="etiqueta_200px">Respuesta 6:</td>
                      <td><input type="text" name="r6" value="<?=(isset($encuesta)) ? $encuesta['r6'] : "";?>" style="width:200px;"/>&nbsp;Hits:&nbsp;<input type="text" name="num_r6" value="<?=(isset($encuesta)) ? $encuesta['num_r6'] : "";?>" ></td>
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
