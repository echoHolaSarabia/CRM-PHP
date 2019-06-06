<?php
require('../../../configuracion.php');
require('../../includes/conexion.inc.php');
require('../../includes/funciones.inc.php');
require('funciones.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gesti&oacute;n de publicaciones</title>
	<link href="../../css/estilos_general.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php
//Parámetros necesarios para construir la consulta

$q = $_POST['q'];
$seccion = $_POST['q_seccion'];
$orden = $_POST['orden'];
$tipo_orden = $_POST['tipo_orden'];

$funciones = new Funciones;
$sqlStr = $funciones->get_query_busqueda($q,$seccion,$orden,$tipo_orden);
$query = mysql_query($sqlStr);
?>
<script>
function no_permitir (){
	alert('No se puede cambiar la noticia origen');
	document.location.href = "origen.php?id=<?=$_GET['id']?>";
}
</script>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
          	<table border="0" width="100%">
          	  <tr>
          	  	<td align="right">
          	  	<form action="origen.php<? if ($_GET['id'] != "") echo "?id=".$_GET['id'];?>" method="POST">
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
            <form name="form_rel_origen" method="post" action="">
			          <div id="resultados">
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
					                 <div class="casilla_listado" style="width:4%"><input type="radio" name="origen" value="<?=$noticia['id']?>" <? if ($_GET['id'] == $noticia['id']) echo "checked";?> <? if ($_GET['id'] != "") echo "onclick='no_permitir()'";?> /></div>
					                 <div class="casilla_listado" style="width:85%"><strong><?=htmlentities($noticia['titular'])?></strong></div>
					                 <div class="casilla_listado" style="width:5%;text-align:right;"><a href="?modulo=mod_noticias&accion=editar&id=<?=$noticia['id']?>"><img src="../../images/page_edit.png" border="0" /></a></div>
					               </div>
					             </div>
					    	<?
					      		if($r%2==0)++$r;else--$r;
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