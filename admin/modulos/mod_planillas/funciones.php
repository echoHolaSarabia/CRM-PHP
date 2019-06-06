<?php

/*
  ALTER TABLE `planillas` ADD `max_11` INT NOT NULL DEFAULT '10', ADD `max_12` INT NOT NULL DEFAULT '10', ADD `max_13` INT NOT NULL DEFAULT '10', ADD `max_14` INT NOT NULL DEFAULT '1', ADD `max_15` INT NOT NULL DEFAULT '1', ADD `max_16` INT NOT NULL DEFAULT '1', ADD `max_21` INT NOT NULL DEFAULT '10', ADD `max_22` INT NOT NULL DEFAULT '10', ADD `max_23` INT NOT NULL DEFAULT '10', ADD `max_24` INT NOT NULL DEFAULT '1', ADD `max_25` INT NOT NULL DEFAULT '1', ADD `max_26` INT NOT NULL DEFAULT '1', ADD `max_31` INT NOT NULL DEFAULT '10', ADD `max_32` INT NOT NULL DEFAULT '10', ADD `max_33` INT NOT NULL DEFAULT '10', ADD `max_34` INT NOT NULL DEFAULT '1', ADD `max_35` INT NOT NULL DEFAULT '1', ADD `max_36` INT NOT NULL DEFAULT '1', ADD `max_41` INT NOT NULL DEFAULT '10', ADD `max_42` INT NOT NULL DEFAULT '10', ADD `max_43` INT NOT NULL DEFAULT '10', ADD `max_44` INT NOT NULL DEFAULT '1', ADD `max_45` INT NOT NULL DEFAULT '1', ADD `max_46` INT NOT NULL DEFAULT '1', ADD `max_51` INT NOT NULL DEFAULT '10', ADD `max_52` INT NOT NULL DEFAULT '10', ADD `max_53` INT NOT NULL DEFAULT '10', ADD `max_54` INT NOT NULL DEFAULT '1', ADD `max_55` INT NOT NULL DEFAULT '1', ADD `max_56` INT NOT NULL DEFAULT '1';
 */

class Funciones extends General {

/////////////-------- VARIABLES---------/////////////////////////////////////////////////////////

  var $control;            //Array con los datos recibidos por GET y que nos indican la funcion a realizar
  var $datos;             //Array con los datos recibudos del formulario por POST
  var $tabla = "planillas";         //Tabla donde se insertar�an los datos
  var $tablas = array(0 => "noticias", 1 => "modulosrosas", 2 => "modulosnegros"); //Tablas afectadas
  var $num_columnas = 7;          //N�mero de columnas y filas en una planilla
  var $num_hojas = 5;          //N�mero de hojas
  var $max_por_fila = 10;          //N�mero m�ximo de elementos verticales en una fila o columna
  var $url_home;




/////////////-------- M�TODOS---------///////////////////////////////////////////////////////////
  /*
    Funciones de inserci�n y actualizaci�n de las tablas planilla y planillas_elementos:
    1. insert($seccion)
    2. update($id,$datos)
    3. reprogramar_planilla
    4. duplicar()
    5. set_elemento_planificable()
    6. set_elemento_planificado($id_elemento,$id_)
    7. unset_elemento_planificable()
    8. actualizar_planillas($id_planilla = -1)
    9. get_elementos_planificados($id_planilla,$programados = 1)
    10. get_elementos_planificables($id_planilla)
    11. get_elementos_planilla($id_planilla)
   */
/////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   * Constructor de la clase funciones del M�dulo mod_planillas
   *
   * @param array $control_recibido
   * @param array $datos_recibidos
   */
  function funciones($control_recibido = "", $datos_recibidos = "") {
    $this->control = $control_recibido;
    $this->datos = $datos_recibidos;
    $this->url_home = "index2.php?modulo=mod_planillas";
  }

  function start() {
    $accion = $this->control['accion'];
    if ($accion != '') {
      switch ($accion) {
        case "insert":
          $id_planilla = $this->insert($this->control["seccion"]);
          $this->control["id"] = $id_planilla;
        case "update":
          $this->update($this->control["id"], $this->datos);
          if ($this->datos['url'] != "")
            header("Location: " . $this->datos['url']);
          else
            $this->redireccionar($this->control["id"]);
          break;
        case "actualizar":
          $this->actualizar_guardar($this->control["id"], $this->control["tabla"], $this->control["planillas_portada"], $this->control["planillas_seccion"], $this->control["activo"], $this->control["fecha_publicacion"], $this->control["redireccion"], $this->control["extra"]);
          break;
        case "actualizar_click" :
          $this->actualizar_click($this->control["id"], $this->control["tabla"], $this->control["activo"], $this->control["fecha_publicacion"], $this->control["redireccion"], $this->control["extra"]);
          break;
        case "eliminar_elementos":
          $this->eliminar_de_planillas($this->control["ids"], $this->control["tabla"], $this->control["redireccion"]);
          break;
        case"delete": $this->delete($this->datos); break;
        case "update_ampliada":
          $this->update_ampliada($this->control["id"], $this->datos);
          header("Location: index2.php?modulo=mod_planillas&ampliada=si&mensaje=si");
          break;
        default:
          return false;
          break;
      }
    } else {
      return false;
    }
  }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//	Funciones de inserci�n y actualizaci�n de las tablas planilla y planillas_elementos
//
//
//
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   * Inserta una nueva entrada en la tabla PLANILLAS con los valores por defecto
   * Devuelve el nuevo id
   *
   * @param int $seccion
   * @return int
   */
  function insert($seccion) {

    $c = "INSERT INTO " . $this->tabla . " (id,seccion,fecha_creacion) VALUE (null," . $seccion . ", NOW())";
    mysql_query($c);

    return mysql_insert_id();
  }

  /**
   * 1. Actualiza los datos de una planilla en la tabla PLANILLAS
   * 2. Borra los datos de esa planilla en la tabla PLANILLAS_ELEMENTOS
   * 3. Inserta los nuevos datos en la tabla PLANILLAS_ELEMENTOS sin importar campos programado
   * 4. Inserta los elementos planificables
   * 5. Llama a reprogramar_planilla que escanea PLANILLAS_ELEMENTOS seg�n el id_planilla
   *    y usando los campos fecha_publicacion y activo de $tablas actualiza el atributo programado
   *
   * $datos["listas"] es una lista con los siguentes separadores:
   * # separa columnas
   * @ separa hojas dentro de las columnas
   * / separa elementos dentro de las hojas
   * , separa atributos dentro de los elementos
   *
   * @param int $id
   * @param unknown_type $datos
   */
  function update($id, $datos) {
    
    // 1. Actualiza los datos de una planilla en la tabla PLANILLAS

    $num_elementos_x_columna = explode("/", $datos["num_elementos_x_columna"]);
    $query_max = "";
    for ($i = 0; $i < count($num_elementos_x_columna); $i++) {
      $num_elementos_x_columna[$i] = explode(",", $num_elementos_x_columna[$i]);
      for ($j = 0; $j < count($num_elementos_x_columna[$i]); $j++) {
        $query_max.= ", max_" . ($i + 1) . ($j + 1) . " = " . $num_elementos_x_columna[$i][$j];
      }
    }

    $num_columnas = $this->num_columnas;

    $query = "UPDATE " . $this->tabla . " SET fecha_publicacion='" . $datos['fecha_publicacion'] . "', tipo='" . $datos['tipo'] . "' " . $query_max . " WHERE id=" . $id;

    mysql_query($query);


    // 2. Borra los datos de esa planilla en la tabla PLANILLAS_ELEMENTOS

    $query = "DELETE FROM planillas_elementos WHERE id_planilla=" . $id;
    mysql_query($query);


    // 3. Inserta los nuevos datos en la tabla PLANILLAS_ELEMENTOS sin importar campos programado

    $primero = 0;
    $bloqueados = array();
    $elementos = array();
    $query = "INSERT INTO planillas_elementos (id,id_planilla,id_elemento,tabla_elemento,planificado,bloqueado,programado";
    for ($i = 0; $i < $num_columnas; $i++) {
      $query .= ",orden" . $i;
    }
    $query .= ") VALUES ";

    $corte_columnas = explode("#", $datos["listas"]);

    // Con $i desplazamiento por las filas
    for ($i = 0; $i < count($corte_columnas); $i++) {
      $corte_hojas[$i] = explode("@", $corte_columnas[$i]);
      // on $j desplazamiento por las columnas
      for ($j = 0; $j < count($corte_hojas[$i]); $j++) {
        $corte_elementos[$j] = explode("/", $corte_hojas[$i][$j]);
        // Con $k desplazamiento por los elementos
        for ($k = 0; $k < count($corte_elementos[$j]); $k++) {
          $corte_atributos[$k] = explode(",", $corte_elementos[$j][$k]);
          if (isset($corte_atributos[$k][2])) {
            $query_activo = "SELECT fecha_publicacion, activo FROM " . $corte_atributos[$k][1] . " WHERE id=" . $corte_atributos[$k][0];
            $r = mysql_fetch_assoc(mysql_query($query_activo));
            if ($r["fecha_publicacion"] <= date('Y-m-d H:i:s') && $r["activo"] = 1)
              $programado = 0;
            else
              $programado = 1;
            if ($primero == 0)
              $primero++;
            else
              $query .=",";

            $query .= "(null," . $id . "," . $corte_atributos[$k][0] . ",'" . $corte_atributos[$k][1] . "',1," . $corte_atributos[$k][2] . ",0";
            for ($m = 0; $m < $num_columnas; $m++) {
              if ($m == $i)
                $query .= "," . ((100 * $j) + $k + 1);
              else
                $query .= ",0";
            }
            $query .= ")";

            if ($corte_atributos[$k][2] > 0) {
              $aux = $corte_atributos[$k];
              $aux[3] = $id;
              $aux[4] = $i;
              $aux[5] = $j;
              $aux[6] = (100 * $j) + $k + 1;
              $bloqueados[] = $aux;
            }
          }
        }
      }
    }


    if ($primero) {
      mysql_query($query);

      // 4. Llama a reprogramar_planilla
      $this->reprogramar_planilla($id);
    }

    // ORDENA BLOQUEADOS Y CORTA COLUMNAS
    $this->ordenar_bloqueados($bloqueados);
    $this->reordena_bloqueados($id, $bloqueados);


    // 6. Inserta los elementos planificables
    if ($datos["no_planificadas"] != "") {
      $query = "INSERT INTO planillas_elementos (id,id_planilla,id_elemento,tabla_elemento) VALUES ";
      $elementos = explode("/", $datos["no_planificadas"]);
      $i = 0;
      foreach ($elementos as $registro) {
        if ($registro != "") {
          if ($i)
            $query .= ",";
          else
            $i++;
          $elemento = explode(",", $registro);
          $query .= " (null," . $id . "," . $elemento[0] . ",'" . $elemento[1] . "')";
        }
      }
      mysql_query($query);
    }
    $query = "
      SELECT `id`
      FROM `planillas`
      WHERE `fecha_publicacion` <=now()
      AND `seccion` =1
      ORDER BY `fecha_publicacion` DESC";
    $result = mysql_query($query);
    $planilla = mysql_fetch_object($result);
    if ($planilla->id == $id) {
      $query = "UPDATE planillas_elementos SET orden6=1 WHERE id_elemento=108 AND id_planilla=" . $id;
      mysql_query($query) or die(mysql_error());
      $query = "UPDATE planillas_elementos SET orden6=2 WHERE id_elemento=145 AND id_planilla=" . $id;
      mysql_query($query) or die(mysql_error());
      $query = "UPDATE planillas_elementos SET orden6=3 WHERE id_elemento=146 AND id_planilla=" . $id;
      mysql_query($query) or die(mysql_error());
      $query = "UPDATE planillas_elementos SET orden6=101 WHERE id_elemento=110 AND id_planilla=" . $id;
      mysql_query($query) or die(mysql_error());
      $query = "UPDATE planillas_elementos SET orden6=201 WHERE id_elemento=155 AND id_planilla=" . $id;
      mysql_query($query) or die(mysql_error());
    }
  }

  /**
   * Ordena los bloqueados por su campo bloqueado
   *
   * @param array $bloqueados
   */
  function ordenar_bloqueados(&$bloqueados) {
    $bloqueados_aux = array();
    $indices = array();
    foreach ($bloqueados as $bloqueado) {
      $indices[] = (1000 * $bloqueado[4]) + $bloqueado[2];
      $bloqueados_aux[(1000 * $bloqueado[4]) + $bloqueado[2]] = $bloqueado;
    }
    sort($indices);
    for ($z = 0; $z < count($indices); $z++)
      $bloqueados[$z] = $bloqueados_aux[$indices[$z]];
  }

  /**
   * Ordena los bloqueados columna a columna y hoja a hoja
   * Pasa los elementos de m�s a la siguiente hoja
   *
   * @param unknown_type $id
   * @param unknown_type $bloqueados
   */
  function reordena_bloqueados($id, $bloqueados) {

    $query = "SELECT DISTINCT * FROM planillas WHERE id=" . $id;
    $planilla = mysql_fetch_assoc(mysql_query($query));
    for ($columna = 1; $columna < 7; $columna++) {
      for ($hoja = 0; $hoja < $this->num_hojas; $hoja++) {
        for ($i = 0; $i < count($bloqueados); $i++) {
          if (($bloqueados[$i][5] == $hoja) && ($bloqueados[$i][4] == $columna)) {
            $this->recolocar_bloqueado($bloqueados[$i]);
          }
        }

        $query_elementos = "SELECT DISTINCT * FROM planillas_elementos WHERE id_planilla = " . $id;
        $query_elementos .= "  AND programado=0 AND orden" . $columna . " > " . (100 * $hoja) . " AND orden" . $columna . " < " . (100 * $hoja + 100) . "";
        $query_elementos .= " ORDER BY orden" . $columna;
        $r_elementos = mysql_query($query_elementos);
        if (mysql_num_rows($r_elementos) > 0) {
          $num_columna = 0;
          $num_pasados = 0;
          while ($r_elemento = mysql_fetch_assoc($r_elementos)) {
            $num_columna++;
            if ($num_columna > $planilla["max_" . ($hoja + 1) . $columna]) {
              $num_pasados++;
              if ($hoja == 4)
                mysql_query("DELETE FROM planillas_elementos WHERE id_planilla=" . $id . " AND id_elemento=" . $r_elemento["id_elemento"] . " AND tabla_elemento like " . $r_elemento["tabla_elemento"]);
              else {
                mysql_query("UPDATE planillas_elementos SET orden" . $columna . " = orden" . $columna . "+1 WHERE orden" . $columna . " >= " . (100 * $hoja + 100 + $num_pasados) . " AND orden" . $columna . " < " . (100 * $hoja + 200) . " AND  id_planilla=" . $id . "");
                mysql_query("UPDATE planillas_elementos SET orden" . $columna . " = " . (100 * ($hoja + 1) + $num_pasados) . " WHERE id_planilla=" . $id . " AND id_elemento=" . $r_elemento["id_elemento"] . " AND tabla_elemento like '" . $r_elemento["tabla_elemento"] . "'");
              }
            }
          }
        }
      }
    }
  }

  /**
   * Coloca un bloqueado en su posici�n, teniendo en cuenta lo elementos programados
   * Si no hay suficientes elementos, pone el bloqueado al final+su indice de bloqueado
   *
   * @param array $bloqueado 0 -> id_elemento,1 -> tabla_elemento,2 -> bloqueado,3 -> id_planilla
   * 						   4 -> columna,5 -> hoja, 6 -> posreal
   */
  function recolocar_bloqueado($bloqueado) {
    $pos_bloqueo = 100 * $bloqueado[5] + $bloqueado[2];
    $pos_inicial = 100 * $bloqueado[5] + 1;

    $query = "SELECT DISTINCT * FROM planillas_elementos WHERE id_planilla = " . $bloqueado[3] . " AND orden" . $bloqueado[4] . ">" . (100 * $bloqueado[5]) . " AND orden" . $bloqueado[4] . "<" . ((100 * $bloqueado[5]) + 100) . " ORDER BY orden" . $bloqueado[4];
    $r = mysql_query($query);
    $elemento_no_programdos = 0;
    $movido = 0;
    $num = mysql_num_rows($r);
    $elementos = array();
    while ($fila = mysql_fetch_assoc($r)) {
      if (($bloqueado[0] == $fila["id_elemento"]) && ($bloqueado[1] == $fila["tabla_elemento"])) {
        $id_bloqueado = $fila["id"];
      } else {
        $elementos[] = $fila;
        if (($pos_inicial <= $pos_bloqueo) && ($fila["programado"] == 1))
          $pos_bloqueo++;
      }
      $pos_inicial++;
    }

    $pos_inicial = 100 * $bloqueado[5] + 1;
    $puesto = 0;
    for ($i = 0; $i < count($elementos); $i++) {
      if ($pos_inicial + $i == $pos_bloqueo) {
        mysql_query("UPDATE planillas_elementos SET orden" . $bloqueado[4] . "=" . ($pos_inicial + $i) . " WHERE id=" . $id_bloqueado);
        $pos_inicial++;
        $i--;
        $puesto++;
      } else {
        if ($elementos[$i]["bloqueado"] == 0) {
          mysql_query("UPDATE planillas_elementos SET orden" . $bloqueado[4] . "=" . ($pos_inicial + $i) . " WHERE id=" . $elementos[$i]["id"]);
        }
      }
    }
    if ($puesto == 0) {
      mysql_query("UPDATE planillas_elementos SET orden" . $bloqueado[4] . "=" . ($bloqueado[2] + $i) . " WHERE id=" . $id_bloqueado);
    }
  }

  function update_ampliada($id, $datos) {
    $query = "DELETE FROM planillas_elementos_ampliada WHERE id_planilla=" . $id;
    mysql_query($query);

    $num_columnas = 3;
    $primero = 0;
    $elementos = array();
    $query = "INSERT INTO planillas_elementos_ampliada (id,id_planilla,id_elemento,tabla_elemento,planificado";
    for ($i = 1; $i <= $num_columnas; $i++) {
      $query .= ",orden" . $i;
    }
    $query .= ") VALUES ";
    $corte_columnas = explode("#", $datos["listas"]);
    for ($i = 0; $i < count($corte_columnas); $i++) {
      $corte_elementos[$i] = explode("/", $corte_columnas[$i]);
      for ($k = 0; $k < count($corte_elementos[$i]); $k++) {
        $corte_atributos[$k] = explode(",", $corte_elementos[$i][$k]);
        if (isset($corte_atributos[$k][1])) {
          if ($primero == 0)
            $primero++;
          else
            $query .=",";

          $query .= "(null," . $id . "," . $corte_atributos[$k][0] . ",'" . $corte_atributos[$k][1] . "',1";
          for ($m = 1; $m <= $num_columnas; $m++) {
            if ($m == ($i + 1))
              $query .= "," . ($k + 1);
            else
              $query .= ",0";
          }
          $query .= ")";
        }
      }
    }
    mysql_query($query);
    //echo $query;
    // 4. Inserta los elementos planificables
    if ($datos["no_planificadas"] != "") {
      $query = "INSERT INTO planillas_elementos_ampliada (id,id_planilla,id_elemento,tabla_elemento) VALUES ";
      $elementos = explode("/", $datos["no_planificadas"]);
      $i = 0;
      foreach ($elementos as $registro) {
        if ($registro != "") {
          if ($i)
            $query .= ",";
          else
            $i++;
          $elemento = explode(",", $registro);
          $query .= " (null," . $id . "," . $elemento[0] . ",'" . $elemento[1] . "')";
        }
      }
      mysql_query($query);
      //echo $query;
    }
  }

// Devuelve los elementos planificados en una planilla sacados de todas las tablas 	configuradas
// Si programados se pone a 0 devuelve s�lo los elementos no programados (Ya publicados)


  function get_elementos_planificados_ampliada($id_planilla, $programados = 1) {

    $num_columnas = 3;
    $resultado = array();
    $query = "";
    $j = 0;

    if ($programados)
      $where_prog = "";
    else
      $where_prog = "AND planillas_elementos_ampliada.programado = 0";

    foreach ($this->tablas as $tabla) {
      if ($j > 0)
        $query .= " UNION ";
      else
        $j++;
      $query .= " (SELECT DISTINCT planillas_elementos_ampliada.id as planillas_elementos_ampliada_id, " . $tabla . ".id," . $tabla . ".titulo," . $tabla . ".fecha_publicacion," . $tabla . ".activo, planillas_elementos_ampliada.tabla_elemento";
      for ($i = 1; $i <= $num_columnas; $i++) {
        $query .= ",planillas_elementos_ampliada.orden" . $i;
      }
      $query .= " FROM planillas_elementos_ampliada, " . $tabla;
      $query .= " WHERE " . $tabla . ".id=planillas_elementos_ampliada.id_elemento AND planillas_elementos_ampliada.tabla_elemento = '" . $tabla . "' AND  planillas_elementos_ampliada.id_planilla=" . $id_planilla . " AND planillas_elementos_ampliada.planificado=1 " . $where_prog . " )";
    }
    for ($i = 1; $i <= $num_columnas; $i++) {
      if ($i > 1)
        $query .= ",";
      else
        $query .= " ORDER BY ";
      $query .= "orden" . $i;
    }
    $r = mysql_query($query);

    if (mysql_num_rows($r) > 0) {
      $fila_actual = $num_columnas;
      while ($fila = mysql_fetch_assoc($r)) {
        if ($fila["orden" . $fila_actual] == 0) {
          for ($k = $fila_actual - 1; $k >= 0; $k--) {
            if ($fila["orden" . $k] > 0) {
              $fila_actual = $k;
              break;
            }
          }
        }
        $resultado[$fila_actual][] = $fila;
      }
    }
    return $resultado;
  }

// Devuelve los elementos planificables de una planilla sacados de todas las tablas configuradas

  function get_elementos_planificables_ampliada($id_planilla) {

    $resultado = array();
    $query = "";
    $j = 0;
    foreach ($this->tablas as $tabla) {
      if ($tabla != "modulosnegros") {
        if ($j > 0)
          $query .= " UNION ";
        else
          $j++;
        $query .= "(SELECT DISTINCT " . $tabla . ".id," . $tabla . ".titulo, planillas_elementos_ampliada.tabla_elemento FROM planillas_elementos_ampliada, " . $tabla;
        $query .= " WHERE " . $tabla . ".id=planillas_elementos_ampliada.id_elemento AND planillas_elementos_ampliada.tabla_elemento = '" . $tabla . "' AND  planillas_elementos_ampliada.id_planilla=" . $id_planilla . " AND planillas_elementos_ampliada.planificado=0)";
      }
    }
    $query .= " ORDER BY tabla_elemento";
    $r = mysql_query($query);
    if (mysql_num_rows($r) > 0) {
      while ($fila = mysql_fetch_assoc($r)) {
        $resultado[] = $fila;
      }
    }
    return $resultado;
  }

  /**
   * Escanea PLANILLAS_ELEMENTOS seg�n un id_planilla
   * y usando los campos fecha_publicacion y activo de $tablas actualiza el atributo programado
   *
   * @param int $id_planilla
   */
  function reprogramar_planilla($id_planilla) {
    $query = "";
    $j = 0;
    foreach ($this->tablas as $tabla) {
      if ($j > 0)
        $query .= " UNION ";
      else
        $j++;
      $query .= " (SELECT DISTINCT planillas_elementos.id ";
      $query .= " FROM planillas_elementos, " . $tabla;
      $query .= " WHERE " . $tabla . ".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '" . $tabla . "' AND  planillas_elementos.id_planilla=" . $id_planilla . " AND (" . $tabla . ".fecha_publicacion > NOW() OR " . $tabla . ".activo = 0  ) )";
    }
    $r = mysql_query($query);
    if (mysql_num_rows($r) > 0) {
      while ($elemento = mysql_fetch_assoc($r)) {
        $query = "UPDATE planillas_elementos SET programado = 1 WHERE id = " . $elemento["id"];
        mysql_query($query);
      }
    }
  }

  /**
   * Duplica una planilla
   *
   * @param int $id
   */
  function duplicar($id) {

  }

  /**
   * Inserta como planificable un elemento desde otro m�dulo
   *
   * @param int $id_planilla
   * @param int $id_elemento
   * @param string $tabla
   */
  function set_elemento_planificable($id_planilla, $id_elemento, $tabla) {
    $query = "SELECT DISTINCT id FROM planillas_elementos WHERE id_planilla=" . $id_planilla . " AND id_elemento=" . $id_elemento . " AND tabla_elemento like '" . $tabla . "'";
    $r = mysql_query($query);
    if (!(mysql_query($r) > 0)) {
      $query = "INSERT INTO planillas_elementos (id,id_planilla,id_elemento,tabla_elemento,planificado,programado) ";
      $query .= " VALUES (null," . $id_planilla . "," . $id_elemento . ",'" . $tabla . "',0,0)";
      mysql_query($query);
    }
  }

  /**
   * Inserta como planificado un elemento desde otro m�dulo con los atributos planificado y programado a 1
   * Al final llama a actualizar_planillas
   * @param unknown_type $id_planilla
   * @param unknown_type $id_elemento
   * @param unknown_type $tabla
   */
  function set_elemento_planificado($id_planilla, $id_elemento, $tabla) {
    $query = "SELECT DISTINCT id FROM planillas_elementos WHERE id_planilla=" . $id_planilla . " AND id_elemento=" . $id_elemento . " AND tabla_elemento like '" . $tabla . "'";
    $r = mysql_query($query);
    if ( (count($r) > 0) ) {
       
      // Se mira en que columna se tiene que meter el elemento
      $query = "SELECT DISTINCT columna_insercion,tipo FROM planillas WHERE id = " . $id_planilla;
      $r = mysql_fetch_assoc(mysql_query($query));

      if (($r["tipo"] == "con_rotador") || ($r["tipo"] == "una_foto"))
        $columna = 0;
      else {
        // Se actualiza el atributo CODIGO PARA METER UNA VEZ EN CADA COLUMNA
        $columna = $r["columna_insercion"] + 1;
        mysql_query("UPDATE planillas SET columna_insercion = " . ($columna % 2) . " WHERE id = " . $id_planilla);

        // CODIGO PARA METER SOLO EN UNA COLUMNA
        //$columna=2;
      }

      // Iinsertamos el elemento

      $query = "UPDATE planillas_elementos SET orden" . $columna . " = orden" . $columna . " + 1 WHERE id_planilla=" . $id_planilla . " AND orden" . $columna . " > 0 AND orden" . $columna . " < 100";
      mysql_query($query);
      $query = "INSERT INTO planillas_elementos (id,id_planilla,id_elemento,tabla_elemento,orden" . $columna . ",planificado,programado) ";
      $query .= " VALUES (null," . $id_planilla . "," . $id_elemento . ",'" . $tabla . "',1,1,1)";
      mysql_query($query);
    }
    $this->actualizar_planilla($id_planilla);
  }

  /**
   * Elimina un elemento de una planilla desde otro m�dulo
   *
   * @param unknown_type $id_planilla
   * @param unknown_type $id_elemento
   * @param unknown_type $tabla
   * @param unknown_type $columna
   */
  function unset_elemento_planificable($id_planilla, $id_elemento, $tabla, $columna) {

    $num_columnas = $this->num_columnas;

    if ($columna > -1) {
      if (($columna > 0) && ($columna < 4))
        $this->actualizar_planilla_elemento($id_planilla, $id_elemento, $tabla, 1, $columna);

      $query = "SELECT DISTINCT planificado";
      for ($i = 0; $i < $num_columnas; $i++) {
        $query .= ",orden" . $i;
      }
      $query .= " FROM planillas_elementos WHERE id_planilla=" . $id_planilla . " AND id_elemento=" . $id_elemento . " AND tabla_elemento like '" . $tabla . "'";
      $r = mysql_query($query);

      if ((mysql_num_rows($r) > 0)) {
        $ordenes = mysql_fetch_assoc($r);
        if ($ordenes["planificado"] == 1) {
          for ($i = 0; $i < $num_columnas; $i++) {
            if ($ordenes["orden" . $i] > 0) {
              $nivel = round($ordenes["orden" . $i] / 100);
              break;
            }
          }
          $query = "UPDATE planillas_elementos SET orden" . $i . " = orden" . $i . " - 1 WHERE id_planilla=" . $id_planilla . " AND orden" . $i . " > " . $ordenes["orden" . $i] . " AND orden" . $i . " < " . (($nivel + 1) * 100);
          mysql_query($query);
        }
      }
    }
    $query = "DELETE FROM planillas_elementos WHERE id_planilla=" . $id_planilla . " AND id_elemento=" . $id_elemento . " AND tabla_elemento like '" . $tabla . "' ";
    mysql_query($query);
  }

  /**
   * Elimina un elemento de una planilla desde otro m�dulo
   *
   * @param unknown_type $id_planilla
   * @param unknown_type $id_elemento
   * @param unknown_type $tabla
   * @param unknown_type $columna
   */
  function unset_elemento_planificado($id_planilla, $id_elemento, $tabla, $columna) {

    $this->unset_elemento_planificable($id_planilla, $id_elemento, $tabla, $columna);
    $this->set_elemento_planificable($id_planilla, $id_elemento, $tabla);
  }

  /**
   * Actualiza planillas_elementos
   * Poniendo el atributo de ($id_planilla & $id_elemento & $tabla) programado igual a $programado
   * Mueve todos los dem�s elementos de esa fila
   *
   * @param unknown_type $id_planilla
   * @param unknown_type $id_elemento
   * @param unknown_type $tabla
   * @param unknown_type $programado
   * @param unknown_type $columna
   */
  function actualizar_planilla_elemento($id_planilla, $id_elemento, $tabla, $programado, $columna) {

    $num_columnas = $this->num_columnas;

    // Cojo los primeros elementos no programados de cada fila horizontal que afecte a la columna
    // Si ese elemento est� bloqueado permitira el salto de un nivel a otro
    // Primero tiene en su indice un n�mero mayor que 0 si se permite el salto.
    $primeros = "";
    if (($columna == 1) || ($columna == 2) || ($columna == 3)) {
      $query = "SELECT DISTINCT * FROM planillas_elementos WHERE id_planilla=" . $id_planilla . " AND programado = 0 AND bloqueado > 0 ";
      if ($columna == 1)
        $query .= " AND ( orden4 > 0 OR orden6 > 0 ) ORDER BY orden6, orden4 ";
      if ($columna == 3)
        $query .= " AND ( orden5 > 0 OR orden6 > 0 ) ORDER BY orden6, orden5 ";
      if ($columna == 2)
        $query .= " AND ( orden4 > 0 OR orden5 > 0 OR orden6 > 0 ) ORDER BY orden6, orden5, orden4 ";
      $primeros_aux = mysql_query($query);
      if (mysql_num_rows($primeros_aux) > 0) {
        while ($p = mysql_fetch_assoc($primeros_aux)) {
          if ($p["bloqueado"] > 0)
            $bloc = 1;
          else
            $bloc = 0;

          if ($p["orden4"] > 0) {
            $nivel = round($p["orden4"] / 100);
            if (isset($primeros[$nivel]))
              $primeros[$nivel] = $primeros[$nivel] + $bloc;
            else
              $primeros[$nivel] = $bloc;
          } else if ($p["orden5"] > 0) {
            $nivel = round($p["orden5"] / 100);
            if (isset($primeros[$nivel]))
              $primeros[$nivel] = $primeros[$nivel] + $bloc;
            else
              $primeros[$nivel] = $bloc;
          } else if ($p["orden6"] > 0) {
            $nivel = round($bloc / 100);
            if (isset($primeros[$nivel]))
              $primeros[$nivel] = $primeros[$nivel] + $bloc;
            else
              $primeros[$nivel] = $bloc;
          }
        }
      }
    }
    // FIN de coger los bloqueados horizontales.
    // Elementos de la columna donde est� el elemento
    $query = "SELECT DISTINCT * FROM planillas_elementos WHERE id_planilla=" . $id_planilla . " AND orden" . $columna . " > 0 ORDER BY orden" . $columna;
    $res = mysql_query($query);

    $posiciones = array();
    $encontrado = -1;

    if (mysql_num_rows($res) > 0) {
      while ($elemento = mysql_fetch_assoc($res)) {
        if (($id_elemento == $elemento["id_elemento"]) && ($tabla == $elemento["tabla_elemento"])) {
          if ($elemento["programado"] == $programado) {
            $fin = 1;
            break;
          }
          else
            $fin = 0;
          if ($programado == 1) {
            $posiciones[] = round($elemento["orden" . $columna] / 100);
            $encontrado = count($posiciones) - 1;
            $elemento["bloqueado"] = 1;
          } else {
            $encontrado = 1;
            //$elemento["bloqueado"] = 0;
          }
          $query_update = "UPDATE planillas_elementos SET programado = " . $programado . " WHERE id=" . $elemento["id"] . "";
          mysql_query($query_update);
        }
        $this->colocar_elemento($posiciones, $primeros, $elemento, $encontrado, $programado, $columna);
      }
    }
    $nivel_actual = -1;

    if ($fin == 0) {
      for ($i = 0; $i < count($posiciones); $i++) {
        if (is_array($posiciones[$i])) {
          if ($posiciones[$i]["nivel"] != $nivel_actual) {
            $nivel_actual = $posiciones[$i]["nivel"];
            $pos = 1;
          }
          $query_update = "UPDATE planillas_elementos SET orden" . $columna . "=" . ((($nivel_actual) * 100) + $pos) . "  WHERE id=" . $posiciones[$i]["id"] . ";";
          mysql_query($query_update);
          $pos++;
        }
      }
    }
  }

  function colocar_elemento(&$posiciones, $primeros, $elemento, &$encontrado, $programado, $columna) {
    if ($encontrado == -1) {
      $ele["id"] = $elemento["id"];
      $ele["nivel"] = round($elemento["orden" . $columna] / 100);
      $ele["bloqueado"] = $elemento["bloqueado"];
      $posiciones[] = $ele;
    } else if ($programado == 0) { // Se activa por lo tanto hay que subir todos los elementos bloqueados
      if ($elemento["bloqueado"] == 0) {
        $ele["id"] = $elemento["id"];
        $ele["nivel"] = round($elemento["orden" . $columna] / 100);
        $ele["bloqueado"] = $elemento["bloqueado"];
        $posiciones[] = $ele;
        if (isset($posiciones[count($posiciones) - 2]) &&
                ($posiciones[count($posiciones) - 2]["bloqueado"] == 0) &&
                ($posiciones[count($posiciones) - 2]["nivel"] < $ele["nivel"])) {
          $sepuede = 1;
          for ($a = $posiciones[count($posiciones) - 2]; $a < $ele["nivel"]; $a++) {
            if (isset($primeros[$a]) && ($primeros[$a] > 0))
              $sepuede++;
            else {
              $sepuede = 0;
              break;
            }
          }
          if ($sepuede > 0) {
            $posiciones[count($posiciones) - 2]["nivel"]++;
          }
          else
            $encontrado = -1;
        }
      }
      else {
        $ele["id"] = $elemento["id"];
        $ele["nivel"] = round($elemento["orden" . $columna] / 100);
        $ele["bloqueado"] = $elemento["bloqueado"];
        if (isset($posiciones[count($posiciones) - 1]) &&
                ($posiciones[count($posiciones) - 1]["bloqueado"] == 0)) {
          if (($posiciones[count($posiciones) - 1]["nivel"] < $ele["nivel"])) {
            $sepuede = 1;
            for ($a = $posiciones[$encontrado]; $a < $ele["nivel"]; $a++) {
              if (isset($primeros[$a]) && ($primeros[$a] > 0))
                $sepuede++;
              else {
                $sepuede = 0;
                break;
              }
            }
            if ($sepuede) {
              $posiciones[count($posiciones) - 1]["nivel"] = $ele["nivel"];
              $aux = $posiciones[count($posiciones) - 1];
              $posiciones[count($posiciones) - 1] = $ele;
              $posiciones[] = $aux;
            } else {
              $posiciones[] = $ele;
              $encontrado = -1;
            }
          } else {
            $aux = $posiciones[count($posiciones) - 1];
            $posiciones[count($posiciones) - 1] = $ele;
            $posiciones[] = $aux;
          }
        }
        else
          $posiciones[] = $ele;
      }
      // Hay encima un no bloqueado por lo tanto se sube si los horizontales lo permiten
    }
    else {
      // Se desactiva, por lo tanto hay que subir los elementos no bloqueados
      // Se ha creado un hueco
      // Los elementos no bloqueados lo ocupan creando otro nuevo si detr�s de ellos no hay nada
      if ($elemento["bloqueado"] > 0) {
        $ele["id"] = $elemento["id"];
        $ele["nivel"] = round($elemento["orden" . $columna] / 100);
        $ele["bloqueado"] = $elemento["bloqueado"];
        $posiciones[] = $ele;
      } else {
        $ele["id"] = $elemento["id"];
        $ele["nivel"] = round($elemento["orden" . $columna] / 100);
        $ele["bloqueado"] = $elemento["bloqueado"];

        $sepuede = 1;
        if ($ele["nivel"] > 0) {
          for ($a = $posiciones[$encontrado]; $a < $ele["nivel"]; $a++) {
            if (isset($primeros[$a]) && ($primeros[$a] > 0))
              $sepuede++;
            else {
              $sepuede = 0;
              break;
            }
          }
        }
        if ($sepuede > 0) {
          $posiciones[] = $ele["nivel"];
          $ele["nivel"] = $posiciones[$encontrado];
          $posiciones[$encontrado] = $ele;
          $encontrado = count($posiciones) - 1;
        } else {
          $encontrado = -1;
          $posiciones[] = $ele;
        }
      }
    }
  }

  /**
   * Actualiza la tabla ELEMENTOS_PLANILLAS
   * Pone a 0 el atributo programado de los elementos con fecha_publicacion < NOW() y atributo activo a 1
   * Si pone alguno a 0, desplaza hacia abajo los elementos inferiores no bloqueados
   * Si pone alguno a 1, desplaza hacia arriba los elementos inferiores no bloqueados
   *
   *
   * @param unknown_type $posiciones
   * @param unknown_type $primeros
   * @param unknown_type $elemento
   * @param unknown_type $encontrado
   * @param unknown_type $programado
   * @param unknown_type $columna
   */
  function actualizar_planilla($id_planilla) {
    $num_columnas = $this->num_columnas;
    $bloqueados = array();

    $query_0 = "UPDATE planillas_elementos SET programado=0 WHERE id=-1";  // Para poner a 0 el atributo programado
    // Actualiza elementos planificados

    $elementos = $this->get_elementos_planificados($id_planilla); // array con los elementos de la planilla
    $tipo_planilla = mysql_fetch_assoc(mysql_query("SELECT DISTINCT tipo FROM planillas WHERE id=" . $id_planilla));

    for ($i = 0; $i < $num_columnas; $i++) {
      if (!isset($elementos[$i]))
        $elementos[$i] = array();
      $posiciones = array();
      $puestos = 0;
      for ($j = 0; $j < count($elementos[$i]); $j++) {
        if ($elementos[$i][$j]["fecha_publicacion"] <= date('Y-m-d H:i:s') && $elementos[$i][$j]["activo"] == 1) {
          $a_activar = 1;
          $puestos++;
          if ($elementos[$i][$j]["bloqueado"] > 0) {

            $aux[0] = $elementos[$i][$j]["id_elemento"];
            $aux[1] = $elementos[$i][$j]["tabla_elemento"];
            $aux[2] = $elementos[$i][$j]["bloqueado"];
            $aux[3] = $id_planilla;
            $aux[4] = $i;
            $aux[5] = round($elementos[$i][$j]["orden" . $i] / 100);
            $aux[6] = $elementos[$i][$j]["orden" . $i];
            $bloqueados[] = $aux;
          }
        } else {
          $a_activar = 0;
        }
        //echo $a_activar;
        if ($elementos[$i][$j]["programado"] == $a_activar) { // Cambiar estado
          if ($elementos[$i][$j]["programado"] == 1) {
            // Se pone a 0 y se bajan los elementos inferiores no bloqueados
            $query_0 .= " OR id=" . $elementos[$i][$j]["planillas_elementos_id"];
          }
        }
        // Si estamos en el rotador se quita el ultimo y se pone en la columna que corresponde
        // Se mira en que columna se tiene que meter el elemento

        if ((($i == 0) && ($tipo_planilla["tipo"] == "con_rotador") && ($puestos > 4))
                || (($i == 0) && ($tipo_planilla["tipo"] == "una_foto") && ($puestos > 1))) {
          // METER EN 2 COLUMNAS

          $query = "SELECT DISTINCT columna_insercion FROM planillas WHERE id = " . $id_planilla;
          $r = mysql_fetch_assoc(mysql_query($query));
          $columna = $r["columna_insercion"] + 1;
          mysql_query("UPDATE planillas SET columna_insercion = " . ($columna % 2) . " WHERE id = " . $id_planilla);

          //METER EN UNA COLUMNA
          //$columna = 2;

          $aux = array_pop($elementos[0]);
          $aux["orden" . $columna] = 1;
          $aux["programado"] = 1;

          if (!isset($elementos[$columna]))
            $elementos[$columna] = array();
          array_unshift($elementos[$columna], $aux);
          $query_update = "UPDATE planillas_elementos SET orden" . $columna . "=orden" . $columna . "+1 WHERE id_planilla=" . $id_planilla . " AND orden" . $columna . ">0 AND orden" . $columna . "<100";

          mysql_query($query_update);
          $query_update = "UPDATE planillas_elementos SET orden0=0, orden" . $columna . "=1 WHERE id=" . $aux["planillas_elementos_id"];

          mysql_query($query_update);
        }
      }
    }
    mysql_query($query_0);
    // ORDENA BLOQUEADOS Y CORTA COLUMNAS
    $this->reordena_bloqueados($id_planilla, $bloqueados);
  }

////////////////////////////////////////////////////////////////////////////////////////////////////	////////////////
//
//	Funciones para obtener informaci�n de las tablas planilla, planillas_elementos
//	y las tablas el archivo de configuraci�n
//
////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
// Devuelve los elementos planificados en una planilla sacados de todas las tablas 	configuradas
// Si programados se pone a 0 devuelve s�lo los elementos no programados (Ya publicados)


  function get_elementos_planificados($id_planilla, $programados = 1) {

    $num_columnas = $this->num_columnas;
    $resultado = array();
    $query = "";
    $j = 0;

    if ($programados)
      $where_prog = "";
    else
      $where_prog = "AND planillas_elementos.programado = 0";

    foreach ($this->tablas as $tabla) {
      if ($j > 0)
        $query .= " UNION ";
      else
        $j++;
      $query .= " (SELECT DISTINCT planillas_elementos.id as planillas_elementos_id, " . $tabla . ".id," . $tabla . ".titulo," . $tabla . ".fecha_publicacion," . $tabla . ".activo, planillas_elementos.tabla_elemento, planillas_elementos.id_elemento";
      for ($i = 0; $i < $num_columnas; $i++) {
        $query .= ",planillas_elementos.orden" . $i;
      }
      $query .= ",planillas_elementos.programado,planillas_elementos.bloqueado FROM planillas_elementos, " . $tabla;
      $query .= " WHERE " . $tabla . ".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '" . $tabla . "' AND  planillas_elementos.id_planilla=" . $id_planilla . " AND planillas_elementos.planificado=1 " . $where_prog . " )";
    }
    for ($i = 0; $i < $num_columnas; $i++) {
      if ($i > 0)
        $query .= ",";
      else
        $query .= " ORDER BY ";
      $query .= "orden" . $i;
    }
    $r = mysql_query($query);

    if (mysql_num_rows($r) > 0) {
      $fila_actual = $num_columnas - 1;
      while ($fila = mysql_fetch_assoc($r)) {
        if ($fila["orden" . $fila_actual] == 0) {
          for ($k = $fila_actual - 1; $k >= 0; $k--) {
            if ($fila["orden" . $k] > 0) {
              $fila_actual = $k;
              break;
            }
          }
        }
        $resultado[$fila_actual][] = $fila;
      }
    }
    return $resultado;
  }

// Devuelve los elementos planificables de una planilla sacados de todas las tablas configuradas

  function get_elementos_planificables($id_planilla) {

    $resultado = array();
    $query = "";
    $j = 0;
    foreach ($this->tablas as $tabla) {
      if ($tabla != "modulosnegros") {
        if ($j > 0)
          $query .= " UNION ";
        else
          $j++;
        $query .= "(SELECT DISTINCT " . $tabla . ".titulo,". $tabla . ".id, planillas_elementos.tabla_elemento, planillas_elementos.bloqueado, planillas_elementos.programado FROM planillas_elementos, " . $tabla;
        $query .= " WHERE " . $tabla . ".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '" . $tabla . "' AND  planillas_elementos.id_planilla=" . $id_planilla . " AND planillas_elementos.planificado=0)";
//				$query .= " WHERE ".$tabla.".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '".$tabla."' AND planillas_elementos.planificado=0)";
      }
    }
    $query .= " ORDER BY tabla_elemento";
    //echo $query;
    $r = mysql_query($query);
    if (mysql_num_rows($r) > 0) {
      while ($fila = mysql_fetch_assoc($r)) {
        $resultado[] = $fila;
      }
    }
    return $resultado;
  }

  function get_modulosrosas() {

    $resultado = array();
    $query = " SELECT titulo,id,'modulosrosas' as tabla_elemento,0 as programado, 0 as bloqueado FROM modulosrosas WHERE activo = 1";
    $r = mysql_query($query);
    if (mysql_num_rows($r) > 0) {
      while ($fila = mysql_fetch_assoc($r)) {
        $resultado[] = $fila;
      }
    }
    return $resultado;
  }

  function get_noticias() {

    $resultado = array();
    $query = " SELECT DISTINCT id,titulo,'noticias' as tabla_elemento,0 as programado, 0 as bloqueado FROM noticias WHERE activo = 1";
    $r = mysql_query($query);
    if (mysql_num_rows($r) > 0) {
      while ($fila = mysql_fetch_assoc($r)) {
        $resultado[] = $fila;
      }
    }
    return $resultado;
  }

// Devuelve los elementos planificados y no programados de una planilla sacados de todas las tablas configuradas
// Para usar en lo p�blico o en previsualizaci�n

  function get_elementos_planilla($id_planilla) {

    $num_columnas = $this->num_columnas;
    $resultado = array();
    $query = "";
    $j = 0;
    foreach ($this->tablas as $tabla) {
      if ($j > 0)
        $query .= " UNION ";
      else
        $j++;
      $query .= " (SELECT " . $tabla . ".id," . $tabla . ".titulo, planillas_elementos.tabla_elemento";
      for ($i = 0; $i < $num_columnas; $i++) {
        $query .= ",planillas_elementos.orden" . $i;
      }
      $query .= " FROM planillas_elementos, " . $tabla;
      $query .= " WHERE " . $tabla . ".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '" . $tabla . "' AND  planillas_elementos.id_planilla=" . $id_planilla . " AND planillas_elementos.planificado=0 AND planillas_elementos.programado = 0 )";
    }
    for ($i = 0; $i < $num_columnas; $i++) {
      if ($i > 0)
        $query .= ",";
      else
        $query .= " ORDER BY ";
      $query .= "orden" . $i;
    }

    $r = mysql_query($query);
    if (mysql_num_rows($r) > 0) {
      $fila_actual = 0;
      while ($fila = mysql_fetch_assoc($r)) {
        if ($fila["orden" . $fila_actual] == 0) {
          for ($k = $fila_actual + 1; $k < $num_columnas; $k++) {
            if ($fila["orden" . $k] > 0) {
              $fila_actual = $k;
              break;
            }
          }
        }
        $resultado[$fila_actual][] = $fila;
      }
    }
    return $resultado;
  }

  /*
    Obtiene la query para listar las noticias con el buscador AJAX.
    Devuelve por referencia los par�metros necesarios.
   */

  function get_query_palabra($page, $items, &$q, &$sqlStr, &$sqlStrAux, &$limit, $id_seccion) {
    if (isset($page) and is_numeric($page))
      $limit = " LIMIT " . (($page - 1) * $items) . ",$items";
    else
      $limit = " LIMIT $items";

    //if (isset($q) and !eregi('^ *$', $q)) {
    if (isset($q) and !preg_match('/^ *$/', $q)) {
      $q = sql_quote($q); //para ejecutar consulta
      $busqueda = htmlentities($q); //para mostrar en pantalla

      $sqlStr = "SELECT * FROM " . $this->tabla . " WHERE seccion = " . $id_seccion . " ORDER BY fecha_publicacion DESC,fecha_creacion DESC";
      $sqlStrAux = "SELECT count(*) as total FROM " . $this->tabla . " WHERE seccion = " . $id_seccion . "";
    } else {
      $sqlStr = "SELECT * FROM " . $this->tabla . " WHERE seccion = " . $id_seccion . " ORDER BY fecha_publicacion DESC,fecha_creacion DESC";
      $sqlStrAux = "SELECT count(*) as total FROM " . $this->tabla . " WHERE seccion = " . $id_seccion . "";
    }
  }

//////////////  FIN function get_elementos_planilla  //////////////

  /**
   * Actualiza las planillas donde est� un elemento, a�ade el elemento en las nuevas
   * y lo elimina de las antiguas en la que no est� mas.
   *
   * @param int $id
   * @param string $tabla
   * @param string( separado por / ) $planillas_portada
   * @param string( separado por / ) $planillas_seccion
   * @param boolean $activo
   * @param string $fecha_publicacion
   * @param string $redireccion
   */
  function actualizar_guardar($id, $tabla, $planillas_portada, $planillas_seccion, $activo, $fecha_publicacion, $redireccion, $extra) {


    if ($activo && ($fecha_publicacion < date('Y-m-d H:i:s')))
      $programado = 0;
    else
      $programado = 1;



    $planillas_portada = explode("-", $planillas_portada);
    $planillas_seccion = explode("-", $planillas_seccion);


    $query = "SELECT * FROM planillas_elementos WHERE id_elemento=" . $id . " AND tabla_elemento like '" . $tabla . "' ";

    $res = mysql_query($query);

    // Se actualizan las planillas en las que estaba

    if (mysql_num_rows($res) > 0) {
      while ($fila = mysql_fetch_assoc($res)) {
        if ($fila["orden0"] > 0)
          $columna = 0;
        else if ($fila["orden1"] > 0)
          $columna = 1;
        else if ($fila["orden2"] > 0)
          $columna = 2;
        else if ($fila["orden3"] > 0)
          $columna = 3;
        else if ($fila["orden4"] > 0)
          $columna = 4;
        else if ($fila["orden5"] > 0)
          $columna = 5;
        else if ($fila["orden6"] > 0)
          $columna = 6;
        else
          $columna = -1;

        $a = array_search($fila["id_planilla"], $planillas_portada);
        if ($a === false) {
          // No estar� en portadas
          $a = array_search($fila["id_planilla"], $planillas_seccion);
          if ($a === false) {
            // No estar� en secciones
            // No est� asi que se llama a unset_planificable
            mysql_query("DELETE FROM planillas_elementos WHERE id=" . $fila["id"]);
            $this->actualizar_planilla($fila["id_planilla"]);
          } else {
            // Estar� en seccion
            $planillas_seccion[$a] = "";
            if (($programado != $fila["programado"]) && ($columna > -1)) {
              mysql_query("UPDATE planillas_elementos SET programado=" . $programado . " WHERE id=" . $fila["id"]);
              $this->actualizar_planilla($fila["id_planilla"]);
            }
          }
        } else {
          // Estar� en Portada
          $planillas_portada[$a] = "";
          if (($programado != $fila["programado"]) && ($columna > -1)) {
            mysql_query("UPDATE planillas_elementos SET programado=" . $programado . " WHERE id=" . $fila["id"]);
            $this->actualizar_planilla($fila["id_planilla"]);
          }
        }
      }
    }

    // Se inserta en las PORTADAS que no estaba

    for ($i = 0; $i < count($planillas_portada); $i++) {
      if ($planillas_portada[$i] != "") {
        $this->set_elemento_planificable($planillas_portada[$i], $id, $tabla);
      }
    }

    // Se inserta en las SECCIONES que no estaba
    for ($i = 0; $i < count($planillas_seccion); $i++) {
      if ($planillas_seccion[$i] != "") {
        $this->set_elemento_planificado($planillas_seccion[$i], $id, $tabla);
      }
    }

    if (isset($redireccion) && $redireccion != "") {
      $redireccion = "index2.php?modulo=mod_noticias&accion=editar&id=" . $id . (($extra != "noticias") ? "&extra=" . $extra : "");
    }
    else
      $redireccion = "index2.php?modulo=mod_noticias" . (($extra != "noticias") ? "&extra=" . $extra : "");


    header("Location: " . $redireccion . "");
  }

////////////// FIN function actualizar_guardar  //////////////

  /**
   * Esta funci�n se activa al pulsar el click activo de un elemento.
   * Actualiza todas las planillas en las que est� el elemento
   * Redirige
   *
   * @param int $id
   * @param string $tabla
   * @param boolean $activo
   * @param string $fecha_publicacion
   * @param string $redireccion
   */
  function actualizar_click($id, $tabla, $activo, $fecha_publicacion, $redireccion, $extra) {

    if ($activo && ($fecha_publicacion < date('Y-m-d H:i:s')))
      $programado = 0;
    else
      $programado = 1;

    $query = "UPDATE planillas_elementos SET programado=" . $programado . " WHERE id_elemento=" . $id . " AND tabla_elemento like '" . $tabla . "' ";
    mysql_query($query);
    //echo $query;

    $query = "SELECT * FROM planillas_elementos WHERE id_elemento=" . $id . " AND tabla_elemento like '" . $tabla . "' ";
    $res = mysql_query($query);

    // Se actualizan las planillas en las que estaba

    if (mysql_num_rows($res) > 0) {
      while ($fila = mysql_fetch_assoc($res)) {

        $this->actualizar_planilla($fila["id_planilla"]);
      }
    }
    $redireccion = "index2.php?modulo=mod_noticias" . (($extra != "") ? "&extra=" . $extra : "");
    header("Location: " . $redireccion . "");
  }

////////////// FIN function actualizar_click

  /**
   * Actualiza las planillas eliminando de ellas los elementos con mismos ids y tabla
   * Una vez hecho esto redirige
   *
   * @param string(separador - ) $ids
   * @param string $tabla
   * @param string $redireccion
   */
  function eliminar_de_planillas($ids, $tabla, $redireccion) {
    $ids = explode("-", $ids);
    $planillas = array();
    foreach ($ids as $id) {
      if ($id != "") {
        $query = "SELECT * FROM planillas_elementos WHERE id_elemento=" . $id . " AND tabla_elemento like '" . $tabla . "'";
        $res = mysql_query($query);

        // Se actualizan las planillas en las que estaba

        if (mysql_num_rows($res) > 0) {
          while ($fila = mysql_fetch_assoc($res)) {
            mysql_query("DELETE FROM planillas_elementos WHERE id = " . $fila["id"] . ";");
            if (!in_array($fila["id_planilla"], $planillas)) {
              $planillas[] = $fila["id_planilla"];
            }
          }
        }
        mysql_query("DELETE FROM " . $tabla . " WHERE id = " . $id . ";");
      }
    }
    foreach ($planillas as $planilla) {
      $this->actualizar_planilla($planilla);
    }

    if ($tabla != "noticias")
      $extra = "&extra=" . $tabla;
    else
      $extra = "";

    $redireccion = "index2.php?modulo=mod_noticias" . $extra;
    header("Location: " . $redireccion . "");
  }

  function delete($elementos) {
    foreach ($elementos as $clave => $valor) {
      if ($clave != "seleccion") {
        mysql_query("DELETE FROM planillas_elementos WHERE id_planilla=" . $valor);
        mysql_query("DELETE FROM " . $this->tabla . " WHERE id = " . $valor);
      }
    }
    $this->redireccionar();
  }

  /**
   * Enter description here...
   *
   * @param unknown_type $id_planilla
   * @param unknown_type $pagina
   */
  function redireccionar($id_planilla, $pagina = "") {
    header("Location: " . $this->url_home);
  }

  /**
   * Enter description here...
   *
   * @param unknown_type $id_planilla
   * @param unknown_type $pagina
   */
  function redireccionar2($id_planilla, $pagina = "") {
    header("Location: " . $this->url_home . "&accion=editar&id=" . $id_planilla . "");
  }

}