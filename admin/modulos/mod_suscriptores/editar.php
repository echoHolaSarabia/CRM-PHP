<?
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM suscriptores WHERE id=".$_GET['id']);
	$suscriptor = mysql_fetch_array($r);
	$accion = "update&id=".$suscriptor['id'];
} else {// INSERTAR
	$accion = "insert";
}

function get_nombre_mes ($num){
		if ($num == 1) return "Enero";
		if ($num == 2) return "Febrero";
		if ($num == 3) return "Marzo";
		if ($num == 4) return "Abril";
		if ($num == 5) return "Mayo";
		if ($num == 6) return "Junio";
		if ($num == 7) return "Julio";
		if ($num == 8) return "Agosto";
		if ($num == 9) return "Septiembre";
		if ($num == 10) return "Octubre";
		if ($num == 11) return "Noviembre";
		if ($num == 12) return "Diciembre";
	}
?>
<script>
function recarga_dias(mes,dia){
	if (dia >= 30){
		var form = document.form_registro;
		form.dia.length = 0;
		
		var limite = 31;
		if (mes == "4" || mes == "6" || mes == "9" || mes == "11"){
			limite = 30;
		}
		if (mes == "2"){
			limite = 29;
		}
		
		var optionName = new Option("Día:", "", false, false)
		var length = form.dia.length;
		form.dia.options[length] = optionName;
		
		for (i = 1; i <= limite; i++){
			var optionName = new Option(i, i, false, false)
			var length = form.dia.length;
			form.dia.options[length] = optionName;
		}
	}
</script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://github.com/malsup/form/raw/master/jquery.form.js?v2.44"></script>

<script type="text/javascript">
$(document).ready(function() { 
    // bind 'myForm' and provide a simple callback function 
    $('#myForm').ajaxForm(function() {
      $.ajax({
        url: $('#myForm').attr("action"),
        data: 'suscriptores='+$("#suscriptores").val(),
        type: 'POST',
        success: function(data){
          $("#info").html(data);
//          alert(data);
          setTimeout(window.location="/admin/index2.php?modulo=mod_suscriptores",5000);
        }
      });
    }); 
}); 
</script>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
                <td class="botones_botonera"><a href="javascript:document.form_suscriptores.submit();" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_suscriptores" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nueva ";else echo "Editar ";?>Suscriptor</span></td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="titulos" colspan="2">Masivos</td>
              </tr>
              <tr>
                <td class="separador" colspan="2"></td>
              </tr>
              <tr>
                <td class="contenido">
                  <form id="myForm" action="modulos/mod_suscriptores/masivos.php" method="post">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td class="separador" colspan="2"></td>
                      </tr>
                      <tr>
                        <td><textarea id="suscriptores" rows="" cols="50" name="suscriptores"></textarea></td>
                        <td class="separador" colspan="2"></td>
                        <td><div id="info"></div></td>
                      </tr>
                      <tr>
                        <td><input type="submit" value="Registrar" /></td>
                      </tr>
                    </table>
                  </form>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
          <form name="form_suscriptores" method="post" action="?modulo=mod_suscriptores&fns=1&accion=<?=$accion?>">
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
                      <td><input type="text" name="nombre" value="<?=(isset($suscriptor)) ? $suscriptor['nombre'] : "";?>" size="100"></td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td class="etiqueta_200px">Apellidos:</td>
                      <td><input type="text" name="apellidos" value="<?=(isset($suscriptor)) ? $suscriptor['apellidos'] : "";?>" size="100"></td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td class="etiqueta_200px">e-mail:</td>
                      <td><input type="text" name="email" oncopy="return false" onpaste="return false" oncut="return false"s value="<?=(isset($suscriptor)) ? $suscriptor['email'] : "";?>" size="100"></td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td class="etiqueta_200px">Tel&eacute;fono:</td>
                      <td><input type="text" name="telefono" value="<?=(isset($suscriptor)) ? $suscriptor['telefono'] : "";?>" size="100"></td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td class="etiqueta_200px">Empresa</td>
                      <td><input type="text" name="empresa" value="<?=(isset($suscriptor)) ? $suscriptor['empresa'] : "";?>" size="100"></td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td class="etiqueta_200px">Ciudad</td>
                      <td><input type="text" name="ciudad" value="<?=(isset($suscriptor)) ? $suscriptor['ciudad'] : "";?>" size="100"></td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td class="etiqueta_200px">Tipo de Empresa</td>
                      <td>
                      <select name="tipo" id="tipo" style="width:530px">
							<option value="0" <?=(isset($suscriptor) && $suscriptor["tipo"]==0) ? " SELECTED" : ""?>></option>
							<option value="1" <?=(isset($suscriptor) && $suscriptor["tipo"]==1) ? " SELECTED" : ""?>>Sector de Hosteler&iacute;a</option>
							<option value="2" <?=(isset($suscriptor) && $suscriptor["tipo"]==2) ? " SELECTED" : ""?>>Medios de comunicación</option>
							<option value="3" <?=(isset($suscriptor) && $suscriptor["tipo"]==3) ? " SELECTED" : ""?>>Organización/Administración
							<option value="4" <?=(isset($suscriptor) && $suscriptor["tipo"]==4) ? " SELECTED" : ""?>>Otros</option>
						</select>
                      </td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td class="etiqueta_200px">Especifique tipo</td>
                      <td><input type="text" name="otros_tipo" value="<?=(isset($suscriptor)) ? $suscriptor['otros_tipo'] : "";?>" size="100"></td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td class="etiqueta_200px">Caracter&iacute;sticas de la empresa</td>
                      <td>
                      <select name="caracteristica" id="caracteristica"  style="width:530px">
							<option value="0" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==0) ? " SELECTED" : ""?>></option>
							<option value="1" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==1) ? " SELECTED" : ""?>>restaurante</option>
							<option value="2" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==2) ? " SELECTED" : ""?>>catering</option>
							<option value="3" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==3) ? " SELECTED" : ""?>>cafetería</option>
							<option value="4" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==4) ? " SELECTED" : ""?>>bar</option>
							<option value="5" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==5) ? " SELECTED" : ""?>>ocio nocturno</option>
							<option value="6" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==6) ? " SELECTED" : ""?>>hotel</option>
							<option value="7" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==7) ? " SELECTED" : ""?>>hostal</option>
							<option value="8" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==8) ? " SELECTED" : ""?>>pensión</option>
							<option value="9" <?=(isset($suscriptor) && $suscriptor["caracteristica"]==9) ? " SELECTED" : ""?>>Otros</option>
						</select>
                      </td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td class="etiqueta_200px">Especifice Caracter&iacute;sticas</td>
                      <td><input type="text" name="otros_caracteristica" value="<?=(isset($suscriptor)) ? $suscriptor['otros_caracteristica'] : "";?>" size="100"></td>
                    </tr>
                    <tr class="separador"></tr>
                    <tr>
                      <td colspan="2">
                      	<table width="50%">
                      		<td>
                      	Newsletter <input value="1" type="checkbox" id="n_1" name="n_1" <?=((isset($suscriptor) && $suscriptor["n_1"]==1) || (!isset($suscriptor))) ? "CHECKED" : "" ?>>
                      		</td><?/*<td>
                      	Aquí España <input value="1" type="checkbox" id="n_2" name="n_2" <?=(isset($suscriptor) && $suscriptor["n_2"]==1) ? "CHECKED" : "" ?>>
                      		</td><td>
                      	Club FEHR <input value="1" type="checkbox" id="n_3" name="n_3" <?=(isset($suscriptor) && $suscriptor["n_3"]==1) ? "CHECKED" : "" ?>>
                      		</td><td>
                      	Confidencial <input value="1" type="checkbox" id="n_4" name="n_4" <?=(isset($suscriptor) && $suscriptor["n_4"]==1) ? "CHECKED" : "" ?>>
                      		</td>*/?>
                      	</table>
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
