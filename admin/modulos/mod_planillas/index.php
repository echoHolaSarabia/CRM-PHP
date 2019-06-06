<?php
if (isset($_GET['newsletter']) && $_GET['newsletter'] == "si") {
  include ("modulos/mod_planillas/editar_planilla_registro.php");
  exit;
}

if (isset($_GET['ampliada']) && $_GET['ampliada'] == "si") {
  include ("modulos/mod_planillas/editar_planilla_ampliada.php");
  exit;
}

require('clases/pagination.class.php');

$id_seccion = (array_key_exists("seccion", $_GET) ? $_GET['seccion'] : 1);

//Parámetros necesarios para construir la consulta
if (isset($_GET['page']) && $_GET['page'] != "")
  $page = $_GET['page'];
else
  $page = 1;

if (isset($_GET['q']) && $_GET['q'] != "")
  $q = $_GET['q'];
else
  $q = "";

$items = $config_registros_paginador;
$sqlStr = "";
$sqlStrAux = "";
$limit = "";


$funciones = new Funciones;

if ($id_seccion == 1000) {
  $nombre_seccion = "Corporativa";
} else {
  $nombre_seccion = $funciones->get_nombre_seccion($id_seccion);
}

$funciones->get_query_palabra($page, $items, $q, $sqlStr, $sqlStrAux, $limit, $id_seccion);
$r = mysql_query($sqlStrAux);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr . $limit);
?>
<tr>
  <td width="100%">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="botonera" align="right">
          <table border="0" cellpadding="0" cellspacing="0" >
            <tr>
<!--      		  	<td class="botones_botonera"><a href="javascript:heredar();" class="enlaces_botones_botonera"><br /><img src="images/desde.png" border="0"><br />Crear desde...</a></td>-->
              <td class="botones_botonera"><a href="index2.php?modulo=mod_planillas&accion=<?php echo ($id_seccion == 1) ? "nuevo" : "nuevo_seccion" ?>&seccion=<?php echo $id_seccion ?>" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nuevo</a></td>
              <td class="botones_botonera"><a href="javascript:document.form_listado_seleccion.submit();" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="buscador"><img src="images/document.png" align="absmiddle" width="70px" alt="Panilla" title="Panilla"><span class="titulo_seccion">Planillas de <?php echo $nombre_seccion ?></span></td>
      </tr>
      <tr>
        <td class="contenido">
          <form name="form_listado_seleccion" method="post" action="index2.php?modulo=mod_planillas&fns=1&accion=delete">
            <?php
            if ($aux['total'] > 0) {
              $p = new pagination;
              $p->Items($aux['total']);
              $p->limit($items);
              if (isset($q))
                $p->target("index2.php?modulo=mod_planillas&q=" . urlencode($q));
              else
                $p->target("index2.php?modulo=mod_planillas");
              $p->currentPage($page);
              ?>
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr class="titulos">
                  <td width="20px"><input type="checkbox" name="seleccion" onclick="checkAll(<?php echo ($aux['total'] + 1) ?>);" /></td>
                  <td>Publicación</td>
                  <td width="150px">Editar</td>
                </tr>
                <?php
                $r = 0;
                $num_fila = 0;
                $mostrada_la_actual = false;
                while ($fila = mysql_fetch_array($query)) {
                  $num_fila++;
                  $fecha = explode(" ", $fila['fecha_publicacion']);
                  $hora = $fecha[1];
                  $fecha = $fecha[0];
                  $fecha = explode("-", $fecha);
                  $hora = explode(":", $hora);
                  if (mktime($hora[0], $hora[1], $hora[2], $fecha[1], $fecha[2], $fecha[0]) > time())
                    $estilo = "fila_tabla_planificada";
                  else {
                    if (!$mostrada_la_actual) {
                      $estilo = "fila_tabla_par";
                      $mostrada_la_actual = true;
                    } else {
                      $estilo = "fila_tabla_no_publicada";
                    }
                  }
                  ?>
                  <tr>
                    <td class="<?php echo $estilo ?>"><input type="checkbox" name="seleccion<?php echo $num_fila; ?>" value="<?php echo $fila['id'] ?>" /></td>
                    <td class="<?php echo $estilo ?>"><b>Planilla programada para:</b> <?php $funciones->muestra_fecha($fila['fecha_publicacion']); ?> <b>y creada el:</b> <?php $funciones->muestra_fecha($fila['fecha_creacion']); ?></td>
                    <td class="<?php echo $estilo ?>" align="right">
                      <a href="#" onclick="window.open('modulos/mod_planillas/previsualizar.php?id=<?php echo $fila['id'] ?>','prev','location=1,status=1,scrollbars=1,width=950,height=800')"><img src="images/preview_peque.png" border="0" title="Previsualizar planilla" /></a>
                      <a href="?modulo=mod_planillas&accion=<?php echo ($id_seccion == 1) ? "nuevo" : "nuevo_seccion" ?>&heredar=ok&id=<?php echo $fila['id'] ?>&seccion=<?php echo $id_seccion ?>"><img src="images/desde.png" border="0" title="Crear desde..." /></a>
                      <a href="?modulo=mod_planillas&accion=<?php echo ($id_seccion == 1) ? "editar" : "editar_seccion" ?>&id=<?php echo $fila['id'] ?>&seccion=<?php echo $id_seccion ?>"><img src="images/page_edit.png" border="0" title="Editar" /></a>
                    </td>
                  </tr>
                  <?php
                  if ($r % 2 == 0) ++$r;
                  else --$r;
                }
                ?>
                <tr>
                  <td colspan='9'><?php echo $p->show() ?></td>
                </tr>
                <?php
                $p->show();
              }
              ?>
            </table>
          </form>
        </td>
      </tr>
    </table>
  </td>
</tr>