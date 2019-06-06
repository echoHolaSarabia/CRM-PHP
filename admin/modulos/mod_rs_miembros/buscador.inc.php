<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  	<td><span class="titulo_seccion">MIEMBROS</span></td>
    <td align="right">
    	<form action="index2.php" method="GET">
    		<input type="hidden" name="modulo" value="<?=$modulo?>">
    		Palabra:&nbsp;<input type="text" name="palabra" id="palabra" size="50" value="<? if (isset($_GET['palabra'])) echo $_GET['palabra'];?>">
    		<br><br>
    		Tipo:
    		<select name="tipo_seguidor">
    			<option value="">Todos</option>
				<option value="socio" <?if (isset($_GET['tipo_seguidor']) && $_GET['tipo_seguidor'] == "socio") echo "selected";?>>Socio</option>
				<option value="aficionado" <?if (isset($_GET['tipo_seguidor']) && $_GET['tipo_seguidor'] == "aficionado") echo "selected";?>>Aficionado</option>
				<option value="seguidor_habitual" <?if (isset($_GET['tipo_seguidor']) && $_GET['tipo_seguidor'] == "seguidor_habitual") echo "selected";?>>Seguidor habitual</option>
				<option value="simpatizante" <?if (isset($_GET['tipo_seguidor']) && $_GET['tipo_seguidor'] == "simpatizante") echo "selected";?>>Simpatizante</option>
				<option value="soy_de_otro_equipo" <?if (isset($_GET['tipo_seguidor']) && $_GET['tipo_seguidor'] == "soy_de_otro_equipo") echo "selected";?>>Soy de otro equipo</option>
    		</select>
    		Mayores de:
    		<select name="edad">
    		<?for ($i=100;$i>=1;$i--){?>
    			<option value="<?=$i?>" <? if (isset($_GET['edad']) && ($_GET['edad'] == $i)) echo "selected";?>><?=$i?></option>
    		<?}?>
    		</select>
    		Sexo:
    		<select name="sexo">
    			<option value="" <? if (!isset($_GET['sexo']) || ($_GET['sexo'] == "")) echo "selected";?>>Todos</option>
    			<option value="1" <? if (isset($_GET['sexo']) && ($_GET['sexo'] == "1")) echo "selected";?>>Hombre</option>
    			<option value="0" <? if (isset($_GET['sexo']) && ($_GET['sexo'] == "0")) echo "selected";?>>Mujer</option>
    		</select>
    		<br><br>
    		Ordenar por:
    		<select name="orden">
    			<option value="email" <? if (isset($_GET['orden']) && ($_GET['orden'] == "email")) echo "selected";?>>Email</option>
    			<option value="nombre" <? if (isset($_GET['orden']) && ($_GET['orden'] == "nombre")) echo "selected";?>>Nombre</option>
    		</select>
    		Tipo de orden:
    		<select name="tipo_orden">
    			<option value="DESC" <? if (isset($_GET['tipo_orden']) && ($_GET['tipo_orden'] == "DESC")) echo "selected";?>>Descendente</option>
    			<option value="ASC"<? if (isset($_GET['tipo_orden']) && ($_GET['tipo_orden'] == "ASC")) echo "selected";?>>Ascendente</option>
    		</select>
    		<br><br>
    		<input type="submit" name="submit" value="Buscar">
    		<br>
    	</form>
    </td>
  </tr>
</table>