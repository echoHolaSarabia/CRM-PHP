<?php

class General {
  /*   * ********************
    FUNCTIONES DE SECCIONES
   * ********************* */

  /**
   * Funcion que me devuelve un array con todas las secciones
   *
   * @return array
   */
  function get_secciones($tabla = "") {
    $c = "SELECT * FROM secciones WHERE id_padre=-1 ";
    if ($tabla != "")
      $c .= " AND tabla='" . $tabla . "'";
    $r = mysql_query($c);
    $secciones = array();
    while ($fila = mysql_fetch_assoc($r)) {
      $secciones[] = $fila;
    }
    return $secciones;
  }

  /**
   * Funcion que me devuelve un array con todas las subsecciones
   *
   * @param int $seccion
   * @return array
   */
  function get_subsecciones($seccion) {
    /* if($seccion==4)
      $c = "SELECT id,titulo FROM autores_opinion ";
      else */
    $c = "SELECT * FROM secciones WHERE id_padre=" . $seccion . ";";
    $r = mysql_query($c);
    $subsecciones = array();
    while ($fila = mysql_fetch_assoc($r)) {
      $subsecciones[] = $fila;
    }
    return $subsecciones;
  }

  /**
   * Funcion que me devuelve un array con todas las secciones y subsecciones
   *
   * @return array
   */
  function get_all_secciones() {
    $c = "SELECT * FROM secciones";
    $r = mysql_query($c);
    while ($fila = mysql_fetch_assoc($r)) {
      $secciones[] = $fila;
    }
    return $secciones;
  }

  /*   * ************************
    FIN FUNCTIONES DE SECCIONES
   * ************************* */

  /*   * *******************
    FUNCIONES DE PLANILLAS
   * ******************** */

  /**
   * Devuelve un array con los datos de las planillas creadas para una sección. El identificador de sección 0 indica la portada.
   *
   * @param int $seccion
   * @return array
   */
  function get_planillas($seccion) {
    $r = mysql_query("SELECT * FROM planillas WHERE seccion=" . $seccion . " ORDER BY fecha_publicacion DESC");
    $resultado = array();
    while ($fila = mysql_fetch_assoc($r)) {
      $resultado[] = $fila;
    }
    return $resultado;
  }

  /**
   * Devuelve un array con los identificadores de las planillas a los que está asociado un elemento.
   *
   * @param int $id_elemento
   * @param int $seccion
   * @return array
   */
  function get_planillas_de_elemento($id_elemento, $seccion = 0, $tabla) {

    $query = "SELECT pe.id_planilla FROM planillas_elementos pe, planillas p WHERE pe.id_planilla = p.id AND pe.id_elemento=" . $id_elemento . " ";
    if ($seccion == 0) {
      $query .=" AND p.seccion=" . $seccion . " ";
    }
    else
      $query .=" AND p.seccion<>0 ";
    $query .= "AND tabla_elemento LIKE '" . $tabla . "'";
    $r = mysql_query($query);
    $resultado = array();
    while ($fila = mysql_fetch_assoc($r)) {
      $resultado[] = $fila['id_planilla'];
    }
    return $resultado;
  }

  /*   * *******************
    FIN FUNCIONES DE PLANILLAS
   * ******************** */

  /*   * *******************
    FUNCIONES DE IMÁGENES
   * ******************** */

  /**
   * Sube una foto al servidor en una ruta concreta
   *
   * @access public
   *
   * @param  array $file      		Array con los datos del fichero a subir
   * @param  string $carpeta      La ruta donde se van a subir los ficheros
   *
   * @return string $destino			Devuelve un string con la ruta relariva donde se ha colocado el archivo en el servidor
   *
   */
  function subirFoto($nombre, $file, $carpeta) {
    $destino = "";
    if (is_uploaded_file($file['tmp_name'])
            && ($file['type'] == "image/jpeg"
            || $file['type'] == "image/pjpeg"
            || $file['type'] == "image/gif"
            || $file['type'] == "image/png")) {
      $destino = $carpeta . $nombre;
      move_uploaded_file($file['tmp_name'], $destino);
    }
    return $destino;
  }

  /**
   * Redimensiona una foto a un ancho dado manteniendo la proporciï¿½n de alto
   *
   * @access public
   *
   * @param  array $imagen      	Array con los datos del fichero
   * @param  int $ancho						Valor del ancho al que se va a redimensionar la imagen
   * @param  string $origen      	La ruta donde estï¿½ en alrchivo a redimensionar
   * @param  string $carpeta      La ruta donde se va a quedar el fichero redimensionado
   *
   */
  function redimensiona($imagen, $ancho, $origen, $destino) {
    if ($imagen['type'] == "image/jpeg")
      $image = @imagecreatefromjpeg($origen);
    else if ($imagen['type'] == "image/pjpeg")
      $image = @imagecreatefromjpeg($origen);
    else if ($imagen['type'] == "image/gif")
      $image = @imagecreatefromgif($origen);
    else if ($imagen['type'] == "image/png")
      $image = @imagecreatefrompng($origen);
    else if ($imagen['type'] == "image/x-png")
      $image = @imagecreatefrompng($origen);

    if ($image === false)
      die('No se pudo abrir la imagen');
    // Get original width and height
    $width = imagesx($image);
    $height = imagesy($image);
    // New width and height
    $new_width = $ancho;
    $new_height = ($height * $ancho) / $width;

    // Resample
    $image_resized = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    if ($imagen['type'] == "image/jpeg")
      imagejpeg($image_resized, $destino, 100);
    else if ($imagen['type'] == "image/pjpeg")
      imagejpeg($image_resized, $destino, 100);
    else if ($imagen['type'] == "image/gif")
      imagegif($image_resized, $destino, 100);
    else if ($imagen['type'] == "image/png")
      imagepng($image_resized, $destino, 100);
    else if ($imagen['type'] == "image/x-png")
      imagepng($image_resized, $destino, 100);
  }

  function copiar_imagen($carpeta, $nombre_origen, $campo, $id, $tabla) {
    $pref = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "_";
    $nombre_destino = $pref . substr($nombre_origen, 5);
    if (!copy($carpeta . $nombre_origen, $carpeta . $nombre_destino))
      echo "Error al duplicar la imagen de la notica";
    else
      mysql_query("UPDATE " . $tabla . " SET " . $campo . "='" . $nombre_destino . "' WHERE id=" . $id);
  }

  /*   * *******************
    FIN FUNCIONES DE IMÁGENES
   * ******************** */

  function quita_caracteres_especiales($cadena) {
    $cadena = str_replace(" ", "_", $cadena);
    $cadena = str_replace("á", "a", $cadena);
    $cadena = str_replace("é", "e", $cadena);
    $cadena = str_replace("í", "i", $cadena);
    $cadena = str_replace("ó", "o", $cadena);
    $cadena = str_replace("ú", "u", $cadena);
    $cadena = str_replace("ñ", "n", $cadena);
    $cadena = str_replace("Á", "A", $cadena);
    $cadena = str_replace("É", "E", $cadena);
    $cadena = str_replace("Í", "I", $cadena);
    $cadena = str_replace("Ó", "O", $cadena);
    $cadena = str_replace("Ú", "U", $cadena);
    $cadena = str_replace("Ñ", "N", $cadena);
    return $cadena;
  }

  function get_nombre_seccion($id_seccion) {
    if ($id_seccion == 1) {
      return "Portada";
      break;
    }
    $c = "SELECT titulo FROM secciones WHERE id = " . $id_seccion;
    $r = mysql_query($c);
    $fila = mysql_fetch_row($r);
    return $fila[0];
  }

  function muestra_fecha($fecha) {
    $arr_fecha = explode(" ", $fecha);
    $arr_dia = explode("-", $arr_fecha[0]);
    $arr_dia = array_reverse($arr_dia);
    $dia = implode("/", $arr_dia);
    echo $arr_fecha[1] . " " . $dia;
  }

  function get_nombre_mes($num) {
    if ($num == 1)
      return "Enero";
    if ($num == 2)
      return "Febrero";
    if ($num == 3)
      return "Marzo";
    if ($num == 4)
      return "Abril";
    if ($num == 5)
      return "Mayo";
    if ($num == 6)
      return "Junio";
    if ($num == 7)
      return "Julio";
    if ($num == 8)
      return "Agosto";
    if ($num == 9)
      return "Septiembre";
    if ($num == 10)
      return "Octubre";
    if ($num == 11)
      return "Noviembre";
    if ($num == 12)
      return "Diciembre";
  }

  function getExtension($fichero) {
    $extension = substr($fichero, strrpos($fichero, ".") + 1, 4);
    return $extension;
  }

  /**
   * Obtiene un array con todos los ids y nombres de paises
   *
   * @access public
   *
   * @return array $paises		Array con el identificador y el nombre de cada pais
   *
   */
  function get_paises() {
    $r = mysql_query("SELECT * FROM paises ORDER BY pais ASC");
    while ($fila = mysql_fetch_assoc($r)) {
      $paises[] = $fila;
    }
    return $paises;
  }

  /**
   * Obtiene todos los ids y nombres de las provincias de un pais
   *
   * @access public
   *
   * @param  int $id_pais      Identificador del pais de donde se quieren sacar las provincias
   *
   * @return array $provincias		Array con el identificador y el nombre de cada provincia de un pais
   *
   */
  function get_provincias($id_pais) {
    $r = mysql_query("SELECT idprovincia,provincia FROM provincias WHERE idpais=" . $id_pais . " ORDER BY provincia ASC");
    while ($fila = mysql_fetch_assoc($r)) {
      $provincias[] = $fila;
    }
    return $provincias;
  }

}