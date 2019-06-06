
<style type="text/css">


div#apDiv4 a{
	float: left;
	position:relative;
	top: 0px;
	left: 266px;
	width:216px;
	height:52px;
}

div#apDiv4 a:hover{

    background-image:url(http://golfconfidencial.com/piepagina/rrhhdigital.jpg);

    background-repeat: no-repeat;

}

div#apDiv5 a{
	float: left;
	position:relative;
	top: 0px;
	left: 320px;
	width:210px;
	height:48px;
}

div#apDiv5 a:hover{

    background-image:url(http://golfconfidencial.com/piepagina/altodirectivo.jpg);

    background-repeat: no-repeat;

}

div#apDiv6 a{
	float: left;
	position:relative;
	top: 55px;
	left: 130px;
	width:104px;
	height:48px;
}

div#apDiv6 a:hover{

    background-image:url(http://golfconfidencial.com/piepagina/padel.jpg);

    background-repeat: no-repeat;

}

div#apDiv7 a{
	float: left;
	position: relative;
	top: 48px;
	left: -250px;
	width:213px;
	height:59px;
}

div#apDiv7 a:hover{

    background-image:url(http://golfconfidencial.com/piepagina/golf.gif);

    background-repeat: no-repeat;

}


div#apDiv9 a{
	float: left;
	position:relative;
	top: -98px;
	left: 810px;
	width:113px;
	height:59px;
}

div#apDiv9 a:hover{

    background-image:url(http://golfconfidencial.com/piepagina/eldiariodelbebe.jpg);

    background-repeat: no-repeat;

}

div#apDiv10 a{
	float: left;
	position:relative;
	top: -40px;
	left: 610px;
	width:243px;
	height:47px;
}

div#apDiv10 a:hover{

    background-image:url(http://golfconfidencial.com/piepagina/sercomercial.jpg);

    background-repeat: no-repeat;

}



div#apDiv8 a{
	float: left;
	position: relative;
	top: -5px;
	left: -700px;
	width:220px;
	height:80px;
}





</style>







<?php
$query = "
  SELECT `id`,`titulo`
  FROM `secciones`
  WHERE `id_padre` = -1
    AND `activo` =1";
$result = mysql_query($query);
?>
    <script type="text/javascript">
         	
			function cambiar(interruptor){
                        if (interruptor == 1){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/rrhhdigital.jpg" ;
                        }
                        if (interruptor == 2){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/eldiariodelbebe.jpg";
                        }
                        if (interruptor == 3){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/padelspain.jpg";
                        }
                        if (interruptor == 4){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/golfconfidencial.jpg";
                        }
                        if (interruptor == 5){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/altodirectivo.jpg";
                        }
                        if (interruptor == 6){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/sercomercial.jpg";
                        }
                        if (interruptor == 7){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/impulsandopymesdigital.jpg";
                        }
						 if (interruptor == 8){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/lobbyworld.jpg";
                        }
            }
 			
			function salir(interruptor){
                        if (interruptor == 11){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/ediciones_mencheta_AlbertoByN.jpg";
                        }
                        if (interruptor == 22){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/ediciones_mencheta_AlbertoByN.jpg";
                        }
                        if (interruptor == 33){
                                    document.getElementById("IMG1").src = "http://www.eds21.es/ediciones_mencheta_AlbertoByN.jpg";
                        }
                        if (interruptor == 44){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/ediciones_mencheta_AlbertoByN.jpg";
                        }
                        if (interruptor == 55){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/ediciones_mencheta_AlbertoByN.jpg";
                        }
                        if (interruptor == 66){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/ediciones_mencheta_AlbertoByN.jpg";
                        }
                        if (interruptor == 77){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/ediciones_mencheta_AlbertoByN.jpg";
                        }
						 if (interruptor == 88){
                                   document.getElementById("IMG1").src = "http://www.eds21.es/ediciones_mencheta_AlbertoByN.jpg";
                        }
            }
 
</script>

<div style="width:970px;">
    <img name="IMG1" id="IMG1" src="http://www.eds21.es/ediciones_mencheta_AlbertoByN.jpg" width="970" height="100" border="0" usemap="#Map" alt="mencheta" />
 
 
            
			
			<map name="Map" id="Map">
              <area shape="rect" coords="640,53,784,91" href="http://www.rrhhdigital.com" alt="RRHH Digital" title="RRHH Digital" target="_blank" onMouseOver="cambiar(1);" onMouseOut="salir(11);"/>
              <area shape="rect" coords="530,6,612,50" href="http://www.eldiariodelbebe.es"  alt="El Diario Del Beb&eacute;" title="El Diario Del Beb&eacute;" target="_blank" onMouseOver="cambiar(2);" onMouseOut="salir(22);"/>
              <area shape="rect" coords="840,6,921,49" href="http://padelspain.net"  alt="PadelSpain" title="PadelSpain" target="_blank" onMouseOver="cambiar(3);" onMouseOut="salir(33);"/>
              <area shape="rect" coords="622,6,806,50" href="http://golfconfidencial.com"  alt="GolfConfidencial" title="GolfConfidencial" target="_blank" onMouseOver="cambiar(4);" onMouseOut="salir(44);"/>
              <area shape="rect" coords="299,9,494,50" href="http://altodirectivo.com"  alt="AltoDirectivo" title="AltoDirectivo" target="_blank"  onMouseOver="cambiar(5);" onMouseOut="salir(55);"/>
              <area shape="rect" coords="792,55,961,89" href="http://www.sercomercial.com"  alt="SerComercial" title="SerComercial" target="_blank"  onMouseOver="cambiar(6);" onMouseOut="salir(66);"/>
              <area shape="rect" coords="294,54,495,92" href="http://impulsandopymesdigital.com"  alt="ImpulsandoPymesDigital" title="ImpulsandoPymesDigital" target="_blank" onMouseOver="cambiar(7);" onMouseOut="salir(77);"/>
              <area shape="rect" coords="510,56,628,89" href="http://www.lobbyworld.com"  alt="Lobby World" title="Lobby World" target="_blank" onMouseOver="cambiar(8);" onMouseOut="salir(88);"/>
              <area shape="rect" coords="3,1,259,96" href="http://www.eds21.es" alt="EdicionesDigitalesSXXI" title="" target="_blank"/>
            </map>
 
</div>



<div class="gBotFooter">

	<script language="JavaScript" type="text/javascript"> 
	usuario="publicidad" 
	dominio="padelspain.net" 
	conector="@" 
	
	
	function dame_correo(){ 
	   return usuario + conector + dominio 
	} 
	
	function escribe_enlace_correo(){ 
	   document.write("<a href='mailto:" + dame_correo() + "'>Publicidad</a>") 
	} 
	</script>


  <?php while($seccion = mysql_fetch_object($result)) : ?>
  <a href="/<?php ($seccion->id>1) and print getSlugify($seccion->titulo) . getFirstSubSeccion($seccion) ?>"><?php echo $seccion->titulo ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <?php endwhile; ?>
  <a href="/registro">BOLET&Iacute;N</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/hemeroteca">HEMEROTECA</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="/rss.feeds">RSS</a>
</div>
<div class="gderechos">
  Copyright © PadelSpain.net&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Todos los derechos reservados&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="/contacto">contacto</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="/webs-amigas">Webs amigas</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <script type="text/javascript">escribe_enlace_correo()</script>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="legal.php">Aviso legal</a>
</div>