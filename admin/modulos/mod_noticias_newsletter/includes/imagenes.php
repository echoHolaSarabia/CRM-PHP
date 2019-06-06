<div id="opcion1" style="display:block">
    <div id="content">
		<img id="loading" src="loading.gif" style="display:none;">
		<table cellpadding="0" cellspacing="0" class="tableForm">
			<tr>
				<td>Seleccione la imagen que desea subir:</td>
			</tr>
			<tr>
				<td><input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input">&nbsp;<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();">Upload</button></td>
			</tr>
		</table>
    </div>
    <table>
    	<tr>
			<td>
				<a href="javascript:mcImageManager.open('','','','',{relative_urls : true,insert_filter : filterURL1});">Elegir imagen para newsletter</a>
			<br><br>
				<div id="miniatura_foto">
					<?if (isset($noticia) && $noticia['foto'] != "") echo "<img src='".$noticia['foto']."' width='100px' onclick='window.open(\"modulos/mod_noticias/verimagen.php?src=\"+this.src,\"Previsualización 1\",\"width=800,height=600,scrollbars=1,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes\")' /><img border='0' src='images/cross.png' alt='Eliminar' title='Eliminar' onclick='borrar_imagen()' style='cursor:pointer'/>"?>
				</div>
				<input type="hidden" name="foto" id="img_foto" value="<?if (isset($noticia) && $noticia['foto'] != "") echo $noticia['foto'];?>">
			</td>
			<td></td>
			</tr>
    </table>
</div>