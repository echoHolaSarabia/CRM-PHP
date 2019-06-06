<?php
require('clases/pagination.class.php');
$funciones = new Funciones;
$modulo = "mod_rs_categorias";
$items = 20;
$tipo = (isset($_GET['tipo'])) ? $_GET['tipo'] : "blog";
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$palabra = (isset($_GET['palabra'])) ? $_GET['palabra'] : "";
$orden = (isset($_GET['orden'])) ? $_GET['orden'] : "id";
$tipo_orden = (isset($_GET['tipo_orden'])) ? $_GET['tipo_orden'] : "DESC";
$desde = (isset($_GET['desde'])) ? $_GET['desde'] : "";
$hasta = (isset($_GET['hasta'])) ? $_GET['hasta'] : "";
$categorias = $funciones -> get_posts($palabra,$page,$items,$orden,$tipo_orden,$desde,$hasta,$tipo);
$num_categorias = $funciones -> get_num_posts($palabra,$desde,$hasta,$tipo);
?>
<script>
function eliminar_posts(){
	if (confirm("¿Desea eliminar los posts seleccionados?"))
		document.form_listado_seleccion.submit();
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
                <td class="botones_botonera"><a href="javascript:eliminar_posts();" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><? include ("buscador.inc.php");?></td>	
        </tr>
        <tr>
          <td class="contenido">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <form name="form_listado_seleccion" method="post" action="index2.php?modulo=<?=$modulo?>&fns=1&accion=delete">
              <?php 
								if ($num_categorias>0){
									$p = new pagination;
									$p->Items($num_categorias);
									$p->limit($items);
									if(isset($palabra))
											$p->target("index2.php?modulo=".$modulo."&palabra=".urlencode($palabra)."&orden=".$orden."&tipo_orden=".$tipo_orden."&desde=".$desde."&hasta=".$hasta."&tipo=".$tipo);		 	  								
									$p->currentPage($page);
							?>
	                <tr class="titulos">
	                  <td>Id</td>
	                  <td><input type="checkbox" name="seleccion" onclick="checkAll(<?=$num_categorias+1?>,'');" /></td>
	                  <td>T&iacute;tulo</td>
	                  <td>Autor</td>
	                  <td>Fecha publicaci&oacute;n</td>
	                  <td align="center">Votos</td>
	                  <td>Tipo</td>
	                  <td align="center">Comentarios</td>
	                  <td align="center">En recurso</td>
	                  <td align="right">Editar</td>
	                </tr>
				  			<?
	              $num_fila = 0;
	              foreach ($categorias as $categoria){
	                $num_fila ++;
	                $estilo = (($num_fila % 2) == 0) ? "fila_tabla_par" : "fila_tabla_impar";
	              ?>
	                <tr>
	                  <td class="<?=$estilo?>"><?=$categoria['id']?></td>
	                  <td class="<?=$estilo?>"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$categoria['id']?>" /></td>
	                  <td class="<?=$estilo?>"><strong><?=stripslashes($categoria['titulo'])?></strong></td>
	                  <td class="<?=$estilo?>"><?=$funciones -> get_nombre_miembro($categoria['id_miembro']);?></td>
	                  <td class="<?=$estilo?>"><?=$categoria['fecha_publicacion']?></td>
	                  <td class="<?=$estilo?>" align="center"><?=$categoria['votos']?></td>
	                  <td class="<?=$estilo?>"><?=$categoria['tipo']?></td>
	                  <td class="<?=$estilo?>" align="center"><a href="?modulo=mod_rs_comentarios&tipo=<?=$categoria['tipo']?>&id_post=<?=$categoria['id']?>"><?=$funciones -> get_num_comentarios($categoria['id'],$categoria['tipo'])?></a></td>
	                  <td class="<?=$estilo?>" align="center"><? if (isset($categoria) && $categoria['en_recurso'] == 0) echo "<a href='?modulo=".$modulo."&accion=activar&tipo=".$tipo."&fns=1&id=".$categoria['id']."&page=".$page."'><img src='images/cross.png' border='0'/></a>"; else echo "<img src='images/tick.png' border='0'/>";?></td>
	                  <td class="<?=$estilo?>" align="right"><a href="?modulo=<?=$modulo?>&accion=editar&tipo=<?=$categoria['tipo']?>&id=<?=$categoria['id']?>"><img src="images/page_edit.png" border="0" /></a></td>
	                </tr>
                  <? } ?>
              </form>
            </table>
          </td>
        </tr>
        <tr>
			    <td colspan='9'><?=$p->show()?></td>
			  </tr>
			  <? } ?>
      </table>
    </td>
</tr>