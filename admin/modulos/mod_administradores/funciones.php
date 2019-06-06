<?php

class Funciones {

  var $control; //Array con los datos recibidos por GET y que nos indican la función a realizar
  var $datos; //Array con los datos recibudos del formulario por POST
  var $tabla; //Tabla donde se insertarán los daros
  var $url_home;

  function funciones($control_recibido, $datos_recibidos) {
    $this->control = $control_recibido;
    $this->datos = $datos_recibidos;
    $this->tabla = "administradores";
    $this->url_home = "index2.php?modulo=mod_administradores";
  }

  function start() {
    $accion = $this->control['accion'];
    if ($accion != '') {
      switch ($accion) {
        case "insert":
          $this->insert($this->datos);
          break;
        case "update":
          $this->update($this->control['id'], $this->datos);
          break;
        case "delete":
          $this->delete($this->datos);
          break;
        case "estado":
          $this->cambia_estado($this->control['id']);
          break;
        default:
          return false;
          break;
      }
    } else {
      return false;
    }
  }

  /* function insert($arr_datos){
    $c="INSERT INTO ".$this->tabla." (id";
    foreach ($arr_datos as $clave => $valor){
    $c.=",".$clave;
    }
    $c.=",activo) VALUES (null";
    foreach ($arr_datos as $clave => $valor){
    if (is_numeric($valor)){
    $c.=",".$valor;
    } else {
    $c.=",'".addslashes($valor)."'";
    }
    }
    $c.=",0);";
    //echo $c;
    mysql_query($c);//Comprobar que no se produce error
    $this->redireccionar();
    } */

  function insert($arr_datos) {
    $c = "INSERT INTO " . $this->tabla . " (id";
    foreach ($arr_datos as $clave => $valor) {
      $c.="," . $clave;
    }
    $c.=",activo) VALUES (null";
    foreach ($arr_datos as $clave => $valor) {
      if ($clave == "password")
        $c.= ",'" . md5($valor) . "'";
      else {
        if (is_numeric($valor)) {
          $c.="," . $valor;
        } else {
          $c.=",'" . addslashes($valor) . "'";
        }
      }
    }
    $c.=",0);";
    //echo $c;
    mysql_query($c); //Comprobar que no se produce error
    $this->redireccionar();
  }

  function update($id, $arr_datos) {
    $iteracion = 1;
    $c = "UPDATE " . $this->tabla . " SET ";
    foreach ($arr_datos as $clave => $valor) {
      if ($iteracion == 1) {
        if (is_numeric($valor)) {
          if ($clave == "password") {
            $q_aux = "SELECT password FROM administradores WHERE id=" . $id;
            $r_aux = mysql_query($q_aux);
            $aux = mysql_fetch_assoc($r_aux);
            if ($valor != $aux['password'])
              $c.="," . $clave . "='" . md5($valor) . "'";
          }
          else
            $c.=$clave . "=" . $valor;
        } else {
          if ($clave == "password") {
            $q_aux = "SELECT password FROM administradores WHERE id=" . $id;
            $r_aux = mysql_query($q_aux);
            $aux = mysql_fetch_assoc($r_aux);
            if ($valor != $aux['password'])
              $c.=$clave . "='" . md5($valor) . "'";
          }
          else
            $c.=$clave . "='" . addslashes($valor) . "'";
        }
      }else {
        if (is_numeric($valor)) {
          if ($clave == "password") {
            $q_aux = "SELECT password FROM administradores WHERE id=" . $id;
            $r_aux = mysql_query($q_aux);
            $aux = mysql_fetch_assoc($r_aux);
            if ($valor != $aux['password'])
              $c.="," . $clave . "='" . md5($valor) . "'";
          }
          else
            $c.="," . $clave . "=" . $valor;
        } else {
          if ($clave == "password") {
            $q_aux = "SELECT password FROM administradores WHERE id=" . $id;
            $r_aux = mysql_query($q_aux);
            $aux = mysql_fetch_assoc($r_aux);
            if ($valor != $aux['password'])
              $c.="," . $clave . "='" . md5($valor) . "'";
          }
          else
            $c.="," . $clave . "='" . addslashes($valor) . "'";
        }
      }
      $iteracion++;
    }
    $c.=" WHERE id=" . $id;
    mysql_query($c); //Comprobar que no se produce error
    $this->redireccionar();
  }

  /* function update($id,$arr_datos){
    $iteracion = 1;
    $c="UPDATE ".$this->tabla." SET ";
    foreach ($arr_datos as $clave => $valor){
    if ($iteracion == 1){
    if (is_numeric($valor)){
    $c.=$clave."=".$valor;
    } else {
    $c.=$clave."='".addslashes($valor)."'";
    }
    }else{
    if (is_numeric($valor)){
    $c.=",".$clave."=".$valor;
    } else {
    $c.=",".$clave."='".addslashes($valor)."'";
    }
    }
    $iteracion ++;
    }
    $c.=" WHERE id=".$id;
    //echo $c;
    mysql_query($c);//Comprobar que no se produce error
    $this->redireccionar();
    } */

  /*
    Borra a los usuarios seleccionados
   */

  function delete($elementos) {
    foreach ($elementos as $clave => $valor) {
      if ($clave != "seleccion") {//No incluyo el checkbox que selecciona todos y que tiene valor "on"
        $c = "DELETE FROM " . $this->tabla . " WHERE id = " . $valor . ";";
        mysql_query($c);
      }
    }
    $this->redireccionar();
  }

  function cambia_estado($id) {
    $c = "SELECT activo FROM " . $this->tabla . " WHERE id=" . $id;
    $r = mysql_query($c);
    $estado = mysql_fetch_array($r);
    if ($estado['activo'] == 1) {
      $nuevo_estado = 0;
    } else {
      $nuevo_estado = 1;
    }
    $c = "UPDATE " . $this->tabla . " SET activo=" . $nuevo_estado . " WHERE id=" . $id;
    mysql_query($c);
    $this->redireccionar();
  }

  /*
    Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar
    contenido
   */

  function redireccionar() {
    header("Location: " . $this->url_home);
  }

}