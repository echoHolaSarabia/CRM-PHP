<? if (isset($_GET["ver"]) && $_GET["ver"]=="secciones") { 
		include("editar_seccion.php");
 } else { ?>
<script type="text/javascript" src="scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="modulos/mod_galerias_video/scripts/tiny_mce.js"></script>
<script language="javascript">
function valida_datos (form){
	txt = eval(form);
	if (txt.titulo.value == ""){
		alert("El video debe tener un título");
		return false;	
	}
	document.form_multimedia.submit();
}
function borrar_video (video, id){
	if (confirm("¿Desea eliminar el video?")){
		document.location.href = "index2.php?modulo=mod_galerias_video&fns=1&accion=borra_video&id="+id+"&video="+video;
	}
}
function borrar_imagen (imagen, id){
	if (confirm("¿Desea eliminar la imagen "+imagen+"?")){
		document.location.href = "index2.php?modulo=mod_galerias_video&fns=1&accion=borra_imagen&id="+id+"&imagen="+imagen;
	}
}

</script>
<?
if (isset($_GET['id']) && $_GET['id']!=""){
	$r = mysql_query("SELECT * FROM videos WHERE id = ".$_GET['id']);
	$video = mysql_fetch_array($r);
	$accion = "update&id=".$video['id'];
}else{
	$accion = "insert";
}

$secciones = mysql_query("SELECT * FROM videos_secciones");

?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td class="botones_botonera"><a href="#" onclick="valida_datos('document.form_multimedia')" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_galerias_video" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Edicion de video</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_multimedia" method="post" action="?modulo=mod_galerias_video&fns=1&accion=<?=$accion?>" onsubmit="return valida_datos(this);" enctype="multipart/form-data">
          	<table width="100%" border="1" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td width="55%" class="contenido" valign="top">
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
                            <td class="etiqueta">Titular:</td>
                      			<td><input type="text" name="titulo" value="<?=(isset($video)) ? $video['titulo'] : "";?>" style="width:550px" /></td>
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
                            <td class="etiqueta">Sección:</td>
	                      		<td>
	                      			<select id="seccion" name="seccion">
	                      				<? if (!isset($video)) {?>
	                      					<option value="">Elegir una...</option>
	                      				<? } ?>
	                      				<? if (mysql_num_rows($secciones) > 0 ){
	                      					while ($seccion = mysql_fetch_array($secciones)){
	                      						?>
	                 							<option value="<?=$seccion["id"]?>" <?=(isset($video)&&($video["seccion"]==$seccion["id"])) ? " SELECTED" : ""?>><?=$seccion["nombre"]?></option>     						
	                      						<?
	                      					}
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
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td class="etiqueta">Publicado:</td>
                      			<td align="left" width="85%"><input type="checkbox" name="activo" value="1" <?=(isset($video) && ($video['activo']==1)) ? " CHECKED" : "";?> /></td>
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
                            <td class="etiqueta">Fecha de publicaci&oacute;n:</td>
                      		<td align="left" width="85%"><input type="text" name="fecha_publicacion" id="fecha_publicacion" value="<?=(isset($video)) ? $video['fecha_publicacion'] : "";?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador2" value="..." /></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                          <script type="text/javascript">
													Calendar.setup({
														inputField     :    "fecha_publicacion",     // id del campo de texto
														 ifFormat       :    "%Y-%m-%d %H:%M",
														 showsTime      :    true,
														 button     :    "lanzador2"     // el id del botón que lanzará el calendario
													});
												  </script>
                    <tr>
                      <td class="separador"></td>
                    </tr>
                    <tr>
                      <td>
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td class="etiqueta">Descripci&oacute;n:</td>
                          </tr>
                          <tr>
                            <td class="separador"></td>
                          </tr>
                          <tr>
                            <td><textarea name="descripcion" id="descripcion" class="mceEditor"><?=(isset($noticia)) ? $noticia['descripcion'] : "";?></textarea></td>
                          </tr>
                        </table>
                      </td>
                    </tr> 
                  </table>
                </td>
                <td width="45%" class="contenido" valign="top">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td>
                      	<div id="menu_auxiliar">
                          <ul>
                            <li><a href="#">Publicaci&oacute;n</a></li>
                          </ul>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div id="opcion1" style="display:block;">
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr id="tipo1" style="display:<?=$estilo1?>">
                          	<td>C&oacute;digo:</td>
                            <td>
                            	<input type="text" name="cod_video" size="40" value="<?=(isset($video)) ? htmlentities($video["cod_video"]) : "";?>">                            	
                            </td>
                          </tr>
                          <tr>
                          	<td colspan="2" align="center"><br>
                          		<? 
                          		if (isset($video)){
                          			echo $video['cod_video'];
                          		}                          		
                          		?>                          		
                          	</td>
                          </tr>
                          <tr>
                            <td class="separador" colspan="2"></td>
                          </tr>
						  						<tr>
                            <td colspan="2">
                          	Tags:<br />
                          	<textarea name="tags" style="width:100%"><?=(isset($video)) ? stripslashes($video['tags']) : "";?></textarea>
                            </td>
                          </tr>
                        </table>
                        </div>
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
<? } ?>