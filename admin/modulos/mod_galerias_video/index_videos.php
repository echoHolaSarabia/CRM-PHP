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

if (isset($_GET['no_publicadas']) && $_GET['no_publicadas'] == "ok")
	$no_publicadas = "ok";
else $no_publicadas = "";

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

$funciones = new Funciones();
$funciones->get_query_palabra($page,$items,$q,$seccion,$sqlStr,$sqlStrAux,$limit,$orden,$tipo_orden,$no_publicadas);
$r = mysql_query($sqlStrAux);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr.$limit);
?>

<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_galerias_video&accion=nuevo" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nuevo</a></td>
                <td class="botones_botonera"><a href="javascript:document.form_listado_seleccion.submit();" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Listado de videos</span></td>
        </tr>
        <tr>
          <td class="contenido">
      	  <form name="form_listado_seleccion" method="post" action="index2.php?modulo=mod_galerias_video&fns=1&accion=delete">
              <div class="titulos">
                <div style="float:left; width:3%">Id</div>
                <div style="float:left; width:3%"><input type="checkbox" name="seleccion" onclick="checkAll(<?=$num_filas+1?>);" /></div>
                <div style="float:left; width:45%">Titular</div>
                <div style="float:left; width:10%;text-align:center">Publicado</div>
                <div style="float:left; width:19%;text-align:center">Sección</div>
                <div style="float:left; width:10%;text-align:right;">Editar</div>
              </div>
    		  <div id="grupo_secciones" class="seccion" style="width:100%;">
			  <?
              $num_fila = 0;
               while ($fila = mysql_fetch_array($query)) {
                $num_fila ++;
              ?>
                <div id="<?=$fila['id']?>" class="<? echo (($num_fila % 2) == 0) ? "fila_par" : "fila_impar"; ?>">
                  <div style="float:left;width:100%;">
                    <div style="float:left;width:3%"><?=$fila['id']?></div>
                    <div style="float:left;width:3%"><input type="checkbox" name="seleccion<?=$num_fila;?>" value="<?=$fila['id']?>" /></div>
                    <div style="float:left;width:45%"><strong><?=stripslashes($fila['titulo'])?></strong></div>
                    <div style="float:left;width:10%;text-align:center"><? if ($fila['activo'] == 0) echo "<a href='?modulo=mod_galerias_video&accion=estado&campo=activo&fns=1&id=".$fila['id']."'><img src='images/cross.png' border='0'/></a>"; else echo "<a href='?modulo=mod_galerias_video&accion=estado&campo=activo&&fns=1&id=".$fila['id']."'><img src='images/tick.png' border='0'/></a>";?></div>
                    <div style="float:left;width:19%;text-align:center"><?=$funciones->get_nombre_seccion($fila['seccion'])?></div>
                    <div style="float:left;width:10%;text-align:right;"><a href="?modulo=mod_galerias_video&accion=editar&id=<?=$fila['id']?>"><img src="images/page_edit.png" border="0" /></a></div>
                  </div>
                </div> 
              <? } ?>
              </div>
          </form>
          </td>
        </tr>
      </table>
    </td>
</tr>