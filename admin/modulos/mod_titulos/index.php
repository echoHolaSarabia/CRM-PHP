<? $funciones = new Funciones;?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="buscador"><span class="titulo_seccion">Edición de títulos y descripciones</span></td>
        </tr>
        <tr>
          <td class="contenido">
          	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          		<tr class="titulos">
          			<td>Sección</td>
          			<td></td>
          		</tr>
          		<tr>
          			<td class="fila_tabla_par">Portada</td>
          			<td class="fila_tabla_par" align="right"><a href="?modulo=mod_titulos&accion=editar&id=0"><img src="images/page_edit.png" border="0" /></a></td>
          		</tr>
          		<?
              //Muestra las categorías raiz
              $fila_seccion = 0;
              $r = $funciones->get_secciones_raiz();
              while ($seccion = mysql_fetch_array($r)) {
                $fila_seccion ++;
                $estilo = (($fila_seccion % 2) == 0) ? "fila_tabla_par" : "fila_tabla_impar";
              ?>
          		<tr>
          			<td class="<?=$estilo?>"><?=$seccion['nombre']?></td>
          			<td class="<?=$estilo?>" align="right"><a href="?modulo=mod_titulos&accion=editar&id=<?=$seccion['id']?>"><img src="images/page_edit.png" border="0" /></a></td>
          		</tr>
          		<?
              }?>
              
          	</table>
          </td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        </tr>
        <tr>
          <td class="contenido">
          	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          		<tr class="titulos">
          			<td>Sección Comunidad</td>
          			<td></td>
          		</tr>
          		<tr>
          			<td class="fila_tabla_par">Portada</td>
          			<td class="fila_tabla_par" align="right"><a href="?modulo=mod_titulos&accion=editar&id=901"><img src="images/page_edit.png" border="0" /></a></td>
          		</tr>
              <tr>
          			<td class="fila_tabla_par">Debates</td>
          			<td class="fila_tabla_par" align="right"><a href="?modulo=mod_titulos&accion=editar&id=902"><img src="images/page_edit.png" border="0" /></a></td>
          		</tr>
          		<tr>
          			<td class="fila_tabla_par">Pizarra</td>
          			<td class="fila_tabla_par" align="right"><a href="?modulo=mod_titulos&accion=editar&id=903"><img src="images/page_edit.png" border="0" /></a></td>
          		</tr>
          		<tr>
          			<td class="fila_tabla_par">Blog</td>
          			<td class="fila_tabla_par" align="right"><a href="?modulo=mod_titulos&accion=editar&id=904"><img src="images/page_edit.png" border="0" /></a></td>
          		</tr>
          		<tr>
          			<td class="fila_tabla_par">Álbumes de fotos</td>
          			<td class="fila_tabla_par" align="right"><a href="?modulo=mod_titulos&accion=editar&id=905"><img src="images/page_edit.png" border="0" /></a></td>
          		</tr>
          		<tr>
          			<td class="fila_tabla_par">Videos</td>
          			<td class="fila_tabla_par" align="right"><a href="?modulo=mod_titulos&accion=editar&id=906"><img src="images/page_edit.png" border="0" /></a></td>
          		</tr>
          		<tr>
          			<td class="fila_tabla_par">Miembros</td>
          			<td class="fila_tabla_par" align="right"><a href="?modulo=mod_titulos&accion=editar&id=907"><img src="images/page_edit.png" border="0" /></a></td>
          		</tr>
          	</table>
          </td>
        </tr>
      </table>
    </td>
</tr>