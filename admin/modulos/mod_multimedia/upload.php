<?
include ("../../../configuracion.php");
include ($config_path_admin."/clases/ficheros.php");
include ($config_path_admin."/includes/conexion.inc.php");
$fichero = new Fichero;
if ($_POST['ruta'] != ""){
	$fichero->upload($_FILES,$_POST['ruta']);
}
if ($_GET['id'] != ""){
	$r = mysql_query("SELECT * FROM multimedia WHERE id=".$_GET['id']."");
	$fila = mysql_fetch_array($r);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title></title>
<link href="../../css/iframe_imagenes.css" type="text/css" rel="stylesheet" />
<script src="../../scripts/ficheros.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript">
function reloadPage(){
	ruta = document.form_upload.carpeta.options[document.form_upload.carpeta.selectedIndex].value;
	document.form_upload.ruta.value = "../../../userfiles"+ruta;
	document.form_upload.submit();
}
</script>
</head>

<body>
<form name="form_upload" method="post" action="" enctype="multipart/form-data">
 	<input type="hidden" name="ruta" value="">
 <table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Seleccione la carpeta origen:</td>
    <td>
    <?
     $directorio_raiz = '../../../userfiles/';
     $fichero = new Fichero;
     $lista = array();
     $lista = $fichero->getCarpetas($directorio_raiz,$lista);
     $string_ruta = substr($fila['img_portada'],0,strrpos($fila['img_portada'],"/")+1);
    ?>
      <select name="carpeta" onchange="cambia_listado(this,'<?=$directorio_raiz?>');">
		<? foreach ($lista as $rutas){?>
		   	 <option <? if (($_GET['ruta'] == $rutas) || ($string_ruta == substr($rutas,18))) echo "selected";?> value="<?=substr($rutas,18)?>"><?=substr($rutas,18)?></option>
		<? } ?>
	  </select>
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Upload de ficheros:</td>
    <td><input type="file" name="fichero">&nbsp;<input type="button" name="subir_foto" value="Upload" onclick="reloadPage();"></td>
  </tr> 
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Imagen de portada:</td>
    <td>
      <table>
        <tr>
          <td>
          	<?
		      if (isset($_GET['ruta']) && ($_GET['ruta'] != "")){
		      	$directorio_raiz = $_GET['ruta'];
		      }else{
		      	if (isset ($_GET['id']) && ($_GET['id'] != ""))
		      		$directorio_raiz = '../../../userfiles/'.$string_ruta;
		      	else $directorio_raiz = '../../../userfiles/';
		      }
		       $fichero = new Fichero;
		       $lista = array();
		       $lista = $fichero->getImagenes($directorio_raiz,$lista);
		    ?>
		      <select multiple name="foto_portada" onchange="cambia_imagen(this,'<?=$directorio_raiz?>',1);" style="width:150px" size="10">
				<? foreach ($lista as $rutas){?>
					<option <? if ($_GET['ruta'] == $rutas) echo "selected";?> value="<?=$rutas?>"><?=$rutas?></option>
				<? } ?>
			  </select>
          </td>
          <td><a href="#" onclick="anyade_imagen(1)">-></a><br><a href="#" onclick="borra_imagen(1)"><-</a></td>
          <td><input type="text" name="img_portada" value="<?=substr($fila['img_portada'],strrpos($fila['img_portada'],"/")+1)?>"></td>
        </tr>
        </tr>
      </table>	          			
    </td>
  </tr>
  <tr>
    <td class="separador" colspan="2"></td>
  </tr>
  <tr>
    <td>Preview:</td>
    <td><div id="portada"></div></td>
  </tr>
 </table>
</form>
</body>
</html>
