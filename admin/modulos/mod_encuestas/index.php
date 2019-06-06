<? $encuesta = new Funciones;?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
      		    <td class="botones_botonera"><a href="index2.php?modulo=mod_encuestas&accion=nuevo" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nueva</a></td>
                <td class="botones_botonera"><a href="javascript:haz_submit('form_listado_seleccion','index2.php?modulo=mod_encuestas&fns=1&accion=delete');" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Eliminar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Listado de encuestas</span></td>
        </tr>
        <tr>
          <td class="contenido">
      	  <form name="form_listado_seleccion" method="post" action="">
              <?
              $c = "SELECT * FROM secciones;";
			  $r = mysql_query($c);
			  $num_filas = mysql_num_rows($r);
			  ?>
              <div class="titulos">
                <div style="float:left; width:2%">Id</div>
                <div style="float:left; width:3%"><input type="checkbox" name="seleccion" onclick="checkAll(<?=$num_filas+1?>);" /></div>
                <div style="float:left; width:64%">Pregunta</div>
                <div style="float:left; width:10%;text-align:center">Mostrar</div>
                <div style="float:left; width:10%;text-align:center">Activa</div>
                <div style="float:left; width:10%;text-align:right;">Editar</div>
              </div>
    		  <div id="grupo_secciones" class="seccion" style="width:100%;">
			  <?
              //Muestra las categorías raiz
              $num_fila = 0;
              $r = $encuesta->get_encuestas();
              while ($fila = mysql_fetch_array($r)) {
                $num_fila ++;
              ?>
                <div id="seccion_<?=$fila['id']?>" class="<? echo (($num_fila % 2) == 0) ? "fila_par" : "fila_impar"; ?>">
                  <div style="float:left;width:100%;">
                    <div style="float:left;width:2%"><?=$num_fila?></div>
                    <div style="float:left;width:3%"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$fila['id']?>" /></div>
                    <div style="float:left;width:64%"><strong><?=$fila['pregunta']?></strong></div>
                    <div style="float:left; width:10%;text-align:center">
                    <? if ($fila['mostrar'] == 0) 
                    	  if ($fila['activa'] == 0)
                    		echo "<a href='?modulo=mod_encuestas&accion=estado&fns=1&id=".$fila['id']."'><img src='images/cross.png' border='0'/></a>";
                    	  else echo "<img src='images/cross.png' border='0'/>";
                       else 
                       	  if ($fila['activa'] == 0)
                    		echo "<a href='?modulo=mod_encuestas&accion=estado&fns=1&id=".$fila['id']."'><img src='images/tick.png' border='0'/></a>";
                    	  else echo "<img src='images/tick.png' border='0'/>";	
                       //else echo "<a href='?modulo=mod_encuestas&accion=estado&fns=1&id=".$fila['id']."'><img src='images/tick.png' border='0'/></a>";
                     ?></div>
                    <div style="float:left; width:10%;text-align:center"><? if ($fila['activa'] == 0) echo "<a href='?modulo=mod_encuestas&accion=publicar&fns=1&id=".$fila['id']."'><img src='images/cross.png' border='0'/></a>"; else echo "<img src='images/tick.png' border='0'/>";?></div>
                    <div style="float:left;width:10%;text-align:right;"><a href="?modulo=mod_encuestas&accion=editar&id=<?=$fila['id']?>"><img src="images/page_edit.png" border="0" /></a></div>
                  </div>
                </div>  
          	  <?
              } ?>
              </div>
          </form>
          </td>
        </tr>
      </table>
    </td>
</tr>