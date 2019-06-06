<?php

class Funciones extends General {

  var $control; //Array con los datos recibidos por GET y que nos indican la funci�n a realizar
  var $datos; //Array con los datos recibudos del formulario por POST
  var $url_home;

  function funciones($control_recibido = "", $datos_recibidos = "", $extra = "") {
    $this->control = $control_recibido;
    $this->datos = $datos_recibidos;
    $this->tabla = "noticias";
    if ($extra != "")
      $this->tabla = $extra;
    else
      $this->tabla = "noticias";
    $this->url_home = "index2.php?modulo=mod_noticias";
  }

  function start() {
    $accion = $this->control['accion'];
    if ($accion != '') {
      switch ($accion) {
        case "insert":
          // Se inserta en las planillas de secci�n en la primera posici�n
          // Se pone como planificable en la de portada
          $this->insert($this->datos, $_FILES, "", $this->control['redireccion']);
          break;
        case "update":
          // Se llama a actualizar_planillas_elemento() con sus planillas
          $this->update($this->datos, $this->control['id'], $_FILES, $this->control['redireccion']);
          break;
        case "delete":
          // Se llama a unset_elemento_planificable() en las planillas donde est�
          $this->delete($this->datos);
          break;
        case "estado":
          // Se llama a actualizar_planillas_elemento() con sus planillas
          $extra = (isset($this->control['extra'])) ? $this->control['extra'] : "";
          $this->cambia_estado($this->control['id'], $this->control['campo'], $this->control['page'], $extra);
          break;
        case "duplicar":
          $this->duplicar($this->datos);
          break;
        case "almacenar":
          $this->almacenar_en_hemeroteca($this->datos);
          break;
        case "update_listado":
          $this->actualizar_listado($this->control);
          break;
        case "newsletter":
          $extra = (isset($this->control['extra'])) ? $this->control['extra'] : "";
          $this->exportar_newsletter($this->control, $this->datos, $extra);
          break;
        case "borra_imagen":
          $this->borrar_imagen($this->control['id'], $this->control['imagen'], $this->control['tipo']);
          break;
        case "borra_doc":
          $this->borrar_doc($this->control['id'], $this->control['doc'], $this->control['tipo']);
          break;
        case "upl":
          $this->subir_imagenes($this->control['id'], "carpeta", $_FILES);
          break;
        case "elimina_relacion":
          $this->eliminar_relacion($this->control['id_noticia'], $this->control['id_relacionada'], $this->control['tipo_elemento']);
          break;
        default:
          return false;
          break;
      }
    } else {
      return false;
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  ///////////
  //////////  Funciones de acciones del usuario
  ///////////
  ////////////////////////////////////////////////////////////////////////////////////////


  function insert($arr_datos, $files, $accion = "", $redireccion) {
    if ($arr_datos['subseccion'] == "")
      $arr_datos['subseccion'] = 0;
    //Se contruye la consulta que inserta la noticia
    $excluir = array("planillas_portada", "planillas_seccion", "exportar", "exportar_audio");
    $c = "INSERT INTO " . $this->tabla . " (id";
    foreach ($arr_datos as $clave => $valor) {
      if (!in_array($clave, $excluir))
        $c.="," . $clave;
    }
    $c.=") VALUES (null";
    foreach ($arr_datos as $clave => $valor) {
      if (!in_array($clave, $excluir)) {
        //if (ereg("fecha_*", $clave)) {
        if (preg_match("/fecha_*/", $clave)) {
          if (($clave == "fecha_creacion") || ($clave == "fecha_modificacion"))
            $c .= ",NOW()";
          else if (($clave == "fecha_publicacion") && $valor == "")
            $c .= ",NOW()";
          else
            $c .= ",'" . formatea_fecha_hora($valor) . "'";
        }else if (is_numeric($valor))
          $c.="," . $valor;
        else
          $c.=",'" . addslashes(html_entity_decode($valor)) . "'";
      }
    }
    $c.=");";
    mysql_query($c);
    echo mysql_error();
    $id = mysql_insert_id();

    $anchos = array(370, 129);
    //Subimos la foto con su miniatura
    if ($files['extra']['name'] != "" && ($files['extra']['type'] == "image/jpeg" || $files['extra']['type'] == "image/pjpeg" || $files['extra']['type'] == "image/gif" || $files['extra']['type'] == "image/png")) {
      $raiz = "../userfiles/extra/";
      $pref = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "_";
      $nombreFoto = $pref . $this->quita_caracteres_especiales($files['extra']['name']);
      $origen = $this->subirFoto($nombreFoto, $files['extra'], $raiz);
      foreach ($anchos as $ancho) {
        $destino = $raiz . "thumbs/" . $ancho . "_" . $nombreFoto;
        $this->redimensiona($files['extra'], $ancho, $origen, $destino);
      }
      mysql_query("UPDATE " . $this->tabla . " SET foto_extra='" . $nombreFoto . "' WHERE id=" . $id);
    }

    //Si estoy subiendo un archivo de video se guardar� el nombre del archivo en el campo cod_video.
    if ($arr_datos['tipo_video'] == "fichero")
      $this->sube_video($id, $files['cod_video']);

    //Si he indicado que quiero exportar el video lo inserto en la tabla multimedia
    if (isset($arr_datos['exportar']) && $arr_datos['exportar'] == "si") {
      $r = mysql_query("SELECT MAX(orden) AS ultimo FROM multimedia;");
      $orden = mysql_fetch_array($r);
      $orden = $orden['ultimo'] + 1;
      mysql_query("INSERT INTO multimedia (titular,codigo,orden,autor,tipo_fuente) VALUES ('" . $arr_datos['titulo'] . "','" . $arr_datos['cod_video'] . "'," . $orden . ",'" . $arr_datos['autor'] . "','codigo')");
    }

    //Si estoy subiendo un archivo de video se guardar� el nombre del archivo en el campo cod_video.
    if ($arr_datos['tipo_audio'] == "fichero_audio")
      $this->sube_audio($id, $files['cod_audio']);

    //SUBE LOS DOCUMENTOS
    $this->subir_documentos($id, $files);

    if (isset($arr_datos['planillas_portada']))
      $planillas_portada = implode("-", $arr_datos['planillas_portada']);
    else
      $planillas_portada = "";


    if (isset($arr_datos['planillas_seccion']))
      $planillas_seccion = implode("-", $arr_datos["planillas_seccion"]);
    else
      $planillas_seccion = "";

    if (isset($arr_datos['activo']))
      $activo = 1;
    else
      $activo = 0;

    if ($arr_datos["fecha_publicacion"] == "")
      $fecha_publicacion = "0000-00-00 00:00:00";
    else
      $fecha_publicacion = $arr_datos["fecha_publicacion"];

    $redireccion_mod_planillas = "index2.php?modulo=mod_planillas&accion=actualizar&fns=1&id=" . $id . "&tabla=" . $this->tabla . "&planillas_portada=" . $planillas_portada . "&planillas_seccion=" . $planillas_seccion . "&activo=" . $activo . "&fecha_publicacion=" . $fecha_publicacion . "&redireccion=" . $redireccion . "&extra=" . $this->tabla;

    header("Location: " . $redireccion_mod_planillas);
    die;
    $this->redireccionar();

    //$this->rellena_planilla_portada($id,$arr_datos['planillas_portada']);
    //foreach ($arr_datos['planillas_seccion'] as $unaPlanillaSeccion) {
    //	$this -> actualiza_planilla ($unaPlanillaSeccion,$id);
    //}
    //$this->rellena_planilla_seccion($id,$arr_datos['planillas_seccion']);

    /* if ($arr_datos['activo'] == 1){
      //$id_planilla = $this -> get_idplanilla_actual($arr_datos['seccion']);
      foreach ($arr_datos['planillas_seccion'] as $unaPlanillaSeccion) {
      $this -> actualiza_planilla ($unaPlanillaSeccion,$id);
      }
      } */
  }

  function update($arr_datos, $id, $files, $redireccion) {
    //Si no marco subseccion por defecto le pongo 0
    if ($arr_datos['subseccion'] == "")
      $arr_datos['subseccion'] = 0;
    //Array de elementos a excluir de la query
    $excluir = array("activo", "portada", "planillas_portada", "planillas_seccion", "exportar");
    //CONSTRUIMOS LA QUERY
    $iteracion = 1;
    $c = "UPDATE " . $this->tabla . " SET ";
    foreach ($arr_datos as $clave => $valor) {
      if (!in_array($clave, $excluir))
        if ($iteracion == 1)
          if (is_numeric($valor))
            $c.=$clave . "=" . $valor;
          else
            $c.=$clave . "='" . addslashes($valor) . "'";
//        else if (ereg("fecha_*", $clave))
        else if (preg_match('/fecha_*/', $clave))
          $c.="," . $clave . "='" . formatea_fecha($valor) . "'";
        else if (is_numeric($valor))
          $c.="," . $clave . "=" . $valor;
        else
          $c.="," . $clave . "='" . addslashes(html_entity_decode($valor)) . "'";
      $iteracion++;
    }
    //Los campos CHECKBOX hay que tratarlos a parte, porque cuando no estan checkados no se env�an
    if (isset($arr_datos['activo']))
      $c.=",activo=" . $arr_datos['activo'];
    else
      $c.=",activo=0";

    if (isset($arr_datos['recomendado']))
      $c.=",recomendado=" . $arr_datos['recomendado'];
    else
      $c.=",recomendado=0";

    if (isset($arr_datos['video_portada']))
      $c.=",video_portada=" . $arr_datos['video_portada'];
    else
      $c.=",video_portada=0";

    if (isset($arr_datos['video_seccion']))
      $c.=",video_seccion=" . $arr_datos['video_seccion'];
    else
      $c.=",video_seccion=0";

    if (isset($arr_datos['video_ampliada']))
      $c.=",video_ampliada=" . $arr_datos['video_ampliada'];
    else
      $c.=",video_ampliada=0";

    if (isset($arr_datos['audio_portada']))
      $c.=",audio_portada=" . $arr_datos['audio_portada'];
    else
      $c.=",audio_portada=0";

    if (isset($arr_datos['audio_seccion']))
      $c.=",audio_seccion=" . $arr_datos['audio_seccion'];
    else
      $c.=",audio_seccion=0";

    if (isset($arr_datos['audio_ampliada']))
      $c.=",audio_ampliada=" . $arr_datos['audio_ampliada'];
    else
      $c.=",audio_ampliada=0";


    $c.=" WHERE id=" . $id;
    mysql_query($c);
    echo mysql_error();

    $anchos = array(370, 129);
    //Subimos la foto con su miniatura
    if ($files['extra']['name'] != "" && ($files['extra']['type'] == "image/jpeg" || $files['extra']['type'] == "image/pjpeg" || $files['extra']['type'] == "image/gif" || $files['extra']['type'] == "image/png")) {
      $raiz = "../userfiles/extra/";
      $pref = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "_";
      $nombreFoto = $pref . $this->quita_caracteres_especiales($files['extra']['name']);
      $origen = $this->subirFoto($nombreFoto, $files['extra'], $raiz);
      foreach ($anchos as $ancho) {
        $destino = $raiz . "thumbs/" . $ancho . "_" . $nombreFoto;
        $this->redimensiona($files['extra'], $ancho, $origen, $destino);
      }
      mysql_query("UPDATE " . $this->tabla . " SET foto_extra='" . $nombreFoto . "' WHERE id=" . $id);
    }

    //Si estoy subiendo un archivo de video se guardar� el nombre del archivo en el campo cod_video.
    if ($arr_datos['tipo_video'] == "fichero")
      $this->sube_video($id, $files['cod_video']);

    //Si he indicado que quiero exportar el video lo inserto en la tabla multimedia
    if (isset($arr_datos['exportar']) && $arr_datos['exportar'] == "si") {
      $r = mysql_query("SELECT MAX(orden) AS ultimo FROM multimedia;");
      $orden = mysql_fetch_array($r);
      $orden = $orden['ultimo'] + 1;
      mysql_query("INSERT INTO multimedia (titular,codigo,orden,autor,tipo_fuente) VALUES ('" . $arr_datos['titulo'] . "','" . $arr_datos['cod_video'] . "'," . $orden . ",'" . $arr_datos['autor'] . "','codigo')");
    }

    //Si estoy subiendo un archivo de video se guardar� el nombre del archivo en el campo cod_video.
    if ($arr_datos['tipo_audio'] == "fichero_audio")
      $this->sube_audio($id, $files['cod_audio']);

    //SUBE LOS DOCUMENTOS
    $this->subir_documentos($id, $files);

    ///////////////////////
    ////// Envio a mod_planillas
    ///////////////////////

    if (isset($arr_datos['planillas_portada']))
      $planillas_portada = implode("-", $arr_datos['planillas_portada']);
    else
      $planillas_portada = "";


    if (isset($arr_datos['planillas_seccion']))
      $planillas_seccion = implode("-", $arr_datos["planillas_seccion"]);
    else
      $planillas_seccion = "";

    if (isset($arr_datos['activo']))
      $activo = 1;
    else
      $activo = 0;

    if ($arr_datos["fecha_publicacion"] == "")
      $fecha_publicacion = "0000-00-00 00:00:00";
    else
      $fecha_publicacion = $arr_datos["fecha_publicacion"];

    $redireccion_mod_planillas = "index2.php?modulo=mod_planillas&accion=actualizar&fns=1&id=" . $id . "&tabla=" . $this->tabla . "&planillas_portada=" . $planillas_portada . "&planillas_seccion=" . $planillas_seccion . "&activo=" . $activo . "&fecha_publicacion=" . $fecha_publicacion . "&redireccion=" . $redireccion . "&extra=" . $this->tabla;
    //echo $redireccion_mod_planillas;

    header("Location: " . $redireccion_mod_planillas);
    die;





    //SUBE LOS VIDEOS ASOCIADOS
    if ($files['video'] != "") {
      $this->sube_video($id, $files['video']);
    }


    //OBTENGO LAS PLANILLAS ACTUALES DE PORTADA Y DE SECCION QUE TIENEN LA NOTICIA A EDITAR
    $planillas_portada = $this->get_planillas_de_elemento($id);
    $planillas_seccion = $this->get_planillas_de_elemento($id, $seccion_anterior['seccion']);

    //ELIMINO LAS PLANILLAS QUE HE DESELECCIONADO
    foreach ($planillas_portada as $planilla) {
      if (!in_array($planilla, $arr_datos['planillas_portada'])) {
        $this->quita_de_planilla($planilla, $id);
      }
    }

    foreach ($planillas_seccion as $planilla) {
      if (!in_array($planilla, $arr_datos['planillas_seccion'])) {
        $this->quita_de_planilla($planilla, $id);
      }
    }

    //INSERTO A LA PLANILLA LAS NUEVAS QUE HE SELECCIONADO
    foreach ($arr_datos['planillas_portada'] as $planilla) {
      if (!in_array($planilla, $planillas_portada)) {
        $c = "INSERT INTO planillas_elementos (id_planilla,id_elemento,tipo_elemento,orden_col1,orden_col2,orden_col3,orden_col0,planificada) \n
						VALUES (" . $planilla . "," . $id . ",'noticias',0,0,0,0,0);";
        mysql_query($c);
      }
    }

    foreach ($arr_datos['planillas_seccion'] as $planilla) {
      if (!in_array($planilla, $planillas_seccion)) {
        $this->actualiza_planilla($planilla, $id);
      }
    }


    /* if ($arr_datos['seccion'] != $seccion_anterior['seccion']){
      $r = mysql_query("SELECT pe.id FROM planillas_elementos pe,planillas p WHERE p.id=pe.id_planilla AND p.seccion<>0 AND pe.id_elemento=".$id);
      while ($fila = mysql_fetch_assoc($r)){
      mysql_query("DELETE FROM planillas_elementos WHERE id=".$fila['id']);
      }
      } */

    if (($seccion_anterior['recurso_maquetacion'] == "hoy_en" || $seccion_anterior['recurso_maquetacion'] == "destacado") && ($arr_datos['recurso_maquetacion'] == "modulo_cuatro" || $arr_datos['recurso_maquetacion'] == "modulo_dos")) {
      mysql_query("DELETE FROM planillas_elementos WHERE id_elemento=" . $id);
    }

    if ($redireccion != "")
      $this->redireccionar2($id);
    else
      $this->redireccionar();
  }

  /**
   * Cambia el estado de los campos pasados como par�metros (de activo a inactivo y viceversa)
   *
   * @param unknown_type $id
   * @param unknown_type $campo
   * @param unknown_type $pagina
   */
  function cambia_estado($id, $campo, $pagina, $extra) {
    //Obtengo algunos datos de la noticia a la que voy a cambiar el estado
    $r = mysql_query("SELECT activo,seccion,subseccion,fecha_publicacion FROM " . $this->tabla . " WHERE id=" . $id);
    $estado = mysql_fetch_array($r);

    //Veo cual va a ser el estado final
    if ($estado[$campo] == 1) {
      $nuevo_estado = 0;
    } else {
      $nuevo_estado = 1;
    }

    //Actualizo el estado en la tabla de noticias
    $c = "UPDATE " . $this->tabla . " SET " . $campo . "=" . $nuevo_estado . " WHERE id=" . $id;
    mysql_query($c);


    ////////// Preparo los campos $id,$tabla,$activo,$fecha_publicacion,$redireccion para mandar a mod_planillas

    $activo = $nuevo_estado;
    $fecha_publicacion = $estado["fecha_publicacion"];

    $redireccion_mod_planillas = "index2.php?modulo=mod_planillas&accion=actualizar_click&fns=1&id=" . $id . "&tabla=" . $this->tabla . "&activo=" . $activo . "&fecha_publicacion=" . $fecha_publicacion . "&extra=" . $extra;

    header("Location: " . $redireccion_mod_planillas);
    die;
    $this->redireccionar($pagina);
  }

  /**
   * Enter description here...
   *
   * @param unknown_type $elementos
   */
  function delete($elementos) {
    $carpeta = "../docs/";
    $id = "";
    foreach ($elementos as $clave => $valor) {
      if ($clave != "seleccion") {//No incluyo el checkbox que selecciona todos y que tiene valor "on"
        //Se borran los dicumentos asociados
        /*
          $docs = $this->get_docs_noticia($valor);
          if (file_exists($carpeta.$docs['doc_1']))
          unlink($carpeta.$docs['doc_1']);
          if (file_exists($carpeta.$docs['doc_2']))
          unlink($carpeta.$docs['doc_2']);
          if (file_exists($carpeta.$docs['doc_3']))
          unlink($carpeta.$docs['doc_3']);
         */
        mysql_query("DELETE FROM " . $this->tabla . " WHERE id = " . $valor . ";");
        mysql_query("DELETE FROM noticias_relacionadas WHERE id_noticia = " . $valor . " OR (id_relacionada=" . $valor . " && tipo_elemento='noticias');");
        $ids .= "-" . $valor;
      }
    }

    $redireccion_mod_planillas = "index2.php?modulo=mod_planillas&accion=eliminar_elementos&fns=1&ids=" . $ids . "&tabla=" . $this->tabla . "&activo=" . $activo . "&fecha_publicacion=" . $fecha_publicacion;
    header("Location: " . $redireccion_mod_planillas);
  }

  function eliminar_relacion($id_noticia, $id_relacionada, $tipo_elemento) {
    mysql_query("DELETE FROM noticias_relacionadas WHERE id_noticia = " . $id_noticia . " AND id_relacionada = " . $id_relacionada . " AND tipo_elemento='" . $tipo_elemento . "'");
    mysql_query("DELETE FROM noticias_relacionadas WHERE id_noticia = " . $id_relacionada . " AND id_relacionada = " . $id_noticia . " AND tipo_elemento='" . $tipo_elemento . "'");
    $this->redireccionar2($id_noticia);
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  ///////////
  //////////  FIN de Funciones de acciones del usuario
  ///////////
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  ///////////
  //////////  Funciones de consultas
  ///////////
  ////////////////////////////////////////////////////////////////////////////////////////

  /*
    Obtiene la query para listar las noticias
    Devuelve por referencia los par�metros necesarios.
   */
  function get_query_palabra($page, $items, &$q, $seccion, &$sqlStr, &$sqlStrAux, &$limit, $orden, $tipo_orden, $no_publicadas) {
    if (isset($page) and is_numeric($page))
      $limit = " LIMIT " . (($page - 1) * $items) . ",$items";
    else
      $limit = " LIMIT $items";

    //if (isset($q) and !eregi('^ *$', $q)) {
    if (isset($q) and !preg_match('/^ *$/', $q)) {
      $q = sql_quote($q); //para ejecutar consulta
      $busqueda = htmlentities($q); //para mostrar en pantalla

      $sqlStr = "SELECT * FROM " . $this->tabla . " WHERE 1=1 ";
      if ($no_publicadas != "")
        $sqlStr .= " AND fecha_publicacion>NOW()";
      if ($seccion != "")
        $sqlStr .= " AND (seccion = " . $seccion . " OR subseccion = " . $seccion . ")";
      $sqlStr .= " AND titulo LIKE '%$q%' ORDER BY " . $orden . " " . $tipo_orden . "";

      $sqlStrAux = "SELECT count(*) as total FROM " . $this->tabla . " WHERE 1=1 ";
      if ($no_publicadas != "")
        $sqlStrAux .= " AND fecha_publicacion>NOW()";
      if ($seccion != "")
        $sqlStrAux .= " AND (seccion = " . $seccion . " OR subseccion = " . $seccion . ")";
      $sqlStrAux .= " AND titulo LIKE '%$q%'";
    }else {
      $sqlStr = "SELECT * FROM " . $this->tabla . " WHERE 1=1 ";
      if ($no_publicadas != "")
        $sqlStr .= " AND fecha_publicacion>NOW()";
      if ($seccion != "")
        $sqlStr .= " AND (seccion = " . $seccion . " OR subseccion = " . $seccion . ")";
      $sqlStr .= " ORDER BY " . $orden . " " . $tipo_orden . "";

      $sqlStrAux = "SELECT count(*) as total FROM " . $this->tabla . " WHERE  1=1 ";
      if ($no_publicadas != "")
        $sqlStrAux .= " AND fecha_publicacion>NOW()";
      if ($seccion != "")
        $sqlStrAux .= " AND (seccion = " . $seccion . " OR subseccion = " . $seccion . ")";
    }
  }

  /**
   * Entrega los elementos relaciondos con una noticia
   *
   * @param int $id
   * @param string $tipo_elemento
   * @return mysql_source
   */
  function get_elementos_relacionados($id, $tipo_elemento) {
    $c = "SELECT * FROM " . $tipo_elemento . " e,noticias_relacionadas nr WHERE e.id = nr.id_relacionada AND nr.tipo_elemento='" . $tipo_elemento . "' AND nr.id_noticia = " . $id;
    $r = mysql_query($c);
    return $r;
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  ///////////
  //////////  FIN de Funciones de consultas
  ///////////
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  ///////////
  //////////  ELEMENTOS MULTIMEDIA
  ///////////
  ////////////////////////////////////////////////////////////////////////////////////////

  /**
   * Fucnion que me devuelve un array con los datos de los videos que hay en la tabla de videos de la BBDD
   *
   * @return array
   */
  function importarVideos() {
    $c = "SELECT titular, id, codigo FROM multimedia ORDER BY id DESC";
    $r = mysql_query($c);
    $resultado = array();
    while ($fila = mysql_fetch_array($r)) {
      $resultado[] = $fila;
    }
    return $resultado;
  }

  /**
   * Sube un archivo de video al servidor y modifica el campo de la tabla que contiene la ruta del archivo
   *
   * @param int $id	Identificador del elemento
   * @param array $video	Array con los datos del archivo
   */
  function sube_video($id, $video) {
    $carpeta = "../videos/";
    if (is_uploaded_file($video['tmp_name']) && ($video['type'] == "application/octet-stream")) {
      $pref = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "_";
      if (move_uploaded_file($video['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($video['name']))) {
        $nombre_video = $pref . $this->quita_caracteres_especiales($video['name']);
        mysql_query("UPDATE " . $this->tabla . " SET cod_video='" . $nombre_video . "' WHERE id=" . $id);
      }
    }
  }

  /**
   * Sube un archivo de audio al servidor y modifica el campo de la tabla que contiene la ruta del archivo
   *
   * @param int $id	Identificador del elemento
   * @param array $video	Array con los datos del archivo
   */
  function sube_audio($id, $audio) {
    $carpeta = "../audios/";
    if (is_uploaded_file($audio['tmp_name']) && ($audio['type'] == "audio/mpeg")) {
      $pref = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "_";
      if (move_uploaded_file($audio['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($audio['name']))) {
        $nombre_audio = $pref . $this->quita_caracteres_especiales($audio['name']);
        mysql_query("UPDATE " . $this->tabla . " SET cod_audio='" . $nombre_audio . "' WHERE id=" . $id);
      }
    }
  }

  /**
   * Funci�n que sube los documentos adjuntos de una noticia
   *
   * @param int $id 				-> id de la noticia
   * @param unknown_type $files	-> datos de los archivos mandados por input file
   */
  function subir_documentos($id, $files) {
    //print_r($files);
    $carpeta = "../docs/";
    $doc_1 = $files['doc_1'];
    $doc_2 = $files['doc_2'];
    $doc_3 = $files['doc_3'];

    if (is_uploaded_file($doc_1['tmp_name']) && (($doc_1['type'] == "application/msword") || ($doc_1['type'] == "application/excel") || ($doc_1['type'] == "application/pdf"))) {
      $pref = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "_";
      if (move_uploaded_file($doc_1['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($doc_1['name']))) {
        $doc = $pref . $this->quita_caracteres_especiales($doc_1['name']);
        mysql_query("UPDATE " . $this->tabla . " SET doc_1='" . $doc . "' WHERE id=" . $id);
      }
    }
    if (is_uploaded_file($doc_2['tmp_name']) && (($doc_2['type'] == "application/msword") || ($doc_2['type'] == "application/excel") || ($doc_2['type'] == "application/pdf"))) {
      $pref = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "_";
      if (move_uploaded_file($doc_2['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($doc_2['name']))) {
        $doc = $pref . $this->quita_caracteres_especiales($doc_2['name']);
        mysql_query("UPDATE " . $this->tabla . " SET doc_2='" . $doc . "' WHERE id=" . $id);
      }
    }
    if (is_uploaded_file($doc_3['tmp_name']) && (($doc_3['type'] == "application/msword") || ($doc_3['type'] == "application/excel") || ($doc_3['type'] == "application/pdf"))) {
      $pref = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "_";
      if (move_uploaded_file($doc_3['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($doc_3['name']))) {
        $doc = $pref . $this->quita_caracteres_especiales($doc_3['name']);
        mysql_query("UPDATE " . $this->tabla . " SET doc_3='" . $doc . "' WHERE id=" . $id);
      }
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  ///////////
  //////////  FIN de ELEMENTOS MULTIMEDIA
  ///////////
  ////////////////////////////////////////////////////////////////////////////////////////

  function exportar_newsletter($id, $arr_ids, $extra) {
    foreach ($arr_ids as $id) {
      $c = "SELECT id,titulo,antetitulo,entradilla,seccion,subseccion,recorte_newsletter,img_rotador,img_horizontal,img_vertical,img_cuadrada,img_ampliada,img_newsletter,nombre_doc_1,nombre_doc_2,nombre_doc_3,doc_1,doc_2,doc_3 FROM " . $this->tabla . " WHERE id=" . $id;
      $r = mysql_query($c);
      $fila = mysql_fetch_array($r);
      $foto = $fila[$fila['recorte_newsletter']];
//			$c = "SELECT * FROM noticias_newsletter WHERE (titulo='".$fila['titulo']."' AND titulo<>'') OR (antetitulo='".$fila['antetitulo']."' AND antetitulo<>'') OR (entradilla='".$fila['entradilla']."' AND entradilla<>'');";
      $c = "SELECT * FROM noticias_newsletter WHERE (id_original = '" . $fila['id'] . "');";
      $r = mysql_query($c);
      //echo $c;
      if (mysql_num_rows($r) == 0) {
        //echo 1;
        if ($fila['doc_1'] != "")
          copy('../docs/' . $fila['doc_1'], '../docs/newsletter/' . $fila['doc_1']);
        if ($fila['doc_2'] != "")
          copy('../docs/' . $fila['doc_2'], '../docs/newsletter/' . $fila['doc_2']);
        if ($fila['doc_3'] != "")
          copy('../docs/' . $fila['doc_3'], '../docs/newsletter/' . $fila['doc_3']);

        $c = "INSERT INTO noticias_newsletter (titulo,antetitulo,entradilla,foto,seccion,id_original,subseccion,nombre_doc_1,nombre_doc_2,nombre_doc_3,doc_1,doc_2,doc_3) VALUES ('" . addslashes(html_entity_decode($fila['titulo'])) . "','" . addslashes(html_entity_decode($fila['antetitulo'])) . "','" . addslashes(html_entity_decode($fila['entradilla'])) . "','" . $foto . "'," . $fila['seccion'] . ",'" . $fila['id'] . "','" . $fila['subseccion'] . "','" . addslashes(html_entity_decode($fila['nombre_doc_1'])) . "','" . addslashes(html_entity_decode($fila['nombre_doc_2'])) . "','" . addslashes(html_entity_decode($fila['nombre_doc_3'])) . "','" . $fila['doc_1'] . "','" . $fila['doc_2'] . "','" . $fila['doc_3'] . "');";
        mysql_query($c);
        //echo $c;
        //die;
      }
      //echo 2;
      //die;
    }
    //cambiar
    header("Location: " . $this->url_home . "&page=" . $pagina . "&extra=" . $extra);
  }

  function borrar_doc($id, $doc, $tipo) {
    $carpeta = "../docs/";
    if (file_exists($carpeta . $doc))
      unlink($carpeta . $doc);
    mysql_query("UPDATE " . $this->tabla . " SET " . $tipo . "='',nombre_" . $tipo . "='' WHERE id=" . $id);
    header("Location: index2.php?modulo=mod_noticias&accion=editar&id=" . $id);
  }

  /*
    Duplica todas las entradas con los identificadores parados como par�metro en un array
   */

  function duplicar($arr_ids) {
    foreach ($arr_ids as $clave => $valor) {
      if ($clave != "seleccion") {//No incluyo el checkbox que selecciona todos y que tiene valor "on"
        $r = mysql_query("SELECT * FROM " . $this->tabla . " WHERE id=" . $valor);
        $fila = mysql_fetch_assoc($r);
        /*
          Eliminamos el primer elemento de la matriz (que es "id") para que no se repita en la funcion insert(), con la funcion
          array_slice()
         */
        $this->insert(array_slice($fila, 1), null, "duplicar", "");
      }
    }
  }

  function redireccionar($pagina = "") {
    header("Location: " . $this->url_home . "&page=" . $pagina);
  }

  function redireccionar2($id) {
    header("Location: index2.php?modulo=mod_noticias&accion=editar&id=" . $id);
  }

}