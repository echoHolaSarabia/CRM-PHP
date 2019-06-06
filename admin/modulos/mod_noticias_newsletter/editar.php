<? //include("clases/ficheros.php");?>
<? $funciones = new Funciones;?>
<script src="scripts/ficheros.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript" src="scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="modulos/mod_noticias_newsletter/scripts/tiny_mce.js"></script>
<script type="text/javascript" src="scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="modulos/mod_noticias/upload/ajaxfileupload.js"></script>
<script language="javascript">

function borrar_doc (doc, id, tipo){
	if (confirm("¿Desea eliminar el documento "+doc+"?")){
		document.location.href = "index2.php?modulo=mod_noticias_newsletter&fns=1&accion=borra_doc&id="+id+"&doc="+doc+"&tipo="+tipo;
	}
}

function filterURL1(file) {
	window.open('/admin/modulos/mod_noticias/recorte/index.php?tipo=foto&foto='+file.url,'recorte2','width=1000,height=800,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes');
}

function elegir_imagen(ref){
	window.open('modulos/mod_noticias_newsletter/seleccion.php?ref='+ref,'retoque','width=1000,height=800,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes');
}

function valida_datos (form){
	txt = eval(form);
	if (txt.titulo.value == ""){
		alert("La noticia debe tener un titular");
		return false;	
	}
	if (txt.seccion.options[txt.seccion.selectedIndex].value == ""){
		alert("Debe asociar la noticia a una sección");
		return false;	
	}
	document.form_noticia.tipo_newsletter.disabled=false;
	document.form_noticia.submit();
}

function borrar_imagen (){
	if (confirm("¿Desea eliminar la imagen de la newsletter?")){
		document.getElementById("img_foto").value = "";
		document.getElementById("miniatura_foto").innerHTML = "";
	}
}
</script>

<?
if (isset($_GET['id']) && $_GET['id']!=""){
	$r = mysql_query("SELECT * FROM noticias_newsletter WHERE id = ".$_GET['id']);
	$noticia = mysql_fetch_array($r);
	$accion = "update&id=".$noticia['id'];
}else{
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
              	<td class="botones_botonera"><a href="#" onclick="valida_datos('document.form_noticia')" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_noticias_newsletter" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Edicion de noticia para newsletter</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_noticia" method="post" action="?modulo=mod_noticias_newsletter&fns=1&accion=<?=$accion?>" onsubmit="return valida_datos(this);" enctype="multipart/form-data">
          <input type="hidden" name="foto" value="<?=(isset($noticia)) ? htmlentities($noticia['foto']) : "";?>">
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
                            <td class="separador"></td>
                          </tr>
                          <tr>
                            <td class="etiqueta">Newsletter:</td>
                            <td>
                            	<input type="text" name="tipo_newsletter" value="<?=(isset($noticia)) ? $noticia["tipo_newsletter"] : "Externo" ?>" disabled>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    
                   
                    <tr>
                      <td>
                      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td class="separador"></td>
                          </tr>
                          <tr>
                            <td class="etiqueta">Secci&oacute;n:</td>
                            <td>
                            	<select name="seccion" style="width:150px">
                            		<option value="">Elija una ...</option>
                                	<?
                                    $r = mysql_query("SELECT * FROM secciones WHERE id<>0 AND id_padre=-1 AND activo=1 ORDER BY id ASC");
									while ($seccion = mysql_fetch_array($r)){
									?>
                                	<option value="<?=$seccion['id']?>" <? if (isset($noticia) && $seccion['id'] == $noticia['seccion']) echo "selected";?>><?=$seccion['titulo']?></option>
                                    <?
                                    }?>
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
                            <td class="etiqueta">Antet&iacute;tulo:</td>
                      		<td><input type="text" name="antetitulo" value="<?=(isset($noticia)) ? htmlentities($noticia['antetitulo']) : "";?>" style="width:100%" /></td>
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
                      		<td><input type="text" name="titulo" value="<?=(isset($noticia)) ? htmlentities($noticia['titulo']) : "";?>" style="width:100%" /></td>
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
                            <td class="etiqueta">Entradilla:</td>
                          </tr>
                          <tr>
                            <td class="separador"></td>
                          </tr>
                          <tr>
                            <td><textarea name="entradilla" id="entradilla" class="mceEditor"><?=(isset($noticia)) ? $noticia['entradilla'] : "";?></textarea></td>
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
                            <li><a href="javascript:cambia_opciones(1,2)">Imágenes</a></li>
                            <li><a href="javascript:cambia_opciones(2,2)">Archivos</a></li>
                          </ul>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      <?include("includes/imagenes.php");?>
                      <?include("includes/archivos.php");?>	
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