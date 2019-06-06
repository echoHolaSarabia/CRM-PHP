<?php

/*
  Cambia el orden sobre un campo determinado
 */

function cambia_orden(&$campo, &$orden) {
  if (($campo == "") || ($orden == "")) {
    $campo = "id";
    $orden = "asc";
  } else {
    if ($orden == "asc") {
      $orden = "desc";
    } else {
      $orden = "asc";
    }
  }
}

/*
  Muestra las fechas para el oreden de las entradas
 */

function muestra_flecha($campo, $orden, $campo_actual) {
  $res = "";
  if ($campo == $campo_actual) {
    if ($orden == "asc")
      $res = "<img src='images/arrow_down.png' border='0' align='absmiddle' />";
    else
      $res = "<img src='images/arrow_top.png' border='0' align='absmiddle' />";
  }
  return $res;
}

/*
  Se le pasa una fecha en formato dd/mm/yyyy y la devuelve en formato yyyy-mm-dd para
  insertarlo en la BBDD.
 */

function formatea_fecha($fecha) {
  if ($fecha == "") {
    $fecha = date("Y-m-d");
  } else {
    $fecha = explode("/", $fecha);
    $fecha = array_reverse($fecha);
    $fecha = implode("-", $fecha);
  }
  return $fecha;
}

/*
  Se le pasa una fecha en formato dd/mm/yyyy y la devuelve en formato yyyy-mm-dd para
  insertarlo en la BBDD.
 */

function formatea_fecha_hora($fecha) {
  $fecha = explode(" ", $fecha);
  $hora = $fecha[1];
  $fecha = $fecha[0];

  $fecha = explode("/", $fecha);
  $fecha = array_reverse($fecha);
  $fecha = implode("-", $fecha);
  return $fecha . " " . $hora;
}

/*
  Recibe una fecha con el formato de la base de datos (yyyy-mm-dd hh:mm:ss) y la coloca en el formato de jscalendar (dd/mm/yyyy)
 */

function muestra_fecha($fecha) {
  $fecha_hora = explode(" ", $fecha);

  $fecha = explode("-", $fecha_hora[0]);
  $array_fecha = array_reverse($fecha);
  $fecha = implode("/", $array_fecha);

  $hora = substr($fecha_hora[1], 0, 5);

  return $fecha . " " . $hora;
}

/*
  Devuelve en un array asociativo el resultado de una query de un elemento a la bbdd
 */

function get_elemento_de_tabla($tabla, $elemento) {
  $c = "SELECT * FROM " . $tabla . " WHERE id=" . $elemento;
  $r = mysql_query($c);
  return mysql_fetch_array($r);
}

function get_secciones_activas() {
  $c = "SELECT * FROM secciones WHERE (id_padre=-1 OR id_padre = 0) order by id";
  $r = mysql_query($c);
  return $r;
}

function get_subsecciones_activas($seccion) {
  $c = "SELECT * FROM secciones WHERE activo=1 AND id_padre=" . $seccion . " order by id;";
  $r = mysql_query($c);
  return $r;
}

/*
  Función que se usa en el buscador en ajax
 */

function sql_quote($value) {
  if (get_magic_quotes_gpc()) {
    $value = stripslashes($value);
  }
  //check if this function exists
  if (function_exists("mysql_real_escape_string")) {
    $value = mysql_real_escape_string($value);
  }
  //for PHP version < 4.3.0 use addslashes
  else {
    $value = addslashes($value);
  }
  return $value;
}