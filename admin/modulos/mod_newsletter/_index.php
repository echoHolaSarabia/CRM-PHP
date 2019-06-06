<?php
require('clases/pagination.class.php');

//Parámetros necesarios para construir la consulta
if (isset($_GET['page']) && $_GET['page'] != "")
	$page = $_GET['page'];
else $page = 1;

$items = $config_registros_paginador;
$q = $_GET['q'];
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
if (isset($_GET['q_seccion']) && ($_GET['q_seccion'] != "")){//Busqueda por seccion
	$funciones->get_query_secciones($page,$items,$_GET['q_seccion'],$sqlStr,$sqlStrAux,$limit,$orden,$tipo_orden);
}else{//Busqueda por palabra
	$funciones->get_query_palabra($page,$items,$q,$sqlStr,$sqlStrAux,$limit,$orden,$tipo_orden);
}

$r=mysql_query($sqlStrAux);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr.$limit);
?>

<script type="text/javascript" language="javascript">
function popup (id) {
	window.open("<?=$config_path_admin."/modulos/mod_noticias/"?>popup.php?id="+id,"Preview","width=600,height=600,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes");
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
      		    <td class="botones_botonera"><a href="javascript:haz_submit('form_listado_seleccion','index2.php?modulo=mod_newsletter&fns=1&accion=duplicar');" class="enlaces_botones_botonera"><br /><img src="images/duplicar2.png" border="0"><br />Duplicar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_newsletter&accion=nuevo" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nueva</a></td>
                <td class="botones_botonera"><a href="javascript:haz_submit('form_listado_seleccion','index2.php?modulo=mod_newsletter&fns=1&accion=delete');" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
          	<table border="0" width="100%">
          	  <tr>
          	  	<td class="buscador"><span class="titulo_seccion">Listado de newsletters</span></td>
          	  	<td align="right">
          	  	  <table border="0">
          	  	    <tr>
          	  	      <td>
          	  	        <form action="buscador.php" onsubmit="return buscar()">
					      <label>Palabra:</label> <input type="text" id="q" name="q" value="<?php if(isset($q)) echo $busqueda;?>" onKeyUp="return buscar('mod_newsletter')" size="50">
					      <input type="submit" value="Buscar" id="search">
					      <span id="loading"></span>
					    </form>
          	  	      </td>
          	  	    </tr>
          	  	    <tr>
          	  	      <td>
          	  	        <form action="index2.php" method="GET" >
					      <label>Secci&oacute;n:</label>
					      <input type="hidden" name="modulo" value="mod_newsletter">
					      <select name="q_seccion">
					      		<option value="">Todas</option>
						      <?
						      $r_secciones = $funciones->get_all_secciones();
						      while ($sec = mysql_fetch_array($r_secciones)){
						      ?>
						      	<option value="<?=$sec['id']?>" <? if ($_GET['q_seccion'] == $sec['id']) echo "selected";?>><?=$sec['nombre']?></option>
						      <?
						      }?>
					      </select>
					      <input type="submit" value="Buscar" id="search">
					    </form>
          	  	      </td>
          	  	    </tr>
          	  	    <tr>
          	  	      <td>
	          	  		<form action="index2.php" method="GET" >
	          	  		   <input type="hidden" name="modulo" value="mod_newsletter">
						   <label>Ordenar por:</label>
						   <select name="orden">
						     <option value="nombre">Nombre</option>
						     <option value="fecha_creacion">Fecha</option>
						     <option value="seccion">Seccion</option>
						   </select>
						   <label> orden:</label>
						   <select name="tipo_orden">
						     <option value="ASC">Ascendente</option>
						     <option value="DESC">Descendente</option>
						   </select>
						   <input type="submit" value="Buscar" id="search">
						 </form>
	          	  	  </td>
          	  	    </tr>
          	  	  </table>
          	  	</td>
          	  </tr>
          	</table>
          </td>
        </tr>
        <tr>
          <td class="contenido">
          <form name="form_listado_seleccion" method="post" action="">
	          <div id="resultados">
				<p>
				  <?php
				  // Indica cuantos registros se mustran en total en todas la páginas
					if($aux['total'] and isset($busqueda)){
							echo "{$aux['total']} Resultado".($aux['total']>1?'s':'')." que coinciden con tu b&uacute;squeda \"<strong>$busqueda</strong>\".";
						}elseif($aux['total'] and !isset($q)){
							echo "Total de registros: {$aux['total']}";
						}elseif(!$aux['total'] and isset($q)){
							echo"No hay registros que coincidan con tu b&uacute;squeda \"<strong>$busqueda</strong>\"";
						}
				  ?>
				</p>
				<?php 
					if($aux['total']>0){
						$p = new pagination;
						$p->Items($aux['total']);
						$p->limit($items);
						if(isset($q))
								$p->target("index2.php?modulo=mod_newsletter&q=".urlencode($q));
							else if (isset($_GET['q_seccion']) && ($_GET['q_seccion'] != ""))
									$p->target("index2.php?modulo=mod_newsletter&q_seccion=".$_GET['q_seccion']);
								 else if (isset($orden) && isset($tipo_orden))
								 		$p->target("index2.php?modulo=mod_newsletter&orden=".$orden."&tipo_orden=".$tipo_orden);
								 	  else $p->target("index2.php?modulo=mod_newsletter");						 	  								
						$p->currentPage($page);
				?>
							<div class="titulos">
				                <div class="casilla_listado" style="width:2%">Id</div>
				                <div class="casilla_listado" style="width:2%"><input type="checkbox" name="seleccion" onclick="checkAll('<?=($aux['total']+1)?>');" /></div>
				                <div class="casilla_listado" style="width:44%">Nombre</div>
				                <div class="casilla_listado" style="width:15%;text-align:center">Fecha envio</div>
				                <div class="casilla_listado" style="width:10%;text-align:center">Seccion</div>
				                <div class="casilla_listado" style="width:10%;text-align:center">Preparada</div>
				                <div class="casilla_listado" style="width:12%;text-align:center">Enviada</div>
				                <div class="casilla_listado" style="width:5%;text-align:right;">Acciones</div>
				            </div>
				            <div id="grupo_secciones" class="seccion" style="width:100%;">
				<?
						$r=0;
						while($newsletter = mysql_fetch_assoc($query)){
							$num_fila ++;
							$nombre_seccion = mysql_fetch_array(mysql_query("SELECT nombre FROM secciones WHERE id=".$newsletter['seccion'].""));
							$datos_noticia = get_elemento_de_tabla ("newsletters",$newsletter['id']);
				?>
					        <div id="seccion_<?=$newsletter['id']?>" class="<? echo (($num_fila % 2) == 0) ? "fila_par" : "fila_impar"; ?>">
			                  <div style="float:left;width:100%;">
			                    <div class="casilla_listado" style="width:2%"><?=$newsletter['id']?></div>
			                    <div class="casilla_listado" style="width:2%"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$newsletter['id']?>" /></div>
			                    <div class="casilla_listado" style="width:44%"><strong><?=htmlentities($newsletter['nombre'])?></strong></div>
			                    <div class="casilla_listado" style="width:15%;text-align:center"><?=muestra_fecha($newsletter['fecha_creacion'])?></div>
			                    <div class="casilla_listado" style="width:10%;text-align:center"><?=$nombre_seccion['nombre']?></div>
			                    <div class="casilla_listado" style="width:10%;text-align:center"><? if ($newsletter['preparada'] == 0) echo "<img src='images/cross.png' border='0'/>"; else echo "<img src='images/tick.png' border='0'/>";?></div>
			                    <div class="casilla_listado" style="width:12%;text-align:center"><? if ($newsletter['enviada'] == 0) echo "<img src='images/cross.png' border='0'/>"; else echo "<img src='images/tick.png' border='0'/>";?></div>
			                    <div class="casilla_listado" style="width:5%;text-align:right;"><img src="images/acrobat.png" border="0" alt="PDF" style="cursor:pointer" onclick="pdf(<?=$newsletter['id']?>);"/><img src="images/preview_peque.png" border="0" alt="Previsualizar" onclick="popup(<?=$newsletter['id']?>);" style="cursor:pointer"/><a href="?modulo=mod_newsletter&accion=editar&id=<?=$newsletter['id']?>"><img src="images/page_edit.png" border="0" /></a></div>
			                  </div>
			                </div>
			    <?
			      		if($r%2==0)++$r;else--$r;
			        	}
			        echo "<div style='width:50px'>&nbsp</div>";
					$p->show();
					}
				?>
			  </div>
		  </form>
          </td>
        </tr>
      </table>
    </td>
</tr>