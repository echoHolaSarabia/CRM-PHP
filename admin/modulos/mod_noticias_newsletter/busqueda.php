<?php
require('../../../configuracion.php');
require('../../includes/conexion.inc.php');
require('../../clases/pagination.class.php');
require('../../includes/funciones.inc.php');
$items = $config_registros_paginador;
$page = 1;

if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
		$limit = " LIMIT ".(($page-1)*$items).",$items";
	else
		$limit = " LIMIT $items";

if(isset($_GET['q']) and !eregi('^ *$',$_GET['q'])){
		$q = sql_quote($_GET['q']); //para ejecutar consulta
		$busqueda = htmlentities($q); //para mostrar en pantalla

		$sqlStr = "SELECT * FROM noticias_newsletter WHERE titular LIKE '%$q%' ORDER BY id DESC";
		$sqlStrAux = "SELECT count(*) as total FROM noticias_newsletter WHERE titular LIKE '%$q%' ORDER BY id DESC";
	}else{
		$sqlStr = "SELECT * FROM noticias_newsletter ORDER BY id DESC";
		$sqlStrAux = "SELECT count(*) as total FROM noticias_newsletter ORDER BY id DESC";
	}
$r=mysql_query($sqlStrAux,$link);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr.$limit, $link);
?>
<script type="text/javascript" language="javascript">
function actualizar_listado(id_noticia){
	titular = document.getElementById("titular_alt_"+id_noticia).value;
	antetitulo = document.getElementById("antetitulo_alt_"+id_noticia).value;
	entradilla = document.getElementById("entradilla_alt_"+id_noticia).value;
	document.location.href='index2.php?modulo=mod_noticias_newsletter&accion=update_listado&fns=1&id='+id_noticia+'&titular_alt='+titular+'&entradilla_alt='+entradilla+'&antetitulo_alt='+antetitulo;
}
</script>	
<p><?php
		if($aux['total'] and isset($busqueda)){
				echo "{$aux['total']} Resultado".($aux['total']>1?'s':'')." que coinciden con tu b&uacute;squeda \"<strong>$busqueda</strong>\".";
			}elseif($aux['total'] and !isset($q)){
				echo "Total de registros: {$aux['total']}";
			}elseif(!$aux['total'] and isset($q)){
				echo"No hay registros que coincidan con tu b&uacute;squeda \"<strong>$busqueda</strong>\"";
			}
	?></p>
	<?php 
		if($aux['total']>0){
			$p = new pagination;
			$p->Items($aux['total']);
			$p->limit($items);
			if(isset($q))
					$p->target("index2.php?modulo=mod_noticias_newsletter&q=".urlencode($q));
				else
					$p->target("index2.php?modulo=mod_noticias_newsletter");
			$p->currentPage($page);
			echo '<div class="titulos">
			         <div style="float:left; width:2%">Id</div>
			         <div style="float:left; width:2%"><input type="checkbox" name="seleccion" onclick="checkAll('.($aux['total']+1).');" /></div>
			         <div style="float:left; width:70%">Titular</div>
			         <div style="float:left; width:10%;text-align:center">Seccion</div>
			         <div style="float:left; width:10%;text-align:center">Categor&iacute;a</div>
			         <div style="float:left; width:6%;text-align:right;">Acciones</div>
			      </div>';
			$r=0;
			while($noticia = mysql_fetch_assoc($query)){
				$num_fila ++;
				$nombre_seccion = mysql_fetch_array(mysql_query("SELECT nombre FROM secciones WHERE id=".$noticia['seccion'].""));
				$nombre_categoria = mysql_fetch_array(mysql_query("SELECT nombre FROM categorias WHERE id=".$noticia['categoria'].""));
	?>
				    <div id="seccion_<?=$noticia['id']?>" style="float:left;width:100%;" class="<? echo (($num_fila % 2) == 0) ? "fila_par" : "fila_impar"; ?>">
			                  <div style="float:left;width:100%;">
			                    <div class="casilla_listado" style="width:2%"><?=$noticia['id']?><img id="mas_menos_<?=$noticia['id']?>" src="images/mas.png" onclick="muestra_oculta('noticia_',<?=$noticia['id']?>)" style="cursor:pointer;"></div>
			                    <div class="casilla_listado" style="width:2%"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$noticia['id']?>" /></div>
			                    <div class="casilla_listado" style="width:70%"><strong><?=htmlentities($noticia['titular'])?></strong></div>
			                    <div class="casilla_listado" style="width:10%;text-align:center"><?=$nombre_seccion['nombre']?></div>
			                    <div class="casilla_listado" style="width:10%;text-align:center"><?=$nombre_categoria['nombre']?></div>
			                    <div class="casilla_listado" style="width:6%;text-align:right;"><a href="?modulo=mod_noticias_newsletter&accion=editar&id=<?=$noticia['id']?>"><img src="images/page_edit.png" border="0" /></a></div>
			                  </div>
			                  <div id="noticia_<?=$noticia['id']?>" style="display:none;margin-bottom:5px;">
		                          Titular alternativo:<br />
		                          <input type="text" id="titular_alt_<?=$noticia['id']?>" value="<?=$noticia['titular_alt']?>" style="width:50%"><br />
		                          Antetitulo alternativo:<br />
		                          <input type="text" id="antetitulo_alt_<?=$noticia['id']?>" value="<?=$noticia['antetitulo_alt']?>" style="width:50%"><br />
		                          Entradilla alternativa:<br />
		                          <input type="text" id="entradilla_alt_<?=$noticia['id']?>" value="<?=$noticia['entradilla_alt']?>" style="width:50%"><input type="button" value="Guardar" onclick="actualizar_listado(<?=$noticia['id']?>);" style="margin-left:5px;"">
			                  </div>
			                </div>
	<?php
          	if($r%2==0)++$r;else--$r;
        	}
		  $p->show();
		}
	?>