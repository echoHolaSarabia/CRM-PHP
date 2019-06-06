<?
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM galerias_imagenes WHERE id=".$_GET['id']);
	$galeria = mysql_fetch_array($r);
	$r = mysql_query("SELECT * FROM imagenes WHERE id_galeria=".$_GET['id']." ORDER BY orden");
	while ($fila = mysql_fetch_array($r)){
		$imagenes[] = $fila['nombre'];
	}
	$accion = "update&id=".$galeria['id'];
} else {// INSERTAR
	$accion = "insert";
}
?>
<script src="scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-personalized-1.6rc2.min.js"></script>


<!--<script type="text/javascript" src="scripts/interface.js"></script>-->


<script type="text/javascript">
function eliminar_imagen (id, imagen){
	if (confirm("¿Desea eliminar la imagen "+imagen+"?")){
		document.location.href = "index2.php?modulo=mod_galerias_imagenes&fns=1&accion=borra_imagen&imagen="+imagen+"&id="+id;
	}
}

function popUp (ruta){
	window.open("modulos/mod_galerias_imagenes/popup.php?ruta="+ruta,"Imagen de galeria","width=600,height=700,toolbar=no,directories=no,menubar=no,status=no,scrollbars=yes");
}

function guardar (){
	document.getElementById('mensajeGuardado').style.display='';
	document.form_galerias_imagenes.submit();
}

var numero = 0; //Esta es una variable de control para mantener nombres
            //diferentes de cada campo creado dinamicamente.
evento = function (evt) { //esta funcion nos devuelve el tipo de evento disparado
   return (!evt) ? event : evt;
}

addCampo = function () { 
//Creamos un nuevo div para que contenga el nuevo campo
   nDiv = document.createElement('div');
//con esto se establece la clase de la div
   nDiv.className = 'archivo';
//este es el id de la div, aqui la utilidad de la variable numero
//nos permite darle un id unico
   nDiv.id = 'file' + (++numero);
//creamos el input para el formulario:
   nCampo = document.createElement('input');
//le damos un nombre, es importante que lo nombren como vector, pues todos los campos
//compartiran el nombre en un arreglo, asi es mas facil procesar posteriormente con php
   nCampo.name = 'archivos[]';
//Establecemos el tipo de campo
   nCampo.type = 'file';
//Ahora creamos un link para poder eliminar un campo que ya no deseemos
   a = document.createElement('a');
//El link debe tener el mismo nombre de la div padre, para efectos de localizarla y eliminarla
   a.name = nDiv.id;
//Este link no debe ir a ningun lado
   a.href = '#';
//Establecemos que dispare esta funcion en click
   a.onclick = elimCamp;
//Con esto ponemos el texto del link
   a.innerHTML = 'Eliminar';
//Bien es el momento de integrar lo que hemos creado al documento,
//primero usamos la función appendChild para adicionar el campo file nuevo
   nDiv.appendChild(nCampo);
//Adicionamos el Link
   nDiv.appendChild(a);
//Ahora si recuerdan, en el html hay una div cuyo id es 'adjuntos', bien
//con esta función obtenemos una referencia a ella para usar de nuevo appendChild
//y adicionar la div que hemos creado, la cual contiene el campo file con su link de eliminación:
   container = document.getElementById('adjuntos');
   container.appendChild(nDiv);
}
//con esta función eliminamos el campo cuyo link de eliminación sea presionado
elimCamp = function (evt){
   evt = evento(evt);
   nCampo = rObj(evt);
   div = document.getElementById(nCampo.name);
   div.parentNode.removeChild(div);
}
//con esta función recuperamos una instancia del objeto que disparo el evento
rObj = function (evt) { 
   return evt.srcElement ?  evt.srcElement : evt.target;
}
</script>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
                <td class="botones_botonera"><a href="javascript:serialize();" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br /><b>Guardar |&nbsp;</b></a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_galerias_imagenes" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br /><b>&nbsp;Cancelar</b></a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><img src="images/photos.png" align="absmiddle" width="70px" alt="Galeria de imágenes" title="Galeria de imágenes"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nueva ";else echo "Editar ";?>galeria de imágenes</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_galerias_imagenes" id="form_galerias_imagenes" method="post" action="?modulo=mod_galerias_imagenes&fns=1&accion=<?=$accion?>" enctype="multipart/form-data">
          	<table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
              	<td class="contenido">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td id="mensajeGuardado" colspan="2" align="center" style="color:#FF0000;display:none;">Se está guardando la galería, por favor no cierre el navegador hasta que el proceso concluya.<br><img src="images/loading.gif"></td>
                    </tr>
                  	<tr>
                      <td class="etiqueta_200px">Nombre de la galeria:</td>
                      <td><input type="text" name="titulo" value="<?=(isset($galeria)) ? htmlentities($galeria['titulo']) : "";?>" style="width:250px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                    	<td class="etiqueta_200px" valign="top">Entradilla:</td>
                      
                      <td><textarea name="descripcion"  style="width:250px;"><?=(isset($galeria)) ? htmlentities($galeria['descripcion']) : "";?></textarea> </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Fecha publicacion:</td>
                      <td><input type="text" name="fecha_publicacion" id="fecha_publicacion" value="<?=(isset($galeria)) ? htmlentities($galeria['fecha_publicacion']) : date("Y-m-d H:i");?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador" value="..." /></td>
                      <script type="text/javascript">
					    Calendar.setup({
					        inputField     :    "fecha_publicacion",     // id del campo de texto
					         ifFormat       :    "%Y-%m-%d %H:%M",
					         showsTime      :    true,
					         button     :    "lanzador"     // el id del botón que lanzará el calendario
					    });
					  </script>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
		              <td colspan="2">
		              	<dl>            
						   <dt><label>Archivos a Subir:</label></dt>
						   <?
						   if (isset($_GET["id"])){
						   		$q_imagenes_subidas=mysql_query("SELECT * FROM imagenes WHERE id_galeria = ".$_GET["id"]." ORDER BY orden");
						   		
						   		
						   		if (mysql_num_rows($q_imagenes_subidas) > 0){
						   			$i=0;
						   			while ($imagenes_subidas[$i]=mysql_fetch_array($q_imagenes_subidas)) {
						   				$i++;						   				
						   			}
						   		}
						   }
						   ?><div id="sort5" class="grupoRecurso">
						   
						   
						   
						   
<!--  <script src="http://code.jquery.com/jquery-latest.js"></script>-->
  
  <script>
  $(document).ready(function(){
    $("#myList").sortable({});
  });
  </script>
  <style>ul { list-style: none; }
li { 
  color: #FFF;
  width: 100px;
  margin: 5px;
  font-size: 10px;
  font-family:Arial;
  padding: 3px; }

.orden_lista{
}
</style>
  
<!--<script src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.core.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.sortable.js"></script>-->
<script>
function serialize()
{
	var cadena = "";
	$(".orden_lista").each(function(i) {
	  var li = $(this);
	  cadena = cadena + li.attr("id") + ",";
	});
	document.getElementById('form_galerias_imagenes').orden.value=cadena;
	guardar();
	//EnviarDatos(cadena,"contenido_plantilla");
    return false;
};

</script>
<input type="hidden" id="orden" name="orden" value="">
<ul id="myList">
	   
				<?
						   	for ($i=0;$i<8;$i++){
						   		?>
						   		<li id="<?=$i?>" class="orden_lista">
						   			<input type="hidden" name="id<?=$i?>" value="<?= (isset($imagenes_subidas[$i])) ? htmlentities($imagenes_subidas[$i]['id']) : ""?>">
						   			<table border="0" bgcolor="#727EA3">
						   				<tr>
						   					<td>T&iacute;tulo:<br><input name='titulo<?=$i?>' id='titulo<?=$i?>' style='width:400px;' value='<?= (isset($imagenes_subidas[$i])) ? htmlentities($imagenes_subidas[$i]['titulo']) : ""?>'>
						   					&nbsp;&nbsp;&nbsp;&nbsp;<?if (isset($imagenes_subidas[$i]) && isset($imagenes_subidas[$i]["nombre"])) echo ""; else {?><input type="file" name="archivos[<?=$i?>]" /><?}?></td>
						   					</td>
						   					<td rowspan="2" align="center" valign="bottom" width="110px">&nbsp;&nbsp;&nbsp;
						   			<? if (isset($imagenes_subidas[$i]) && isset($imagenes_subidas[$i]["nombre"])){?>
						   			<img src='../img_galerias/112/<?=$imagenes_subidas[$i]["nombre"]?>' onclick="popUp('../img_galerias/591/<?=$imagenes_subidas[$i]["nombre"]?>')" style='cursor:pointer;'><br><a href='#' style='color:#FF0000' onclick="eliminar_imagen('<?=$_GET['id']?>','<?=$imagenes_subidas[$i]["id"]?>')">Eliminar</a><? }
						   			else { ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <? } ?>
						   					</td>
						   				</tr>
						   		   		<tr><td>Entradilla:<br><textarea name="entradilla<?=$i?>" id="entradilla<?=$i?>" style="width:625px;"><?= (isset($imagenes_subidas[$i])) ? htmlentities($imagenes_subidas[$i]['entradilla']) : ""?></textarea>					   		
						   				</td></tr><br>
						   			</table>
						   		</li>
						   		
						   		<?
						   	}
						   	?></ul>
					    </dl>
		              </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
			        <tr>
			       	  <td colspan="2" align="center">
			       	  	
			       	  	  <?php
			       	  	  /*
			       	  	  <table width="800px" border="0" cellpadding="0" cellspacing="5">
			       	  	  if (isset($imagenes)){
			       	  	  	$num_imagenes = count($imagenes);
			       	  	  	if (isset($galeria) && $num_imagenes > 0){
			       	  	  	  echo "<tr>";
			       	  	  	  for ($i = 0; $i < $num_imagenes; $i++){
			       	  	  	  	echo "<td align='center'><img src='../img_galerias/".$imagenes[$i]."' width='100px' onclick=\"popUp('../img_galerias/".$imagenes[$i]."')\" style='cursor:pointer;'><br><a href='#' style='color:#FF0000' onclick=\"eliminar_imagen(".$_GET['id'].",'".$imagenes[$i]."')\">Eliminar</a></td>";
			       	  	  	  	if ((($i + 1) % 4) == 0){
			       	  	  	  	  echo "</tr>";
			       	  	  	  	}
			       	  	  	  }
			       	  	  	}
			       	  	  }</table>*/
			       	  	  ?>
			       	  	
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
