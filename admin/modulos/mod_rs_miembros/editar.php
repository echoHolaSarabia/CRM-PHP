<?
$modulo = "mod_rs_miembros";
$funciones = new Funciones;
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM rs_miembros WHERE id=".$_GET['id']);
	$miembro = mysql_fetch_array($r);
	$accion = "update&id=".$miembro['id'];
} else {// INSERTAR
	$r = mysql_query("SELECT * FROM administradores WHERE id=".$_GET['id_usuario']);
	$miembro = mysql_fetch_array($r);
	$accion = "insert";
}
?>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script>
function recarga_provincias (id_pais){
	$(document).ready(function(){
		$.ajax({
	        type: "POST",
	        url: "modulos/mod_rs_miembros/ajax_get_provincias.php",
	        data: "id_pais="+id_pais,
	        success: function(datos){
	       		$('#provincia').html(datos);
	       		//alert(datos);
	      	}
		});
	});
}

function valida_miembro(form){
	/*if (form.password.value == "" || (form.password.value != form.password2.value)){
		alert("Las contraseñas deben ser iguales");
		return;
	}*/
	form.submit();
}
</script>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td align="center"><? include ("includes/menu_rs.inc.php");?></td>
        </tr>
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
                <td class="botones_botonera"><a href="javascript:valida_miembro(document.form_rs_miembro)" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=<?=$modulo?>" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nueva ";else echo "Editar ";?>Miembro</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_rs_miembro" method="post" action="?modulo=<?=$modulo?>&fns=1&accion=<?=$accion?>" enctype="multipart/form-data">
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
                      <td><input type="text" name="nombre" value="<?=(isset($miembro['nombre'])) ? $miembro['nombre'] : "";?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Apellidos:</td>
                      <td><input type="text" name="apellidos" value="<?=(isset($miembro['apellidos'])) ? $miembro['apellidos'] : "";?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">e-mail:</td>
                      <td><input type="text" name="email" value="<?=(isset($miembro['email'])) ? $miembro['email'] : "";?>" style="width:300px;"/></td>
                    </tr>
                    <?
                    if ($accion == "insert"){
                    ?>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Password:</td>
                      <td><input type="password" name="password" value="" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Repite password:</td>
                      <td><input type="password" name="password2" value="" style="width:300px;"/></td>
                    </tr>
                    <?
                    }
                    ?>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Tel&eacute;fono:</td>
                      <td><input type="text" name="telefono" value="<?=(isset($miembro['telefono'])) ? $miembro['telefono'] : "";?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Fecha de nacimiento:</td>
                      <td><!--<input type="text" name="fecha_nacimiento" value="<?=(isset($miembro['fecha_nacimiento'])) ? $miembro['fecha_nacimiento'] : "";?>" style="width:300px;"/>-->
                      	<?
                      	if (!isset($miembro['fecha_nacimiento']))
                      		$miembro['fecha_nacimiento'] = "0000-00-00";
												$fecha = explode("-",$miembro['fecha_nacimiento']);
												?>
								        <select name="dia" id="dia" class="form_campoSelect" style="width:50px;">
									        	<option value="">Día:</option>
								          <? for ($i = 1; $i <= 31; $i++){?>
								        		<option value="<?=$i?>" <?=($i == $fecha[2]) ? "selected" : "";?>><?=$i?></option>
								        	<? } ?>
								        </select>
								        <select name="mes" id="mes" onchange="recarga_dias(this.value,document.form_rs_miembro.dia.value)" class="form_campoSelect" style="width:80px;">
								        		<option value="">Mes:</option>
								          <? for ($i = 1; $i <= 12; $i++){?>
								        		<option value="<?=$i?>" <?=($i == $fecha[1]) ? "selected" : "";?>><?=$funciones -> get_nombre_mes($i)?></option>
								        	<? } ?>
								        </select>
								        <select name="anyo" id="anyo" class="form_campoSelect" style="width:60px;">
								        		<option value="">Año:</option>
								          <? for ($i = date("Y"); $i >= 1900; $i--){?>
								        		<option value="<?=$i?>" <?=($i == $fecha[0]) ? "selected" : "";?>><?=$i?></option>
								        	<? } ?>
								        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Sexo:</td>
                      <td>
                      		<select name="sexo">
                      			<option value="1" <? if (isset($miembro['sexo']) && $miembro['sexo'] == 1) echo "selected";?>>Hombre</option>
                      			<option value="0" <? if (isset($miembro['sexo']) && $miembro['sexo'] == 0) echo "selected";?>>Mujer</option>
                      		</select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Foto:</td>
                      <td>
                      	La foto debe subirla desde su perfil
                      	<? /*if (isset($miembro['url_foto']))
                      			$funciones -> muestra_foto("/rs_fotos_miembros/thumbnails/65_".$miembro['url_foto'],"url_foto");
                      		else{*/
                      	?>
                      	<!--<input type="file" name="url_foto">-->
                      	<?//}?>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Como nos conociste:</td>
                      <td>
                      		<select name="como">
                      			<option value="1" <?if (isset($miembro['como']) && $miembro["como"] == 1) echo "selected";?>>Buscadores</option>
														<option value="2" <?if (isset($miembro['como']) && $miembro["como"] == 2) echo "selected";?>>Otras p&aacute;ginas web</option>
														<option value="3" <?if (isset($miembro['como']) && $miembro["como"] == 3) echo "selected";?>>Radio</option>
														<option value="4" <?if (isset($miembro['como']) && $miembro["como"] == 4) echo "selected";?>>Televisi&oacute;n</option>
														<option value="5" <?if (isset($miembro['como']) && $miembro["como"] == 5) echo "selected";?>>Un amigo</option>
														<option value="6" <?if (isset($miembro['como']) && $miembro["como"] == 6) echo "selected";?>>Otros</option>
                      		</select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Pais:</td>
                      <td>
                      		<select name="pais" onchange="recarga_provincias(this.value)">
                      		<?
                      		$r = mysql_query("SELECT * FROM paises ORDER BY pais");
                      		while ($pais = mysql_fetch_assoc($r)){
                      		?>
                      			<option value="<?=$pais['idpais']?>" <? if (isset($miembro['pais']) && $miembro['pais'] == $pais['idpais']) echo "selected";?>><?=$pais['pais']?></option>
                      		<? } ?>
                      		</select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Provincia:</td>
                      <td>
                      		<select name="provincia" id="provincia">
                      			<?
                      		if (isset($miembro['pais']) && $miembro['pais'] == 1){
	                      		$r = mysql_query("SELECT * FROM provincias ORDER BY provincia");
	                      		while ($provincia = mysql_fetch_assoc($r)){
	                      		?>
                      			<option value="<?=$provincia['idprovincia']?>" <? if (isset($miembro['provincia']) && $miembro['provincia'] == $provincia['idprovincia']) echo "selected";?>><?=$provincia['provincia']?></option>
                      			<? 
	                      		}
                      		}else{
                      			echo '<option value="0">Fuera de Espa&ntilde;a</option>';
                      		} ?>
                      		</select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Es madridista:</td>
                      <td>
                      		<select name="madridista">
                      			<option value="1" <? if (isset($miembro['madridista']) && $miembro['madridista'] == 1) echo "selected";?>>Si</option>
                      			<option value="0" <? if (isset($miembro['madridista']) && $miembro['madridista'] == 0) echo "selected";?>>No</option>
                      		</select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Tipo de seguidor:</td>
                      <td>
                      		<select name="tipo_seguidor">
                      			<option value="socio" <? if (isset($miembro['tipo_seguidor']) && $miembro['tipo_seguidor'] == "socio") echo "selected";?>>Socio</option>
                      			<option value="aficionado" <? if (isset($miembro['tipo_seguidor']) && $miembro['tipo_seguidor'] == "aficionado") echo "selected";?>>Aficionado</option>
                      			<option value="seguidor_habitual" <? if (isset($miembro['tipo_seguidor']) && $miembro['tipo_seguidor'] == "seguidor_habitual") echo "selected";?>>Seguidor habitual</option>
                      			<option value="simpatizante" <? if (isset($miembro['tipo_seguidor']) && $miembro['tipo_seguidor'] == "simpatizante") echo "selected";?>>Simpatizante</option>
                      			<option value="soy_de_otro_equipo" <? if (isset($miembro['tipo_seguidor']) && $miembro['tipo_seguidor'] == "soy_de_otro_equipo") echo "selected";?>>Soy de otro equipo</option>
                      		</select>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Equipo:</td>
                      <td><input type="text" name="equipo" value="<?=(isset($miembro['equipo'])) ? $miembro['equipo'] : "";?>" style="width:300px;"/></td>
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
