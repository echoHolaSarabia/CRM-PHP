<? include("clases/ficheros.php");?>
<? $funciones = new Funciones;?>
<script language="javascript" type="text/javascript">
function valida_datos (form){
	txt = eval(form);
	if (txt.autor.value == ""){
		alert("Debe rellenar el campo autor");
		return false;
	}
	if (txt.titular.value == ""){
		alert("La noticia debe tener un titular");
		return false;
	}
	if (txt.seccion.options[txt.seccion.selectedIndex].value == ""){
		alert("Debe asociar la noticia a una sección");
		return false;
	}
	document.form_noticia.img_portada.value = upload.document.form_upload.carpeta.value + upload.document.form_upload.img_portada.value;
	document.form_noticia.img_seccion.value = upload.document.form_upload.carpeta.value + upload.document.form_upload.img_seccion.value;
	document.form_noticia.submit();
}

function anadir(){
	if (document.form_noticia_newsletter.noticia1.length!=0){
		texto=document.form_noticia_newsletter.noticia1.options[document.form_noticia_newsletter.noticia1.selectedIndex].text;
		valor=document.form_noticia_newsletter.noticia1.options[document.form_noticia_newsletter.noticia1.selectedIndex].value;
		op = new Option(texto,valor,"","");
		document.form_noticia_newsletter.noticia2.options[document.form_noticia_newsletter.noticia2.length]=op;
		document.form_noticia_newsletter.noticia1.options[document.form_noticia_newsletter.noticia1.selectedIndex]=null;
	}else{
		alert("No quedan mas noticias");
	}
}

function quitar(){
	if (document.form_noticia_newsletter.noticia2.length!=0){
		valor=document.form_noticia_newsletter.noticia2.options[document.form_noticia_newsletter.noticia2.selectedIndex].value;
		texto=document.form_noticia_newsletter.noticia2.options[document.form_noticia_newsletter.noticia2.selectedIndex].text;
		op = new Option(texto,valor,"","");
		document.form_noticia_newsletter.noticia1.options[document.form_noticia_newsletter.noticia1.length]=op;
		document.form_noticia_newsletter.noticia2.options[document.form_noticia_newsletter.noticia2.selectedIndex]=null;
	}else{
		alert("No quedan mas noticias");
	}
}
</script>
<?
if (isset($_GET['id']) && $_GET['id']!=""){
	$r = mysql_query("SELECT * FROM noticias WHERE id = ".$_GET['id']);
	$noticia = mysql_fetch_array($r);
	$orden = $noticia['orden'];
	$accion = "update&id=".$noticia['id'];
}else{
	$r = mysql_query("SELECT MAX(orden) AS ultimo FROM noticias;");
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
              	<td class="botones_botonera"><a href="javascript:previsualizar();" class="enlaces_botones_botonera"><img src="images/preview.png" border="0"><br />Previsualizar</a></td>
              	<td class="botones_botonera"><a href="#" onclick="valida_datos('document.form_noticia')" class="enlaces_botones_botonera"><img src="images/accept.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_noticias" class="enlaces_botones_botonera"><img src="images/x.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Edicion de newsletter</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_noticia_newsletter" method="post" action="?modulo=mod_noticias&fns=1&accion=<?=$accion?>" enctype="multipart/form-data">
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
                      		<td><input type="text" name="nombre" value="<?=$noticia['nombre']?>" style="width:100%" /></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
                    </tr>
                    <tr>
                      <td>
                      	<table width="100%" border="0">
	                      	<tr>
								<td align="center">
									<?
									$c="SELECT * FROM noticias_newsletter ORDER BY id";
									$r=mysql_query($c);
									?>
									<select name="noticia1" size="20" style=" width:400px">
									<? while ($fila=mysql_fetch_array($r)){?>
										<option value="<?=$fila['id']?>"><?=$fila['titular']?></option>
									<? } ?>
									</select>
								</td>
								<td><a href="#" onclick="anadir();">-></a><br><a href="#" onclick="quitar();"><-</a></td>
								<td align="center">
									<?
										$c="SELECT * FROM noticias n,categorias_noticias cn 
										    WHERE n.id=cn.idNoticia 
											AND cn.idNewsletter=".$_GET['id']."
											AND cn.idCategoria<>0 ORDER BY cn.orden  DESC";
										$r=mysql_query($c);
									?>
									<select multiple="multiple" name="noticia2" size="20" style=" width:400px">
									<? while ($fila=mysql_fetch_array($r)){?>
										<option value="<?=$fila['id']?>"><?=$fila['titulo']?></option>
									<? } ?>
									</select>
									<input type="hidden" name="noticias" value="">
								</td>
							</tr>
                      	</table>
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