<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td>Pais:</td>
    <td>
    <select name="pais" id="pais" class="txt" onchange="recarga_provincias(this.value)">
    	<option value="">Todos</option>
		<option value="">-------</option>
		<option value='1'>Espa&ntilde;a</option>
		<option value="">-------</option>
		<?
		$paises = $funciones -> get_paises();
		foreach ($paises as $unPais){
			$selected = ($unPais['idpais'] == $noticia['pais']) ? "selected" : "";
			echo "<option value='".$unPais['idpais']."' ".$selected.">".htmlentities($unPais['pais'])."</option>";
		}
		?>
    </select>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Provincia:</td>
    <td>
    <select name="provincia" id="provincia" class="txt">
    	<option value="">Todas</option>
		<?
		if (isset($noticia['pais']) && $noticia['pais'] == 1){
			$provs = $funciones->get_provincias($noticia['pais']);
			foreach ($provs as $unaProvincia){
				$selected = ($unaProvincia['idprovincia'] == $noticia['provincia']) ? "selected" : "";
				echo "<option value='".$unaProvincia['idprovincia']."' ".$selected.">".$unaProvincia['provincia']."</option>";
			}
		}
		?>
    </select>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Lugar de celebracion:</td>
    <td><input type="text" name="lugar_celebracion" value="<?=(isset($noticia)) ? $noticia['lugar_celebracion']: "";?>"></td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Fecha inicio:</td>
    <td><input type="text" name="fecha_inicio" id="fecha_inicio" value="<?=(isset($noticia)) ? $noticia['fecha_inicio']: "";?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador5" value="..." /></td>
  </tr>
  <script type="text/javascript">
    Calendar.setup({
        inputField     :    "fecha_inicio",     // id del campo de texto
         ifFormat       :    "%Y-%m-%d",
         showsTime      :    true,
         button     :    "lanzador5"     // el id del botón que lanzará el calendario
    });
  </script>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Fecha fin:</td>
    <td><input type="text" name="fecha_fin" id="fecha_fin" value="<?=(isset($noticia)) ? $noticia['fecha_fin']: "";?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador4" value="..." /></td>
  </tr>
  <script type="text/javascript">
    Calendar.setup({
        inputField     :    "fecha_fin",     // id del campo de texto
         ifFormat       :    "%Y-%m-%d",
         showsTime      :    true,
         button     :    "lanzador4"     // el id del botón que lanzará el calendario
    });
  </script>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Teléfono:</td>
    <td><input type="text" name="telefono" value="<?=(isset($noticia)) ? $noticia['telefono']: "";?>"></td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Fax:</td>
    <td><input type="text" name="fax" value="<?=(isset($noticia)) ? $noticia['fax']: "";?>"></td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr><br>
<tr>
    <td>Web:</td>
    <td><input type="text" name="web" value="<?=(isset($noticia)) ? $noticia['web']: "";?>"></td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Contacto:</td>
    <td><input type="text" name="contacto" value="<?=(isset($noticia)) ? $noticia['contacto']: "";?>"></td>
  </tr>
</table>