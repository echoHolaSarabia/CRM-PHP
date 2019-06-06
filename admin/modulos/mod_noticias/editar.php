<?php
include("modulos/mod_noticias/conf.php");
$funciones = new Funciones;
$num_pestanias = 5;
$url_extra = "";
//Este par�metro se usa para cambiar la tabla donde consulta la query. Por defecto es noticias pero si le paso el par�metro "extra", su contenido ser�
//la tabla que se consulte.
if (isset($_GET['extra'])) {
  $funciones->tabla = $_GET['extra'];
  $num_pestanias = $num_pestanias + 1;
  $url_extra = "&extra=" . $_GET['extra'];
}

if (isset($_GET['id']) && $_GET['id'] != "") {
  $r = mysql_query("SELECT * FROM " . $funciones->tabla . " WHERE id = " . $_GET['id']);
  $noticia = mysql_fetch_array($r);
  $accion = "update&id=" . $noticia['id'];
  $autor = $noticia['autor'];
} else {
  $accion = "insert";
  $autor = $_SESSION['nombre'];
}
?>

<script type="text/javascript" src="scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="modulos/mod_noticias/scripts/scripts.js"></script>
<script type="text/javascript" src="modulos/mod_noticias/scripts/tiny_mce.js"></script>
<script type="text/javascript" src="modulos/mod_noticias/upload/ajaxfileupload.js"></script>

<tr>
  <td width="100%">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="botonera" align="right">
          <table border="0" cellpadding="2" cellspacing="0" >
            <tr>
              <td class="botones_botonera"><a href="#" onclick="guardar_y_volver('document.form_noticia','<?php echo $accion ?>','<?php echo $url_extra ?>')" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
              <td class="botones_botonera"><a href="#" onclick="valida_datos('document.form_noticia')" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar y salir</a></td>
              <td class="botones_botonera"><a href="index2.php?modulo=mod_noticias<?php echo $url_extra ?>" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="buscador"><span class="titulo_seccion">Edicion de noticia</span><?php echo ((isset($_GET['id']) && $_GET['id'] != "") ? "Fecha de modificaci&oacute;n: " . $noticia['fecha_modificacion'] : ""); ?></td>
      </tr>
      <tr>
        <td>
          <form name="form_noticia" method="post" action="?modulo=mod_noticias&fns=1&accion=<?php echo $accion ?><?php echo $url_extra ?>" onsubmit="return valida_datos(this);" enctype="multipart/form-data">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td width="55%" class="contenido" valign="top">
                  <?php include ("modulos/mod_noticias/includes/redaccion.php"); ?>
                </td>
                <td width="45%" class="contenido" valign="top">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td>
                        <div id="menu_auxiliar">
                          <ul>
                            <li><a href="#" onClick="cambia_opciones(1,<?php echo $num_pestanias ?>);">Publicaci&oacute;n</a></li>
                            <li><a href="#" onClick="cambia_opciones(2,<?php echo $num_pestanias ?>);">Audiovisual</a></li>
                            <li><a href="#" onClick="cambia_opciones(3,<?php echo $num_pestanias ?>);">Archivos</a></li>
                            <li><a href="#" onClick="cambia_opciones(4,<?php echo $num_pestanias ?>);">Relacionar</a></li>
                            <li><a href="#" onClick="cambia_opciones(5,<?php echo $num_pestanias ?>);">Maquetaci�n</a></li>
                            <?php if (isset($_GET['extra'])) { ?>
                              <li><a href="#" onClick="cambia_opciones(6,<?php echo $num_pestanias ?>);">Extra</a></li>
                            <?php } ?>
                          </ul>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div id="opcion1" style="display:block;">
                          <?php include ("modulos/mod_noticias/includes/publicacion.php"); ?>
                        </div>
                        <div id="opcion2" style="display:none">
                          <?php include ("modulos/mod_noticias/includes/audiovisual.php"); ?>
                        </div>
                        <div id="opcion3" style="display:none">
                          <?php include ("modulos/mod_noticias/includes/archivos.php"); ?>
                        </div>
                        <div id="opcion4" style="display:none">
                          <?php include ("modulos/mod_noticias/includes/relacionadas.php"); ?>
                        </div>
                        <div id="opcion5" style="display:none">
                          <?php include ("modulos/mod_noticias/includes/maquetacion.php"); ?>
                        </div>
                        <?php if (isset($_GET['extra'])) { ?>
                          <div id="opcion6" style="display:none">
                            <?php include ("modulos/mod_noticias/extras/" . $_GET['extra'] . ".php"); ?>
                          </div>
                        <?php } ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>
  </td>
</tr>