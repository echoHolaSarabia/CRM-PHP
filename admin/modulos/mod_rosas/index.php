<?
include("modulos/mod_rosas/conf.php");
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
          <td class="buscador"><span class="titulo_seccion">Listado de Módulos</span></td>
        </tr>
        <tr>
          <td class="contenido">
          	<table border="0" width="100%" cellpadding="0" cellspacing="0">
          	<? $num_filas = $funciones->get_num_modulos_rosas();?>
              <form name="form_listado_seleccion" method="post" action="index2.php?modulo=<?=MODULO?>&fns=1&accion=delete">
	                <tr class="titulos">
	                  <td>Id</td>
	                  <td><input type="checkbox" name="seleccion" onclick="checkAll(<?=$num_filas+1?>,'');" /></td>
	                  <td>T&iacute;tulo</td>
	                  <td align="right">Editar</td>
	                </tr>
				  	<?
		            //Muestra las categorías raiz
		            $fila = 0;
		            $rosas = $funciones->get_modulos_rosas();
		            foreach ($rosas as $rosa){
		              $fila ++;
		              if (($fila % 2) == 0) $estilo = "fila_tabla_par";
                	  else $estilo = "fila_tabla_impar";

		            ?>
	                <tr>
	                  <td class="<?=$estilo?>"><?=$rosa['id']?></td>
	                  <td class="<?=$estilo?>"><input type="checkbox" name="seleccion<?=$fila;?>" value="<?=$rosa['id']?>" /></td>
	                  <td class="<?=$estilo?>"><a href="?modulo=<?=MODULO?>&accion=editar&id=<?=$rosa['id']?>" style="text-decoration: none; color: #000000;"><strong><?=stripslashes($rosa['titulo'])?></strong></a></td>
	                  <td class="<?=$estilo?>" align="right"><a href="?modulo=<?=MODULO?>&accion=editar&id=<?=$rosa['id']?>"><img src="images/page_edit.png" border="0" /></a></td>
	                </tr>
                  <?}?>
              </form>
            </table>
          </td>
        </tr>
      </table>
    </td>
</tr>