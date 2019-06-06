<?php

class Planilla1 extends Planillas {

  function mostrar_contenido() {
    $elementos = $this->get_elementos_planilla($this->id_planilla);
    for ($hoja = 0; $hoja < 3; $hoja++) {
//      echo $hoja."<br/>";
      if ($this->hay_elementos_en_hoja($elementos, $hoja)) {
//        echo "hay elementos<br/>";
        $this->muestra_hoja($elementos, $hoja, $this->tipo);
      }
    }
  }

  function muestra_hoja(&$elementos, $hoja, $tipo) {

//    echo "hoja = " . $hoja . "<br/>";
//    echo "tipo = " . $tipo . "<br/>";

    if ($hoja == 0) {
      if ($tipo == "con_rotador") {
        ?><div class="iContModulo01" align="left"><?php
        ?><div class="iModulo01L" ><?php
        $elementos[1] = $this->mostrar_rotador($elementos[1]);
        ?></div><?php
        ?><div class="iModulo01C" ><?php
        $elementos[2] = $this->muestra_columna($elementos[2], 2, $hoja);
        ?></div><?php
        ?><div class="iModulo01R" ><?php
        $elementos[3] = $this->muestra_columna($elementos[3], 3, $hoja);
        ?></div></div><?php
        $elementos[6] = $this->muestra_columna($elementos[6], 6, $hoja);
      }
    } else {
      if ($hoja == 1) {
        ?><div class="iContModulo01" align="left"><?php
        ?><div class="iModulo01L"><?php
        $elementos[1] = $this->muestra_columna($elementos[1], 1, $hoja);
        ?></div><?php
        ?><div class="iModulo01C"><?php
        $elementos[2] = $this->muestra_columna($elementos[2], 2, $hoja);
        ?></div><?php
        ?><div class="iModulo01R"><?php
        $elementos[3] = $this->muestra_columna($elementos[3], 3, $hoja);
        ?></div>
        </div>
        <?php
        $elementos[6] = $this->muestra_columna($elementos[6], 6, $hoja);
      } else {
        if ($hoja == 2) {
          ?><div class="iContExtras" align="left">
            <div style="width:900px">
                </div>
          <?php ?><div class="iContPart01"><?php
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

  function mostrar_rotador($elementos) {
    $rotador = array();
    $seguir = 1;
    while ($seguir) {
      if (isset($elementos[0]) && ($elementos[0]["orden1"] < 100)) {
        $aux = array_shift($elementos);
        if (count($rotador) > 0) {
          if ($rotador[count($rotador) - 1]["id"] != $aux["id"]) {
            $rotador[] = $aux;
          }
        }
        else
          $rotador[0] = $aux;
      }
      else
        break;
    }
    ?>
    <?php //FOTO INICIAL?>
<style type="text/css">#todo_out img{max-height:410px}</style>
    <div class="iContRoTador">
      <div class="iBckTitRotador">
        <div class="iTitRotador">
          <div class="iTxtTitRotador">
    <?php
    for ($j = 1; $j <= count($rotador); $j++) {
      if ($j > 1)
        echo "&nbsp;&nbsp;|&nbsp;&nbsp;";
      ?>
              <a id="enlace_rotador<?php echo ($j - 1) ?>" href="javascript:mover_manual(<?php echo ($j - 1) ?>)" style="<?php echo ($j == 1) ? "color:#ff6600" : "" ?>"><?php echo $j ?></a>
            <?php } ?>
          </div>
          <div class="iPuntaRotador">
            <img src="sitefiles/img/puntaTitRotador.jpg" width="21" alt="" />
          </div>
          <div class="gfloatRight"><a href="javascript:anterior_rotador()"><img src="sitefiles/img/rotadorAnt.jpg" width="20" alt="" /></a><a href="javascript:siguiente_rotador()"><img src="sitefiles/img/rotadorSig.jpg" width="23" alt="" /></a></div>
        </div>
      </div>
      <div id="todo_out" style="width:370px;overflow:hidden;position:relative;height:490px" onmouseover="rotador_stop++;" onmouseout="rotador_stop--;">
        <div id="todo_in" style="width:<?php echo (370 * (count($rotador) + 1)) ?>px;position:absolute;left:0px">
          <?php for ($j = 0; $j < count($rotador); $j++) { ?>
            <div style="float:left;width:370px;cursor:pointer" onclick="window.location='<?php echo $this->enlace_sin($rotador[$j]) ?>'"  >
              <div style="position:relative;width:370px" >
                <img src="<?php echo $rotador[$j]["img_rotador"] ?>" width="370" alt="" />
                <div style="position:absolute;left:0px;bottom:0px;font-size:24px;filter: alpha(opacity=70);background-color: #000000;opacity: .7;color:#ffffff;width:350px;padding:10px">
                  <?php echo ($rotador[$j]["titular_alt"] == "") ? $rotador[$j]["titulo"] : $rotador[$j]["titular_alt"] ?>
                </div>
              </div>

              <div class="itxtRoTador" style="width:360px;height:90px;overflow:hidden">
                <?php echo $this->escribir_entradilla($rotador[$j], "gris_rotador") ?>
              </div>
            </div>

          <?php } ?>
        </div></div>
    </div>

    <script type="text/javascript">
      var actual_rotador=0;
      var total_rotador=<?php echo $j ?>;
      var cad="";
      var cad2="";
      var posicion_rotador=0;
      var hilo_rotador;
      var rotador_stop = 0;




      function siguiente_rotador(){
        if(actual_rotador == (total_rotador-1)) mover_manual(0);
        else mover_manual(actual_rotador+1);
      }


      function anterior_rotador(){
        if(actual_rotador == 0) mover_manual(total_rotador-1);
        else mover_manual(actual_rotador-1);
      }

      function mover_manual(num){
        if(rotador_stop==0){
          veces = actual_rotador - num;
          document.getElementById("enlace_rotador"+actual_rotador).style.color="#ffffff";
          actual_rotador = num;
          if(veces > 0) desplazar_der(veces);
          else if(veces < 0) {
            veces = veces*-1;
            desplazar_izq(veces);
          }
        }
      }

      function desplazar_izq(num){

        rotador_stop++;
        num = 370*num
        setTimeout("desplazar_izq_aux("+num+")",10);
      }

      function desplazar_izq_aux(num){

        valor = parseInt(document.getElementById("todo_in").style.left);
        document.getElementById("todo_in").style.left =  (valor - 10)+"px";
        num = num - 10;
        if(num==0) {
          document.getElementById("enlace_rotador"+actual_rotador).style.color="#ff6600";
          rotador_stop--;
        }
        else setTimeout("desplazar_izq_aux("+num+")",10);
      }

      function desplazar_der(num){

        rotador_stop++;
        num = 370*num
        setTimeout("desplazar_der_aux("+num+")",10);
      }

      function desplazar_der_aux(num){

        valor = parseInt(document.getElementById("todo_in").style.left);
        document.getElementById("todo_in").style.left =  (valor + 10)+"px";
        num = num - 10;
        if(num==0){
          rotador_stop--;
          document.getElementById("enlace_rotador"+actual_rotador).style.color="#ff6600";
        }
        else setTimeout("desplazar_der_aux("+num+")",10);
      }

      function rotador_auto(){
        siguiente_rotador();
      }

      setInterval("rotador_auto()",5000);
    </script>
    <?php
    return $elementos;
  }

  function mostrar_extendido_noticia($noticia) {
    $fecha_ini = explode("-", $noticia["fecha_inicio"]);
    $fecha = $fecha_ini[2] . "/" . $fecha_ini[1] . "/" . $fecha_ini[0];

    if ($noticia["recurso_maquetacion"] == "destacado_verde") {
      ?>
      <div class="eContenedorNoticias">
        <div class="eBordesVerdes">
          <div class="eContenedorNoticiaDestacada">
            <a href="<?php echo $this->enlace_sin($noticia) ?>" class="eTituloNoticiaDestacada">
      <?php $this->mostrar_multimedia($noticia, 5); ?>
      <?php echo ($noticia["titular_alt"] == "") ? $noticia["titulo"] : $noticia["titular_alt"] ?>
            </a>
            <br />
            <a href="<?php echo $this->enlace_sin($noticia) ?>" class="eTextosNoticiaDestacada">
              <b><?php echo $noticia["lugar_celebracion"] ?></b> • <?php echo $fecha ?><br />
      <?php echo $this->escribir_entradilla($noticia, "blanco") ?>
            </a><br />
            <div class="gClear"></div>
          </div>
        </div>
      </div>
      <?php
    } else {
      ?>
      <div class="eContenedorNoticias">
        <div class="eBordesGris">
          <div class="eContenedorNoticiaDestacadaGris">
            <a href="<?php echo $this->enlace_sin($noticia) ?>" class="eTituloNoticiaDestacadaGris">
      <?php $this->mostrar_multimedia($noticia, 5); ?>

      <?php echo ($noticia["titular_alt"] == "") ? $noticia["titulo"] : $noticia["titular_alt"] ?>
            </a>
            <br />
            <a href="#" class="eTextosNoticiaDestacadaGris">
              <b><?php echo $noticia["lugar_celebracion"] ?></b> • <?php echo $fecha ?><br />
              <?php echo $this->escribir_entradilla($noticia, "gris") ?>
            </a><br /><br />
            <div class="gClear"></div>
          </div>
        </div>
      </div>
            <?php
          }
        }

        function una_columna_html() {
          ?>
    <div class="iContenedorNoticias"><img src="sitefiles/img/esp/eImBannerEcoTur.gif" width="310" height="129" title="EcoTur.es" alt="EcoTur.es" /></div>
    <div class="iContenedorNoticias"><img src="sitefiles/img/esp/eImBannerBosquesNaturales.gif" width="310" height="69" title="Bosques Naturales" alt="Bosques Naturales" /></div>
    <?php
  }

}