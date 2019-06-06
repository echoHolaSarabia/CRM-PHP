<?php
class Html extends General {
  function escribir_entradilla($noticia, $tipo) {
    $tam = 200;
    $sin_ampliar = 0;
    if ($tipo == "gris")
      $color = "#58595B";
    else if ($tipo == "gris2") {
      $color = "#58595B";
      $sin_ampliar = 1;
    } else if ($tipo == "hemeroteca") {
      $color = "#58595B";
      $sin_ampliar = 1;
      $tam = 400;
    } else if ($tipo == "gris_corto") {
      $color = "#596167";
      $sin_ampliar = 1;
      $tam = 35;
    } else if ($tipo == "gris_rotador") {
      $color = "#6D6E71";
    } else if ($tipo == "blanco_corto") {
      $color = "#ffffff";
      $sin_ampliar = 1;
      $tam = 35;
    } else if ($tipo == "blanco")
      $color = "#ffffff";
    else if ($tipo == "negro") {
      $color = "#000000";
      $tam = 150;
      $sin_ampliar = 1;
    } else if ($tipo == "primera_seccion") {
      $color = "#636363";
      $tam = 480;
    }
    if ($noticia["entradilla_alt"] != "")
      $entradilla = $this->cut_string($noticia["entradilla_alt"], $tam, $this->enlace_sin($noticia), $color, $sin_ampliar);
    else if ($noticia["entradilla"] != "")
      $entradilla = $this->cut_string($noticia["entradilla"], $tam, $this->enlace_sin($noticia), $color, $sin_ampliar);
    else {
      $texto = mysql_fetch_array(mysql_query("SELECT texto FROM noticias WHERE id=" . $noticia["id"]));
      $entradilla = $this->cut_string($texto["texto"], $tam, $this->enlace_sin($noticia), $color, $sin_ampliar);
    }

    return htmlspecialchars_decode($entradilla);
  }

  function cut_string($string, $charlimit, $enlace, $color, $sin_ampliar = 0) {
    $string = strip_tags($string);
    $string = html_entity_decode($string);
    if (strlen($string) > $charlimit) {
      //$final_string = "...<a href='".$enlace."' style='text-decoration:none;color:".$color.";'><i>ampliar</i></a>";
      if ($sin_ampliar)
        $final_string = "...";
      else
        $final_string = "...<a rel='nofollow' href='".$enlace."' style='text-decoration: none; color: #636363;'><b><i>ampliar</i></b></a>";
      if (substr($string, $charlimit - 1, 1) != ' ') {
        $string = substr($string, '0', $charlimit);
        //$string = htmlentities($string);
        $array = explode(' ', $string);
        array_pop($array);
        $new_string = implode(' ', $array);
        return "<a href='" . $enlace . "' style='text-decoration:none;color:" . $color . ";'>" . htmlentities($new_string) ."</a>". $final_string . "";
      } else {
        $string = substr($string, '0', $charlimit - 1);
        return "<a href='" . $enlace . "' style='text-decoration:none;color:" . $color . ";'>" . htmlentities($string) ."</a>". $final_string . "";
      }
    }
    else
      echo "<a href='" . $enlace . "' style='text-decoration:none;color:" . $color . ";'>" . $string . "</a>";
  }

  function enlace_sin($a) {
    $cadena = "";
    $cadena = "/";
    $cadena .= strtolower($this->insertar_nombre_seccion_sin($a));
    if ($a['nombre_seccion'] != $a['nombre_subseccion']) {
      $cadena .= "/";
      $cadena .= strtolower($this->insertar_nombre_subseccion_sin($a));
    }
    $cadena .= "/" . $a['id'];
    //if (isset($a['fecha_modificacion'])){
    //	$fecha = explode (" ", $a['fecha_modificacion']);
    //	$cadena .= "/".str_replace("-", "/", $fecha[0]);
    //}
    //$c_tags ="SELECT tags FROM noticias WHERE id=".$a["id"];
    //$tags_aux = mysql_fetch_array(mysql_query($c_tags));
    //$tags = $tags_aux["tags"];
    if ($a["tags"] != "")
      $t = $a["tags"];
    else
      $t = $a["titulo"];
    $b= array("Á","É","Í","Ó","Ú","á","é","í","ó","ú","ä","ë","ï","ö","ü","à","è","ì","ò","ù","ñ","%"," ",",",".",";",":","¡","!","¿","?",'"',"\"","'");
    $c = array("A","E","I","O","U","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","n","","-","","","","","","","","",'',"","");

    $titular = str_replace($b, $c, $t);
    $cadena .= "/" . $titular;
    return $cadena;
  }

  function insertar_nombre_seccion_sin($noticia) {
    $b = array("Á","É","Í","Ó","Ú","á","é","í","ó","ú","ä","ë","ï","ö","ü","à","è","ì","ò","ù","ñ","%"," ",",",".",";",":","¡","!","¿","?",'"',"\"","'");
    $c = array("A","E","I","O","U","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","n","","-","","","","","","","","",'',"","");

    //if ($noticia["nombre_subseccion"] != "") $seccion = $noticia["nombre_subseccion"];
    //else
    $seccion = $noticia["nombre_seccion"];
    return strtolower(str_replace($b, $c, $seccion));
  }

  function insertar_nombre_subseccion_sin($noticia) {
    $b= array("Á","É","Í","Ó","Ú","á","é","í","ó","ú","ä","ë","ï","ö","ü","à","è","ì","ò","ù","ñ","%"," ",",",".",";",":","¡","!","¿","?",'"',"\"","'");
    $c = array("A","E","I","O","U","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","n","","-","","","","","","","","",'',"","");
    //if ($noticia["nombre_subseccion"] != "") $seccion = $noticia["nombre_subseccion"];
    //else
    $seccion = $noticia["nombre_subseccion"];
    return strtolower(str_replace($b, $c, $seccion));
  }

  function redimensionaVideo($codigo, $ancho, $alto) {
    if ($ancho == "")
      $ancho = $alto * 1.24;
    $codigo = preg_replace("/width=.[0-9]*./", "width=\"" . $ancho . "\"", $codigo);

    if ($alto == "")
      $alto = $ancho / 1.24;
    $codigo = preg_replace("/height=.[0-9]*./", "height=\"" . $alto . "\"", $codigo);

    return $codigo;
  }

  function mostrar_video_audio($noticia, $lugar) {
    $ancho = 0;
    $tipo = "";
    switch ($lugar) {
      case 1:// Noticia normal de portada
        if ($this->id_seccion == 0) {
          $ancho = 370;
          if ($noticia["video_portada"] == 1)
            $tipo = "video";
          else if ($noticia["audio_portada"] == 1)
            $tipo = "audio";
        }
        else {
          $ancho = 370;
          if ($noticia["video_seccion"] == 1)
            $tipo = "video";
          else if ($noticia["audio_seccion"] == 1)
            $tipo = "audio";
        }
        break;
      case 2: // Noticia portada columna central
        if ($this->id_seccion == 0) {
          $ancho = 230;
          if ($noticia["video_portada"] == 1)
            $tipo = "video";
          else if ($noticia["audio_portada"] == 1)
            $tipo = "audio";
        }
        else {
          $ancho = 230;
          if ($noticia["video_seccion"] == 1)
            $tipo = "video";
          else if ($noticia["audio_seccion"] == 1)
            $tipo = "audio";
        }
        break;

      case 3: // Primera de secciï¿½n
        $ancho = 260;
        if ($noticia["video_seccion"] == 1)
          $tipo = "video";
        else if ($noticia["audio_seccion"] == 1)
          $tipo = "audio";
        break;
      case 4: // Ampliada
        $ancho = 306;
        if ($noticia["video_ampliada"] == 1)
          $tipo = "video";
        else if ($noticia["audio_ampliada"] == 1)
          $tipo = "audio";
        break;
      case 5: // Eventos y formaciï¿½n en secciï¿½n
        $ancho = 287;
        if ($noticia["video_seccion"] == 1)
          $tipo = "video";
        else if ($noticia["audio_seccion"] == 1)
          $tipo = "audio";
        break;
    }
    if ($tipo == "video") {
      if ($noticia["tipo_video"] == "cod_video") {
        //video de youtube
        echo "<div>" . $this->redimensionaVideo($noticia["cod_video"], $ancho, "") . "</div>";
      } else {
        $alto = $ancho / 1.24;
        //video archivo
        ?>
        <script type='text/javascript' src='/videoplayer/swfobject.js'></script>
        <div id='video'>Video</div>
        <script type='text/javascript'>
          var s1 = new SWFObject('/videoplayer/player.swf','player','<?php echo $ancho ?>','<?php echo $alto ?>','9','#ffffff');
          s1.addParam('allowfullscreen','true');
          s1.addParam('allowscriptaccess','always');
          s1.addParam('wmode','opaque');
          s1.addParam('flashvars','file=/videos/<?php echo $noticia['cod_video'] ?>');
          s1.write('video');
        </script>
        <?php
      }
      return 1;
    } else
    if ($tipo == "audio") {
      if ($noticia["tipo_audio"] == "cod_audio") {
        //video de youtube
        echo "<div>" . $this->redimensionaVideo($noticia["cod_audio"], $ancho, -1) . "</div>";
      } else {
        $alto = $ancho / 1.24;
        //Audio archivo
        ?>

        <script type='text/javascript' src='/videoplayer/swfobject.js'></script>
        <div id='video'>Audio</div>
        <script type='text/javascript'>
          var s1 = new SWFObject('/videoplayer/player.swf','player','<?php echo $ancho ?>','21px','9','#ffffff');
          s1.addParam('allowfullscreen','true');
          s1.addParam('allowscriptaccess','always');
          s1.addParam('wmode','opaque');
          s1.addParam('flashvars','file=/audios/<?php echo addslashes($noticia['cod_audio']) ?>');
          s1.write('video');
        </script>
        <?php
      }
      return 1;
    }

    return 0;
  }

  function mostrar_multimedia($noticia, $lugar) {
    $float = "";
    if (!$this->mostrar_video_audio($noticia, $lugar)) {
      //Si no se muestra video se muestra la foto
      switch ($lugar) {
        case 1:// Noticia columna izquerda portada
          if ($noticia["posicion_imagen_portada"] == "derecha") {
            $imagen = "cuadrada";
            $align = "right";
            $float = "float:right;";
            $ancho = 129;
            $margin = 7;
          } else if ($noticia["posicion_imagen_portada"] == "izquierda") {
            $imagen = "cuadrada";
            $align = "left";
            $float = "float:left;";
            $ancho = 129;
            $margin = 7;
          } else if ($noticia["posicion_imagen_portada"] == "encima") {
            $imagen = "horizontal";
            $ancho = 370;
            $align = "";
            $margin = 0;
          } else if ($noticia["posicion_imagen_portada"] == "debajo") {
            $imagen = "horizontal";
            $ancho = 370;
            $align = "";
            $margin = 0;
          }
          break;
        case 2: // Noticia central portada
          if ($noticia["posicion_imagen_portada"] == "derecha") {
            $imagen = "cuadrada";
            $align = "right";
            $ancho = 129;
            $margin = 7;
          } else if ($noticia["posicion_imagen_portada"] == "izquierda") {
            $imagen = "cuadrada";
            $align = "left";
            $ancho = 129;
            $margin = 7;
          } else if ($noticia["posicion_imagen_portada"] == "encima") {
            $imagen = "horizontal";
            $ancho = ($_SERVER['QUERY_STRING']) ? 230 : 225;
            $align = "";
            $margin = 0;
          } else if ($noticia["posicion_imagen_portada"] == "debajo") {
            $imagen = "horizontal";
            $ancho = ($_SERVER['QUERY_STRING']) ? 230 : 175;
            $align = "";
            $margin = 0;
          }
          break;
        case 6: // Noticia destacada verdiblanca de portada
          if ($noticia["posicion_imagen_portada"] == "derecha") {
            $imagen = "cuadrada";
            $align = "right";
            $ancho = 146;
            $margin = 7;
          } else if ($noticia["posicion_imagen_portada"] == "izquierda") {
            $imagen = "cuadrada";
            $align = "left";
            $ancho = 146;
            $margin = 7;
          } else if ($noticia["posicion_imagen_portada"] == "encima") {
            $imagen = "cuadrada";
            $ancho = 287;
            $align = "";
            $margin = 0;
          } else if ($noticia["posicion_imagen_portada"] == "debajo") {
            $imagen = "cuadrada";
            $ancho = 287;
            $align = "";
            $margin = 0;
          }
          break;
        case 5: // Eventos y formaciï¿½n en secciï¿½n
          $imagen = "rotador";
          $align = "";
          $ancho = 287;
          $margin = 0;
      }

      if (isset($imagen) && isset($noticia["img_" . $imagen]) && $noticia["img_" . $imagen] != "") {
        $src_aux = explode("/", substr($noticia["img_" . $imagen], 3));
        $aux = $src_aux[count($src_aux) - 1];
        $src_aux[count($src_aux) - 1] = $imagen . "_" . $ancho;
        $src_aux[] = $aux;
        //$src =  implode("/",$src_aux);
        $src = $noticia["img_" . $imagen];
        ?>
        <img width="<?php echo $ancho; ?>" src="/<?php echo $src ?>"  title="<?=$noticia["titulo"]?>" alt="<?=$noticia["titulo"]?>" style="<?php echo $float ?>margin-top:7px;<?php $_SERVER['QUERY_STRING'] and print 'margin-bottom:7px;' ?>margin-left:<?php echo $margin ?>px;margin-right:<?php echo $margin ?>px;" />
        <?php
      } else {
        if (isset($imagen) && $noticia["foto_extra"] != "") {
          ?>
          <!-- <?php echo 'img_' . $imagen; ?>--><img src="/userfiles/extra/thumbs/129_<?php echo $noticia["foto_extra"] ?>"  title="" alt="" style="float:left;margin-top:7px;margin-bottom:7px;margin-left:7px;margin-right:7px;" />
          <?php
        }
      }
    }
  }

}