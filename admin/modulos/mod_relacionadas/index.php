<?php
require('clases/pagination.class.php');

$tipo_elemento = (array_key_exists("elemento",$_REQUEST)) ? $_REQUEST['elemento'] : "noticias";
if ($tipo_elemento == "noticias" || $tipo_elemento == "videos" || $tipo_elemento == "formacion" || $tipo_elemento == "eventos")
	$atributo = "titulo";
elseif ($tipo_elemento == "galerias_videos" || $tipo_elemento == "galerias_imagenes")
		$atributo = "titulo";
	elseif ($tipo_elemento == "encuestas")
			$atributo = "pregunta";
$palabra = (array_key_exists("q",$_REQUEST)) ? $_REQUEST['q'] : "";
$seccion = (array_key_exists("q_seccion",$_REQUEST)) ? $_REQUEST['q_seccion'] : "";
$orden = (array_key_exists("orden",$_REQUEST)) ? $_REQUEST['orden'] : "id";
$tipo_orden = (array_key_exists("tipo_orden",$_REQUEST)) ? $_REQUEST['tipo_orden'] : "DESC";
$page = (array_key_exists("page",$_GET)) ? $_GET['page'] : 1;
$items = $config_registros_paginador;

if(is_numeric($page))
	$limit = " LIMIT ".(($page-1)*$items).",$items";
else $limit = " LIMIT $items";

$funciones = new Funciones($_GET,$_POST);

$strQuery = $funciones -> get_strQuery($tipo_elemento,$atributo,$palabra,$seccion,$orden,$tipo_orden);
$strQueryTotal = $funciones -> get_strQueryTotal($tipo_elemento,$atributo,$palabra,$seccion);
$r = mysql_query($strQueryTotal);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($strQuery.$limit);

?>
<script>
function reload(obj){
	cadena = obj.options[obj.selectedIndex].value;
	//location.href = location.href + "&elemento=" + cadena;
	location.href = "?modulo=mod_relacionadas&id=<?=$_GET['id']?>&elemento=" + cadena;
}
</script>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
          	<table border="0" width="100%">
          	  <tr>
          	  	<td style="padding_left:10px;" valign="top">
          	  	<br><br>
          	  	<span class="titulo_seccion">Relacionar noticias</span><br><br>
          	  	<?
          	  	 $c = "SELECT titulo FROM noticias WHERE id = ".$_GET['id'];
          	  	 $r = mysql_query($c);
          	  	 $fila = mysql_fetch_array($r);
          	  	 echo "<span>&nbsp;EST&Aacute; RELACIONANDO LA NOTICIA: <b><a href='index2.php?modulo=mod_noticias&accion=editar&id=".$_GET['id']."' style='color:#000000'>".$fila['titulo']."</a></b></span>";
          	  	?>
          	  	con <select name="tipo_elemento" onchange="reload(this)">
          	  			<option value="noticias" <?=($tipo_elemento == "noticias") ? "selected" : ""?>>Noticias</option>
          	  			
          	  			<option value="encuestas" <?=($tipo_elemento == "encuestas") ? "selected" : ""?>>Encuestas</option>
          	  		</select>
          	  	</td>
          	    <td align="right" style="padding-right:10px">
          	    	<a href='index2.php?modulo=mod_noticias&accion=editar&id=<?=$_GET['id']?>' style='color:#000000;text-decoration:none'>
          	    		<img src="images/deshacer.png" border="0"><br>Volver
          	    	</a>          	    
          	    <br><br>
          	  	<form action="index2.php?modulo=mod_relacionadas<? if ($_GET['id'] != "") echo "&id=".$_GET['id'];?>" method="POST">
          	  		<input type="hidden" name="elemento" value="<?=(isset($_REQUEST['elemento']) ? $_REQUEST['elemento'] : "noticias")?>">
          	  	  <table border="0">
          	  	    <tr>
          	  	      <td>
					      <label>Palabra:</label>
					      <input type="text" id="q" name="q" value="<?=$palabra?>" size="46">
          	  	      </td>
          	  	    </tr>
          	  	    <?if ($tipo_elemento == "noticias"){?>
          	  	    <tr>
          	  	      <td>
					      <label>Secci&oacute;n:</label>
					      <select name="q_seccion">
					      		<option value="">Todas</option>
						      <?
						      $r_secciones = $funciones->get_all_secciones();
						      foreach ($r_secciones as $sec){
						      ?>
						      	<option value="<?=$sec['id']?>" <? if (array_key_exists('q_seccion',$_REQUEST) && $seccion == $sec['id']) echo "selected";?>><?=$sec['titulo']?></option>
						      <?
						      }?>
					      </select>
          	  	      </td>
          	  	    </tr>  
          	  	    <?}?>
          	  	    <tr>
          	  	      <td>
          	  	      <?if ($tipo_elemento == "noticias"){?>
										   <label>Ordenar por:</label>
										   <select name="orden">
										   	<option value="fecha_creacion">Fecha</option>
										     <option value="titulo">Titular</option>
										    <option value="seccion">Seccion</option>
										     <option value="subseccion">Subseccion</option>
										     <option value="autor">Autor</option>
										   </select>
										   <label> orden:</label>
										   <select name="tipo_orden">									     
										     <option value="DESC">Descendente</option>
										     <option value="ASC">Ascendente</option>
										   </select>
										  <?}?>
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
            	$c = "SELECT id_relacionada FROM noticias_relacionadas WHERE tipo_elemento='".$tipo_elemento."' AND id_noticia=".$_GET['id'];
            	
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
								$p->target("index2.php?modulo=mod_relacionadas&q=".urlencode($palabra)."&id=".$_GET['id']."&q_seccion=".$seccion."&orden=".$orden."&tipo_orden=".$tipo_orden."&elemento=".$tipo_elemento);				
								$p->currentPage($page);
						?>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr class="titulos">
						  <td>Id</td>
						  <td></td>
						  <td>Titular</td>
						  <td>Sección</td>
						  <td align="right">Editar</td>
						</tr>
						<?
						$r = 0;
						$num_fila = 0;
						while($noticia = mysql_fetch_assoc($query)){
							$num_fila ++;
							$estilo = (($num_fila % 2) == 0) ? "fila_tabla_par" : "fila_tabla_impar";
							if ($tipo_elemento == "noticias")
								$nombre_seccion = mysql_fetch_array(mysql_query("SELECT titulo FROM secciones WHERE id=".$noticia['seccion'].""));
						?>
						<tr>
						  <td class="<?=$estilo?>"><?=$noticia['id']?></td>
						  <td class="<?=$estilo?>">
						  	  <? 
					             if ((isset($_GET['id'])) && ($_GET['id'] != "")){ 
					                 if (in_array($noticia['id'],$relacionadas)) 
					                 	echo "<a href='?modulo=mod_relacionadas&accion=estado&fns=1&id=".$_GET['id']."&id_relacionada=".$noticia['id']."&elemento=".$tipo_elemento."&page=".$page."&q=".urlencode($palabra)."&id=".$_GET['id']."&q_seccion=".$seccion."&orden=".$orden."&tipo_orden=".$tipo_orden."'><img src='images/tick.png' border='0'/></a>";
					                 else echo "<a href='?modulo=mod_relacionadas&accion=estado&fns=1&id=".$_GET['id']."&id_relacionada=".$noticia['id']."&elemento=".$tipo_elemento."&page=".$page."&q=".urlencode($palabra)."&id=".$_GET['id']."&q_seccion=".$seccion."&orden=".$orden."&tipo_orden=".$tipo_orden."'><img src='images/cross.png' border='0'/></a>";
					             }
					          ?>
						  </td>
						  <td class="<?=$estilo?>"><b><?=htmlentities($noticia[$atributo])?></b></td>
						  <td class="<?=$estilo?>"><?=($tipo_elemento == "noticias") ? $nombre_seccion['titulo'] : "";?></td>
						  <td class="<?=$estilo?>" align="right"><a href="?modulo=mod_<?=$tipo_elemento?>&accion=editar&id=<?=$noticia['id']?>"><img src="images/page_edit.png" border="0" /></a></td>
						</tr>
					    <?
					    if($r%2==0)++$r;else--$r;
					  }
					    ?>
					    </table>  
					    <?
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