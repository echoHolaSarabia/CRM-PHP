<? $funciones = new Funciones;?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
          	  <tr>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_galerias_video&accion=nuevo&ver=secciones" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nuevo</a></td>
                <td class="botones_botonera"><a href="javascript:document.form_listado_seleccion.submit();" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Listado de Secciones de vídeos</span></td>
        </tr>
        <tr>
          <td class="contenido">
      	  <form name="form_listado_seleccion" method="post" action="index2.php?modulo=mod_galerias_video&fns=1&accion=delete_secciones">
              <? $filas = $funciones->get_secciones();?>
              <div class="titulos">
                <div style="float:left; width:2%">Id</div>
                <div style="float:left; width:3%"><input type="checkbox" name="seleccion" onclick="checkAll(<?=$num_filas+1?>);" /></div>
                <div style="float:left; width:43%">Nombre de la secci&oacute;n</div>
                <div style="float:left; width:9%;text-align:center">Activa</div>
                <div style="float:left; width:10%;text-align:right;">Editar</div>
              </div>
    		  <div id="grupo_secciones" class="seccion" style="width:100%;">
			  <?
              //Muestra las categorías raiz
              $fila_seccion = 0;
              for ($i = 0 ; $i<count($filas); $i++) {
              	$seccion = $filas[$i];
              	//$num_subsecciones = $funciones->get_num_subsecciones($seccion['id']);
              	//$num_elementos = $funciones->get_num_noticias_seccion($seccion['id']);
              	//$nombre_plantilla = $funciones->get_plantilla($seccion['id']);
                $fila_seccion ++;
              ?>
                <div id="seccion_<?=$seccion['id']?>" class="<? echo (($fila_seccion % 2) == 0) ? "fila_par" : "fila_impar"; ?>">
                  <div style="float:left;width:100%;">
                    <div style="float:left;width:2%"><?=$fila_seccion?></div>
                    <div style="float:left;width:3%"><input type="checkbox" name="seleccion<?=$fila_seccion;?>" value="<?=$seccion['id']?>" /></div>
                    <div style="float:left;width:43%">
                    	<?/* if ($num_subsecciones > 0){?><img id="mas_menos_<?=$seccion['id']?>" src="images/mas.png" align="absmiddle" onclick="muestra_oculta('subsecciones_',<?=$seccion['id']?>)" style="cursor:pointer;"/><? } */?>
                    	<strong><?=$seccion['nombre']?></strong>
                    </div>
                    <div style="float:left;width:9%;text-align:center">
                    	<? if ($seccion['activo'] == 0) 
                    		echo "<a href='?modulo=mod_galerias_video&accion=estado_seccion&fns=1&id=".$seccion['id']."'><img src='images/cross.png' border='0'/></a>"; else echo "<a href='?modulo=mod_galerias_video&accion=estado_seccion&fns=1&id=".$seccion['id']."'><img src='images/tick.png' border='0'/></a>";?></div>
                    <div style="float:left;width:10%;text-align:right;">
                    	<a href="?modulo=mod_galerias_video&accion=editar&ver=secciones&id=<?=$seccion['id']?>"><img src="images/page_edit.png" border="0" /></a></div>
                  </div>                  
                </div><!-- Esto es un grupo de Seccion+Subseccion-->  
              <? } ?>
              </div>
          </form>
          </td>
        </tr>
      </table>
    </td>
</tr>