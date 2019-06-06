<?php

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
        $this->redireccionar($this->control["id"]);
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
      //	Funciones de inserción y actualización de las tablas planilla y planillas_elementos
//	
//	
//
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Inserta una nueva entrada en la tabla PLANILLAS con los valores por defecto
// Devuelve el nuevo id
//

      function insert($seccion) {

  $c = "INSERT INTO " . $this->tabla . " (id,seccion,fecha_creacion) VALUE (null," . $seccion . ", NOW())";
  mysql_query($c);

  return mysql_insert_id();
}

///////////////////////////////////////////////////////////////////////////////////////////////////7
// 1. Actualiza los datos de una planilla en la tabla PLANILLAS
// 2. Borra los datos de esa planilla en la tabla PLANILLAS_ELEMENTOS
// 3. Inserta los nuevos datos en la tabla PLANILLAS_ELEMENTOS sin importar campos programado
// 4. Inserta los elementos planificables
// 5. Llama a reprogramar_planilla que escanea PLANILLAS_ELEMENTOS según el id_planilla
//    y usando los campos fecha_publicacion y activo de $tablas actualiza el atributo programado
//
      // $datos["listas"] es una lista con los siguentes separadores:
// # separa columnas
// @ separa hojas dentro de las columnas
// / separa elementos dentro de las hojas
// , separa atributos dentro de los elementos

function update($id, $datos) {

  $num_columnas = $this->num_columnas;

  // 1. Actualiza los datos de una planilla en la tabla PLANILLAS

  $query = "UPDATE " . $this->tabla . " SET fecha_publicacion='" . $datos['fecha_publicacion'] . "', tipo='" . $datos['tipo'] . "' WHERE id=" . $id;
  mysql_query($query);


  // 2. Borra los datos de esa planilla en la tabla PLANILLAS_ELEMENTOS

  $query = "DELETE FROM planillas_elementos WHERE id_planilla=" . $id;
  mysql_query($query);


  // 3. Inserta los nuevos datos en la tabla PLANILLAS_ELEMENTOS sin importar campos programado

  $primero = 0;
  $elementos = array();
  $query = "INSERT INTO planillas_elementos (id,id_planilla,id_elemento,tabla_elemento,planificado,bloqueado,programado";
  for ($i = 0; $i < $num_columnas; $i++) {
    $query .= ",orden" . $i;
  }
  $query .= ") VALUES ";

  $corte_columnas = explode("#", $datos["listas"]);
  for ($i = 0; $i < count($corte_columnas); $i++) {
    $corte_hojas[$i] = explode("@", $corte_columnas[$i]);
    for ($j = 0; $j < count($corte_hojas[$i]); $j++) {
      $corte_elementos[$j] = explode("/", $corte_hojas[$i][$j]);
      for ($k = 0; $k < count($corte_elementos[$j]); $k++) {
        $corte_atributos[$k] = explode(",", $corte_elementos[$j][$k]);
        if (isset($corte_atributos[$k][2])) {
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
        }
      }
    }
  }

  if ($primero) {
    mysql_query($query);

    // 4. Inserta los elementos planificables
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
          echo $registro . "<br>";
          $elemento = explode(",", $registro);
          $query .= " (null," . $id . "," . $elemento[0] . ",'" . $elemento[1] . "')";
        }
      }
      mysql_query($query);
    }

    // 5. Llama a reprogramar_planilla
    $this->reprogramar_planilla($id);
  }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Escanea PLANILLAS_ELEMENTOS según un id_planilla
// y usando los campos fecha_publicacion y activo de $tablas actualiza el atributo programado

function reprogramar_planilla($id_planilla) {
  $query = "";
  $j = 0;
  foreach ($this->tablas as $tabla) {
    if ($j > 0)
      $query .= " UNION ";
    else
      $j++;
    $query .= " (SELECT planillas_elementos.id ";
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