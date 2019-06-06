<? 
include("clases/ficheros.php");
if (isset($_GET['ruta']) && ($_GET['ruta'] != ""))
	$ruta = $_GET['ruta'];
else $ruta = "";
?>
<style type="text/css">
/* Easy CSS Tooltip */
* {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px;text-decoration:none; }
a:hover {background:#6c6c6c; text-decoration:none;} /*BG color is a must for IE6*/
a.tooltip span {display:none; padding:2px 3px; margin-left:8px; width:200px;}
a.tooltip:hover span{display:inline; position:absolute; border:1px solid #cccccc; background:#ffffff; color:#6c6c6c;}
</style>
<script src="scripts/ficheros.js" language="javascript" type="text/javascript"></script>
<tr>
  <td class="botonera" align="right">
    <table border="0" cellpadding="0" cellspacing="0" >
      <tr>
        <td class="botones_botonera"><a href="javascript:document.form_archivos.submit();" class="enlaces_botones_botonera"><img src="images/subir.png" border="0"><br />Subir</a></td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td align="center" class="cuadro_ficheros" valign="top">
    <?
    $directorio_raiz = '../userfiles/';
	$fichero = new Fichero;
	$imagenes_permitidas = $fichero->getImagenesPermitidas();
	//Elimino las rutas maliciosas en la medida de lo posible
	if ($ruta != "" && ereg("../userfiles/*",$ruta)) 
		$current_dir = $ruta."/";
	else $current_dir = $directorio_raiz;
	?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	      <tr>
	      <td class="buscador" valign="top"><span class="titulo_seccion">GESTION DE FICHEROS</span>&nbsp;&nbsp;&nbsp;<img src="images/btnFolderUp.gif" onclick="javascript:subir_nivel('<?=$ruta?>');" style="cursor:pointer;"></td>	
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
			  $dir = opendir($current_dir);
			  $lista = array();
			  $lista = $fichero->getCarpetas($directorio_raiz,$lista);
			  ?>
	          Ir a:&nbsp;<select name="ruta" onchange="cambia_ruta(this,'../userfiles');">
	          				<? foreach ($lista as $rutas){?>
	                        	   <option <? if ($ruta == $rutas) echo "selected";?>><?=substr($rutas,12);?></option>
							<? } ?>
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
	            <img border="0" align="middle" alt="" src="images/carpeta.png" onclick="document.location.href='index2.php?modulo=mod_ficheros&ruta=<?=$current_dir.$file?>';" style="cursor:pointer"/><br>
	        </div>
	        <div class="acciones">
	        	<span><?=substr($file,0,10)?></span><br />
	            <img src="images/delete.png" align="left" onclick="elimina_carpeta('<?=$current_dir.$file?>','<?=$current_dir?>');" style="cursor:pointer;" />
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
			$dimension = getimagesize($current_dir.$file);
	?>
		<div id="cuadro">
	        <div class="imagen">
	            <a href="#">
	            	<? if (in_array($extension,$imagenes_permitidas)){ ?>
	                	 <img border="0" align="middle" alt="" src="<?=$current_dir.$file?>" width="60px" height="40px" onclick="popup('<?=$current_dir.$file?>');"/><br>
	                <? } else { ?>
			             <img border="0" align="middle" alt="" src="images/desconocido.png" /><br>
			        <? } ?>
	            </a>
	        </div>
	        <div class="acciones">
	        	<a href="#" class="tooltip" style="color:#FFFFFF"><?=substr($file,0,10)?><span><?=$file?><br>ancho: <?=$dimension[0]?>px<br>alto: <?=$dimension[1]?>px</span></a><br />
	            <img src="images/delete.png" align="left" onclick="elimina_fichero('<?=$current_dir.$file?>','<?=$current_dir?>');" style="cursor:pointer;" />
	        </div>
	    </div>
	<?
		}
	}
	closedir($dir);
	?>
  </td>
</tr>