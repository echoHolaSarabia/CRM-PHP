<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_multimedia&accion=nuevo" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nuevo</a></td>
                <td class="botones_botonera"><a href="javascript:document.form_listado_seleccion.submit();" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Listado de videos</span></td>
        </tr>
        <tr>
          <td class="contenido">
      	  <form name="form_listado_seleccion" method="post" action="index2.php?modulo=mod_multimedia&fns=1&accion=delete">
              <?
             // echo "www".$ancho_video;
              //die;
              $c = "SELECT * FROM multimedia;";
			  $r = mysql_query($c);
			  $num_filas = mysql_num_rows($r);
			  ?>
              <div class="titulos">
                <div style="float:left; width:3%">Id</div>
                <div style="float:left; width:3%"><input type="checkbox" name="seleccion" onclick="checkAll(<?=$num_filas+1?>);" /></div>
                <div style="float:left; width:45%">Nombre</div>
                <div style="float:left; width:10%;text-align:center">Publicado</div>
                <div style="float:left; width:10%;text-align:center">Portada</div>
                <div style="float:left; width:19%;text-align:center">Autor</div>
                <div style="float:left; width:10%;text-align:right;">Editar</div>
              </div>
    		  <div id="grupo_secciones" class="seccion" style="width:100%;">
			  <?
              $num_fila = 0;
              $c = "SELECT * FROM multimedia ORDER BY orden DESC;";
              $r = mysql_query($c);
              while ($fila = mysql_fetch_array($r)) {
                $num_fila ++;
              ?>
                <div id="seccion_<?=$fila['id']?>" class="<? echo (($num_fila % 2) == 0) ? "fila_par" : "fila_impar"; ?>">
                  <div style="float:left;width:100%;">
                    <div style="float:left;width:3%"><?=$fila['id']?></div>
                    <div style="float:left;width:3%"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$fila['id']?>" /></div>
                    <div style="float:left;width:45%"><strong><?=stripslashes($fila['titular'])?></strong></div>
                    <div style="float:left;width:10%;text-align:center"><? if ($fila['activo'] == 0) echo "<a href='?modulo=mod_multimedia&accion=estado&campo=activo&fns=1&id=".$fila['id']."'><img src='images/cross.png' border='0'/></a>"; else echo "<a href='?modulo=mod_multimedia&accion=estado&campo=activo&&fns=1&id=".$fila['id']."'><img src='images/tick.png' border='0'/></a>";?></div>
                    <div style="float:left;width:10%;text-align:center"><? if ($fila['portada'] == 0) echo "<a href='?modulo=mod_multimedia&accion=estado&campo=portada&fns=1&id=".$fila['id']."'><img src='images/cross.png' border='0'/></a>"; else echo "<a href='?modulo=mod_multimedia&accion=estado&campo=portada&&fns=1&id=".$fila['id']."'><img src='images/tick.png' border='0'/></a>";?></div>
                    <div style="float:left;width:19%;text-align:center"><?=$fila['autor']?></div>
                    <div style="float:left;width:10%;text-align:right;"><a href="?modulo=mod_multimedia&accion=editar&id=<?=$fila['id']?>"><img src="images/page_edit.png" border="0" /></a></div>
                  </div>
                </div> 
              <? } ?>
              </div>
              <script type="text/javascript" language="javascript">
				  Sortable.create('grupo_secciones',{
				   tag:'div',
				   dropOnEmpty: false, 
				   constraint:false}
				  );
			  </script>
              <p align="center">
                <input type="button" name="Button" value="Guardar orden" onclick="guardarListado('multimedia')" />
              </p>
          </form>
          </td>
        </tr>
      </table>
    </td>
</tr>