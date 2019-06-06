<?
include("fckeditor/fckeditor.php");
$modulo = "mod_rs_categorias";
$funciones = new Funciones;
$tipo = (isset($_GET['tipo'])) ? $_GET['tipo'] : "blog";
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM rs_categorias WHERE tipo='".$tipo."' AND id=".$_GET['id']);
	$categoria = mysql_fetch_array($r);
	$accion = "update&id=".$categoria['id'];
} else {// INSERTAR
	$accion = "insert";
}
?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td align="center"><? include ("includes/menu_rs.inc.php");?></td>
        </tr>
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
                <td class="botones_botonera"><a href="javascript:document.form_rs_categoria.submit();" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=<?=$modulo?>" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nueva ";else echo "Editar ";?><?=$tipo?></span></td>
        </tr>
        <tr>
          <td>
          <form name="form_rs_categoria" method="post" action="?modulo=<?=$modulo?>&fns=1&accion=<?=$accion?>" enctype="multipart/form-data">
          	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td class="titulos" colspan="2">Detalles</td>
              </tr>
              <tr>
              	<td class="separador" colspan="2"></td>
              </tr>
              <tr>
              	<td class="contenido">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">                    
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">T&iacute;tulo:</td>
                      <td><input type="text" name="titulo" value="<?=(isset($categoria)) ? stripslashes(htmlentities($categoria['titulo'])) : "";?>" style="width:500px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Texto:</td>
                      <td>
                      	<?
								        $oFCKeditor = new FCKeditor('comentario');
								        $oFCKeditor->BasePath = 'fckeditor/';
								        $oFCKeditor->Value = (isset($categoria)) ? stripslashes($categoria['comentario']) : "";
								        $oFCKeditor->Width  = '500' ;
								        $oFCKeditor->Height = '200' ;
								        $oFCKeditor->ToolbarSet = 'Basic';
								        $oFCKeditor->Create();
								        ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Autor:</td>
                      <td><a href="index2.php?modulo=mod_rs_miembros&accion=editar&id=<?=$categoria['id_miembro']?>"><?=$funciones -> get_nombre_miembro($categoria['id_miembro']);?></a></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Foto:</td>
                      <td><? $funciones -> muestra_foto("/rs_fotos/thumbnails/170_".$categoria['url_foto'],"url_foto")?></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Video:</td>
                      <td><? $funciones -> muestra_video($categoria['cod_video'],"cod_video")?></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Archivo:</td>
                      <td><? $funciones -> muestra_archivo($categoria['url_archivo'],"url_archivo")?></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">T&iacute;tulo del archivo:</td>
                      <td><input type="text" name="titulo_archivo" value="<?=(isset($categoria)) ? $categoria['titulo_archivo'] : "";?>" style="width:500px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"><hr></td>
                    </tr> 
                    <tr>
                      <td class="etiqueta_200px">T&iacute;tulo alternativo:</td>
                      <td><input type="text" name="titulo_alt" value="<?=(isset($categoria)) ? $categoria['titulo_alt'] : "";?>" style="width:500px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Entradilla alternativa:</td>
                      <td><input type="text" name="entradilla_alt" value="<?=(isset($categoria)) ? $categoria['entradilla_alt'] : "";?>" style="width:500px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Foto alternativa:</td>
                      <td><? $funciones -> muestra_foto_alt($categoria['foto_alt'],"foto_alt")?></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <input type="hidden" name="tipo" value="<?=$tipo?>">
          </form>
          </td>
        </tr>
      </table>
    </td>
</tr>
