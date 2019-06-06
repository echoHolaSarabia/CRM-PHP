<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  	<td><span class="titulo_seccion">COMENTARIOS</span></td>
    <td align="right">
    	<form action="index2.php" method="GET">
    		<input type="hidden" name="modulo" value="<?=$modulo?>">
    	<table width="400" border="0" cellpadding="0" cellspacing="0">
    		<tr>
    			<td>Palabra:&nbsp;<input type="text" name="palabra" id="palabra" size="50" value="<? if (isset($_GET['palabra'])) echo $_GET['palabra'];?>"></td>
    		</tr>
    		<tr>
    			<td>
    				Ordenar por:
		    		<select name="orden">
		    			<option value="fecha_publicacion" <? if (isset($_GET['orden']) && ($_GET['orden'] == "fecha_publicacion")) echo "selected";?>>Fecha publicaci&oacute;n</option>
		    		</select>
		    		Tipo de orden:
		    		<select name="tipo_orden">
		    			<option value="DESC" <? if (isset($_GET['tipo_orden']) && ($_GET['tipo_orden'] == "DESC")) echo "selected";?>>Descendente</option>
		    			<option value="ASC"<? if (isset($_GET['tipo_orden']) && ($_GET['tipo_orden'] == "ASC")) echo "selected";?>>Ascendente</option>
		    		</select>
    			</td>
    		</tr>
    		<tr>
    			<td>
    				entre el:&nbsp;<input type="text" name="desde" id="desde" value="<? if (isset($_GET['desde'])) echo $_GET['desde'];?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador1" value="..." />
			          	  	<script type="text/javascript">
									Calendar.setup({
										inputField     :    "desde",     // id del campo de texto
										 ifFormat     :     "%Y-%m-%d",     // formato de la fecha que se escriba en el campo de texto
										 button     :    "lanzador1"     // el id del botón que lanzará el calendario
									});
							</script>
			      y el:&nbsp;<input type="text" name="hasta" id="hasta" value="<? if (isset($_GET['hasta'])) echo $_GET['hasta'];?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador2" value="..." />
			          	  	<script type="text/javascript">
									Calendar.setup({
										inputField     :    "hasta",     // id del campo de texto
										 ifFormat     :     "%Y-%m-%d",     // formato de la fecha que se escriba en el campo de texto
										 button     :    "lanzador2"     // el id del botón que lanzará el calendario
									});
							</script>
    			</td>
    		</tr>
    		<tr>
    			<td>
    			Tipo:&nbsp;<select name="tipo">
    				<option value="">Todos</option>
    				<option value="debate" <? if (isset($_GET['tipo']) && ($_GET['tipo'] == "debate")) echo "selected";?>>Debates</option>
    				<option value="pizarra" <? if (isset($_GET['tipo']) && ($_GET['tipo'] == "pizarra")) echo "selected";?>>Pizarra</option>
    				<option value="blog" <? if (isset($_GET['tipo']) && ($_GET['tipo'] == "blog")) echo "selected";?>>Blog</option>
    				<option value="album" <? if (isset($_GET['tipo']) && ($_GET['tipo'] == "album")) echo "selected";?>>Álbumes de fotos</option>
    				<option value="videos" <? if (isset($_GET['tipo']) && ($_GET['tipo'] == "videos")) echo "selected";?>>Videos</option>
    			</select>
    			</td>
    		</tr>
    		<tr>
    			<td align="right"><input type="submit" name="submit" value="Buscar"></td>
    		</tr>
			</table>
    	</form>
    </td>
  </tr>
</table>