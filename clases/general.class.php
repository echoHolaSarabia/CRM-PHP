<?php

class General {
  ///////////////////////////////////////////////////////////////////////////////////////
  ///////////////
  /////////////// FUNCIONES GET
  ///////////////
  ///////////////////////////////////////////////////////////////////////////////////////
  //

  /**
   * O cojoquery portada
   *
   * Devuelve los elementos planificados en una planilla sacados de todas las tablas 	configuradas
   * Si programados se pone a 0 devuelve sólo los elementos no programados (Ya publicados)
   *
   * @param int $id_planilla
   * @return Matriz
   *
   */
  function get_elementos_planilla($id_planilla, $programados = 0) {

    $num_columnas = $this->num_columnas;
    $resultado = array();
    $query = "";
    $j = 0;

    if ($programados)
      $where_prog = "";
    else
      $where_prog = "AND planillas_elementos.programado = 0";
    //print_r($this);
    foreach ($this->tablas as $tabla) {
      if ($j > 0)
        $query .= " UNION ";
      else
        $j++;
      $query .= " (SELECT planillas_elementos.id as planillas_elementos_id, " . $tabla . ".id," . $tabla . ".titulo," . $tabla . ".fecha_publicacion," . $tabla . ".activo, planillas_elementos.tabla_elemento";
      for ($i = 0; $i < $num_columnas; $i++) {
        $query .= ",planillas_elementos.orden" . $i;
      }
      $query .= ",planillas_elementos.programado,planillas_elementos.bloqueado ";

      if ($tabla == "noticias") {
        $query.= ", " . $tabla . ".recurso_maquetacion, " . $tabla . ".foto_extra," . $tabla . ".tamanio_titular," . $tabla . ".entradilla," . $tabla . ".antetitulo," . $tabla . ".subtitular," . $tabla . ".tags," . $tabla . ".titular_alt," . $tabla . ".antetitulo_alt," . $tabla . ".entradilla_alt," . $tabla . ".recomendado," . $tabla . ".hits," . $tabla . ".seccion," . $tabla . ".subseccion," . $tabla . ".cod_video," . $tabla . ".video_portada," . $tabla . ".video_seccion," . $tabla . ".posicion_imagen_portada," . $tabla . ".tipo_video," . $tabla . ".img_rotador," . $tabla . ".img_horizontal," . $tabla . ".img_vertical," . $tabla . ".img_cuadrada," . $tabla . ".num_comentarios," . $tabla . ".tipo_audio," . $tabla . ".cod_audio," . $tabla . ".audio_portada," . $tabla . ".audio_seccion," . $tabla . ".doc_1," . $tabla . ".nombre_doc_1," . $tabla . ".doc_2," . $tabla . ".nombre_doc_2," . $tabla . ".doc_3," . $tabla . ".nombre_doc_3, secciones.titulo as nombre_seccion, subsecciones.titulo as nombre_subseccion, 0 as ruta_archivo, 0 as codigo, noticias_relacionadas.id_relacionada as id_relacionada, secciones_rel1.titulo as nombre_seccion_rel, secciones_rel2.titulo as nombre_subseccion_rel, noticias_rel.titulo as titulo_rel, noticias_rel.tags as tags_rel,  noticias_relacionadas.tipo_elemento as tipo_rel, 0 as lugar_celebracion,0 as fecha_inicio, 0 as tipo_rosa";

        //$query .= " FROM planillas_elementos, secciones as secciones_1, secciones as secciones_2 , ".$tabla;
        $query .= " FROM planillas_elementos, secciones as secciones, secciones as subsecciones, " . $tabla;
        $query .= " LEFT JOIN noticias_relacionadas ON " . $tabla . ".id = noticias_relacionadas.id_noticia";
        $query .= " LEFT JOIN noticias as noticias_rel ON noticias_relacionadas.id_relacionada = noticias_rel.id";

        $query .= " LEFT JOIN secciones as secciones_rel1 ON noticias_rel.seccion = secciones_rel1.id";
        $query .= " LEFT JOIN secciones as secciones_rel2 ON noticias_rel.subseccion = secciones_rel2.id";
        $query .= " WHERE ((" . $tabla . ".seccion=secciones.id AND " . $tabla . ".subseccion = subsecciones.id AND " . $tabla . ".subseccion <> 0 AND " . $tabla . ".seccion <> 4) OR ( (" . $tabla . ".subseccion = 0 OR " . $tabla . ".seccion = 4) AND " . $tabla . ".seccion = secciones.id AND " . $tabla . ".seccion = subsecciones.id)) AND " . $tabla . ".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '" . $tabla . "' AND  planillas_elementos.id_planilla=" . $id_planilla . " AND planillas_elementos.planificado=1 " . $where_prog . " )";
      } else if ($tabla == "modulosrosas") {
        $query.= ", 0 as recurso_maquetacion, 0 as foto_extra, 0 as tamanio_titular,0 as entradilla,0 as antetitulo,0 as subtitular,0 as tags,0 as titular_alt,0 as antetitulo_alt,0 as entradilla_alt,0 as recomendado,0 as hits,0 as seccion,0 as subseccion,0 as cod_video,0 as video_portada,0 as video_seccion,0 as posicion_imagen_portada,0 as tipo_video,0 as img_rotador,0 as  img_horizontal,0 as img_vertical,0 as  img_cuadrada,0 as  num_comentarios,0 as tipo_audio,0 as cod_audio,0 as audio_portada,0 as audio_seccion,0 as doc_1, 0 as nombre_doc_1, 0 as doc_2, 0 as nombre_doc_2, 0 as doc_3, 0 as nombre_doc_3, 0 as nombre_seccion,0 as nombre_subseccion, " . $tabla . ".ruta_archivo, " . $tabla . ".codigo, 0 as id_relacionada,0 as nombre_seccion_rel,0 as nombre_subseccion_rel,0 as titulo_rel,0 AS tags_rel, 0 as tipo_rel, 0 as lugar_celebracion,0 as fecha_inicio ," . $tabla . ".tipo as tipo_rosa ";
        $query .= " FROM planillas_elementos, " . $tabla;
        $query .= " WHERE " . $tabla . ".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '" . $tabla . "' AND  planillas_elementos.id_planilla=" . $id_planilla . " AND planillas_elementos.planificado=1 " . $where_prog . " )";
      } else if ($tabla == "modulosnegros") {
        $query.= ", 0 as recurso_maquetacion, 0 as foto_extra, 0 as tamanio_titular,0 as entradilla,0 as antetitulo,0 as subtitular,0 as tags,0 as titular_alt,0 as antetitulo_alt,0 as entradilla_alt,0 as recomendado,0 as hits,0 as seccion,0 as subseccion,0 as cod_video,0 as video_portada,0 as video_seccion,0 as posicion_imagen_portada,0 as tipo_video,0 as img_rotador,0 as  img_horizontal,0 as img_vertical,0 as  img_cuadrada,0 as  num_comentarios,0 as tipo_audio,0 as cod_audio,0 as audio_portada,0 as audio_seccion,0 as doc_1, 0 as nombre_doc_1, 0 as doc_2, 0 as nombre_doc_2, 0 as doc_3, 0 as nombre_doc_3,0 as nombre_seccion,0 as nombre_subseccion,0 as ruta_archivo, 0 as codigo, 0 as id_relacionada,0 as nombre_seccion_rel,0 as nombre_subseccion_rel, 0 as titulo_rel,0 AS tags_rel, 0 as tipo_rel, 0 as lugar_celebracion,0 as fecha_inicio, 0 as tipo_rosa ";
        $query .= " FROM planillas_elementos, " . $tabla;
        $query .= " WHERE " . $tabla . ".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '" . $tabla . "' AND  planillas_elementos.id_planilla=" . $id_planilla . " AND planillas_elementos.planificado=1 " . $where_prog . " )";
      } else {
        $query.= ", " . $tabla . ".recurso_maquetacion, 0 as foto_extra," . $tabla . ".tamanio_titular," . $tabla . ".entradilla," . $tabla . ".antetitulo," . $tabla . ".subtitular," . $tabla . ".tags," . $tabla . ".titular_alt," . $tabla . ".antetitulo_alt," . $tabla . ".entradilla_alt," . $tabla . ".recomendado," . $tabla . ".hits," . $tabla . ".seccion,0 AS subseccion," . $tabla . ".cod_video," . $tabla . ".video_portada," . $tabla . ".video_seccion," . $tabla . ".posicion_imagen_portada," . $tabla . ".tipo_video," . $tabla . ".img_rotador," . $tabla . ".img_horizontal," . $tabla . ".img_vertical," . $tabla . ".img_cuadrada," . $tabla . ".num_comentarios," . $tabla . ".tipo_audio," . $tabla . ".cod_audio," . $tabla . ".audio_portada," . $tabla . ".audio_seccion," . $tabla . ".doc_1," . $tabla . ".nombre_doc_1," . $tabla . ".doc_2," . $tabla . ".nombre_doc_2," . $tabla . ".doc_3," . $tabla . ".nombre_doc_3, '" . $tabla . "' as nombre_seccion, 0 as ruta_archivo, 0 as codigo, 0 as id_relacionada, 0 as nombre_seccion_rel, 0 as nombre_subseccion_rel, 0 as titulo_rel, 0 as tags_rel,  0 as tipo_rel, " . $tabla . ".lugar_celebracion, " . $tabla . ".fecha_inicio, 0 as tipo_rosa";
        $query .= " FROM planillas_elementos, " . $tabla;
        $query .= " WHERE " . $tabla . ".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '" . $tabla . "' AND  planillas_elementos.id_planilla=" . $id_planilla . " AND planillas_elementos.planificado=1 " . $where_prog . " )";
      }
    }
    for ($i = 0; $i < $num_columnas; $i++) {
      if ($i > 0)
        $query .= ",";
      else
        $query .= " ORDER BY ";
      $query .= "orden" . $i;
    }
    $query .= " ,tipo_rel DESC ";

    //echo $query;

    $r = mysql_query($query)
    //or print(mysql_error())
    ;

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
    for ($i = 0; $i < 7; $i++) {
      if (!isset($resultado[$i]))
        $resultado[$i] = array();
    }
    return $resultado;
  }

  function hay_elementos_en_hoja($elementos, $hoja) {
    $hay = 0;
    for ($a = 0; $a < 7; $a++) {
      if (isset($elementos[$a]) && isset($elementos[$a][0]) && round($elementos[$a][0]["orden" . $a] / 100) == $hoja) {
        $hay = 1;
        break;
      }
    }
    return $hay;
  }

  function get_encuesta() {
    $c = "SELECT * FROM encuestas WHERE activa=1 ORDER BY id DESC LIMIT 1";
    $r = mysql_fetch_array(mysql_query($c));
    return $r;
  }

  /**
   * Redimensiona un video
   *
   * @access public
   *
   * @param  array $codigo			 	Cï¿½digo del video
   * @param  array $ancho			 	  El ancho deseado
   * @param  array $alto			 		El alto deseado
   *
   * @return array $codigo				Cï¿½digo del video con el nuevo tamaï¿½o
   *
   */
  function redimensionaVideo($codigo, $ancho, $alto) {
    if ($ancho == "")
      $ancho = $alto * 1.24;
    $codigo = preg_replace("/width=.[0-9]*./", "width=\"" . $ancho . "\"", $codigo);

    if ($alto == "")
      $alto = $ancho / 1.24;
    $codigo = preg_replace("/height=.[0-9]*./", "height=\"" . $alto . "\"", $codigo);

    return $codigo;
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