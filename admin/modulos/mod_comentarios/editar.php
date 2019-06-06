<script type="text/javascript" src="scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="modulos/mod_noticias/scripts/tiny_mce.js"></script>

<script language="javascript">
function valida_datos (form){
	document.form_comentarios.submit();
}
</script>
<?
if (isset($_GET['id']) && $_GET['id']!=""){
	$r = mysql_query("SELECT * FROM comentarios WHERE id = ".$_GET['id']);
	$comentario = mysql_fetch_array($r);
	$accion = "update&id=".$comentario['id'];
}else{
	$accion = "insert";
}

if (isset($_GET["tabla_comentado"]))
	$tabla_comentado = $_GET["tabla_comentado"];
else $tabla_comentado = "";

if (isset($_GET["id_comentado"]))
	$id_comentado = $_GET["id_comentado"];
else $id_comentado = "";

?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td class="botones_botonera"><a href="#" onclick="valida_datos('document.form_comentarios')" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_comentarios&tabla_comentado=<?=$tabla_comentado?>&id_comentado=<?=$id_comentado?>" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion">Edicion de comentarios</span></td>
        </tr>
        <tr>
          <td>
          <form name="form_comentarios" method="post" action="?modulo=mod_comentarios&fns=1&accion=<?=$accion?>&tabla_comentado=<?=$tabla_comentado?>&id_comentado=<?=$id_comentado?>" onsubmit="return valida_datos(this);" enctype="multipart/form-data">
          	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td width="100%" class="contenido" valign="top">
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
                            <td><input type="text" name="autor" value="<?=$comentario['autor']?>" style="width:200px" /></td>
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
                          	<td class="etiqueta">email:</td>
                            <td><input type="text" name="email" value="<?=$comentario['email']?>" style="width:200px" /></td>
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
                            <td class="etiqueta">Descripci&oacute;n:</td>
                          </tr>
                          <tr>
                            <td class="separador"></td>
                          </tr>
                          <tr>
                            <td>
                            <textarea name="texto" id="texto" class="mceEditor" style="height:400px"><?=(isset($comentario)) ? $comentario['texto'] : "";?></textarea>                            
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
                            <td class="etiqueta">Web:</td>
                      		<td><input type="text" name="web" value="<?=$comentario["web"]?>" style="width:550px" /></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador"></td>
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