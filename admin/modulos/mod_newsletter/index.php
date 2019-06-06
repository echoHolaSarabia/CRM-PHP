<?php
require('clases/pagination.class.php');

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

if (isset($_GET['orden']) && $_GET['orden'] != "") {
  $orden = $_GET['orden'];
  $tipo_orden = $_GET['tipo_orden'];
} else {
  $orden = "id";
  $tipo_orden = "DESC";
}

$funciones = new Funciones;
$funciones->get_query_palabra($page, $items, $q, $sqlStr, $sqlStrAux, $limit, $orden, $tipo_orden);


$r = mysql_query($sqlStrAux);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr . $limit);
?>
<script type="text/javascript" src="/sitefiles/scripts/jquery-1.4.2.min.js"></script>
<script type="text/javascript" language="javascript">
  function popup(id){window.open("../newsletter/newsletter3/index.php?id="+id,"Preview","scrollbars=yes,width=800,height=800")}
  function enviar(estado, newlsetter){$.ajax({url:'/ajax/newsletter_estado.php',type:'POST',async:true,data:'estado='+estado+'&newsletter='+newlsetter,success:function(){location.reload();var t=setTimeout("location.reload()",10000)},error:function(){alert("No se ha podido cambiar el estado")}})}
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
                <form action="index2.php" method="GET">
                  <input type="hidden" name="modulo" value="mod_newsletter">
                  <table border="0">
                    <tr>
                      <td>
                        <label>Palabra:</label> <input type="text" id="q" name="q" value="<?php if (isset($q)) echo $q; ?>" size="50">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label>Ordenar por:</label>
                        <select name="orden">
                          <option value="nombre" <?php if ($orden == "nombre") echo "selected"; ?>>Nombre</option>
                          <option value="fecha_modificacion" <?php if ($orden == "fecha_modificacion") echo "selected"; ?>>Fecha</option>
                        </select>
                        <label> orden:</label>
                        <select name="tipo_orden">
                          <option value="ASC" <?php if ($tipo_orden == "ASC") echo "selected"; ?>>Ascendente</option>
                          <option value="DESC" <?php if ($tipo_orden == "DESC") echo "selected"; ?>>Descendente</option>
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
                  if ($aux['total'] and isset($q)) {
                    echo "{$aux['total']} Resultado" . ($aux['total'] > 1 ? 's' : '') . " que coinciden con tu b&uacute;squeda \"<strong>$q</strong>\".";
                  } elseif ($aux['total'] and !isset($q)) {
                    echo "Total de registros: {$aux['total']}";
                  } elseif (!$aux['total'] and isset($q)) {
                    echo"No hay registros que coincidan con tu b&uacute;squeda \"<strong>$q</strong>\"";
                  }
                  ?>
                </p>
                <?php
                if ($aux['total'] > 0) {
                  $p = new pagination;
                  $p->Items($aux['total']);
                  $p->limit($items);
                  if (isset($q))
                    $p->target("index2.php?modulo=mod_newsletter&q=" . urlencode($q));
                  else if (isset($_GET['q_seccion']) && ($_GET['q_seccion'] != ""))
                    $p->target("index2.php?modulo=mod_newsletter&q_seccion=" . $_GET['q_seccion']);
                  else if (isset($orden) && isset($tipo_orden))
                    $p->target("index2.php?modulo=mod_newsletter&orden=" . $orden . "&tipo_orden=" . $tipo_orden);
                  else
                    $p->target("index2.php?modulo=mod_newsletter");
                  $p->currentPage($page);
                  ?>
                  <tr class="titulos">
                    <td width="2%">Id</td>
                    <td><input type="checkbox" name="seleccion" onclick="checkAll('<?php echo ($aux['total'] + 1) ?>');" /></td>
                    <td>Nombre</td>
                    <td>Tipo newsletter</td>
                    <td>Fecha de envio</td>
                    <td align="center">Preparada</td>
                    <td align="center">Enviada</td>
                    <td align="right">Acciones</td>
                  </tr>
                  <?php
                  $r = 0;
                  $num_fila = 0;
                  while ($newsletter = mysql_fetch_assoc($query)) {
                    $num_fila++;
                    $datos_noticia = get_elemento_de_tabla("newsletters", $newsletter['id']);
                    if (($num_fila % 2) == 0)
                      $estilo = "fila_tabla_par";
                    else
                      $estilo = "fila_tabla_impar";
                    ?>
                    <tr>
                      <td class="<?php echo $estilo ?>"><?php echo $newsletter['id'] ?></td>
                      <td class="<?php echo $estilo ?>"><input type="checkbox" name="seleccion<?php echo $num_fila; ?>" value="<?php echo $newsletter['id'] ?>" /></td>
                      <td class="<?php echo $estilo ?>"><strong><?php echo htmlentities($newsletter['nombre']) ?></strong></td>
                      <td class="<?php echo $estilo ?>"><?php echo htmlentities($newsletter['tipo_newsletter']) ?></td>
                      <td class="<?php echo $estilo ?>"><?php echo date("H:i d-m-Y", $newsletter['hora_envio']); ?></td>
                      <td class="<?php echo $estilo ?>" align="center"><?php if ($newsletter['preparada'] == 0)
                      echo "<a href='?modulo=mod_newsletter&accion=estado&campo=preparada&fns=1&id=" . $newsletter['id'] . "&page=" . $page . "'><img src='images/cross.png' border='0'/></a>"; else
                      echo "<a href='?modulo=mod_newsletter&accion=estado&campo=preparada&fns=1&id=" . $newsletter['id'] . "&page=" . $page . "'><img src='images/tick.png' border='0'/></a>"; ?></td>
                      <td class="<?php echo $estilo ?>" align="center">
                    <?php
//			                if ($newsletter['enviada'] == 0) echo "<img src='images/cross.png' border='0' onclick='enviar(false," . $newsletter['id'] . ");'/>";
                    if ($newsletter['enviada'] == 0)
                      echo "<a href='/ajax/newsletter_estado.php?estado=false&newsletter=" . $newsletter['id'] . "'><img src='images/cross.png' border='0'/></a>";
//			                else echo "<img src='images/tick.png' border='0' onclick='enviar(false," . $newsletter['id'] . ");'/>";
                    else
                      echo "<a href='/ajax/newsletter_estado.php?estado=true&newsletter=" . $newsletter['id'] . "'><img src='images/tick.png' border='0'/></a>";
                    ?>
                      </td>
                      <td class="<?php echo $estilo ?>" align="right"><img src="images/preview_peque.png" border="0" alt="Previsualizar" title="Previsualizar" onclick="popup(<?php echo $newsletter['id'] ?>);" style="cursor:pointer"/><a href="?modulo=mod_newsletter&accion=editar&id=<?php echo $newsletter['id'] ?>"><img src="images/page_edit.png" alt="Editar" title="Editar" border="0" /></a></td>
                    </tr>
    <?php
    if ($r % 2 == 0)
      ++$r;
      else--$r;
  }
  ?>
                  <tr>
                    <td colspan='9'><?php echo $p->show() ?></td>
                  </tr>
  <?php } ?>
              </table>
            </div>
          </form>
        </td>
      </tr>
    </table>
  </td>
</tr>