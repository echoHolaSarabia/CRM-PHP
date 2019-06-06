<?
require('clases/pagination.class.php');
require('modulos/mod_comentarios/config.php');

//Parámetros necesarios para construir la consulta
if (isset($_GET['page']) && $_GET['page'] != "")
	$page = $_GET['page'];
else $page = 1;

if (isset($_GET['q']) && $_GET['q'] != "")
	$q = $_GET['q'];
else $q = "";

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

if (isset($_GET['tabla_comentado']))
	$tabla_comentado = $_GET['tabla_comentado'];
else
	$tabla_comentado = "";

if (isset($_GET['id_comentado']))
	$id_comentado = $_GET['id_comentado'];
else
	$id_comentado = "";





$funciones = new Funciones;
$funciones->get_query_palabra($tabla_comentado,$id_comentado,$page,$items,$q,$sqlStr,$sqlStrAux,$limit,$orden,$tipo_orden);

$r=mysql_query($sqlStrAux);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr.$limit);
?>
<script>
	function cambiar_estado(){
		document.form_listado_seleccion.action = "index2.php?modulo=mod_comentarios&fns=1&accion=cambiar_estado&tabla_comentado=<?=$tabla_comentado?>&id_comentado=<?=$id_comentado?>";
		document.form_listado_seleccion.submit();
	}
</script>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
      		  	<td class="botones_botonera"><a href="javascript:cambiar_estado();" class="enlaces_botones_botonera"><br /><img src="images/accept.png" border="0"><br />Cambiar estado</a></td>
				<td class="botones_botonera">&nbsp;&nbsp;&nbsp;</td>
      		  	<td class="botones_botonera"><a href="javascript:document.form_listado_seleccion.submit();" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador">
          	<table width="100%">
          		<tr>
          			<td>
          				<span class="titulo_seccion">Listado de comentarios</span>
          			</td>
          			<td align="right">
          			<form action="index2.php?modulo=mod_comentarios&tabla_comentado=<?=$tabla_comentado?>&id_comentado=<?=$id_comentado?>" method="GET">
          	  	  		<input type="hidden" name="modulo" value="mod_comentarios">
          	  	  			<table border="0">
          	  	    			<tr>
          	  	      				<td>
					      				<label>Palabra:</label> 
					      				<input type="text" id="q" name="q" value="<?php if(isset($q)) echo $q;?>"  size="50"><br><br>
					      				<label>Tipo comentado:</label> 
					      					<select name="tabla_comentado" id="tabla_comentado">
					      						<option value="" >Todos</option>
					      						<?
					      						foreach ($tablas_comentables as $tabla){
					      							?><option value="<?=$tabla?>" <?if (isset($tabla_comentado) && ($tabla_comentado==$tabla)) echo " Selected ";?>><?=$tabla?></option><?
					      						}
					      						?>
					      					</select>
					      				&nbsp;&nbsp;<input type="submit" value="Buscar" id="search">
					   
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
      	  <form name="form_listado_seleccion" method="post" action="index2.php?modulo=mod_comentarios&fns=1&accion=delete&tabla_comentado=<?=$tabla_comentado?>&id_comentado=<?=$id_comentado?>">
          <?php 
			if($aux['total']>0){
				$p = new pagination;
				$p->Items($aux['total']);
				$p->limit($items);
				if (isset($q))
					$p->target("index2.php?modulo=mod_comentarios&q=".urlencode($q));
				else $p->target("index2.php?modulo=mod_comentarios");						 	  								
				$p->currentPage($page);
		  ?>
              <div class="titulos">
                <div style="float:left; width:3%"><input type="checkbox" name="seleccion" onclick="checkAll(<?=($aux['total']+1)?>);" /></div>
                <div style="float:left; width:15%;">e-mail</div>
                <div style="float:left; width:10%;">IP</div>
                <div style="float:left; width:30%">Comentario</div>
                <div style="float:left; width:25%">Titulo</div>
                <div style="float:left; width:5%">Tipo</div>
                <div style="float:left; width:5%;text-align:right;">Edit/Act</div>
              </div>
    		  <div id="grupo_secciones" class="seccion" style="width:100%;">
			  <?
              $r = 0;
              $num_fila = 0;
              while ($fila = mysql_fetch_array($query)) {
                $num_fila ++;
                $noticia = $funciones->get_noticia ($fila['id_comentado'], $fila["tabla_comentado"]);
              ?>
                <div id="seccion_<?=$fila['id']?>" class="<? echo (($num_fila % 2) == 0) ? "fila_par" : "fila_impar"; ?>">
                  <div style="float:left;width:100%;">
                    <div style="float:left;width:3%"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$fila['id']?>" /></div>
                    <div style="float:left;width:15%;"><?=$fila['email']."&nbsp;&nbsp;&nbsp;"?></div>
                    <div style="float:left;width:10%;"><?=($fila['ip'] != "") ? $fila['ip'] : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";?></div>
                    <div style="float:left;width:30%"><b><?=substr(strip_tags($fila['texto']),0,150)?></b></div>
                    <div style="float:left;width:25%;"><?=substr(strip_tags($noticia['titulo']),0,50)."..."?><a href="index2.php?modulo=<? 
                    if ($fila["tabla_comentado"] == "noticias") echo "mod_noticias";
                    else if ($fila["tabla_comentado"] == "galeria_imagenes") echo "mod_galerias_imagenes";
                    else if ($fila["tabla_comentado"] == "galeria_videos") echo "mod_galerias_video";
                    ?>&accion=editar&id=<?=$noticia['id']?>" style="color:#000000">[Editar]</a></div>
                    <div style="float:left;width:5%;text-align:left;"><?=$fila["tabla_comentado"]?></div>
                    <div style="float:left;width:5%;text-align:center;">
                    	<a href="?modulo=mod_comentarios&accion=editar&id=<?=$fila['id']?>&tabla_comentado=<?=$tabla_comentado?>&id_comentado=<?=$id_comentado?>"><img src="images/page_edit.png" border="0" /></a>
                    	<a href="?modulo=mod_comentarios&fns=1&accion=publicar_uno&id=<?=$fila['id']?>&tabla_comentado=<?=$tabla_comentado?>&id_comentado=<?=$id_comentado?>">
                    		<img width="15px" src="<? if ($fila["publicado"]==1) echo "images/tick.png"; else echo "images/eliminar.png";?>" border="0" />
                    	</a>
                    </div>
                  </div>
                </div> 
              <? 
                if($r%2==0)++$r;else--$r;
              }
              echo "<div style='width:50px'>&nbsp</div>";
			  $p->show();
			} ?>
              </div>
          </form>
          </td>
        </tr>
        
      </table>
    </td>
</tr>