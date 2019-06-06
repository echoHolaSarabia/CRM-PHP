<?php
if (isset($_GET['mensaje']) && $_GET['mensaje'] == "si") {
  echo "<script>alert('Se ha guardado correctamente');</script>";
}
include("modulos/mod_planillas/conf.php");
$funciones = new Funciones();
$id_planilla_ampliada = 1;

$query = "SELECT * FROM planillas_ampliada WHERE id=" . $id_planilla_ampliada;
$planilla = mysql_fetch_array(mysql_query($query));
$elementos_planificados = $funciones->get_elementos_planificados_ampliada($id_planilla_ampliada);
$elementos_planificables = $funciones->get_elementos_planificables_ampliada($id_planilla_ampliada);
//$elementos_planificados = array();
//$elementos_planificables = $funciones->get_modulosrosas();

$accion = "update_ampliada&id=" . $id_planilla_ampliada;


$num_tablas = count($funciones->tablas);
?>
<?php
/*
  Esta función muestra el html de un elemento de cualquier tipo.
  $estilo -> Es un string con las variables css para de como se va a mostrar el recuadro. Su contenido está en conf.php
  $estilo_helper -> Es un string con las variables css para mostrar el recuadro oscuro de arriba donde esta el atributo de bloquear, de +info, etc...
  Su contenido está en conf.php
  $titulo -> Es un string con el título del elemento
  $descripcion -> Es un string con la descripción del elemento
  $num -> Es un int. Contiene un valór único para diferenciar el elemento de todos los demás.
 */

function mostrar_recuadro_noticia($estilo, $estilo_helper, $tabla, $id, $titulo, $descripcion) {
  global $tablas_con_previsualizaion;
  ?>
  <li id="item_<?php echo $id ?>,<?php echo $tabla ?>" style="<?php echo $estilo ?>" value="<?php echo $tabla ?>" title="<?php echo $tabla ?>">
    <div style="<?php echo $estilo_helper ?>">
      <table width="100%">
        <tr>
          <td></td>
  <?php if (in_array($tabla, $tablas_con_previsualizaion)) { ?>
            <td onclick="javascript:muestra_contenido(<?php echo $id ?>,'<?php echo $tabla ?>',<?php echo $num ?>)" style="cursor:pointer" align="right" id="abiertocerrado-<?php echo $tabla . "-" . $id ?>">[+]</td>
            <td onclick="javascript:eliminar_elemento_de_planilla(<?php echo $id ?>,'<?php echo $tabla ?>')" style="cursor:pointer" align="right">X</td>
  <?php } ?>
        </tr>
      </table>
    </div>
    <div class="titulo"><?php echo $titulo ?></div>
    <div id="prev-<?php echo $tabla . "-" . $id ?>"></div>
  </li>
  <?php
}

function muestra_columna($elementos, $num_columna, $estilos, $estilos_helper) {
  if (!empty($elementos[$num_columna])) {
    $num_elemento = 0;
    foreach ($elementos[$num_columna] as $unElemento) {
      $estilo_helper = $estilos_helper[$unElemento['tabla_elemento']];
      $estilo = $estilos[$unElemento['tabla_elemento']];
      $elemento_rosa = mysql_fetch_assoc(mysql_query("SELECT tipo FROM modulosrosas WHERE id=" . $unElemento['id']));
      if ($elemento_rosa['tipo'] == "banner") {
        $estilo = $estilos[$unElemento['tabla_elemento'] . "_banner"];
        $estilo_helper = $estilos_helper[$unElemento['tabla_elemento'] . "_banner"];
      }
      mostrar_recuadro_noticia($estilo, $estilo_helper, $unElemento['tabla_elemento'], $unElemento['id'], $unElemento['titulo'], "");
      $num_elemento++;
      //$num++;
    }
  }
}
?>
<script type="text/javascript" src="scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.7.1.custom.min.js"></script>
<script src="modulos/mod_planillas/scripts.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#destino1').sortable({
      connectWith: 'ul',
      cursor: 'move'
    });
    $('#destino2').sortable({
      connectWith: 'ul',
      cursor: 'move'
    });
    $('#destino3').sortable({
      connectWith: 'ul',
      cursor: 'move'
    });
  });
</script>
<link href="modulos/mod_planillas/estilos.css" type="text/css" rel="stylesheet" />
<!-- TÍTULO Y BOTONERA -->
<table class="barra_titulo_botones">
  <tr>
    <td><p>Edición de planilla de noticia ampliada</p></td>
    <td align="right">
      <table>
        <tr align="right">
          <td class="botones_botonera"><a href="#" onclick="serializar_noticia_ampliada(<?php echo $num_tablas ?>);" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<!-- FIN DE TÍTULO Y BOTONERA -->
<form name="form_planillas" method="post" action="?modulo=<?php echo MODULO ?>&fns=1&accion=<?php echo $accion ?>&ampliada=si">
  <input type="hidden" id="listas" name="listas" value="">
  <input type="hidden" id="no_planificadas" name="no_planificadas" value="">
  <input type="hidden" name="url" id="url" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
</form>
<!-- PLANILLA -->
<!-- AREA DE LA PLANILLA ACTUAL -->
<div class="tabla_columnas">
  <table width="100%" border="0">
    <tr>
      <td width="70%" valign="top">
        <div style="display:none">
          <div class="hueco_ampliada">
            <h2>Banner 1</h2>
            <ul id="destino1" style="padding-bottom:5px">
<?php muestra_columna($elementos_planificados, 1, $estilos, $estilos_helper); ?>
            </ul>
          </div>
        </div>
        <div class="hueco_ampliada" style="margin:5px 0px 5px 0px;text-align:center">
          Espacio reservado para el contenido de la noticia
        </div>
        <div style="display:none">
          <div class="hueco_ampliada">
            <h2>Banner 2</h2>
            <ul id="destino2" style="padding-bottom:5px">
<?php muestra_columna($elementos_planificados, 2, $estilos, $estilos_helper); ?>
            </ul>
          </div>
        </div>
      </td>
      <td width="30%" valign="top">
        <div>
          <div class="hueco_ampliada">
            <h2>Columna 3</h2>
            <ul id="destino3" style="padding-bottom:5px">
<?php muestra_columna($elementos_planificados, 3, $estilos, $estilos_helper); ?>
            </ul>
          </div>
        </div>
      </td>
    </tr>
  </table>
</div>
<!-- FIN AREA DE LA PLANILLA ACTUAL -->

<!-- AREA DE ELEMENTOS QUE SE PUEDEN DEPOSITAR EN LA PLANILLA -->
<div class="tabla_posibles">
  <table width="300px">
    <?php
    $i = 1;
    foreach ($funciones->tablas as $tabla) {
      ?>
      <script type="text/javascript">
        $(document).ready(function(){
          $('#origen<?php echo $i ?>').sortable({
            items: "li",
            connectWith: 'ul',
            cursor: 'move'
          });
        });
      </script>
      <tr>
        <td class="recuadro-titulos" onclick="javascript:$('#<?php echo $tabla ?>').toggle('drop');"><?php echo $titulos_planificables[$tabla] ?></td>
      </tr>
      <tr id="<?php echo $tabla ?>">
        <td>
          <ul id="origen<?php echo $i ?>" style="padding-left:0px;margin-left:0px;padding-bottom:10px;background-color:#EEEEEE;border:1px solid #CCCCCC">
            <?php
            $j = 1;
            foreach ($elementos_planificables as $unElementoPlanificable) {
              if (!empty($elementos_planificables[$j - 1])) {
                $estilo_helper = $estilos_helper[$unElementoPlanificable['tabla_elemento']];
                $estilo = $estilos[$unElementoPlanificable['tabla_elemento']];
                $elemento_rosa = mysql_fetch_assoc(mysql_query("SELECT tipo FROM modulosrosas WHERE id=" . $unElementoPlanificable['id']));
                if ($elemento_rosa['tipo'] == "banner") {
                  $estilo = $estilos[$unElementoPlanificable['tabla_elemento'] . "_banner"];
                  $estilo_helper = $estilos_helper[$unElementoPlanificable['tabla_elemento'] . "_banner"];
                }
                if ($tabla == $unElementoPlanificable['tabla_elemento'])
                  mostrar_recuadro_noticia($estilo, $estilo_helper, $unElementoPlanificable['tabla_elemento'], $unElementoPlanificable['id'], $unElementoPlanificable['titulo'], "");
              }
              $j++;
            }
            ?>
          </ul>
        </td>
      </tr>
      <?php
      $i++;
    }
    ?>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#negros').sortable({
          items: "li",
          connectWith: 'ul',
          cursor: 'move',
          stop: function(event,ui){
            $('#negros').html('<li id="item_0,modulosnegros" style="background-color:#000000;list-style-type:none;color:#FFFFFF;height:50px" value="eo" title="Salto de linea"><div><table width="100%"><tr><td>Bloquear:<input type="checkbox" id="bloqueado-modulosnegros-0" checked=\'checked\'></td></tr></table></div><div class="titulo">Este módulo inserta un salto de linea</div></li>');
          }
        });
      });
    </script>
    <tr>
      <td class="recuadro-titulos" onclick="javascript:$('#modulosnegros').toggle('drop');">Saltos de linea</td>
    </tr>
    <tr id="modulosnegros">
      <td>
        <ul id="negros" style="padding-left:0px;margin-left:0px;padding-bottom:10px;background-color:#EEEEEE;border:1px solid #CCCCCC">
          <li id="item_0,modulosnegros" style="background-color:#000000;list-style-type:none;color:#FFFFFF;height:50px;padding-bottom:5px;margin-bottom:5px;" value="eo" title="Salto de linea">
            <div>
              <table width="100%">
                <tr>
                  <td>Bloquear:<input type="checkbox" id="bloqueado-modulosnegros-0" checked='checked'></td>
                </tr>
              </table>
            </div>
            <div class="titulo">Este módulo inserta un salto de linea</div>
          </li>
        </ul>
      </td>
    </tr>
  </table>
</div>
<!-- AREA DE ELEMENTOS QUE SE PUEDEN DEPOSITAR EN LA PLANILLA -->
<!-- FIN PLANILLA -->