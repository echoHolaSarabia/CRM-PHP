<? 
include("modulos/mod_newsletter/conf.php");
$funciones = new Funciones;
$elementos_planificados = array();
?>
<script type="text/javascript" src="scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.7.1.custom.min.js"></script>
<script src="modulos/mod_newsletter/scripts.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	$('#destino1').sortable({
		connectWith: 'ul',
		cursor: 'move'
	});
	$('#destino2').sortable({
		connectWith: 'ul',
		cursor: 'move'
	});
	$('#destino3').sortable({
		connectWith: 'ul',
		cursor: 'move'
	});
});

function previsualizar_newsletter(){
 	if (document.form_newsletter.tipo_newsletter.value=="")
 		alert("Seleccione un tipo de newsletter y guarde");
 	else{
 		url="../newsletters.php?id=<?=(isset($_GET["id"])) ? $_GET["id"] : ""?>";
// 		url="../last/index.php?id=<?=(isset($_GET["id"])) ? $_GET["id"] : ""?>";
 		window.open(url);
 	}
 }
 
 function cargar_noticias(obj,refresca_suscriptores){
 	var tipo ="estado_1";
 	<?foreach ($tipos_newsletter as $tipo){?>
 	if (obj.value=="<?=$tipo[0]?>") tipo ="<?=$tipo[1]?>";
 	<?}?>
 	if (refresca_suscriptores)
 		document.getElementById("form_newsletter").suscriptores.value="";
 	
 	url_destino="modulos/mod_newsletter/ajax/cargar_noticias.php";
 	datos = "id=<?=(isset($_GET["id"])) ? $_GET["id"] : "-1"?>&tipo="+tipo
	$.ajax({
        url: url_destino,
        data:datos,
        success: function(datos){
            $("#noticias").html(datos);
        },
        type: "POST"
	});	
 }
 
 function obtener_suscriptores(){
 	tipo = document.getElementById("form_newsletter").tipo_newsletter.value;
 	if (tipo == "") alert("debe seleccionar un tipo de newsletter");
 	else{
 		var tipo ="n_1";
	 	<?foreach ($tipos_newsletter as $tipo){?>
	 	if (tipo=="<?=$tipo[0]?>") tipo ="<?=$tipo[2]?>";
	 	<?}?>
 		url = "modulos/mod_suscriptores/listado_suscriptores.php?tipo="+tipo;
 	window.open(url,"Preview","width=1,height=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes");		
 	}
 }
</script>
<link href="modulos/mod_newsletter/estilos_newsletter.css" type="text/css" rel="stylesheet" />
<?
if (isset($_GET['id']) && $_GET['id']!=""){
	$r = mysql_query("SELECT * FROM newsletters WHERE id = ".$_GET['id']);
	$newsletter = mysql_fetch_array($r);
	$accion = "update&id=".$newsletter['id'];
	$elementos_planificados = $funciones->get_elementos_planificados($_GET["id"]);
} else {
	$accion = "insert";
	//Saco el identificador de la �ltima newsletter creada para obtener la posicion de usu banners
	$last_id = mysql_fetch_array(mysql_query("SELECT id FROM newsletters ORDER BY id DESC LIMIT 1"));
	if (empty($last_id))
		$last_id['id'] = -1;
	$elementos_planificados = $funciones->get_elementos_planificados($last_id["id"],array("banners_newsletter"));
}
?>

<?php
$sqlb = 'SELECT id,titulo FROM banners_newsletter';
$resb = mysql_query($sqlb);
?>

<?
/*
Esta funci�n muestra el html de un elemento de cualquier tipo.
$estilo -> Es un string con las variables css para de como se va a mostrar el recuadro. Su contenido est� en conf.php
$estilo_helper -> Es un string con las variables css para mostrar el recuadro oscuro de arriba donde esta el atributo de bloquear, de +info, etc...
				  Su contenido est� en conf.php
$titulo -> Es un string con el t�tulo del elemento
$descripcion -> Es un string con la descripci�n del elemento
$num -> Es un int. Contiene un val�r �nico para diferenciar el elemento de todos los dem�s.
*/
function mostrar_recuadro_noticia ($estilo,$estilo_helper,$tabla,$id,$titulo,$descripcion){
	$tabla = explode("_",$tabla);
	$tabla = $tabla[0];
?>
<li id="item_<?=$id?>,<?=$tabla?>" style="<?=$estilo?>" value="<?=$tabla?>" title="<?=$tabla?>">
	<div style="<?=$estilo_helper?>">
		<table width="100%">
			<tr>
				<td></td>
<!--				<td onclick="javascript:muestra_contenido(<?=$id?>,'<?=$tabla?>'<?//=",".$num?>)" style="cursor:pointer" align="right" id="abiertocerrado-<?=$tabla."-".$id?>">[+]</td>-->
				<td onclick="javascript:eliminar_elemento_de_planilla(<?=$id?>,'<?=$tabla?>')" style="cursor:pointer" align="right">X</td>
			</tr>
		</table>
	</div>
	<div class="titulo"><?=$titulo?></div>
	<div id="prev-<?=$tabla."-".$id?>"></div>
</li>
<?
}

function muestra_columna ($elementos,$num_columna,$estilos,$estilos_helper){
	if (!empty($elementos[$num_columna])){
		$num_elemento=0;
		foreach ($elementos[$num_columna] as $unElemento){
			$estilo = $estilos[$unElemento['tabla_elemento']];
			mostrar_recuadro_noticia($estilo,$estilos_helper[$unElemento['tabla_elemento']],$unElemento['tabla_elemento'],$unElemento['id'],$unElemento['titulo'],"");
			$num_elemento++;
			//$num++;
		}
	}
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
          <input type="hidden" id="listas" name="listas" value="">
          	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td width="55%" class="contenido">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  	<tr>
                      <td class="titulos" colspan="2">Detalles</td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                  	<tr>
                  		<td valign="top">
                  			<table width="100%" border="0" cellpadding="0" cellspacing="0" >
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
			                          <tr style="display: none;">
			                            <td class="etiqueta">Tipo de newsletter:</td>
				                      		<td style="display: none;">
				                      			<select name="tipo_newsletter" id="tipo_newsletter" style="width:448px" onchange="cargar_noticias(this,true)">
				                      				<?foreach ($tipos_newsletter as $tipo){?>
				                      				<option value="<?=$tipo[0]?>" <?=(isset($newsletter) && ($newsletter["tipo_newsletter"]==$tipo[0])) ? "selected" : ""?>><?=$tipo[0]?></option>
				                      				<?}?>
				                      			</select>
				                      		</td>
			                          </tr>
			                        </table>
			                      </td>
			                    </tr>
			                    <tr>
			                      <td class="separador"></td>
			                    </tr>
			                    <!--<tr>
                            <td>
                              <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                                <tr>
                                  <td class="etiqueta">Megabanner:</td>
                                  <td>
	                                  <select name="megabanner">
	                                  <?php while($banner = mysql_fetch_array($resb)) : ?>
	                                    <option value="<?php echo $banner["id"]; ?>" <?php echo (isset($newsletter) && $newsletter["megabanner"] == $banner["id"]) ? 'selected="selected"' : "" ; ?>><?php echo $banner["titulo"]; ?></option>
	                                  <?php endwhile; ?>
	                                  </select>
	                                </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td class="separador"></td>
                          </tr>-->
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
			                      			  	echo "<option value='".date("Y-m-d H", strtotime('now +'. ( $hora - 1 ) .' hours')).":00:00' ".$cad.">".date("d-m-Y H", strtotime('now +'.$hora.' hours')).":00 horas</option>";
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
                  			</table>
                  		</td>
                  		<td valign="top">
                  			<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  				<tr>
			                      <td>
			                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
			                          <tr>
			                            <td class="etiqueta">Suscriptores:</td>
			                      		<td><textarea name="suscriptores" cols="61" rows="6"><?=(isset($newsletter)) ? htmlentities($newsletter['suscriptores']) : "";?></textarea><br>
			                      		<button type="button" onclick='obtener_suscriptores()' value="Insertar emails de suscriptores" >Insertar emails de los suscriptores a esta newsletter</button>
			                      		</td>
			                          </tr>
			                        </table>
			                      </td>
			                    </tr>
                  			</table>
                  		</td>
                  	</tr>
                  	<tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                      <!-- PLANILLA DE NEWSLETTER -->
                      <div class="tabla_columnas">
						<table width="100%" border="0">
							<tr>
								<td width="50%" valign="top">
								<div>
										<div class="hueco_ampliada">
											<h2>Columna 1</h2>
											<ul id="destino1" style="padding-bottom:5px">
											<?muestra_columna($elementos_planificados,1,$estilos,$estilos_helper);?>
											</ul>
										</div>
								</div>
								</td>
<!--								<td width="50%" valign="top" style="display: none;">-->
								<td width="50%" valign="top">
								<div>
										<div class="hueco_ampliada">
											<h2>Columna 2</h2>
											<ul id="destino2" style="padding-bottom:5px">
											<?muestra_columna($elementos_planificados,2,$estilos,$estilos_helper);?>
											</ul>
										</div>
								</div>
								</td>
							</tr>
						</table>
						</div>
						<!-- FIN AREA DE LA PLANILLA ACTUAL -->
						
						<!-- AREA DE ELEMENTOS QUE SE PUEDEN DEPOSITAR EN LA PLANILLA -->
						<div class="tabla_posibles">
						<table width="300px">
							<script type="text/javascript">
							$(document).ready(function(){ 
								$('#noticias').sortable({
									items: "li",
									connectWith: 'ul',
									cursor: 'move'
								});
							});
							</script>
							<tr>
								<td class="recuadro-titulos" onclick="javascript:$('#modulo_noticias').toggle('drop');">Noticias</td>
							</tr>
							<tr id="modulo_noticias">
								<td>	
									<ul id="noticias" style="padding-left:0px;margin-left:0px;padding-bottom:10px;background-color:#EEEEEE;border:1px solid #CCCCCC">
										<script>cargar_noticias(document.form_newsletter.tipo_newsletter,false)</script>
									</ul>
								</td>
							</tr>
							<script type="text/javascript">
							$(document).ready(function(){ 
								$('#banners').sortable({
									items: "li",
									connectWith: 'ul',
									cursor: 'move'
								});
							});
							</script>
<!--							<tr style="display: none;">-->
							<tr>
								<td class="recuadro-titulos" onclick="javascript:$('#modulo_banners').toggle('drop');">Banners</td>
							</tr>
<!--							<tr id="modulo_banners" style="display: none;">-->
							<tr id="modulo_banners">
								<td>	
									<ul id="banners" style="padding-left:0px;margin-left:0px;padding-bottom:10px;background-color:#EEEEEE;border:1px solid #CCCCCC">
										<?
										if(isset($last_id["id"]) && $last_id["id"]=="") $last_id["id"]=-1;
										$c = "SELECT id,titulo FROM banners_newsletter  WHERE id NOT IN (SELECT id_elemento FROM noticias_newsletter_relacionadas WHERE tabla_elemento = 'banners_newsletter' AND id_newsletter=".((isset($_GET["id"])) ? $_GET["id"] : $last_id["id"]).")";
										$r = mysql_query($c);
										if(mysql_num_rows($r)>0){
											while ($fila = mysql_fetch_assoc($r)){
											?>
											<li id="item_<?=$fila['id']?>,banners" style="<?=$estilos['banners_newsletter']?>" value="eo" title="Banners">
												<div class="titulo"><?=$fila['titulo']?></div>
											</li>
											<?
											}
										}
										?>
									</ul>
								</td>
							</tr>
						</table>
						</div>
                      <!-- FIN PLANILLA DE NEWSLETTER -->
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