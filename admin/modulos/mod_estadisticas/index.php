<?
if (isset($_POST['submit'])){
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
}
?>

<script src="scripts/jquery.js" type="text/javascript"></script>
<script language="javascript">

function pon_fechas(tipo){
            fecha=new Date();
            if (tipo=="b_ayer"){
                         milisegundos=parseInt(1*24*60*60*1000);//milisegundos de un dia
                         tiempo=fecha.getTime();//milisegundos actuales
                         total=fecha.setTime(parseInt(tiempo-milisegundos));
                         dia=fecha.getDate();
                         mes=fecha.getMonth()+1;
                         anio=fecha.getFullYear();
                         escribe_fecha1(dia,mes,anio);
                         escribe_fecha2(dia,mes,anio);
            }
            if (tipo=="b_hoy"){
                         dia=fecha.getDate();
                         mes=fecha.getMonth()+1;
                         anio=fecha.getFullYear();
                         escribe_fecha1(dia,mes,anio);
                         escribe_fecha2(dia,mes,anio);
            }
            if (tipo=="b_semana"){
                        num_dias=fecha.getDay()-1;
                        milisegundos=parseInt(num_dias*24*60*60*1000);//milisegundos desde el lunes hasta hoy
                        tiempo=fecha.getTime();//milisegundos actuales
                        total=fecha.setTime(parseInt(tiempo-milisegundos));//ponemos la fecha actual a lunes de esta semana
                        dia1=fecha.getDate();
                        mes1=fecha.getMonth()+1;
                        anio1=fecha.getFullYear();
                        milisegundos=parseInt(6*24*60*60*1000);//milisegundos de una semana
                       tiempo=fecha.getTime();//milisegundos actuales
                        total=fecha.setTime(parseInt(tiempo+milisegundos));//ponemos la fecha actual a lunes de esta semana
                        dia2=fecha.getDate();
                        mes2=fecha.getMonth()+1;
                        anio2=fecha.getFullYear();
                        escribe_fecha1(dia1,mes1,anio1);
                        escribe_fecha2(dia2,mes2,anio2);
            }
            if (tipo=="b_mes"){
                        dia1=1;
                        mes1=fecha.getMonth()+1;
                        anio1=fecha.getFullYear();
                        dia2=cant_ds(mes1,anio1);
                        mes2=fecha.getMonth()+1;
                        anio2=fecha.getFullYear();
                        escribe_fecha1(dia1,mes1,anio1);
                        escribe_fecha2(dia2,mes2,anio2);
            }
            if (tipo=="b_anio"){
                        dia1=1;
                        mes1=1;
                        anio1=fecha.getFullYear();
                        dia2=31;
                        mes2=12;
                        anio2=fecha.getFullYear();
                        escribe_fecha1(dia1,mes1,anio1);
                        escribe_fecha2(dia2,mes2,anio2);
            }
}

 

function escribe_fecha1 (dia,mes,anio){
            if (dia<10) dia="0"+dia;
            if (mes<10) mes="0"+mes;
            document.form_estadisticas_newsletter.desde.value=anio+"-"+mes+"-"+dia;
}
function escribe_fecha2 (dia,mes,anio){
            if (dia<10) dia="0"+dia;
            if (mes<10) mes="0"+mes;
            document.form_estadisticas_newsletter.hasta.value=anio+"-"+mes+"-"+dia;
}

function cant_ds(mes,ano){
di=28
f = new Date(ano,mes-1,di);
while(f.getMonth()==mes-1){
di++;
f = new Date(ano,mes-1,di);
}
return di-1;
} 
</script>



<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="3" cellspacing="0">
        <tr>
          <td>
          	<form name="form_estadisticas_newsletter" method="POST" action="index2.php?modulo=mod_estadisticas">
	          <table border="0" width="100%">
	          	  <tr>
	          	  	<td class="buscador" width="25%"><span class="titulo_seccion">Estadísticas del sitio</span></td>
	          	  	<td class="buscador" width="75%">
	          	  	  <table border="0" width="100%" cellpadding="0" cellspacing="0">
	          	  	    <tr>
	          	  	      <td align="right" style="padding-right:5px" width="51%">Las:&nbsp;<input type="text" name="limite" value="10" size="5"></td>
	          	  	      <td>
	          	  	      	<select name="tipo">
	          	  	      	  <option value="noticias_mas_leidas" <? if (array_key_exists('tipo',$_POST) && $_POST['tipo'] == "noticias_mas_leidas") echo "selected";?>>noticias mas leidas</option>
	          	  	      	  <option value="comentarios_noticias" <? if (array_key_exists('tipo',$_POST) && $_POST['tipo'] == "comentarios_noticias") echo "selected";?>>noticias con mas comentarios</option>
	          	  	      	  <option value="secciones_mas_leidas" <? if (array_key_exists('tipo',$_POST) && $_POST['tipo'] == "secciones_mas_leidas") echo "selected";?>>secciones mas leidas</option>
	          	  	      	  <option value="secciones_mas_comentadas" <? if (array_key_exists('tipo',$_POST) && $_POST['tipo'] == "secciones_mas_comentadas") echo "selected";?>>secciones mas comentadas</option>
							  <option value="total" <? if (array_key_exists('tipo',$_POST) && $_POST['tipo'] == "total") echo "selected";?>>Totalizar</option>
	          	  	      	  </select>
	          	  	      </td>
	          	  	    </tr>
	          	  	    <tr><td colspan="2"><br></td></tr>
	          	  	    <tr>
	          	  	      <td align="right" style="padding-right:5px">entre el:&nbsp;<input type="text" name="desde" id="desde" value="<?=(array_key_exists('desde',$_POST)) ? $_POST['desde'] : "";?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador1" value="..." />
			          	  	<script type="text/javascript">
									Calendar.setup({
										inputField     :    "desde",     // id del campo de texto
										 ifFormat     :     "%Y-%m-%d",     // formato de la fecha que se escriba en el campo de texto
										 button     :    "lanzador1"     // el id del botón que lanzará el calendario
									});
							</script>
			          	  </td>
			          	  <td> y el:&nbsp;<input type="text" name="hasta" id="hasta" value="<?=(array_key_exists('hasta',$_POST)) ? $_POST['hasta'] : "";?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador2" value="..." />
			          	  	<script type="text/javascript">
									Calendar.setup({
										inputField     :    "hasta",     // id del campo de texto
										 ifFormat     :     "%Y-%m-%d",     // formato de la fecha que se escriba en el campo de texto
										 button     :    "lanzador2"     // el id del botón que lanzará el calendario
									});
							</script>
			          	  </td>
	          	  	    </tr>
	          	  	    <tr><td colspan="2"><br></td></tr>
	          	  	    <tr>
	          	  	      <td align="center" colspan="2">
	          	  	      		
	          	  	      		<input type="button" onClick=javascript:poner_fecha("b_ayer") class="poner_fecha" id="b_ayer" name="b_ayer" value="Ayer">&nbsp;&nbsp;&nbsp;&nbsp;
	          	  	      		<input type="button" onClick=javascript:poner_fecha("b_hoy") class="poner_fecha" id="b_hoy" name="b_hoy" value="Hoy">&nbsp;&nbsp;&nbsp;&nbsp;
	          	  	      		<input type="button" onClick=javascript:poner_fecha("b_semana") class="poner_fecha" id="b_semana" name="b_semana" value="Esta semana">&nbsp;&nbsp;&nbsp;&nbsp;
	          	  	      		<input type="button" onClick=javascript:poner_fecha("b_mes") class="poner_fecha" id="b_mes" name="b_mes" value="Este mes">&nbsp;&nbsp;&nbsp;&nbsp;
	          	  	      		<input type="button" onClick=javascript:poner_fecha("b_anio") class="poner_fecha" id="b_anio" name="b_anio" value="Este año">&nbsp;&nbsp;&nbsp;&nbsp;
	          	  	      		<input type="submit" name="submit" value="Generar">
	          	  	      </td>
	          	  	    </tr>
	          	  	  </table>
	          	  	</td>
	          	  </tr>
	          </table>
	       </form>
          </td>
        </tr>
        <tr>
          <td class="contenido">
              <div class="titulos">
                <div style="float:left; width:50%">T&iacute;tulo</div>
                <div style="float:left; width:50%;">Veces Le&iacute;da</div>
              </div>
    		  <div id="grupo_secciones" class="seccion" style="width:100%;">
			  <?
			  if (isset($_POST['submit'])){
			  	  if (isset($_POST['tipo'])){
			  	  	$ids = array();
			  	  	
			  	  	if ($_POST['tipo'] == "noticias_mas_leidas"){
			  		  $c = "SELECT count(*) AS num_veces,e.id_noticia AS id,n.titular AS nombre FROM estadisticas e,noticias n WHERE n.id=e.id_noticia ";
			  		  if (isset($desde) && ($desde != "") && isset($hasta) && ($hasta != ""))
			  		  	 $c .=" AND e.fecha >= '".$desde." 00:00:00' AND e.fecha <= '".$hasta." 23:59:59'";
			  		  $c .= " GROUP BY e.id_noticia ORDER BY count(*) DESC LIMIT 0,".$_POST['limite']."";
				  	  $r = mysql_query($c);
				  	  echo mysql_error();
			  	    }
			  	    
			  	    if ($_POST['tipo'] == "secciones_mas_leidas"){
			  		  $c = "SELECT count(*) AS num_veces,e.id_seccion AS id,s.nombre AS nombre FROM estadisticas e,secciones s WHERE s.id=e.id_seccion ";
			  		  if (isset($desde) && ($desde != "") && isset($hasta) && ($hasta != ""))
			  		  	 $c .=" AND e.fecha >= '".$desde." 00:00:00' AND e.fecha <= '".$hasta." 23:59:59'";
			  		  $c .= " GROUP BY e.id_seccion ORDER BY count(*) DESC LIMIT 0,".$_POST['limite']."";
				  	  $r = mysql_query($c);
				  	  echo mysql_error();
			  	    }
			  	    
			  	    if ($_POST['tipo'] == "comentarios_noticias"){
			  		  $c = "SELECT count(*) AS num_veces,c.id_noticia AS id,n.titular AS nombre FROM comentarios c,noticias n WHERE n.id=c.id_noticia ";
			  		  if (isset($desde) && ($desde != "") && isset($hasta) && ($hasta != ""))
			  		  	 $c .=" AND c.fecha >= '".$desde." 00:00:00' AND c.fecha <= '".$hasta." 23:59:59'";
			  		  $c .= " GROUP BY c.id_noticia ORDER BY count(*) DESC LIMIT 0,".$_POST['limite']."";
				  	  $r = mysql_query($c);
				  	  echo mysql_error();
			  	    }
			  	    
			  	   	if ($_POST['tipo'] == "secciones_mas_comentadas"){
			  		  $c = "SELECT count(*) AS num_veces,c.id_seccion AS id,s.nombre AS nombre FROM comentarios c,secciones s WHERE s.id=c.id_seccion ";
			  		  if (isset($desde) && ($desde != "") && isset($hasta) && ($hasta != ""))
			  		  	 $c .=" AND c.fecha >= '".$desde." 00:00:00' AND c.fecha <= '".$hasta." 23:59:59'";
			  		  $c .= " GROUP BY c.id_seccion ORDER BY count(*) DESC LIMIT 0,".$_POST['limite']."";
				  	  $r = mysql_query($c);
				  	  echo mysql_error();
			  	    }	  	  
			  	  	
			  	  
			  	  if ($_POST['tipo'] == "total"){
			  		  $c = "SELECT count(*) AS num_veces,e.dia AS nombre FROM estadisticas e WHERE ";
			  		  if (isset($desde) && ($desde != "") && isset($hasta) && ($hasta != ""))
			  		  	 $c .="e.fecha >= '".$desde." 00:00:00' AND e.fecha <= '".$hasta." 23:59:59'";
			  		  $c .= " GROUP BY e.dia ORDER BY e.dia";
				  	  $r = mysql_query($c);
				  	  echo mysql_error();
			  	    }
			  	  }		  	  

	              $num_fila = 0;
	              $datos_grafica = "";
	              $etiquetas_grafica = "";
	              while ($fila = mysql_fetch_array($r)) {
	                $num_fila ++;
	                $datos_grafica .= $fila['num_veces'].",";
	                $etiquetas_grafica .= htmlentities(substr($fila['nombre'],8,10))."|";
	              ?>
	                <div id="seccion_<?=$fila['id']?>" class="<? echo (($num_fila % 2) == 0) ? "fila_par" : "fila_impar"; ?>">
	                  <div style="float:left;width:50%;color:#00ff00"><a href="index2.php?modulo=mod_noticias&accion=editar&id=<?=$fila['id']?>" style="color:#000000;text-decoration:none"><b><?=$fila['nombre']?></b></a></div>
	                  <div style="float:left;width:50%;"><span style="margin-left:5px;"><?=$fila['num_veces']?></span></div>
	                </div>
	              <? 
	              }
	              $datos_grafica = substr($datos_grafica,0,(strlen($datos_grafica)-1));
	              $etiquetas_grafica = substr($etiquetas_grafica,0,(strlen($etiquetas_grafica)-1));
	               ?>
	              </div>
              <? }?>
          </td>
        </tr>
        <tr>
          <td align="center">
          <? 
          	if (isset($_POST['tipo'])){
          		if ($_POST['tipo']=="total"){
          			?>
          			<img src="http://chart.apis.google.com/chart?cht=lc&chs=1000x150&chco=1296e2&chd=t:<?=(isset($datos_grafica)) ? $datos_grafica : ""?>&chds=0,5000&chl=<?=(isset($etiquetas_grafica)) ? $etiquetas_grafica : ""?>" alt="No se pudo mostrar el gráfico. Pruebe con una muestra menor." />
          			<?
          		}
          		else{
          			?>
          			<img src="http://chart.apis.google.com/chart?cht=p3&chs=1000x150&chco=1296e2&chd=t:<?=(isset($datos_grafica)) ? $datos_grafica : ""?>&chds=0,100000&chl=<?=(isset($etiquetas_grafica)) ? $etiquetas_grafica : ""?>" alt="No se pudo mostrar el gráfico. Pruebe con una muestra menor." />
          			<?
          		}
          	}
      		?>
          </td>
        </tr>
      </table>
    </td>
</tr>
<script>
		function poner_fecha(cad){
			pon_fechas(cad);	
			//alert ("Prueba de " + "\"concatenado\" " + '\'doble\'');				
		}
</script>