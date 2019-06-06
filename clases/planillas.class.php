<?php

class Planillas extends html {

var $ideando=0;
  var $num_noticias = 0;
  var $destacados = 0;
  var $num_elementos_portada = 7;
  var $tipo;
  var $id_seccion;
  var $id_planilla;
  var $num_columnas = 7;
  var $columna_insercion;
  var $tabla = "planillas";         //Tabla donde se insertarían los datos
  var $tablas = array(0 => "noticias", 1 => "modulosrosas", 2 => "modulosnegros"); //Tablas afectadas
  

  // cuando el id de la noticia = 178 -> style="height: 200px; overfloat: hidden;"	
  function __construct($id_seccion, $id_planilla, $tipo, $columna_insercion = 0) {
    $this->id_seccion = $id_seccion;
    $this->id_planilla = $id_planilla;
    $this->tipo = $tipo;
    if ($columna_insercion == 0)
    //$this->columna_insercion = 2;
      $this->columna_insercion = 1;
    else
      $this->columna_insercion = 1;
  }


  function mostrar_noticia($elementos, $col) {
	  $id_seccion = $this->id_seccion;
	$nom_subsecc = $elementos[0]["nombre_subseccion"];
	if($nom_subsecc == "Encuentros digitales"){?>
		<!--<div style="width: 100%; height:50px;"><img src="http://www.rrhhdigital.com/img/charla.png" width="57"/></div>-->
        	
	<?php }
    if ($col == 1) {
      ?>
      <div class="iNoticia01L">
      
        <div class="iModulo01LTit01" ><strong><?php echo ($this->id_seccion == 0) ? $elementos[0]["nombre_seccion"] : $elementos[0]["nombre_subseccion"] ?></strong>  |  <?php echo ($elementos[0]["antetitulo_alt"] == "") ? $elementos[0]["antetitulo"] : $elementos[0]["antetitulo_alt"] ?></div>
        <?php if ($elementos[0]["posicion_imagen_portada"] == "encima") {
          ?><div class="iModulo01Limg"><a href="<?php echo $this->enlace_sin($elementos[0]) ?>"><?php
        $this->mostrar_multimedia($elementos[0], 1)
          ?></a></div><?php
      }
        ?>
        <div class="iModulo01LTit" style="padding-top:0px"><h1 class="iModulo01LTitH"><a href="<?php echo $this->enlace_sin($elementos[0]) ?>"><?php echo ($elementos[0]["titular_alt"] != "") ? $elementos[0]["titular_alt"] : $elementos[0]["titulo"] ?></a></h1></div>
        <?php if ($elementos[0]["posicion_imagen_portada"] != "encima") {
          ?><div class="iModulo01Limg"><a href="<?php echo $this->enlace_sin($elementos[0]) ?>"><?php
        $this->mostrar_multimedia($elementos[0], 1);
          ?></a></div><?php
      }
        ?>
        <div class="iModulo01LTxt"><?php echo $this->escribir_entradilla($elementos[0], "gris") ?></div>
        <div class="iModulo01LCom"></div>
      </div>
    <?php } else {
      ?>
      
       <!-- cuando el id de la noticia = 178, style="height: 200px; overfloat: hidden;"	-->
       
       
      <div class='iNoticia01C'>
        <div class="iModulo01CTit01"><strong><?php echo ($this->id_seccion == 0) ? $elementos[0]["nombre_seccion"] : $elementos[0]["nombre_subseccion"] ?></strong>  |  <?php echo ($elementos[0]["antetitulo_alt"] == "") ? $elementos[0]["antetitulo"] : $elementos[0]["antetitulo_alt"] ?></div>
        <?php if ($elementos[0]["posicion_imagen_portada"] == "encima") {
          ?><div class="iModulo01Cimg" <?php	if($this->enlace_sin($elementos[0])=="/portada/actualidad/178/Padel-Clubes-Pistas-Palas-Comunidad-Autonoma-Federaciones"){echo "style='height: 200px; overflow:hidden;'";	    }   ?>>

           <?php $ideando++;
		   
		   if ($ideando == 1){
          	echo "";
          }?>
                 
       
          <a href="<?php echo $this->enlace_sin($elementos[0]) ?>">         
          
		  <?php $this->mostrar_multimedia($elementos[0], 2) ?>
          
          </a></div><?php
      }
        ?>

        <div class="iModulo01CTit" style="padding-top:0px"><h1 class="iModulo01CTitH"><a style="font-size: 24px;" href="<?php echo $this->enlace_sin($elementos[0]) ?>">
            <?php echo ($elementos[0]["titular_alt"] != "") ? $elementos[0]["titular_alt"] : $elementos[0]["titulo"] ?>
          </a></h1></div>
        <?php
        if ($elementos[0]["posicion_imagen_portada"] != "encima") {
          if ($elementos[0]["posicion_imagen_portada"] == "izquierda")
            $float = "left";
          else if ($elementos[0]["posicion_imagen_portada"] == "derecha")
            $float = "right";
          ?><div class="iModulo01Cimg" <?php echo ((($this->id_seccion == 0 && $elementos[0]["video_portada"]) || ($this->id_seccion != 0 && $elementos[0]["video_seccion"])) || ($elementos[0]["posicion_imagen_portada"] == "debajo")) ? "" : "style='width:151px;float:" . $float . "'" ?>><a href="<?php echo $this->enlace_sin($elementos[0]) ?>"><?php
        $this->mostrar_multimedia($elementos[0], 2);
        ?></a></div><?php
      }
      ?>
        <div class="iModulo01LTxt"<?php !$_SERVER['QUERY_STRING'] and print 'style="padding:0"' ?>><?php echo $this->escribir_entradilla($elementos[0], "gris") ?></div>
        <?php if($_SERVER['QUERY_STRING']) : ?><div class="iModulo01CCom"></div><?php endif; ?>
      </div>
      <?php
    }
    $rel = 0;
    if ($elementos[0]["titulo_rel"] != "") {
      array_unshift($elementos, $elementos[0]);
      while (isset($elementos[1])) {
        if (isset($elementos[1]) && ($elementos[0]["id"] == $elementos[1]["id"]) && ($elementos[1]["tabla_elemento"] == "noticias")) {
         array_shift($elementos);
          $rel++;
        }
        else
          break;
        //if($rel>0) {echo "<br>";$rel=0;}
      }
    }
    if ($col == 1)
      echo "<div class='iPunteado01' style='clear:both;border-bottom: 1px dotted rgb(229, 229, 229);'></div>";
    else
      echo "<div class='iPunteado02' style='clear:both;border-bottom: 1px dotted rgb(229, 229, 229);'></div>";
    return $elementos;
  }

  function mostrar_columna_12($elementos, $columna, $hoja) {
    while (isset($elementos[0]) && (round($elementos[0]["orden" . $columna] / 100)) == $hoja) {
      switch ($elementos[0]["tabla_elemento"]) {
        case "eventos": $this->mostrar_extendido_noticia($elementos[0]);
          break;
        case "formacion": $this->mostrar_extendido_noticia($elementos[0]);
          break;
        case "noticias": $elementos = $this->mostrar_noticia($elementos);
          break;

        case "modulosrosas":
          if ($elementos[0]["ruta_archivo"] != "")
            include($elementos[0]["ruta_archivo"]);
          else if ($elementos[0]["codigo"] != "")
            echo $elementos[0]["codigo"];
          //if($elementos[0]["tipo_rosa"]=="banner")
          echo "<div class='iContenedorNoticias'></div>";
          break;

        case "modulosnegros": echo "negro";
          break;
      }
      array_shift($elementos);
    }
    //include("sitefiles/includes/lateral.php");
    return $elementos;
  }

  function muestra_tercera_columna($elementos, $columna, $hoja) {
    $id_seccion = $this->id_seccion;
    while (isset($elementos[0]) && (round($elementos[0]["orden" . $columna] / 100)) == $hoja) {
      switch ($elementos[0]["tabla_elemento"]) {
        case "eventos": $this->mostrar_extendido_noticia($elementos[0]);
          break;
        case "formacion": $this->mostrar_extendido_noticia($elementos[0]);
          break;
        case "noticias": $elementos = $this->mostrar_noticia($elementos);
          break;

        case "modulosrosas":
          if ($elementos[0]["ruta_archivo"] != "")
            include($elementos[0]["ruta_archivo"]);
          else if ($elementos[0]["codigo"] != "")
            echo $elementos[0]["codigo"];
          //if($elementos[0]["tipo_rosa"]=="banner")
          echo "<div class='iContenedorNoticias'></div>";
          break;

        case "modulosnegros": echo "negro";
          break;
      }
      array_shift($elementos);
    }
    return $elementos;
  }

}