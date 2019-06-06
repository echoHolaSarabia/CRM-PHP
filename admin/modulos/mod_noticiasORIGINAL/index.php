<?php
require('clases/pagination.class.php');
//ParÃ¡metros necesarios para construir la consulta
if (isset($_GET['page']) && $_GET['page'] != "")
  $page = $_GET['page'];
else
  $page = 1;

if (isset($_GET['q']) && $_GET['q'] != "")
  $q = $_GET['q'];
else
  $q = "";

if (isset($_GET['q_seccion']) && $_GET['q_seccion'] != "")
  $seccion = $_GET['q_seccion'];
else
  $seccion = "";

if (isset($_GET['no_publicadas']) && $_GET['no_publicadas'] == "ok")
  $no_publicadas = "ok";
else
  $no_publicadas = "";

if (isset($_GET['orden']) && $_GET['orden'] != "") {
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

$funciones = new Funciones;
$url_extra = "";
//Este parámetro se usa para cambiar la tabla donde consulta la query. Por defecto es noticias pero si le paso el parámetro "extra", su contenido será
//la tabla que se consulte.
if (isset($_GET['extra']) && $_GET['extra'] != "") {
  $funciones->tabla = $_GET['extra'];
  $url_extra = "&extra=" . $_GET['extra'];
}
else
  $url_extra = "";

$funciones->get_query_palabra($page, $items, $q, $seccion, $sqlStr, $sqlStrAux, $limit, $orden, $tipo_orden, $no_publicadas);

//echo $sqlStrAux;

$r = mysql_query($sqlStrAux);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr . $limit);
?>
<style type="text/css">
  /* Easy CSS Tooltip */
  a:hover {text-decoration:none;} /*BG color is a must for IE6*/
  a.tooltip{color:#000000;}
  a.tooltip span {display:none; padding:2px 3px; margin-left:8px; width:200px;}
  a.tooltip:hover span{display:inline; position:absolute; border:1px solid #cccccc; background:#ffffff; color:#6c6c6c;}
</style>
<script type="text/javascript" language="javascript">
  function actualizar_listado(id_noticia){
    titular = document.getElementById("titular_alt_"+id_noticia).value;
    antetitulo = document.getElementById("antetitulo_alt_"+id_noticia).value;
    entradilla = document.getElementById("entradilla_alt_"+id_noticia).value;
    document.location.href='index2.php?modulo=mod_noticias&accion=update_listado&fns=1&id='+id_noticia+'&titular_alt='+titular+'&entradilla_alt='+entradilla+'&antetitulo_alt='+antetitulo;
  }

  function popup (id) {
    window.open("../preview.php?id="+id,"Preview","width=1015,height=750,toolbar=no,directories=no,menubar=no,status=no,scrollbars=yes");
  }

  function pdf (id) {
    window.open("modulos/mod_noticias/pdf.php?id="+id,"PDF","width=800,height=600,toolbar=yes,directories=yes,menubar=yes,status=yes,scrolling=yes");
  }

  function eliminar_noticias (){
    if (confirm("¿Desea eliminar las noticias seleccionadas?"))
      haz_submit('form_listado_seleccion','index2.php?modulo=mod_noticias&fns=1&accion=delete<?php echo $url_extra ?>');
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
              <td class="botones_botonera"><a href="javascript:haz_submit('form_listado_seleccion','index2.php?modulo=mod_noticias&fns=1&accion=newsletter<?php echo $url_extra ?>');" class="enlaces_botones_botonera"><br /><img src="images/exp_newsletter.jpg" border="0"><br />Newsletter</a></td>
              <td class="botones_botonera"><a href="javascript:haz_submit('form_listado_seleccion','index2.php?modulo=mod_noticias&fns=1&accion=duplicar');" class="enlaces_botones_botonera"><br /><img src="images/duplicar2.png" border="0"><br />Duplicar</a></td>
              <td class="botones_botonera"><a href="index2.php?modulo=mod_noticias&accion=nuevo<?php echo $url_extra ?>" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nuevo</a></td>
              <td class="botones_botonera"><a href="javascript:eliminar_noticias();" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table border="0" width="100%">
            <tr>
              <td class="buscador"><span class="titulo_seccion">Listado de art&iacute;culos</span></td>
              <td align="right">
                <form action="index2.php" method="GET">
                  <input type="hidden" name="modulo" value="mod_noticias">
                  <input type="hidden" name="extra" value="<?php echo $funciones->tabla ?>">
                  <table border="0">
                    <tr>
                      <td>
                        <label>Palabra:</label> <input type="text" id="q" name="q" value="<?php if (isset($q))
  echo $q; ?>"  size="50">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label>Secci&oacute;n:</label>
                        <select name="q_seccion">
                          <option value="">Todas</option>
                          <?php
                          $r_secciones = $funciones->get_all_secciones();
                          //$excluir = array(231,232,244,240);
                          $excluir = array();
                          foreach ($r_secciones as $sec) {
                            if (!in_array($sec['id'], $excluir)) {
                              ?>
                              <option value="<?php echo $sec['id'] ?>" <?php if (array_key_exists('q_seccion', $_GET) && $_GET['q_seccion'] == $sec['id'])
                                echo "selected"; ?>><?php echo $sec['titulo'] ?></option>
    <?php
  }
}
?>
                        </select><input type="checkbox" name="no_publicadas" value="ok" <?php if ($no_publicadas == "ok")
  echo "checked"; ?>>No publicadas
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label>Ordenar por:</label>
                        <select name="orden">
                          <option value="titulo" <?php if ($orden == "titulo")
  echo "selected"; ?>>Titular</option>
                          <option value="fecha_publicacion" <?php if ($orden == "fecha_publicacion")
  echo "selected"; ?>>Fecha</option>
                          <option value="seccion" <?php if ($orden == "seccion")
  echo "selected"; ?>>Seccion</option>
                          <option value="autor" <?php if ($orden == "autor")
  echo "selected"; ?>>Autor</option>
                        </select>
                        <label> orden:</label>
                        <select name="tipo_orden">
                          <option value="ASC" <?php if ($tipo_orden == "ASC")
  echo "selected"; ?>>Ascendente</option>
                          <option value="DESC" <?php if ($tipo_orden == "DESC")
  echo "selected"; ?>>Descendente</option>
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
                  echo"No hay registros que coincidan con tu b&uacute;squeda \"<strong>" . $q . "</strong>\"";
                }
                ?>
                </p>
<?php
if ($aux['total'] > 0) {
  $p = new pagination;
  $p->Items($aux['total']);
  $p->limit($items);
  if (isset($q))
    $p->target("index2.php?modulo=mod_noticias&q=" . urlencode($q) . "&q_seccion=" . $seccion . "&orden=" . $orden . "&tipo_orden=" . $tipo_orden);
  else if (isset($_GET['q_seccion']) && ($_GET['q_seccion'] != ""))
    $p->target("index2.php?modulo=mod_noticias&q_seccion=" . $_GET['q_seccion']);
  else if (isset($orden) && isset($tipo_orden))
    $p->target("index2.php?modulo=mod_noticias&orden=" . $orden . "&tipo_orden=" . $tipo_orden);
  else
    $p->target("index2.php?modulo=mod_noticias");
  $p->currentPage($page);
  ?>
                  <tr class="titulos">
                    <td width="5%">Id</td>
                    <td><input type="checkbox" name="seleccion" onclick="checkAll('<?php echo ($aux['total'] + 1) ?>');" /></td>
                    <td>Titular</div>
                    <td align="center">Publicaci&oacute;n</td>
                    <td align="center">Publicado</td>
                    <td>Seccion</td>
                    <td align="center">Comentarios(P/T)</td>
                    <td align="right">Acciones</td>
                  </tr>
                  <?php
                  $num_fila = 0;
                  while ($noticia = mysql_fetch_assoc($query)) {
                    $num_fila++;

                    $q_num_comentarios = "SELECT count(*) as c FROM comentarios WHERE id_comentado=" . $noticia['id'] . " AND tabla_comentado='noticias'";
                    $num_comentarios_total = mysql_fetch_assoc(mysql_query($q_num_comentarios));

                    $fecha = explode(" ", $noticia['fecha_publicacion']);
                    $hora = $fecha[1];
                    $fecha = $fecha[0];
                    $fecha = explode("-", $fecha);
                    $hora = explode(":", $hora);
                    if (mktime($hora[0], $hora[1], $hora[2], $fecha[1], $fecha[2], $fecha[0]) > time())
                      $estilo = "fila_tabla_planificada";
                    else if ($noticia['recurso_maquetacion'] != "")
                      $estilo = "fila_destacado";
                    else if (($num_fila % 2) == 0)
                      $estilo = "fila_tabla_par";
                    else
                      $estilo = "fila_tabla_impar";

                    $nombre_seccion = mysql_fetch_array(mysql_query("SELECT titulo FROM secciones WHERE id=" . $noticia['seccion'] . ""));
                    $datos_noticia = get_elemento_de_tabla("noticias", $noticia['id']);
                    ?>
                    <tr>
                      <td class="<?php echo $estilo ?>"><?php echo $noticia['id'] ?><img id="mas_menos_<?php echo $noticia['id'] ?>" src="images/mas.png" onclick="muestra_oculta('noticia_',<?php echo $noticia['id'] ?>)" style="cursor:pointer;"></td>
                      <td class="<?php echo $estilo ?>"><input type="checkbox" name="seleccion<?php echo $num_fila; ?>" value="<?php echo $noticia['id'] ?>" /></td>
                      <td class="<?php echo $estilo ?>"><strong><a href="?modulo=mod_noticias&accion=editar&id=<?php echo $noticia['id'] ?><?php echo $url_extra ?>" style="color:#000000;text-decoration:none"><?php echo htmlentities($noticia['titulo']) ?></a></strong></td>
                      <td class="<?php echo $estilo ?>" align="center"><a href="#" class="tooltip">+info<span>Seccion: <?php echo $funciones->get_nombre_seccion($noticia['seccion']) ?><br>Subseccion: <?php echo $noticia['subseccion'] ?><br>Hits: <?php echo $noticia['hits'] ?><br>Recurso de maquetacion: <?php echo $noticia['recurso_maquetacion'] ?><br>Fecha de creaci&oacute;n: <?php echo $noticia['fecha_creacion'] ?><br>Autor: <?php echo $noticia['autor'] ?></span></a></td>
                      <td class="<?php echo $estilo ?>" align="center"><?php if (isset($noticia) && $noticia['activo'] == 0)
                  echo "<a href='?modulo=mod_noticias&accion=estado&campo=activo&fns=1&id=" . $noticia['id'] . "&page=" . $page . $url_extra . "'><img src='images/cross.png' border='0'/></a>"; else
                  echo "<a href='?modulo=mod_noticias&accion=estado&campo=activo&fns=1&id=" . $noticia['id'] . "&page=" . $page . $url_extra . "'><img src='images/tick.png' border='0'/></a>"; ?></td>
                      <td class="<?php echo $estilo ?>"><?php echo $nombre_seccion['titulo'] ?></td>
                      <td class="<?php echo $estilo ?>" align="center"><a href="index2.php?modulo=mod_comentarios&q=&tabla_comentado=<?php echo $funciones->tabla ?>&id_comentado=<?php echo $noticia["id"] ?>"><?php echo $noticia["num_comentarios"] ?>/<?php echo $num_comentarios_total["c"] ?></a></td>
                      <td class="<?php echo $estilo ?>" align="right"><a href="?modulo=mod_noticias&accion=editar&id=<?php echo $noticia['id'] ?><?php echo $url_extra ?>"><img src="images/page_edit.png" border="0" /></a></td>
                    </tr>
                    <tr id="noticia_<?php echo $noticia['id'] ?>" style="display:none;margin-bottom:5px;">
                      <td colspan="9" class="<?php echo $estilo ?>">
                        Titular alternativo:<br />
                        <input type="text" id="titular_alt_<?php echo $noticia['id'] ?>" value="<?php echo $noticia['titular_alt'] ?>" style="width:50%"><br />
                        Antetitulo alternativo:<br />
                        <input type="text" id="antetitulo_alt_<?php echo $noticia['id'] ?>" value="<?php echo $noticia['antetitulo_alt'] ?>" style="width:50%"><br />
                        Entradilla alternativa:<br />
                        <input type="text" id="entradilla_alt_<?php echo $noticia['id'] ?>" value="<?php echo $noticia['entradilla_alt'] ?>" style="width:50%"><input type="button" value="Guardar" onclick="actualizar_listado(<?php echo $noticia['id'] ?>);" style="margin-left:5px;"">
                      </td>
                    </tr>
    <?php
  }
  ?>
                  <tr>
                    <td colspan='9'><?php echo $p->show() ?></td>
                  </tr>
  <?php
}
?>
              </table>
            </div>
          </form>
        </td>
      </tr>
    </table>
  </td>
</tr>