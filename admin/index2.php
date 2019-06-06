<?php
session_start();

/* AQUI VAN LOS INCLUDES */
include ("../configuracion.php");
include ("includes/conexion.inc.php");
include ("includes/funciones.inc.php");
include ("includes/acceso.inc.php");
include ("clases/general.php");
es_valido();
$pagina = "";
/*
  Consigo el modulo correspondiente para obtener la ruta de los archivos.
  Por defecto carga la página de inicio del admin.
 */
if ($_GET['modulo'] == '') {
  $modulo = 'mod_index';
} else if (!tiene_permisos($_GET['modulo'], $_SESSION['permisos'])) {
  $modulo = 'mod_index';
} else {
  $modulo = $_GET['modulo'];
}
if (file_exists($config_path_absoluta . "/admin/modulos/" . $modulo . "/funciones.php"))
  require_once($config_path_absoluta . "/admin/modulos/" . $modulo . "/funciones.php");

/*
  Compruebo si estoy tratando de hacer una query y si no hay que hacerla muestro el html que corresponda
 */
if (isset($_GET['fns']) && $_GET['fns'] == 1) {
  $extra = (isset($_GET['extra'])) ? $_GET['extra'] : "";
  $funciones = new Funciones($_GET, $_POST, $extra);
  $funciones->start();
  //No se debe mostrar salida despues de esta función pues hay una redirección con header
} else {
  if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {
      case "nuevo":
        $pagina = "editar.php";
        break;
      case "editar":
        $pagina = "editar.php";
        break;
      case "nuevo_seccion":
        $pagina = "editar_planilla_seccion.php";
        break;
      case "editar_seccion":
        $pagina = "editar_planilla_seccion.php";
        break;
      case "eliminar":
        $pagina = "index2.php";
        break;
      default:
        $pagina = "index2.php";
        break;
    }
  } else {
    $pagina = "index.php";
  }
}
?>
<?php include("includes/header.inc.php"); ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <?php include("includes/titulo.inc.php"); ?>
  <tr>
    <td style="background-color:#666666"><?php include("includes/menu.inc.php"); ?></td>
  </tr>
  <?php
  if (file_exists($config_path_absoluta . "/admin/modulos/" . $modulo . "/" . $pagina)) {
    require_once($config_path_absoluta . "/admin/modulos/" . $modulo . "/" . $pagina);
  } else {
    echo "mostrar el modulo de p&aacute;gina no encontrada";
  }
  ?>
</table>
<?php include("includes/footer.inc.php"); ?>