<?php
require('clases/pagination.class.php');
$funciones = new Funciones;
$modulo = "mod_rs_miembros";
$items = 20;
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$palabra = (isset($_GET['palabra'])) ? $_GET['palabra'] : "";
$sexo = (isset($_GET['sexo'])) ? $_GET['sexo'] : "";
$edad = (isset($_GET['edad'])) ? $_GET['edad'] : "";
$tipo_seguidor = (isset($_GET['tipo_seguidor'])) ? $_GET['tipo_seguidor'] : "";
$orden = (isset($_GET['orden'])) ? $_GET['orden'] : "id";
$tipo_orden = (isset($_GET['tipo_orden'])) ? $_GET['tipo_orden'] : "DESC";
$miembros = $funciones -> get_miembros($palabra,$sexo,$edad,$tipo_seguidor,$page,$items,$orden,$tipo_orden);
$num_miembros = $funciones -> get_num_miembros($palabra,$sexo,$edad,$tipo_seguidor);
?>
<script>
function eliminar_miembro(){
	if (confirm("¿Desea eliminar los miembros seleccionados?"))
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
                <td class="botones_botonera"><a href="javascript:eliminar_miembro();" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
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
								if ($num_miembros>0){
									$p = new pagination;
									$p->Items($num_miembros);
									$p->limit($items);
									if(isset($palabra))
											$p->target("index2.php?modulo=".$modulo."&palabra=".urlencode($palabra)."&orden=".$orden."&tipo_orden=".$tipo_orden."&sexo=".$sexo."&edad=".$edad."&tipo_seguidor=".$tipo_seguidor);		 	  								
									$p->currentPage($page);
							?>
	                <tr class="titulos">
	                  <td>Id</td>
	                  <td><input type="checkbox" name="seleccion" onclick="checkAll(<?=$num_miembros+1?>,'');" /></td>
	                  <td>Nombre</td>
	                  <td>Apellidos</td>
	                  <td align="center">Periodista</td>
	                  <td align="center">Sexo</td>
	                  <td>E-mail</td>
	                  <td align="center">Fecha de nacimiento</td>
	                  <td align="right">Desactivar</td>
	                  <td align="right">Editar</td>
	                </tr>
				  			<?
	              $num_fila = 0;
	              foreach ($miembros as $miembro){
	                $num_fila ++;
	                $estilo = (($num_fila % 2) == 0) ? "fila_tabla_par" : "fila_tabla_impar";
	                if ($miembro['activo'] == 0){
	                	if ($miembro['clave_activacion'] == "1234567890abcd")
	                		$estilo = "fila_tabla_inactivo";
	                	else $estilo = "fila_tabla_no_publicada";
	                }
	              ?>
	                <tr>
	                  <td class="<?=$estilo?>"><?=$miembro['id']?></td>
	                  <td class="<?=$estilo?>"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$miembro['id']?>" /></td>
	                  <td class="<?=$estilo?>"><strong><?=$miembro['nombre']?></strong></td>
	                  <td class="<?=$estilo?>"><?=$miembro['apellidos']?></td>
	                  <td class="<?=$estilo?>" align="center"><?=($miembro['periodista'] == 1) ? "Si" : "No";?></td>
	                  <td class="<?=$estilo?>" align="center"><?=($miembro['sexo'] == 1) ? "Hombre" : "Mujer";?></td>
	                  <td class="<?=$estilo?>"><?=$miembro['email']?></td>
	                  <td class="<?=$estilo?>" align="center"><?=$miembro['fecha_nacimiento']?></td>
	                  <td class="<?=$estilo?>" align="center"><? if ($miembro['activo'] == 0) echo ""; else echo "<a href='index2.php?modulo=mod_rs_miembros&accion=desactivar&fns=1&id=".$miembro['id']."'><img src='images/user_delete.png' border='0'/></a>";?></td>
	                  <td class="<?=$estilo?>" align="right"><a href="?modulo=<?=$modulo?>&accion=editar&id=<?=$miembro['id']?>"><img src="images/page_edit.png" border="0" /></a></td>
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