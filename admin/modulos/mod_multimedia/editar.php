<? include_once ("fckeditor/fckeditor.php"); ?>
<? include("clases/ficheros.php");?>
<script language="javascript">
function valida_datos (form){
	txt = eval(form);
	if (txt.autor.value == ""){
		alert("Debe rellenar el campo autor");
		return false;	
	}
	if (txt.titular.value == ""){
		alert("El video debe tener un t�tulo");
		return false;	
	}
	document.form_multimedia.submit();
}
function borrar_video (video, id){
	if (confirm("�Desea eliminar el video?")){
		document.location.href = "index2.php?modulo=mod_multimedia&fns=1&accion=borra_video&id="+id+"&video="+video;
	}
}
function borrar_imagen (imagen, id){
	if (confirm("�Desea eliminar la imagen "+imagen+"?")){
		document.location.href = "index2.php?modulo=mod_multimedia&fns=1&accion=borra_imagen&id="+id+"&imagen="+imagen;
	}
}
</script>
<?
if (isset($_GET['id']) && $_GET['id']!=""){
	$r = mysql_query("SELECT * FROM multimedia WHERE id = ".$_GET['id']);
	$multimedia = mysql_fetch_array($r);
	$orden = $multimedia['orden'];
	$accion = "update&id=".$multimedia['id'];
}else{
	$r = mysql_query("SELECT MAX(orden) AS ultimo FROM multimedia;");
	$orden = mysql_fetch_array($r);
	$orden = $orden['ultimo']+1;
	$accion = "insert";
}
?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td class="botones_botonera"><a href="#" onclick="valida_datos('document.form_multimedia')" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_multimedia" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Edicion de contenido multimedia</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_multimedia" method="post" action="?modulo=mod_multimedia&fns=1&accion=<?=$accion?>" onsubmit="return valida_datos(this);" enctype="multipart/form-data">
          <input type="hidden" name="thumbnail" value="<?=(isset($multimedia)) ? $multimedia['thumbnail'] : "";?>">
          <input type="hidden" name="referer" value="<?=$_SERVER['HTTP_REFERER']?>">
          <input type="hidden" name="id_galeria" value="<?=(isset($_GET["id_galeria"])) ? $_GET["id_galeria"] : "";?>">
          	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
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
                          	<td class="etiqueta">Autor:</td>
                            <td><input type="text" name="autor" value="<?=$_SESSION['nombre']?>" style="width:200px" /></td>
                          </tr>
                          <tr>
                            <td class="separador"></td>
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
                            <td class="etiqueta">Titular:</td>
                      		<td><input type="text" name="titular" value="<?=(isset($multimedia)) ? $multimedia['titular'] : "";?>" style="width:550px" /></td>
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
                            <td class="etiqueta">Descripci&oacute;n:</td>
                          </tr>
                          <tr>
                            <td class="separador"></td>
                          </tr>
                          <tr>
                            <td>
                               <?        
								$oFCKeditor = new FCKeditor('descripcion');
								$oFCKeditor->BasePath = 'fckeditor/';
								$oFCKeditor->Value = (isset($multimedia)) ? $multimedia['descripcion'] : "";
								$oFCKeditor->Width  = '100%' ;
								$oFCKeditor->Height = '200' ;
								$oFCKeditor->Create();
							   ?>
							</td>
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
                    	 <? /* <tr>
                          	<td>Publicado:</td>
                            <td><input type="checkbox" name="activo" value="1" <? if (isset($multimedia) && $multimedia['activo'] == 1) echo "checked";?>/></td>
                          </tr>
                          <tr>
                            <td class="separador" colspan="2"></td>
                          </tr>
                          <tr>
                          	<td>Ver en la portada:</td>
                            <td><input type="checkbox" name="portada" value="1" <? if (isset($multimedia) && $multimedia['portada'] == 1) echo "checked";?>/></td>
                          </tr>
                          <tr>
                            <td class="separador" colspan="2"></td>
                          </tr>
                          <input type="hidden" name="fecha_creacion" id="fecha_creacion" value="<?=$multimedia['fecha_creacion']?>" />
                          <input type="hidden" name="fecha_modificacion" id="fecha_modificacion" value="<?=date("Y-m-d H:i:s")?>" />
                          <tr>
                          	<td>Fecha de publicaci&oacute;n:</td>
                            <td><input type="text" name="fecha_publicacion" id="fecha_publicacion" value="<?=(isset($multimedia)) ? $multimedia['fecha_publicacion'] : "";?>" />&nbsp;&nbsp;<input type="button" class="txt" id="lanzador2" value="..." /></td>
                          </tr>
                          <script type="text/javascript">
							Calendar.setup({
								inputField     :    "fecha_publicacion",     // id del campo de texto
								 ifFormat       :    "%Y-%m-%d %H:%M",
								 showsTime      :    true,
								 button     :    "lanzador2"     // el id del bot�n que lanzar� el calendario
							});
						  </script>
                          <tr>
                            <td class="separador" colspan="2"></td>
                          </tr>
                          */?>
                          <script language="javascript">
                          	function cambia_fuente(obj){
                          		if (obj.options[obj.selectedIndex].value == "youtube"){
                          			document.getElementById('tipo1').style.display = '';
                          			document.getElementById('tipo2').style.display = 'none';
                          		} else {
                          			document.getElementById('tipo1').style.display = 'none';
                          			document.getElementById('tipo2').style.display = '';
                          		}
                          	}
                          </script>
                          <?
                          if (isset($multimedia) && $multimedia["tipo_fuente"] == "video_externo"){
                          	$opcion1 = "";
                          	$opcion2 = "selected";
                          	$estilo1 = "none";
                          	$estilo2 = "table-row";
                          } else {
                          	$opcion1 = "selected";
                          	$opcion2 = "";
                          	$estilo1 = "table-row";
                          	$estilo2 = "none";
                          }
                          ?>
                          <tr>
                          	<td>Fuente:</td>
                            <td>
                            	<select name="tipo_fuente" onchange="cambia_fuente(this);">
                            		<option value="youtube" <?=$opcion1?>>Video internet</option>
                            		<option value="video_externo" <?=$opcion2?>>Video externo</option>
                            	</select>
                            </td>
                          </tr>
                          <tr>
                            <td class="separador" colspan="2"></td>
                          </tr>
                          <tr id="tipo1" style="display:<?=$estilo1?>">
                          	<td>C&oacute;digo:</td>
                            <td>
                            	<input type="text" name="codigo" size="40" value="<?=(isset($multimedia)) ? htmlentities($multimedia["codigo"]) : "";?>">                            	
                            </td>
                          </tr>
                          <tr id="tipo2" style="display:<?=$estilo2?>">
                          	<td>Video:</td>
                            <td>
                              <? if (!isset($multimedia) || $multimedia['video'] == ""){?>
                            	<input type="file" name="video">
                              <? }else{ ?>
                              	<input type="text" name="video" value="<?=(isset($multimedia)) ? stripslashes($multimedia["video"]) : "";?>" disabled>
                              	<img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar video" onclick="borrar_video('<?="../videos/".$multimedia['video']?>',<?=$multimedia['id']?>)">
                              <? } ?>
                            </td>
                          </tr>
                          <tr>
                          	<td colspan="2" align="center"><br>
                          		
                          	</td>
                          </tr>
                          <tr>
                            <td class="separador" colspan="2"></td>
                          </tr>
                          <? if (isset($multimedia) && $multimedia['thumbnail'] != ""){?>
							  <tr>
								<td colspan="2">Thumbnail:</td>
							  </tr>
							  <tr>
								<td class="separador" colspan="2"></td>
							  </tr>
							  <tr>
								<td><img src="../videos/thumbnails/<?=$multimedia['thumbnail']?>" width="100px" border="0" alt="<?=(isset($multimedia)) ? $multimedia['pie_thumbnail'] : "";?>"></td>
								<td><img src="images/cross.png" border="0" style="cursor:pointer" alt="Eliminar imagen" onclick="borrar_imagen('<?=$multimedia['thumbnail']?>',<?=$multimedia['id']?>)"></td>
							  </tr>
						  <? } else { ?>
							  <tr>
							    <td>Thumbnail: (Ancho: 100px)</td>
							    <td><input type="file" name="thumbnail" size="40" value=""></td>
							  </tr>
							  <tr>
			                    <td class="separador" colspan="2"></td>
			                  </tr>
						  <? } ?>
						  <tr>
                            <td class="separador" colspan="2"></td>
                          </tr>
						  <tr>
                            <td colspan="2">
                          	Tags:<br />
                          	<textarea name="tags" style="width:100%"><?=(isset($multimedia)) ? stripslashes($multimedia['tags']) : "";?></textarea>
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
            <input type="hidden" name="orden" value="<?=$orden?>" />
          </form>
          </td>
        </tr>
      </table>
    </td>
</tr>