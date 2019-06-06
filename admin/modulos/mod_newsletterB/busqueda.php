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

		$sqlStr = "SELECT * FROM newsletters WHERE nombre LIKE '%$q%' ORDER BY id DESC";
		$sqlStrAux = "SELECT count(*) as total FROM newsletters WHERE nombre LIKE '%$q%' ORDER BY id DESC";
	}else{
		$sqlStr = "SELECT * FROM newsletters ORDER BY id DESC";
		$sqlStrAux = "SELECT count(*) as total FROM newsletters ORDER BY id DESC";
	}
$r = mysql_query($sqlStrAux,$link);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr.$limit, $link);
?>
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
					$p->target("index2.php?modulo=mod_newsletter&q=".urlencode($q));
				else
					$p->target("index2.php?modulo=mod_newsletter");
			$p->currentPage($page);
			echo '<div class="titulos">
			         <div style="float:left; width:2%">Id</div>
			         <div style="float:left; width:2%"><input type="checkbox" name="seleccion" onclick="checkAll('.($aux['total']+1).');" /></div>
			         <div class="casilla_listado" style="width:44%">Nombre</div>
				     <div class="casilla_listado" style="width:15%;text-align:center">Fecha envio</div>
   	                 <div class="casilla_listado" style="width:10%;text-align:center">Seccion</div>
				     <div class="casilla_listado" style="width:10%;text-align:center">Preparada</div>
				     <div class="casilla_listado" style="width:12%;text-align:center">Enviada</div>
				     <div class="casilla_listado" style="width:5%;text-align:right;">Acciones</div>
			      </div>';
			$r=0;
			while($newsletter = mysql_fetch_assoc($query)){
				$num_fila ++;
				$nombre_seccion = mysql_fetch_array(mysql_query("SELECT nombre FROM secciones WHERE id=".$newsletter['seccion'].""));
	?>
				    <div id="seccion_<?=$newsletter['id']?>" style="float:left;width:100%;" class="<? echo (($num_fila % 2) == 0) ? "fila_par" : "fila_impar"; ?>">
			                  <div style="float:left;width:100%;">
			                    <div class="casilla_listado" style="width:2%"><?=$newsletter['id']?></div>
			                    <div class="casilla_listado" style="width:2%"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$newsletter['id']?>" /></div>
			                    <div class="casilla_listado" style="width:44%"><strong><?=htmlentities($newsletter['nombre'])?></strong></div>
			                    <div class="casilla_listado" style="width:15%;text-align:center"><strong><?=muestra_fecha($newsletter['fecha_creacion'])?></strong></div>
			                    <div class="casilla_listado" style="width:10%;text-align:center"><?=$nombre_seccion['nombre']?></div>
			                    <div class="casilla_listado" style="width:10%;text-align:center"><?=$newsletter['preparada']?></div>
			                    <div class="casilla_listado" style="width:12%;text-align:center"><?=$newsletter['enviada']?></div>
			                    <div class="casilla_listado" style="width:5%;text-align:right;"><a href="?modulo=mod_newsletter&accion=editar&id=<?=$newsletter['id']?>"><img src="images/page_edit.png" border="0" /></a></div>
			                  </div>
			                </div>
	<?php
          	if($r%2==0)++$r;else--$r;
        	}
		  $p->show();
		}
	?>