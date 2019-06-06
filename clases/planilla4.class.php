<?php

class Planilla2 extends Planillas {

  function mostrar_contenido() {
    $elementos = $this->get_elementos_planilla($this->id_planilla);
    for ($hoja = 0; $hoja < 4; $hoja++) {
      if ($this->hay_elementos_en_hoja($elementos, $hoja)) {
        $this->muestra_hoja($elementos, $hoja, $this->tipo);
      }
    }
  }

  function muestra_hoja(&$elementos, $hoja, $tipo) {
	//$id_seccion = $this->id_seccion;

    if ($hoja == 0) {
      ?><div class="iContModulo01" align="left">
      <?php ?>
        <div class="fContLeft"><?php 
		
		//$nom_subsecc = $elementos[0]["nombre_subseccion"];
		
	  	//$this->id_seccion = $id_seccion;
		//echo "aa: ".$id_seccion."<br>";	
		
		?>
        <div style="width: 98.5%; margin-left: 10px; height: 57px; background-image: url('http://www.padelspain.net/img/oscuro4.png'); color:white; font-size:40px; font-family: Aliquam; margin-bottom:15px;">
        	<div style="float:left; margin-left:25px;"><img src="http://www.rrhhdigital.com/img/charla.png" width="57"/></div>
        	<div style="float:left;margin-top: 5px;margin-left: 24px;">Encuentros digitales<font style="font-size: 27px;margin-left: 17px;">de</font> </div>
            <div style="float:left;margin-top: 5px;margin-left: 10px;"><img src="http://www.padelspain.net/img/logo_padelblanco.png" width="107"/></div>  
            
        </div>
        
        <div class="fModuloDestacado"><?php
      if (isset($elementos[0]) && isset($elementos[0][0]))
        $this->mostrar_primera_seccion($elementos[0]);
      ?></div><?php
      ?><div class="iModulo01L" ><?php
      $elementos[1] = $this->muestra_columna($elementos[1], 1, $hoja);
      ?></div><?php
      ?><div class="iModulo01C" ><?php
      $elementos[2] = $this->muestra_columna($elementos[2], 2, $hoja);
      ?></div>
        </div><?php
      ?><div class="iModulo01R" ><?php
      $elementos[3] = $this->muestra_columna($elementos[3], 3, $hoja);
      ?></div>
      </div><div class="iPunteado03"></div><?php
      $elementos[6] = $this->muestra_columna($elementos[6], 6, $hoja);
    }
    else {
      if (($hoja > 0) && ($hoja < 2)) {
        ?>
        <div class="iContModulo01" align="left">
          <div class="fContLeft">
            <div class="iModulo01L" ><?php
        $elementos[1] = $this->muestra_columna($elementos[1], 1, $hoja);
        ?></div><?php
        ?><div class="iModulo01C" ><?php
        $elementos[2] = $this->muestra_columna($elementos[2], 2, $hoja);
        ?></div>
          </div><?php
        ?><div class="iModulo01R" ><?php
        $elementos[3] = $this->muestra_columna($elementos[3], 3, $hoja);
        ?></div>
        </div><div class="iPunteado03"></div><?php
        $elementos[6] = $this->muestra_columna($elementos[6], 6, $hoja);
      } else {
        if ($hoja == 2) {
          ?><div class="iContExtras" align="left">
            <div style="width:900px"><img width="407" alt="" src="/sitefiles/img/titOpinion.jpg"/></div>
          <?php
          ?><div class="iContPart01"><?php
          $elementos[1] = $this->muestra_columna($elementos[1], 1, $hoja);
          ?></div><?php
          ?><div class="iContPart02"><?php
          $elementos[2] = $this->muestra_columna($elementos[2], 2, $hoja);
          ?></div><?php
          ?><div class="iContPart03"><?php
          $elementos[3] = $this->muestra_columna($elementos[3], 3, $hoja);
          ?></div></div><?php
          ?><?php
          $elementos[6] = $this->muestra_columna($elementos[6], 6, $hoja);
          ?><?php
        }
      }
    }
  }

  function muestra_columna($elementos, $columna, $hoja) {
    while (isset($elementos[0]) && (round($elementos[0]["orden" . $columna] / 100)) == $hoja) {
      switch ($elementos[0]["tabla_elemento"]) {
        case "noticias": $elementos = $this->mostrar_noticia($elementos, $columna);
          break;

        case "modulosrosas":
          if ($elementos[0]["ruta_archivo"] != "") {
            include($elementos[0]["ruta_archivo"]);
          } else if ($elementos[0]["codigo"] != "") {
            echo $elementos[0]["codigo"];
          }
          break;

        case "modulosnegros": echo "negro";
          break;
      }
      array_shift($elementos);
    }
    return $elementos;
  }

  function mostrar_primera_seccion($elementos) {
    ?>
    <?php if ($elementos[0]["video_seccion"] == 1) { ?>
      <div style="float:left;margin-right:10px">
      <?php $this->mostrar_video_audio($elementos[0], 3); ?>
      </div>
    <?php } else {
      if ($elementos[0]["img_horizontal"] != "") { ?>
        <a href="<?php echo $this->enlace_sin($elementos[0]) ?>">
          <div class="fModuloImg">
          <?php

          $src_aux = explode("/", substr($elementos[0]["img_horizontal"], 3));
          $aux = $src_aux[count($src_aux) - 1];
          $src_aux[count($src_aux) - 1] = "horizontal_260";
          $src_aux[] = $aux;
          $src = implode("/", $src_aux);
          /*
            <img src="/<?php echo$src?>" width="260px" alt="" /> */
          ?>
            <img src="/<?php echo $elementos[0]["img_horizontal"]; ?>" width="260px" alt="" />
          </div>
        </a>
          <?php } else if ($elementos[0]["img_cuadrada"] != "") { ?>
        <a href="<?php echo $this->enlace_sin($elementos[0]) ?>">
          <div class="fModuloImg">
        <?php
        $src_aux = explode("/", substr($elementos[0]["img_cuadrada"], 3));
        $aux = $src_aux[count($src_aux) - 1];
        $src_aux[count($src_aux) - 1] = "cuadrada_151";
        $src_aux[] = $aux;
        $src = implode("/", $src_aux);
        ?>
            <img src="/<?php echo $src ?>" width="151px" alt="" />
          </div>
        </a>
          <?php } else if ($elementos[0]["img_vertical"] != "") { ?>
        <a href="<?php echo $this->enlace_sin($elementos[0]) ?>">
          <div class="fModuloImg">
            <?php
            $src_aux = explode("/", substr($elementos[0]["img_vertical"], 3));
            $aux = $src_aux[count($src_aux) - 1];
            $src_aux[count($src_aux) - 1] = "vertical_151";
            $src_aux[] = $aux;
            $src = implode("/", $src_aux);
            ?>
            <img src="/<?php echo $src ?>" width="151" alt="" />
          </div>
        </a>
          <?php }
        } ?>

    <div class="iModulo01LTit01"><strong><?php echo $elementos[0]["nombre_subseccion"] ?></strong>  <?php echo ($elementos[0]["antetitulo_alt"] == "") ? "| " . $elementos[0]["antetitulo"] : "| " . $elementos[0]["antetitulo_alt"] ?></div>
    <div class="fModuloDestTit">
      <a href="<?php echo $this->enlace_sin($elementos[0]) ?>">
    <?php echo ($elementos[0]["titular_alt"] == "") ? $elementos[0]["titulo"] : $elementos[0]["titular_alt"] ?>
      </a>
    </div>
    <div class="iModulo01LTxt">
    <?php echo $this->escribir_entradilla($elementos[0], "primera_seccion") ?>
    </div>
    <!--        <div class="iModulo01LCom"><a href="<?php echo $this->enlace_sin($elementos[0]) ?>#comentarios">(<?php echo $elementos[0]["num_comentarios"] ?>) COMENTARIOS</a></div>    -->
    <div class="fPunteado01" style="border-bottom: 1px dotted #E5E5E5"></div>
    <?php
  }

}