<?php

/*
  Cheque que el usuario y la contraseña sean correctos. Si lo son llama a una función para que cree la sesion y lo redireccione
  al inicio y si no lo son devuelve un mensaje de error para mostrarlo en el index.
 */

function check_user($user, $pass, $guardar_datos) {
  if (!isset($guardar_datos) || ($guardar_datos == "")) {
    setcookie("usr", "");
    setcookie("pwd", "");
    setcookie("tick_guardado", "");
  }
  $user = mysql_real_escape_string($user);
  $pass = mysql_real_escape_string($pass);
  $c = "SELECT id FROM administradores WHERE usuario='" . $user . "' AND password='" . md5($pass) . "'";
  $r_aux = mysql_query($c);
  if (mysql_num_rows($r_aux) > 0) {
    crea_sesion($user, $pass);
    if (isset($guardar_datos) && $guardar_datos == "guarda") {
      setcookie("usr", $user, time() + 60 * 60 * 24 * 30);
      setcookie("pwd", $pass, time() + 60 * 60 * 24 * 30);
      setcookie("tick_guardado", "guardado", time() + 60 * 60 * 24 * 30);
    }
  } else {
    return "Nombre de usuario o contrase&ntilde;a incorrectos";
  }
}

/*
  Despues de haber comprobado que los datos son correctos creamos la sesion con el nombre y los permisos y actualizamos la hora
  del último acceso. Para finalizar hacemos una redireccion a la página principal de la aplicación.
 */

function crea_sesion($user, $pass) {
  $r = mysql_query("SELECT * FROM administradores WHERE usuario='" . $user . "' AND password='" . md5($pass) . "';");
  $fila = mysql_fetch_array($r);
  $hora = mktime();
  $S = md5($fila['nombre'] . $hora . $_SERVER['REMOTE_ADDR']);
  mysql_query("UPDATE administradores SET ultimo_acceso=NOW(), clave='" . $S . "' WHERE id=" . $fila['id'] . "");
  session_start();
  $_SESSION["usr_autentificado"] = session_id();
  $_SESSION['hora'] = $hora;
  $_SESSION['id'] = $fila['id'];
  $_SESSION['nombre'] = $fila['nombre'];
  $_SESSION['permisos'] = $fila['permisos'];
  $hora = "";
  header("Location: index2.php?modulo=mod_noticias");
}

function destruye_sesion() {
  mysql_query("UPDATE administradores SET clave='' WHERE id=" . $_SESSION['id']);
  session_destroy();
}

function es_valido() {
  if ($_SESSION["usr_autentificado"] != session_id()) {
    header("Location: index.php");
    exit();
  }
}

function tiene_permisos($modulo, $nivel_permisos) {
  $r = mysql_query("SELECT permisos FROM modulos WHERE nombre='" . $modulo . "'");
  $permisos_modulo = mysql_fetch_array($r);
  if ($permisos_modulo['permisos'] < $nivel_permisos)
    return false;
  else
    return true;
}