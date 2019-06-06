<?php
include_once("../../../../configuracion.php");
include_once("../../../includes/conexion.inc.php");

if ($_POST['id_seccion'] != "nada") {

  if ($_POST['id_seccion'] < 0)
    $_POST['id_seccion'] = -$_POST['id_seccion'];

  if (isset($_POST['id_seccion']) && ($_POST['id_seccion'] != "")) {
    $c = "SELECT id_padre FROM secciones WHERE id=" . $_POST['id_seccion'];
    $r = mysql_query($c);
    $r = mysql_fetch_assoc($r);
    if ($r["id_padre"] == -1) {
      // Sección
      $id_seccion = $_POST['id_seccion'];
      $id_subseccion = -1;
    } else {
      // Subsección
      $id_seccion = $r["id_padre"];
      $id_subseccion = $_POST['id_seccion'];
    }
  }


  $c = "SELECT id,fecha_publicacion FROM planillas WHERE seccion=" . $id_seccion . " ORDER BY fecha_publicacion DESC";
  $r = mysql_query($c);
  if (mysql_num_rows($r)) {
    $enc = 0;
    $selected = "";
    ?><optgroup label="Secci&oacute;n"><?php
    while ($fila = mysql_fetch_assoc($r)) {
      if ($enc == 0) {
        $fecha = explode(" ", $fila['fecha_publicacion']);
        $hora = $fecha[1];
        $fecha = $fecha[0];
        $fecha = explode("-", $fecha);
        $hora = explode(":", $hora);
        if (mktime($hora[0], $hora[1], $hora[2], $fecha[1], $fecha[2], $fecha[0]) < time()) {
          $enc = 1;
          $selected = " SELECTED ";
        }
      }
      else
        $selected = "";
      ?>
        <option value="<?php echo $fila['id'] ?>" <?php echo $selected ?> ><?php echo $fila['id'] ?> - <?php echo $fila['fecha_publicacion'] ?></option>
        <?php
      }
      ?></optgroup><?php
  }


  if ($id_subseccion != -1) {
    $c = "SELECT id,fecha_publicacion FROM planillas WHERE seccion=" . $id_subseccion . " ORDER BY fecha_publicacion DESC";
    $r = mysql_query($c);
    if (mysql_num_rows($r)) {
      $enc = 0;
      $selected = "";
        ?><optgroup label="Subsecci&oacute;n"><?php
      while ($fila = mysql_fetch_assoc($r)) {
        if ($enc == 0) {
          $fecha = explode(" ", $fila['fecha_publicacion']);
          $hora = $fecha[1];
          $fecha = $fecha[0];
          $fecha = explode("-", $fecha);
          $hora = explode(":", $hora);
          if (mktime($hora[0], $hora[1], $hora[2], $fecha[1], $fecha[2], $fecha[0]) < time()) {
            $enc = 1;
            $selected = " SELECTED ";
          }
        }
        else
          $selected = "";
          ?>
          <option value="<?php echo $fila['id'] ?>" <?php echo $selected ?> ><?php echo $fila['id'] ?> - <?php echo $fila['fecha_publicacion'] ?></option>
          <?php
        }
        ?></optgroup><?php
    }
  }
}
else
  echo "";