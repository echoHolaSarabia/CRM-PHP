<?php
require('../../../configuracion.php');
require('../../includes/conexion.inc.php');
require('../../includes/funciones.inc.php');
require('../../clases/pagination.class.php');
require('funciones.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gesti&oacute;n de publicaciones</title>
	<link href="../../css/estilos_general.css" type="text/css" rel="stylesheet" />
	<link href="../../css/paginador.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php
//Parámetros necesarios para construir la consulta
if (isset($_GET['page']) && $_GET['page'] != "")
	$page = $_GET['page'];
else $page = 1;

$items = $config_registros_paginador;

$q = $_POST['q'];
$sqlStr = "";
$sqlStrAux = "";
$limit = "";

if (isset($_POST['orden']) && $_POST['orden'] != ""){
	$orden = $_POST['orden'];
	$tipo_orden = $_POST['tipo_orden'];
} else {
	$orden = "id";
	$tipo_orden = "DESC";
}

$funciones = new Funciones;
if (isset($_POST['q_seccion']) && ($_POST['q_seccion'] != "")){//Busqueda por seccion
	$funciones->get_query_secciones($page,$items,$_POST['q_seccion'],$sqlStr,$sqlStrAux,$limit,$orden,$tipo_orden);
}else{//Busqueda por palabra
	$funciones->get_query_palabra($page,$items,$q,$sqlStr,$sqlStrAux,$limit,$orden,$tipo_orden);
}

$r=mysql_query($sqlStrAux);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr.$limit);
?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
          	<table border="0" width="100%">
          	  <tr>
          	  	<td align="right">
          	  	<form action="destino.php<? if ($_GET['id'] != "") echo "?id=".$_GET['id'];?>" method="POST">
          	  	  <table border="0">
          	  	    <tr>
          	  	      <td>
					      <label>Palabra:</label>
					      <input type="text" id="q" name="q" value="<?=$q?>" size="46">
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
						      	<option value="<?=$sec['id']?>" <? if ($seccion == $sec['id']) echo "selected";?>><?=$sec['nombre']?></option>
						      <?
						      }?>
					      </select>
          	  	      </td>
          	  	    </tr>
          	  	    <tr>
          	  	      <td>
						   <label>Ordenar por:</label>
						   <select name="orden">
						     <option value="titular">Titular</option>
						     <option value="fecha_creacion">Fecha</option>
						     <option value="seccion">Seccion</option>
						     <option value="subseccion">Subseccion</option>
						     <option value="autor">Autor</option>
						   </select>
						   <label> orden:</label>
						   <select name="tipo_orden">
						     <option value="ASC">Ascendente</option>
						     <option value="DESC">Descendente</option>
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
          <td>
            <?
            if ((isset($_GET['id'])) &&($_GET['id'] != "")){
            	$c = "SELECT id_relacionada FROM noticias_relacionadas WHERE id_noticia=".$_GET['id'];
            	$r = mysql_query($c);
            	$relacionadas = array();
            	while ($fila = mysql_fetch_array($r)){
            		$relacionadas[] = $fila['id_relacionada'];
            	}
            }
            ?>
            <form name="form_rel_destino" method="post" action="">
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
										$p->target("destino.php?q=".urlencode($q)."&id=".$_GET['id']);
									else if (isset($_POST['q_seccion']) && ($_POST['q_seccion'] != ""))
											$p->target("destino.php?q_seccion=".$_POST['q_seccion']."&id=".$_GET['id']);
										 else if (isset($orden) && isset($tipo_orden))
										 		$p->target("destino.php?orden=".$orden."&tipo_orden=".$tipo_orden."&id=".$_GET['id']);
										 	  else $p->target("destino.php?id=".$_GET['id']);						 	  								
								$p->currentPage($page);
						?>
						<div class="titulos">
						  <div class="casilla_listado" style="width:8%">Id</div>
						  <div class="casilla_listado" style="width:83%">Titular</div>
						  <div class="casilla_listado" style="width:5%;text-align:right;">Preview</div>
						</div>
						<div id="grupo_secciones" class="seccion" style="width:100%;">
							<?
							$r=0;
							while($noticia = mysql_fetch_assoc($query)){
								$num_fila ++;
							?>
							    <div id="seccion_<?=$noticia['id']?>" class="<? echo (($num_fila % 2) == 0) ? "fila_par" : "fila_impar"; ?>" style="height:18px">
					               <div style="float:left;width:100%;">
					                 <div class="casilla_listado" style="width:4%"><?=$noticia['id']?></div>
					                 <!--<div class="casilla_listado" style="width:4%"><input type="checkbox" name="destino" value="<?=$noticia['id']?>" <? if ((isset($_GET['id'])) &&($_GET['id'] != "")){ if (in_array($noticia['id'],$relacionadas)) echo "checked";}?>/></div>-->
					                 <div class="casilla_listado" style="width:4%">
					                 <? 
					                 	if ((isset($_GET['id'])) && ($_GET['id'] != "")){ 
					                 		if (in_array($noticia['id'],$relacionadas)) 
					                 			echo "<a href=''><img src='../../images/tick.png' border='0'/></a>";
					                 		else echo "<a href=''><img src='../../images/cross.png' border='0'/></a>";
					                    }
					                 ?>
					                 </div>
					                 <div class="casilla_listado" style="width:85%"><strong><?=htmlentities($noticia['titular'])?></strong></div>
					                 <div class="casilla_listado" style="width:5%;text-align:right;"><a href="?modulo=mod_noticias&accion=editar&id=<?=$noticia['id']?>"><img src="../../images/page_edit.png" border="0" /></a></div>
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
</body>
</html>