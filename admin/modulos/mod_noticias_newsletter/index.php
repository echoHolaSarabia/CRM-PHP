<?php
require('clases/pagination.class.php');

//Parámetros necesarios para construir la consulta
if (isset($_GET['page']) && $_GET['page'] != "")
	$page = $_GET['page'];
else $page = 1;

if (isset($_GET['q']) && $_GET['q'] != "")
	$q = $_GET['q'];
else $q = "";

if (isset($_GET['q_seccion']) && $_GET['q_seccion'] != "")
	$seccion = $_GET['q_seccion'];
else $seccion = "";

if (isset($_GET['orden']) && $_GET['orden'] != ""){
	$orden = $_GET['orden'];
	$tipo_orden = $_GET['tipo_orden'];
} else {
	$orden = "id";
	$tipo_orden = "DESC";
}

$items = $config_registros_paginador;
$sqlStr = "";
$sqlStrAux = "";
$limit = "";

if (isset($_GET['orden']) && $_GET['orden'] != ""){
	$orden = $_GET['orden'];
	$tipo_orden = $_GET['tipo_orden'];
} else {
	$orden = "id";
	$tipo_orden = "DESC";
}

$funciones = new Funciones;
$funciones->get_query_palabra($page,$items,$q,$seccion,$sqlStr,$sqlStrAux,$limit,$orden,$tipo_orden);

$r = mysql_query($sqlStrAux);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr.$limit);
?>

<script type="text/javascript" language="javascript">
function actualizar_listado(id_noticia){
	titular = document.getElementById("titular_alt_"+id_noticia).value;
	antetitulo = document.getElementById("antetitulo_alt_"+id_noticia).value;
	entradilla = document.getElementById("entradilla_alt_"+id_noticia).value;
	document.location.href='index2.php?modulo=mod_noticias_newsletter&accion=update_listado&fns=1&id='+id_noticia+'&titular_alt='+titular+'&entradilla_alt='+entradilla+'&antetitulo_alt='+antetitulo;
}

function cambiar_estado(id,estado){
	url = "?modulo=mod_noticias_newsletter&accion=estado&campo="+estado+"&fns=1&id="+id+"&page=<?=$page?>";
	window.location=url;
}
</script>
<script src="scripts/buscador.js" type="text/javascript" language="javascript"></script>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
      		    <td class="botones_botonera" valign="top"><a href="javascript:haz_submit('form_listado_seleccion','index2.php?modulo=mod_noticias_newsletter&fns=1&accion=duplicar');" class="enlaces_botones_botonera"><br /><img src="images/duplicar2.png" border="0"><br />Duplicar</a><br><br><br></td>
      		    <td class="botones_botonera" valign="top"><a href="javascript:haz_submit('form_listado_seleccion','index2.php?modulo=mod_noticias_newsletter&fns=1&accion=duplicar_externo');" class="enlaces_botones_botonera"><br /><img src="images/duplicar2.png" border="0"><br />Duplicar<br>para boletín<br>externo</a></td>
                <td class="botones_botonera" valign="top"><a href="index2.php?modulo=mod_noticias_newsletter&accion=nuevo" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nuevo</a><br><br><br></td>
                <td class="botones_botonera" valign="top"><a href="javascript:haz_submit('form_listado_seleccion','index2.php?modulo=mod_noticias_newsletter&fns=1&accion=delete');" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a><br><br><br></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
          	<table border="0" width="100%">
          	  <tr>
          	  	<td class="buscador"><span class="titulo_seccion">Listado de art&iacute;culos para Newsletter</span></td>
          	  	<td align="right">
          	  	<form action="index2.php" method="GET">
          	  	  <input type="hidden" name="modulo" value="mod_noticias_newsletter">
          	  	  <table border="0">
          	  	    <tr>
          	  	      <td>
					    <label>Palabra:</label> <input type="text" id="q" name="q" value="<?php if(isset($q)) echo $q;?>" size="50">
          	  	      </td>
          	  	    </tr>
          	  	    <tr>
          	  	      <td>
					      <label>Secci&oacute;n:</label>
					      <select name="q_seccion">
					      		<option value="">Todas</option>
						      <?
						      $r_secciones = $funciones->get_all_secciones();
						      while ($sec = mysql_fetch_array($r_secciones)){
						      ?>
						      	<option value="<?=$sec['id']?>" <? if (array_key_exists('q_seccion',$_GET) && $_GET['q_seccion'] == $sec['id']) echo "selected";?>><?=$sec['titulo']?></option>
						      <?
						      }?>
					      </select>
          	  	      </td>
          	  	    </tr>
          	  	    <tr>
          	  	      <td>
						   <label>Ordenar por:</label>
						   <select name="orden">
						     <option value="titulo" <? if ($orden == "titulo") echo "selected";?>>Titular</option>
						     <option value="seccion" <? if ($orden == "seccion") echo "selected";?>>Seccion</option>
						   </select>
						   <label> orden:</label>
						   <select name="tipo_orden">
						     <option value="ASC" <? if ($tipo_orden == "ASC") echo "selected";?>>Ascendente</option>
						     <option value="DESC" <? if ($tipo_orden == "DESC") echo "selected";?>>Descendente</option>
						   </select>
						   <input type="submit" value="Buscar" id="search">
	          	  	  </td>
          	  	    </tr>
          	  	  </table>
          	  	</form>
          	  	</td>
          	  </tr>
          	</table>
          </td>
        </tr>
        <tr>
          <td class="contenido">
          <form name="form_listado_seleccion" method="post" action="">
	          <div id="resultados">
	            <table width="100%" border="0" cellpadding="0" cellspacing="0">
				<p>
				  <?php
				  // Indica cuantos registros se mustran en total en todas la páginas
					if($aux['total'] and isset($q)){
						echo "{$aux['total']} Resultado".($aux['total']>1?'s':'')." que coinciden con tu b&uacute;squeda \"<strong>$q</strong>\".";
					}elseif($aux['total'] and !isset($q)){
						echo "Total de registros: {$aux['total']}";
					}elseif(!$aux['total'] and isset($q)){
						echo"No hay registros que coincidan con tu b&uacute;squeda \"<strong>".$q."</strong>\"";
					}
				  ?>
				</p>
				<?php 
					if($aux['total']>0){
						$p = new pagination;
						$p->Items($aux['total']);
						$p->limit($items);
						if(isset($q))
								$p->target("index2.php?modulo=mod_noticias_newsletter&q=".urlencode($q));
							else if (isset($_GET['q_seccion']) && ($_GET['q_seccion'] != ""))
									$p->target("index2.php?modulo=mod_noticias_newsletter&q_seccion=".$_GET['q_seccion']);
								 else if (isset($orden) && isset($tipo_orden))
								 		$p->target("index2.php?modulo=mod_noticias_newsletter&orden=".$orden."&tipo_orden=".$tipo_orden);
								 	  else $p->target("index2.php?modulo=mod_noticias_newsletter");						 	  								
						$p->currentPage($page);
				?>
						<tr class="titulos">
				          <td width="2%">Id</td>
				          <td><input type="checkbox" name="seleccion" onclick="checkAll('<?=($aux['total']+1)?>');" /></td>
				          <td>Titular</td>
				          <td align="center">Tipo noticia</td>
				          <td align="center">Seccion</td>
				          <td align="center">Estado</td>
				          <td align="center">Boletín</td>
				          <!--<td align="center">| A.España</td>
				          <td align="center">| Club</td>
				          <td align="center">| Conf.</td>-->
				          <td align="right">Acciones</td>
				        </tr>
				<?
						$r = 0;
						$num_fila = 0;						
						$iconos_estado[0] = "images/cross.png";
						$iconos_estado[1] = "images/tick.png";
						$iconos_estado[2] = "images/enviada.jpg";
						while($noticia = mysql_fetch_assoc($query)){
							$num_fila ++;
							$nombre_seccion = mysql_fetch_array(mysql_query("SELECT titulo FROM secciones WHERE id=".$noticia['seccion'].""));
							$datos_noticia = get_elemento_de_tabla ("noticias",$noticia['id']);
							if (($num_fila % 2) == 0)
                		 	  	$estilo = "fila_tabla_par";
                		 	else $estilo = "fila_tabla_impar";
				?>
					        <tr>
			                  <td class="<?=$estilo?>"><?=$noticia['id_original']?></td>
			                  <td class="<?=$estilo?>"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$noticia['id']?>" /></td>
			                  <td class="<?=$estilo?>"><strong><?=htmlentities($noticia['titulo'])?></strong></td>
			                  <td class="<?=$estilo?>" align="center">
			                  	<?=str_replace("rio","rió",$noticia['tipo_newsletter'])?></strong></td>
			                  <td class="<?=$estilo?>" align="center"><?=$nombre_seccion['titulo']?></td>
			                   <td class="<?=$estilo?>" align="center"></td>
			                  <td class="<?=$estilo?>" align="center"><a href="javascript:cambiar_estado(<?=$noticia["id"]?>,1)"><img src="<?=$iconos_estado[$noticia['estado_1']]?>" border="0"></td>
			                 <?/* <td class="<?=$estilo?>" align="center"><a href="javascript:cambiar_estado(<?=$noticia["id"]?>,2)"><img src="<?=$iconos_estado[$noticia['estado_2']]?>" border="0"></a></td>
			                  <td class="<?=$estilo?>" align="center"><a href="javascript:cambiar_estado(<?=$noticia["id"]?>,3)"><img src="<?=$iconos_estado[$noticia['estado_3']]?>" border="0"></a></td>
			                  <td class="<?=$estilo?>" align="center"><a href="javascript:cambiar_estado(<?=$noticia["id"]?>,4)"><img src="<?=$iconos_estado[$noticia['estado_4']]?>" border="0"></a></td>*/?>
			                  <td class="<?=$estilo?>" align="right"><a href="?modulo=mod_noticias_newsletter&accion=editar&id=<?=$noticia['id']?>"><img src="images/page_edit.png" border="0" /></a></td>
			                </tr>
			    <?
			      		if($r%2==0)++$r;else--$r;
			        	}
			        	?>
			        	<tr>
			        	  <td colspan='9'><?=$p->show()?></td>
			        	</tr>
			        	<?
					}
				?>
				</table>
			  </div>
		  </form>
          </td>
        </tr>
      </table>
    </td>
</tr>