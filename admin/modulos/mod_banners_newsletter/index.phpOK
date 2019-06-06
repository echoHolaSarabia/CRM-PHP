<?php
//require('clases/pagination.class.php');
//Parámetros necesarios para construir la consulta
/* if (isset($_GET['page']) && $_GET['page'] != "")
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

  $funciones = new Funciones;
  $funciones->get_query_palabra($page,$items,$q,$sqlStr,$sqlStrAux,$limit,$orden,$tipo_orden);


  $r = mysql_query($sqlStrAux);
  $aux = mysql_fetch_assoc($r);
  $query = mysql_query($sqlStr.$limit); */
$c_aux = "SELECT count(*) FROM banners_newsletter";
$query_aux = mysql_query($c_aux);
$num_resultados = mysql_fetch_row($query_aux);

$c = "SELECT * FROM banners_newsletter";
$query = mysql_query($c);
?>

<script type="text/javascript" language="javascript">
  function popup (id) {
    window.open("../newsletter/newsletter.php?id_newsletter="+id,"Preview","width=800,height=600");
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
              <td class="botones_botonera"><a href="index2.php?modulo=mod_banners_newsletter&accion=nuevo" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br />Nuevo</a></td>
              <td class="botones_botonera"><a href="javascript:haz_submit('form_listado_seleccion','index2.php?modulo=mod_banners_newsletter&fns=1&accion=delete');" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table border="0" width="100%">
            <tr>
              <td class="buscador"><span class="titulo_seccion">Listado de banners para newsletters</span></td>
              <td align="right">
                <!--<form action="index2.php" method="GET">
                  <input type="hidden" name="modulo" value="mod_banners_newsletter">
                  <table border="0">
                    <tr>
                      <td>
                                      <label>Palabra:</label> <input type="text" id="q" name="q" value="<?php if (isset($q))
  echo $q; ?>" size="50">
                      </td>
                    </tr>
                  </table>
                </form>-->
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
                <tr class="titulos">
                  <td width="2%">Id</td>
                  <td><input type="checkbox" name="seleccion" onclick="checkAll('<?php echo $num_resultados[0] + 1 ?>');" /></td>
                  <td>Nombre</div>
                  <td>Tipo</td>
                  <td>Banner</td>
                  <td align="right">Editar</td>
                </tr>
                <?php
                $r = 0;
                $num_fila = 0;
                while ($newsletter = mysql_fetch_assoc($query)) {
                  $num_fila++;
                  if (($num_fila % 2) == 0)
                    $estilo = "fila_tabla_par";
                  else
                    $estilo = "fila_tabla_impar";
                  ?>
                  <tr>
                    <td class="<?php echo $estilo ?>"><?php echo $newsletter['id'] ?></td>
                    <td class="<?php echo $estilo ?>"><input type="checkbox" name="seleccion<?php echo $num_fila; ?>" value="<?php echo $newsletter['id'] ?>" /></td>
                    <td class="<?php echo $estilo ?>"><strong><?php echo htmlentities($newsletter['titulo']) ?></strong></td>
                    <td class="<?php echo $estilo ?>"><strong><?php echo $newsletter['tipo'] ?></strong></td>
                    <td class="<?php echo $estilo ?>">
                      <?php if ($newsletter['fuente'] == "imagen") { ?>
                        <img src="../userfiles/banners/<?php echo $newsletter['imagen'] ?>" >
                      <?php } else { ?>
                        <?php echo $newsletter['imagen'] ?>
  <?php } ?>
                    </td>
                    <td class="<?php echo $estilo ?>" align="center"><a href="?modulo=mod_banners_newsletter&accion=editar&id=<?php echo $newsletter['id'] ?>"><img src="images/page_edit.png" border="0" /></a></td>
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