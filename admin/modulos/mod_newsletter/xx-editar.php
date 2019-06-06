<? $funciones = new Funciones;?>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/interface.js"></script>
<link href="modulos/mod_newsletter/estilos_newsletter.css" type="text/css" rel="stylesheet" />
<script language="javascript" type="text/javascript">
 function valida_datos (form,redireccion){
	txt = eval(form);
	if (txt.nombre.value == ""){
		alert("Debe rellenar el nombre de la newsletter");
		return false;
	}
	if (txt.asunto.value == ""){
		alert("Debe rellenar el asunto de la newsletter");
		return false;
	}
	
	var sort0 = "";
	var sort1 = "";
	$("#destino").children(".elemento").each(function(i) {
	  var li = $(this);
	  sort0 = sort0 + li.attr("id") + ",";
	});
	
	$("#destino_banners").children(".elemento").each(function(i) {
	  var li = $(this);
	  sort1 = sort1 + li.attr("id") + ",";
	});
	
	var cadena = sort0;
	$("#noticias_relacionadas").val(cadena);
	
	var cadena = sort1;
	$("#banners_relacionados").val(cadena);
	document.form_newsletter.redireccion.value=redireccion;
	document.form_newsletter.submit();
 }
 
 
 function obtener_suscriptores(){
 	tipo = document.getElementById("form_newsletter").tipo_newsletter.value;
 	if (tipo == "") alert("debe seleccionar un tipo de newsletter");
 	else{
 		if(tipo=="Newsletter") tipo="n_1";
 		else if(tipo=="Aquí España") tipo="n_2";
 		else if(tipo=="Club FEHR") tipo="n_3";
 		else if(tipo=="Confidencial") tipo="n_4";
 		url = "modulos/mod_suscriptores/listado_suscriptores.php?tipo="+tipo;
 	window.open(url,"Preview","width=1,height=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes");		
 	}
 }
  
 
 
 
 function muestra_suscriptores(){
 	window.open("modulos/mod_suscriptores/listado_suscriptores.php","Preview","width=800,height=600,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes");
 }
 
 function mostrar_tabla(v){
 	if (v){
 		document.getElementById("pagina_noticias").style.display="inline";
 		document.getElementById("pagina_banners").style.display="none";
 	}
 	else{
 		document.getElementById("pagina_noticias").style.display="none";
 		document.getElementById("pagina_banners").style.display="inline";
 	}
 }
 
 function previsualizar_newsletter(){
 	if (document.form_newsletter.tipo_newsletter.value=="")
 		alert("Seleccione un tipo de newsletter y guarde");
 	else{
 		url="../newsletters.php?id=<?=(isset($_GET["id"])) ? $_GET["id"] : ""?>";
 		window.open(url);
 	}
 }
 
 function activar_sortable(){
		//$("#origen").sortable( "destroy" ); 
		$('#origen').Sortable(
			{
				accept: 'elemento',
				helperclass: 'sortHelper',
				activeclass : 	'sortableactive',
				hoverclass : 	'sortablehover',
				tolerance: 'pointer',
				onChange : function(ser)
				{
				},
				onStart : function()
				{
					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
				},
				onStop : function()
				{
					$.iAutoscroller.stop();
				}
			}
		);
 }
 
 function cargar_noticias(obj){
 	if (obj.value=="Newsletter") tipo ="estado_1";
 	else if (obj.value=="Aquí España") tipo ="estado_2";
 	else if (obj.value=="Club FEHR") tipo ="estado_3";
 	else if (obj.value=="Confidencial") tipo ="estado_4";
 	
 	document.getElementById("form_newsletter").suscriptores.value="";
 	
 	url_destino="/fehr/admin/modulos/mod_newsletter/ajax/cargar_noticias.php";
 	datos = "id=<?=(isset($_GET["id"])) ? $_GET["id"] : "-1"?>&tipo="+tipo
	$.ajax({
        url: url_destino,
        data:datos,
        success: function(datos){
            $("#origen").html(datos);
            
        },
        type: "POST"
	});	
	
	setTimeout("activar_sortable()",2000);
 }
 
</script>
<?
if (isset($_GET['id']) && $_GET['id']!=""){
	$r = mysql_query("SELECT * FROM newsletters WHERE id = ".$_GET['id']);
	$newsletter = mysql_fetch_array($r);
	$accion = "update&id=".$newsletter['id'];
} else {
	$accion = "insert";
}
?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="5" >
      		  <tr>
      		  	<td class="botones_botonera" ><a href="#" onclick="valida_datos('document.form_newsletter',1)" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar<br>&nbsp;</a></td>
              	<td class="botones_botonera"><a href="#" onclick="valida_datos('document.form_newsletter',0)" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar<br> y salir</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_newsletter" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar<br>&nbsp;</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Edici&oacute;n de newsletter</span></td>
        </tr>
        <tr>
          <td>
          <form id="form_newsletter" name="form_newsletter" method="post" action="?modulo=mod_newsletter&fns=1&accion=<?=$accion?>" enctype="multipart/form-data">
          <input type="hidden" name="redireccion" id="redireccion" value="" />
          <input type="hidden" name="fecha_creacion" id="fecha_creacion" value="<?=(isset($newsletter)) ? htmlentities($newsletter['fecha_creacion']) : "";?>" />
          <input type="hidden" name="fecha_modificacion" id="fecha_modificacion" value="<?=date("Y-m-d H:i:s")?>" />
          <input type="hidden" name="noticias_relacionadas" id="noticias_relacionadas" value="" />
          <input type="hidden" name="banners_relacionados" id="banners_relacionados" value="" />
          	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td width="55%" class="contenido">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td class="titulos">Detalles</td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
                    </tr>
                    <tr>
                      <td>
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td class="etiqueta">Nombre:</td>
                      		<td><input type="text" name="nombre" value="<?=(isset($newsletter)) ? htmlentities($newsletter['nombre']) : "";?>" size="85" /></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
                    </tr>
                    <tr>
                      <td>
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td class="etiqueta">Asunto:</td>
                      		<td><input type="text" name="asunto" value="<?=(isset($newsletter)) ? htmlentities($newsletter['asunto']) : "";?>" size="85" /></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
                    </tr>
                    <tr>
                      <td>
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td class="etiqueta">Tipo de newsletter:</td>
                      		<td>       
                      			<select name="tipo_newsletter" id="tipo_newsletter" style="width:448px" onchange="cargar_noticias(this)">
                      				<option value="">Seleccione uno...</option>
                      				<option value="Newsletter" <?=(isset($newsletter) && ($newsletter["tipo_newsletter"]=="Newsletter")) ? "SELECTED" : ""?>>Newsletter</option>
                      				<option value="Aquí España" <?=(isset($newsletter) && ($newsletter["tipo_newsletter"]=="Aquí España")) ? "SELECTED" : ""?>>Aquí España</option>
                      				<option value="Club FEHR" <?=(isset($newsletter) && ($newsletter["tipo_newsletter"]=="Club FEHR")) ? "SELECTED" : ""?>>Club FEHR</option>
                      				<option value="Confidencial" <?=(isset($newsletter) && ($newsletter["tipo_newsletter"]=="Confidencial")) ? "SELECTED" : ""?>>Confidencial</option>
                      				<?
                      				while ($banner = mysql_fetch_array($r)){
                      				?>
                      				<option value="<?=$banner['id']?>" <?=(isset($newsletter) && ($newsletter['id_banner'] == $banner['id'])) ? "selected" : "";?>><?=$banner['nombre']?></option>
                      				<?
                      				}
                      				?>
                      			</select>
                      		</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
                    </tr>
                  	<?/*
                    <tr>
                      <td>
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td class="etiqueta">Banner:</td>
                      		<td>
                      			<?
                      			$r = mysql_query("SELECT * FROM banners_newsletter WHERE tipo='Megabanner'");
                      			?>
                      			<select name="id_banner" style="width:448px">
                      				<option value="">Ninguno</option>
                      				<?
                      				while ($banner = mysql_fetch_array($r)){
                      				?>
                      				<option value="<?=$banner['id']?>" <?=(isset($newsletter) && ($newsletter['id_banner'] == $banner['id'])) ? "selected" : "";?>><?=$banner['nombre']?></option>
                      				<?
                      				}
                      				?>
                      			</select>
                      		</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
                    </tr>
                    */?>
                    <tr>
                      <td>
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td class="etiqueta">Suscriptores:</td>
                      		<td><textarea name="suscriptores" cols="61" rows="10"><?=(isset($newsletter)) ? htmlentities($newsletter['suscriptores']) : "";?></textarea><br>
                      		<button type="button" onclick='obtener_suscriptores()' value="Insertar emails de suscriptores" >Insertar emails de los suscriptores a esta newsletter</button>
                      		</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
                    </tr>
                    <tr>
                      <td>
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td class="etiqueta">Enviar a las:</td>
                      		<td>
                      			<select name="hora_envio">
                      			  <? 
                      			  //La hora de envio se guardare como un timestamp
                      			  for ($hora = 1; $hora <= 24 ;$hora ++){
                      			  	if (isset($newsletter['hora_envio']) && date("Y-m-d H", $newsletter['hora_envio']).":00:00" == date("Y-m-d H", strtotime('now +'.$hora.' hours')).":00:00")
                      			  		$cad = "selected";
                      			  	echo "<option value='".date("Y-m-d H", strtotime('now +'.$hora.' hours')).":00:00' ".$cad.">".date("d-m-Y H", strtotime('now +'.$hora.' hours')).":00 horas</option>";
                      			  	$cad = "";	
                      			  	//echo "<option value='".date("Y-m-d H", strtotime('now +'.$hora.' hours')).":00:00'>".date("d-m-Y H", strtotime('now +'.$hora.' hours')).":00 horas</option>";
                      			  }
                      			  ?>
                      			</select>
                      		</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
                    </tr>
            
                    <tr>
                      <td>
                      <br>
                      &nbsp;Insertar:
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="button" value="noticias" onclick="mostrar_tabla(1)">
                      <input type="button" value="banners" onclick="mostrar_tabla(0)">
                      <? if (isset($newsletter)) { ?>
                      		<input type="button" value="Previsualizar" onclick="previsualizar_newsletter()" style="margin-left:280px">
                      <? } else { ?>
                      		<span style="margin-left:80px">Guarde para poder previsualizar la newsletter.</span>
                      <? } ?>
                      <br><br>
                      	<div id="pagina_noticias" style="display:inline">
						  <div id="origen" style="float:left;width:50%;background-color:#DDDDDD">
						  <h3 class="arrastrar">Noticias</h3>
							<?
							  $num_fila = 0;
							  if (isset($newsletter)){
							  	$id_news = $newsletter['id'];							  
							  	$r = $funciones->get_noticias_newsletter_origen($id_news, $newsletter["tipo_newsletter"]);
							  while ($origen = mysql_fetch_array($r)){
							  	$num_fila ++;
							?>
							  <div id='<?=$origen['id']?>' class='elemento'><?=$origen['titular']?></div>
						    <? }} ?>
						  </div>
						  
						  <div id="destino" class="seccion" style="float:left;width:50%;background-color:#DDDDDD">
						  <h3 class="arrastrar">Newsletter&nbsp;&nbsp;&nbsp;</h3>
						 
							 <?
							 if (isset($_GET['id']) && $_GET['id']!=""){
							  $num_fila = 0;
							  $r = $funciones->get_noticias_newsletter_destino($newsletter['id']);
							  while ($destino = mysql_fetch_array($r)){
							  	$num_fila ++;
							?>
							  <div id='<?=$destino['id']?>' class='elemento'><?=$destino['titular']?></div>
						    <? }
							 } ?>
						  </div>
						</div>
						
						<div id="pagina_banners" style="display:none">
						  <div id="origen_banners" style="float:left;width:50%;background-color:#DDDDDD">
						  <h3 class="arrastrar">Banners</h3>
						  -- Megabanners --
							<?
							  $num_fila = 0;
							  if (isset($newsletter))
							  	$id_news = $newsletter['id'];
							  else $id_news = "";
							  $r = $funciones->get_banners_newsletter_origen($id_news);
							  $recomendado = 0;
							  while ($origen = mysql_fetch_array($r)){
							  	$num_fila ++;
							  	 if (($origen["tipo"]=="Recomendado")&&($recomendado==0)){
							  		echo "-- RECOMENDADOS --";
							  		$recomendado++;
							  	} 
							?>
							  <div id='<?=$origen['id']?>' class='elemento'><?=$origen['tipo']." - ".$origen['nombre']?></div>
						    <? } ?>
						  </div>
						  
						  <div id="destino_banners" class="seccion" style="float:left;width:50%;background-color:#DDDDDD">
						  <h3 class="arrastrar">Newsletter&nbsp;&nbsp;&nbsp;(1 Megabanner y hasta 6 recomendados)</h3>
						  		
							 <?
							 if (isset($_GET['id']) && $_GET['id']!=""){
							 	echo "-- Megabanners --";
							  $num_fila = 0;
							  $recomendado = 0;
							  $r = $funciones->get_banners_newsletter_destino($newsletter['id']);
							  while ($destino = mysql_fetch_array($r)){
							  	$num_fila ++;
							  	if (($destino["tipo"]=="Recomendado")&&($recomendado==0)){
							  		echo "-- Recomendados --";
							  		$recomendado++;
							  	} 
							?>
							  <div id='<?=$destino['id']?>' class='elemento'><?=$destino['tipo']." - ".$destino['nombre']?></div>
						    <? }
							 } ?>
						  </div>
						</div>
						
						
<script type="text/javascript">
$(document).ready(
	function () {
		$('#origen').Sortable(
			{
				accept: 'elemento',
				helperclass: 'sortHelper',
				activeclass : 	'sortableactive',
				hoverclass : 	'sortablehover',
				tolerance: 'pointer',
				onChange : function(ser)
				{
				},
				onStart : function()
				{
					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
				},
				onStop : function()
				{
					$.iAutoscroller.stop();
				}
			}
		);
		$('#destino').Sortable(
			{
				accept: 'elemento',
				helperclass: 'sortHelper',
				activeclass : 	'sortableactive',
				hoverclass : 	'sortablehover',
				tolerance: 'pointer',
				onChange : function(ser)
				{
				},
				onStart : function()
				{
					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
				},
				onStop : function()
				{
					$.iAutoscroller.stop();
				}
			}
		);
	}
);
$(document).ready(
	function () {
		$('#origen_banners').Sortable(
			{
				accept: 'elemento',
				helperclass: 'sortHelper',
				activeclass : 	'sortableactive',
				hoverclass : 	'sortablehover',
				tolerance: 'pointer',
				onChange : function(ser)
				{
				},
				onStart : function()
				{
					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
				},
				onStop : function()
				{
					$.iAutoscroller.stop();
				}
			}
		);
		$('#destino_banners').Sortable(
			{
				accept: 'elemento',
				helperclass: 'sortHelper',
				activeclass : 	'sortableactive',
				hoverclass : 	'sortablehover',
				tolerance: 'pointer',
				onChange : function(ser)
				{
				},
				onStart : function()
				{
					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
				},
				onStop : function()
				{
					$.iAutoscroller.stop();
				}
			}
		);
	}
);
</script>
                      </td>
                    </tr> 
                  </table>
                </td>
              </tr>
            </table>
          </form>
          </td>
        </tr>
      </table>
    </td>
</tr>