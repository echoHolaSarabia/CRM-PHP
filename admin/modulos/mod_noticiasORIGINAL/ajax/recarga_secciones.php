<?php
include_once("../../../../configuracion.php");
include_once("../../../includes/conexion.inc.php");
if (isset($_POST['id_seccion']) && $_POST['id_seccion'] != "") {
  /* 	if($_POST["id_seccion"]==4)
    $c = "SELECT id,titulo FROM autores_opinion ";
    else */
  $c = "SELECT id,titulo FROM secciones WHERE id_padre=" . $_POST['id_seccion'];
  //echo '*****************'.$c;
  $r = mysql_query($c);
  if (mysql_num_rows($r) > 0) {
    ?><option value="-<?php echo $_POST['id_seccion'] ?>">Elige una...</option><?php
    while ($fila = mysql_fetch_assoc($r)) {
      ?>
      <option value="<?php echo $fila['id'] ?>"><?php echo htmlentities($fila['titulo']) ?></option>
      <?php
    }
  }
}?>