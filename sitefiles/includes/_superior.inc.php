<div style="margin-left: 10px; margin-top: -95px; float: left; width: 728px; height:90px;">


</div>
<div style="margin-top: -95px; float: left; width: 225px; height:90px; margin-left:755px;">

    
 

</div>
<a href="/portada" style="color:#9C9A8E;text-decoration:none"></a>
<div class="gAccesos1">
  <div class="gbckHeader2" onclick="javascript:document.location.href='/'" style="cursor:pointer">
    <div class="gReloj" id="capa_reloj">
      <script type="text/javascript">
        am_pm="";var ahora=new Date();hora=ahora.getHours();
        if(ahora.getHours()>11){
          //am_pm = "PM";
          //if(hora>12) hora = hora - 12;
        }
        //else am_pm = "AM";
        if(hora<10)document.write("0");document.write(hora);document.write(":");if(ahora.getMinutes()<10)document.write("0");document.write(ahora.getMinutes())
        //document.write(" "+am_pm)
      </script>
    </div>
    <script type="text/javascript">
      function actualizar_reloj(){var ahora=new Date();hora=ahora.getHours();am_pm="";
        if(ahora.getHours()>11){
          //am_pm = "PM";
          //if(hora>12) hora = hora - 12;
        }
        //else am_pm = "AM";
        cad="";
        if(hora<10)cad+="0";cad+=hora;cad+=":";
        if(ahora.getMinutes()<10)cad+="0";cad+=ahora.getMinutes();
        //cad += " "+am_pm;
        document.getElementById("capa_reloj").innerHTML=cad
      }
      setInterval("actualizar_reloj()",10000);
    </script>
  </div>
  <div class="gAccesosL lastUpdate">
    <?php
    function fecha() {
      $mes = date("n");
      $mesArray = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");
      $semana = date("D");
      $semanaArray = array("Mon" => "Lunes", "Tue" => "Martes", "Wed" => "Miércoles", "Thu" => "Jueves", "Fri" => "Viernes", "Sat" => "Sábado", "Sun" => "Domingo",);
      $mesReturn = $mesArray[$mes];
      $semanaReturn = $semanaArray[$semana];
      $dia = date("d");
      $y = date("Y");
      return $semanaReturn . " " . $dia . " de " . $mesReturn . " de " . $y;
    }

    $fecha = mysql_fetch_assoc(mysql_query("SELECT fecha_modificacion FROM noticias ORDER BY fecha_modificacion DESC LIMIT 1"));
    if ($fecha) {
    $fecha_aux = explode(" ", $fecha["fecha_modificacion"]);
    $dia = explode("-", $fecha_aux[0]);
    $hora = explode(":", $fecha_aux[1]);
    strtoupper(fecha())
    ?>
      
    <?php $hora[0] . ":" . $hora[1];
    } ?>
  </div>
  <div class="gAccesosR">
    <a href="/registro">SUSCR&Iacute;BASE A NUESTRO BOLET&Iacute;N</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="/hemeroteca.php">HEMEROTECA</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="/rss-feeds">RSS&nbsp;&nbsp;&nbsp;<img src="/sitefiles/img/ico_rss.png" alt="" /></a>

    <a href="http://www.facebook.com/pages/PadelSpain/131524374579" target="_blank"><img src="/sitefiles/img/ico_facebook.png" alt="facebook" /></a>
    <a href="http://twitter.com/#!/PadelSpain" target="_blank"><img src="/sitefiles/img/ico_twitter.png" alt="twitter"/></a>
    <a href="http://www.youtube.com/user/PADELSPAIN" target="_blank"><img src="/sitefiles/img/ico_youtube.png" alt="youtube"/></a>
  </div>
</div>