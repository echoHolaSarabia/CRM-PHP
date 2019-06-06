<?
include("modulos/mod_secciones/conf.php");
$funciones = new Funciones;
?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
          	  <tr>
                <td class="botones_botonera"><a href="index2.php?modulo=<?=MODULO?>&accion=nuevo" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nuevo</a></td>
                <td class="botones_botonera"><a href="javascript:document.form_listado_seleccion.submit();" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Listado de Secciones</span></td>
        </tr>
        <tr>
          <td class="contenido">
          	<table border="0" width="100%" cellpadding="0" cellspacing="0">
          	<? $num_filas = $funciones->get_num_secciones();?>
              <form name="form_listado_seleccion" method="post" action="index2.php?modulo=<?=MODULO?>&fns=1&accion=delete">
	                <tr class="titulos">
	                  <td>Id</td>
	                  <td><input type="checkbox" name="seleccion" onclick="checkAll(<?=$num_filas+1?>,'');" /></td>
	                  <td>T&iacute;tulo</td>
	                  <td align="center">Subsecciones</td>
	                  <td>Tabla</td>
	                  <td align="right">Editar</td>
	                </tr>
				  	<?
		            //Muestra las categorías raiz
		            $fila_seccion = 0;
		            $secciones = $funciones->get_secciones();
		            foreach ($secciones as $seccion){
		              $num_subsecciones = $funciones->get_num_subsecciones($seccion['id']);
		              $fila_seccion ++;
		              if (($fila_seccion % 2) == 0) $estilo = "fila_tabla_par";
                	  else $estilo = "fila_tabla_impar";

		            ?>
	                <tr>
	                  <td class="<?=$estilo?>"><?=$seccion['id']?><? if ($num_subsecciones > 0){?><img id="mas_menos_<?=$seccion['id']?>" src="images/mas.png" align="absmiddle" onclick="muestra_oculta('subsecciones_',<?=$seccion['id']?>)" style="cursor:pointer;"/><? } ?></td>
	                  <td class="<?=$estilo?>"><input type="checkbox" name="seleccion<?=$fila_seccion;?>" value="<?=$seccion['id']?>" /></td>
	                  <td class="<?=$estilo?>"><strong><?=stripslashes($seccion['titulo'])?></strong></td>
	                  <td class="<?=$estilo?>" align="center"><?=$num_subsecciones?></td>
	                  <td class="<?=$estilo?>"><?=$seccion['tabla']?></td>
	                  <td class="<?=$estilo?>" align="right"><a href="?modulo=<?=MODULO?>&accion=editar&id=<?=$seccion['id']?>"><img src="images/page_edit.png" border="0" /></a></td>
	                </tr>
	                <? 
                        //Por cada raiz muestra sus descendientes
                        $fila_subseccion = 0;
                        $subsecciones = $funciones->get_subsecciones($seccion['id']);
                        foreach ($subsecciones as $subseccion){
                            $fila_subseccion ++;
                      ?>
                      <tr id="subsecciones_<?=$seccion['id']?>" style="display:none;width:100%;background-color: #F5E8E8;">
                        <td>&nbsp;</td>
                        <td><input type="checkbox" name="seleccion<?=$fila_subseccion;?>" value="<?=$subseccion['id']?>" /></td>
                        <td><?=$subseccion['titulo']?></td>
                        <td align="center">-</td>
                        <td><?=$subseccion['tabla']?></td>
                        <td align="right"><a href="?modulo=<?=MODULO?>&accion=editar&id=<?=$subseccion['id']?>"><img src="images/page_edit.png" border="0" /></a></td>
                      </tr>
                      <? } ?>
                  <?}?>
              </form>
            </table>
          </td>
        </tr>
      </table>
    </td>
</tr>