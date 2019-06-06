<?php
include("modulos/mod_planillas/conf.php");
$funciones = new Funciones();
//$funciones->actualizar_planilla(634);
/*
  $query = "SELECT id FROM modulosrosas";
  $r = mysql_query($query);
  while ($fila = mysql_fetch_assoc($r)){
  $funciones->set_elemento_planificable(588,$fila["id"],"modulosrosas");
  }
 */
if (isset($_GET['id']) && $_GET['id'] != "") {
  $id_planilla = $_GET['id'];
  $query = "SELECT * FROM planillas WHERE id=" . $id_planilla;
  $planilla = mysql_fetch_array(mysql_query($query));
  $elementos_planificados = $funciones->get_elementos_planificados($id_planilla);
  $elementos_planificables = $funciones->get_elementos_planificables($id_planilla);
  if (!isset($_GET["heredar"]))
    $accion = "update&id=" . $id_planilla;
  else
    $accion = "insert";
}else {
  $elementos_planificados = array();
  $elementos_planificables = $funciones->get_modulosrosas();
//	$elementos_planificables = $funciones->get_noticias();
  $accion = "insert";
}
//$num_tablas = count($funciones->tablas);
$num_tablas = 2;
?>
<?php
/*
  Esta funci�n muestra el html de un elemento de cualquier tipo.
  $estilo -> Es un string con las variables css para de como se va a mostrar el recuadro. Su contenido est� en conf.php
  $estilo_helper -> Es un string con las variables css para mostrar el recuadro oscuro de arriba donde esta el atributo de bloquear, de +info, etc...
  Su contenido est� en conf.php
  $titulo -> Es un string con el t�tulo del elemento
  $descripcion -> Es un string con la descripci�n del elemento
  $num -> Es un int. Contiene un val�r �nico para diferenciar el elemento de todos los dem�s.
 */

function mostrar_recuadro_noticia($estilo, $estilo_helper, $tabla, $id, $titulo, $descripcion, $bloqueado, $num) {
  global $tablas_con_previsualizaion;
  ?>
  <li id="item_<?php echo $id ?>,<?php echo $tabla ?>" style="<?php echo $estilo ?>" value="<?php echo $tabla ?>" title="<?php echo $tabla ?>">
    <div style="<?php echo $estilo_helper ?>">
      <table width="100%">
        <tr>
          <td>Bloquear:<input type="text" id="bloqueado-<?php echo $tabla . "-" . $id ?>" value="<?php echo $bloqueado ?>" style="width:20px"></td>
          <?php if(in_array($tabla, $tablas_con_previsualizaion)) { ?>
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

function muestra_columna($elementos, $num_columna, $estilos, $estilos_helper, $limite_inferior, $limite_superior, &$num) {
  if (!empty($elementos[$num_columna])) {
    $num_elemento = 0;
    foreach ($elementos[$num_columna] as $unElemento) {
      $estilo_helper = $estilos_helper[$unElemento['tabla_elemento']];
      if ($unElemento['programado'] == 1)
        $estilo = $estilos['programada'];
      else if ($unElemento['tabla_elemento'] == "modulosrosas") {//Si es un m�dulo rosa tengo que averiguar su tipo para mostrarlo de uno u otro color
        $elemento_rosa = mysql_fetch_assoc(mysql_query("SELECT tipo FROM modulosrosas WHERE id=" . $unElemento['id']));
        if ($elemento_rosa['tipo'] == "banner") {
          $estilo = $estilos[$unElemento['tabla_elemento'] . "_banner"];
          $estilo_helper = $estilos_helper[$unElemento['tabla_elemento'] . "_banner"];
        }
        else
          $estilo = $estilos[$unElemento['tabla_elemento']];
      }else
        $estilo = $estilos[$unElemento['tabla_elemento']];
      if ($unElemento['orden' . $num_columna] > $limite_inferior && $unElemento['orden' . $num_columna] < $limite_superior) {
        mostrar_recuadro_noticia($estilo, $estilo_helper, $unElemento['tabla_elemento'], $unElemento['id'], $unElemento['titulo'], "", $unElemento['bloqueado'], $num);
        $num_elemento++;
      }
      $num++;
    }
  }
}
?>
<!--<script type="text/javascript" src="scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.7.1.custom.min.js"></script>-->
<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js"></script>

<script src="modulos/mod_planillas/scripts.js" type="text/javascript"></script>

<script src="https://raw.githubusercontent.com/furf/jquery-ui-touch-punch/master/jquery.ui.touch-punch.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
<?php for ($i=0;
$i <= NUM_SUBPLANILLAS;
$i++) { ?>
      $('#destino1<?php echo $i ?>').sortable({
        connectWith: 'ul',
        cursor: 'move'
      });
      $('#destino2<?php echo $i ?>').sortable({
        connectWith: 'ul',
        cursor: 'move'
      });
      $('#destino3<?php echo $i ?>').sortable({
        connectWith: 'ul',
        cursor: 'move'
      });
      $('#destino4<?php echo $i ?>').sortable({
        connectWith: 'ul',
        cursor: 'move'
      });
      $('#destino5<?php echo $i ?>').sortable({
        connectWith: 'ul',
        cursor: 'move'
      });
      $('#destino6<?php echo $i ?>').sortable({
        connectWith: 'ul',
        cursor: 'move'
      });

      $("#pp<?php echo $i ?>").droppable({
        over: function(event, ui) {
          //$("#pp<?php echo $i ?>").css('background-color','#FF0000');
          cambiar_capa('tabla_contenedor',<?php echo $i ?>,<?php echo NUM_SUBPLANILLAS ?>);
        }
      });
<?php } ?>
  });
</script>
<link href="modulos/mod_planillas/estilos.css" type="text/css" rel="stylesheet" />
<!-- T�TULO Y BOTONERA -->
<table class="barra_titulo_botones">
  <tr>
    <td><p>Edici�n de planilla</p></td>
    <td align="right">
      <table>
        <tr align="right">
<?php if (isset($id_planilla)) { ?>
            <td class="botones_botonera"><a href="#" onclick="window.open('modulos/mod_planillas/previsualizar.php?id=<?php echo $id_planilla ?>','prev','location=1,status=1,scrollbars=1,width=950,height=800')" class="enlaces_botones_botonera"><img src="images/preview2.png" border="0" width="40"><br />Previsualizar</a></td>
<?php } ?>
          <td class="botones_botonera"><a href="#" onclick="serializar(<?php echo NUM_SUBPLANILLAS ?>,<?php echo $num_tablas ?>);" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
          <td class="botones_botonera"><a href="#" onclick="history.back(-1)" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<!-- FIN DE T�TULO Y BOTONERA -->

<!-- PLANILLA -->
<?php /*
  <table>
  <tr>
  <td class="pestania_on" id="pestania0" onclick="javascript:cambiar_capa('tabla_contenedor',0,<?php=NUM_SUBPLANILLAS+1?>);"><div id="pp0">Rotador</div></td>
  <?php/*for ($i=1;$i<=NUM_SUBPLANILLAS;$i++){?>
  <td class="pestania_off" id="pestania<?php=$i?>" onclick="javascript:cambiar_capa('tabla_contenedor',<?php=$i?>,<?php=NUM_SUBPLANILLAS+1?>);"><div id="pp<?php=$i?>">Pesta�a <?php=$i?></div></td>
  <?php}?>
  </tr>
  </table>
  <hr>
 */ ?>
<table>
  <tr>
    <td>
      <form name="form_planillas" method="post" action="?modulo=<?php echo MODULO ?>&fns=1&accion=<?php echo $accion ?>&seccion=<?php echo $_GET["seccion"] ?>">
        <input type="hidden" id="listas" name="listas" value="">
        <input type="hidden" id="no_planificadas" name="no_planificadas" value="">
        <input type="hidden" id="num_elementos_x_columna" name="num_elementos_x_columna" value="">
        <input type="hidden" name="url" id="url" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
        Fecha de publicaci�n:<input type="text" name="fecha_publicacion" id="fecha_publicacion" value="<?php echo (isset($planilla)) ? $planilla['fecha_publicacion'] : date("Y-m-d H:i"); ?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador" value="..." />
        Tipo de portada:
        <select name="tipo">
          <option value="una_foto" <?php echo (isset($planilla) && ($planilla["tipo"] == "una_foto")) ? " SELECTED " : "" ?>>Una foto/Secci�n</option>
          <option value="sin_rotador" <?php echo (isset($planilla) && ($planilla["tipo"] == "sin_rotador")) ? " SELECTED " : "" ?>>Sin rotador</option>
<?php /* <option value="con_rotador" <?php=(isset($planilla) && ($planilla["tipo"]=="con_rotador")) ? " SELECTED " : ""?>>Con rotador</option> */ ?>
        </select>
      </form>
      <script type="text/javascript">
        Calendar.setup({
          inputField     :    "fecha_publicacion",     // id del campo de texto
          ifFormat       :    "%Y-%m-%d %H:%M",
          showsTime      :    true,
          button     :    "lanzador"     // el id del bot�n que lanzar� el calendario
        });
      </script>
    </td>
  </tr>
</table>
<hr>
<!-- AREA DE LA PLANILLA ACTUAL -->
<div class="tabla_columnas">
  <?php
//El orden en planillas_elementos se incrementa en 100 cuando cambias de p�gina, es decir, en la p�gina 0 el orden v� de 1 a 100, en la 2 de 100 a 200, etc...
//$limite_inferior y $limite_superior sirven para ir controlando esos valores
  $limite_inferior = 0;
  $limite_superior = 100;
//$num es un contador que va contando los elementos de cada planilla para darles un identificador �nico.
  $num = 0;
  ?>

  <table  border="0"  id="tabla_contenedor0">
    <tr>
      <td>
        <div>
          <div class="rotador">
            <h2>ROTADOR</h2>
            <ul id="destino10" style="padding-bottom:5px">
  <?php muestra_columna($elementos_planificados, 0, $estilos, $estilos_helper, $limite_inferior, $limite_superior, $num); ?>
            </ul>
          </div>
        </div>
      </td>
    </tr>
  </table>

<?php for ($i = 1; $i <= NUM_SUBPLANILLAS; $i++) { ?>
    <table  border="0" style='display:<?php echo ($i < 4) ? "inline" : "none" ?>' id="tabla_contenedor<?php echo $i ?>">
      <tr>
        <td>
          <div>
            <div class="columna1">
              <h2>Columna 1</h2>
              <h4 style="text-align:center">Num. de elementos:&nbsp;<input type="text" name="num_elem_col_1_hoja_<?php echo $i ?>" id="num_elem_col_1_hoja_<?php echo $i ?>" value="<?php echo (isset($planilla)) ? $planilla['max_' . $i . '1'] : "10" ?>" style="width:15px"></h4>
              <ul id="destino1<?php echo $i ?>">
                <?php muestra_columna($elementos_planificados, 1, $estilos, $estilos_helper, $limite_inferior, $limite_superior, $num); ?>
              </ul>
            </div>
            <div class="columna2">
              <h2>Columna 2</h2>
              <h4 style="text-align:center">Num. de elementos:&nbsp;<input type="text" name="num_elem_col_2_hoja_<?php echo $i ?>" id="num_elem_col_2_hoja_<?php echo $i ?>" value="<?php echo (isset($planilla)) ? $planilla['max_' . $i . '2'] : "10" ?>" style="width:15px"></h4>
              <ul id="destino2<?php echo $i ?>">
                <?php muestra_columna($elementos_planificados, 2, $estilos, $estilos_helper, $limite_inferior, $limite_superior, $num); ?>
              </ul>
            </div>
            <div class="columna3">
              <h2>Columna 3</h2>
              <h4 style="text-align:center">Num. de elementos:&nbsp;<input type="text" name="num_elem_col_3_hoja_<?php echo $i ?>" id="num_elem_col_3_hoja_<?php echo $i ?>" value="<?php echo (isset($planilla)) ? $planilla['max_' . $i . '3'] : "10" ?>" style="width:15px"></h4>
              <ul id="destino3<?php echo $i ?>">
  <?php muestra_columna($elementos_planificados, 3, $estilos, $estilos_helper, $limite_inferior, $limite_superior, $num); ?>
              </ul>
            </div>
            <div class="salto"></div>
            <div class="columna4">
              <h2>Columna 1 y 2</h2>
              <h4 style="text-align:center">Num. de elementos:&nbsp;<input type="text" name="num_elem_col_4_hoja_<?php echo $i ?>" id="num_elem_col_4_hoja_<?php echo $i ?>" value="<?php echo (isset($planilla)) ? $planilla['max_' . $i . '4'] : "1" ?>" style="width:15px"></h4>
              <ul id="destino4<?php echo $i ?>">
                <?php muestra_columna($elementos_planificados, 4, $estilos, $estilos_helper, $limite_inferior, $limite_superior, $num); ?>
              </ul>
            </div>
            <div class="columna5">
              <h2>Columna 2 y 3</h2>
              <h4 style="text-align:center">Num. de elementos:&nbsp;<input type="text" name="num_elem_col_5_hoja_<?php echo $i ?>" id="num_elem_col_5_hoja_<?php echo $i ?>" value="<?php echo (isset($planilla)) ? $planilla['max_' . $i . '5'] : "1" ?>" style="width:15px"></h4>
              <ul id="destino5<?php echo $i ?>">
                <?php muestra_columna($elementos_planificados, 5, $estilos, $estilos_helper, $limite_inferior, $limite_superior, $num); ?>
              </ul>
            </div>
            <div class="columna6">
              <h2>Columna 1, 2 y 3</h2>
              <h4 style="text-align:center">Num. de elementos:&nbsp;<input type="text" name="num_elem_col_6_hoja_<?php echo $i ?>" id="num_elem_col_6_hoja_<?php echo $i ?>" value="<?php echo (isset($planilla)) ? $planilla['max_' . $i . '6'] : "1" ?>" style="width:15px"></h4>
              <ul id="destino6<?php echo $i ?>">
    <?php muestra_columna($elementos_planificados, 6, $estilos, $estilos_helper, $limite_inferior, $limite_superior, $num); ?>
              </ul>
            </div>
          </div>
        </td>
      </tr>
    </table>
  <?php
  $limite_superior += 100;
  $limite_inferior += 100;
}
?>
</div>
<!-- FIN AREA DE LA PLANILLA ACTUAL -->

<!-- AREA DE ELEMENTOS QUE SE PUEDEN DEPOSITAR EN LA PLANILLA -->
<div class="tabla_posibles">
  <table width="300px">
<?php
$i = 1;
//	die( print_r($funciones->tablas) );
unset($funciones->tablas[2]);
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
//            print_r($elementos_planificables);
            sort($elementos_planificables);
//            asort($elementos_planificables);
//            print_r($elementos_planificables);
            foreach ($elementos_planificables as $unElementoPlanificable) {
              if (!empty($elementos_planificables[$j - 1])) {
                $estilo_helper = $estilos_helper[$tabla];
                if ($unElementoPlanificable['programado'] == 1)
                  $estilo = $estilos['programada'];
                else if ($unElementoPlanificable['tabla_elemento'] == "modulosrosas") {//Si es un m�dulo rosa tengo que averiguar su tipo para mostrarlo de uno u otro color
                  $elemento_rosa = mysql_fetch_assoc(mysql_query("SELECT tipo FROM modulosrosas WHERE id=" . $unElementoPlanificable['id']));
                  if ($elemento_rosa['tipo'] == "banner") {
                    $estilo = $estilos[$unElementoPlanificable['tabla_elemento'] . "_banner"];
                    $estilo_helper = $estilos_helper[$unElementoPlanificable['tabla_elemento'] . "_banner"];
                  }
                  else
                    $estilo = $estilos[$unElementoPlanificable['tabla_elemento']];
                } else
                  $estilo = $estilos[$unElementoPlanificable['tabla_elemento']];
                if ($tabla == $unElementoPlanificable['tabla_elemento'])
                  mostrar_recuadro_noticia($estilo, $estilo_helper, $unElementoPlanificable['tabla_elemento'], $unElementoPlanificable['id'], $unElementoPlanificable['titulo'], "", $unElementoPlanificable['bloqueado'], $j);
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
            $('#negros').html('<li id="item_0,modulosnegros" style="background-color:#000000;list-style-type:none;color:#FFFFFF;height:50px" value="eo" title="Salto de linea"><div><table width="100%"><tr><td>Bloquear:<input type="checkbox" id="bloqueado-modulosnegros-0" checked=\'checked\'></td></tr></table></div><div class="titulo">Este m�dulo inserta un salto de linea</div></li>');
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
            <div class="titulo">Este m�dulo inserta un salto de linea</div>
          </li>
        </ul>
      </td>
    </tr>
  </table>
</div>
<!-- AREA DE ELEMENTOS QUE SE PUEDEN DEPOSITAR EN LA PLANILLA -->
<!-- FIN PLANILLA -->