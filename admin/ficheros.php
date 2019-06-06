<? include("clases/ficheros.php");?>
<? include("configuracion.php");?>
<?
//echo $path_absoluta;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/estilos_ficheros.css" type="text/css" rel="stylesheet" />
<link href="css/estilos_general.css" type="text/css" rel="stylesheet" />
<script src="scripts/ficheros.js" language="javascript" type="text/javascript"></script>
<title>Documento sin t&iacute;tulo</title>
</head>

<body bgcolor="#FFFFFF">
<?
$fichero = new Fichero;
$imagenes_permitidas = $fichero->getImagenesPermitidas();
if ($_GET["ruta"] != "")
	$current_dir = $_GET["ruta"]."/";
else $current_dir = '../userfiles/';
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
      <td class="buscador"><span class="titulo_seccion">GESTION DE FICHEROS</span></td>	
      <td align="right">
          <form name="form_carpeta" action="index2.php?modulo=mod_ficheros&fns=1&accion=crear_carpeta&dir=<?=$current_dir?>" method="post">
        	Nueva carpeta:&nbsp;<input type="text" name="carpeta" size="50" />&nbsp;<input type="submit" name="nueva_carpeta" value="Nueva Carpeta" />
          </form>
          <br />
          <form name="form_archivos" action="index2.php?modulo=mod_ficheros&fns=1&accion=subir&dir=<?=$current_dir?>" method="post" enctype="multipart/form-data">
        	Subir archivo:&nbsp;<input type="file" name="archivo" size="50" />
          </form>
          <br />
          <?
          //$current_dir = '../userfiles/';
		  $dir = opendir($current_dir);
		  ?>
          Ir a:&nbsp;<select name="ruta">
          				<? 
						   while ($file = readdir($dir)){
						   	 $extension = $fichero->getExtension($file);
						     if ($extension == "" && $file != "." && $file != ".."){
						?>
                        	   <option><?=$file?></option>
						<?   }
						   } ?>
          			 </select>
        </td>
      </tr>
</table>
<?
//Primero listamos las carpetas
$dir = opendir($current_dir);
while ($file = readdir($dir)){
	if (is_dir($current_dir.$file) && (($file != ".") && ($file != ".."))){
	?>
	<div id="cuadro">
        <div class="imagen">
            <img border="0" align="middle" alt="" src="images/carpeta.png" onclick="document.location.href='ficheros.php?ruta=<?=$current_dir.$file?>';" style="cursor:pointer"/><br>
        </div>
        <div class="acciones">
        	<span><?=substr($file,0,15)?></span><br />
            <img src="images/delete.png" align="left" onclick="elimina_carpeta('<?=$current_dir.$file?>');" style="cursor:pointer;" />
        </div>
    </div>
<?
	}
}
closedir($dir);

//Y despues el resto de los ficheros
$dir = opendir($current_dir);
while ($file = readdir($dir)){
	$extension = $fichero->getExtension($file);
	if (!is_dir($current_dir.$file)){
?>
	<div id="cuadro">
        <div class="imagen">
            <a href="#">
            	<? if (in_array($extension,$imagenes_permitidas)){ ?>
                	 <img border="0" align="middle" alt="" src="<?=$current_dir.$file?>" width="60px" onclick="popup('<?=$current_dir.$file?>');"/><br>
                <? } else { ?>
		                <img border="0" align="middle" alt="" src="images/desconocido.png" /><br>
		                <? } ?>
            </a>
        </div>
        <div class="acciones">
        	<span><?=substr($file,0,15)?></span><br />
            <img src="images/delete.png" align="left" onclick="elimina_fichero('<?=$current_dir.$file?>');" style="cursor:pointer;" />
        </div>
    </div>
<?
	}
}
closedir($dir);
?>
</body>
</html>