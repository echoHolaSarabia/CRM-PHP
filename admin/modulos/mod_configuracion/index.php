<? include ($config_path_admin."/modulos/mod_configuracion/funciones_auxiliares.php");?>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td class="botones_botonera"><a href="javascript:document.form_configuracion.submit();" class="enlaces_botones_botonera"><img src="images/subir.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=mod_index" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador">
            <table width="100%" border="0">
           	  <tr>
           	  	<td><span class="titulo_seccion">CONFIGURACI&Oacute;N</span></td>
           	  	<td><? if (!is_writable($config_path_absoluta."/configuracion.php")){?>AVISO: <span class="advertencia">No tiene permisos para modificar configuracion.php</span><? } ?></td>
           	  </tr>
            </table>	
          </td>
        </tr>
        <?
        $df = fopen($config_path_absoluta."/configuracion.php","r");
        $configuracion = getVariables($df);
        //print_r($configuracion);
        ?>
        <tr>
          <td class="contenido">
            <table width="100%" border="0">
              <tr>
              	<td>
              	  <form name="form_configuracion" method="post" action="index2.php?modulo=mod_configuracion&fns=1&accion=save">
              	  <fieldset><legend>Sitio</legend> 
		            <table>
		      	      <tr>
		      	        <td>Nombre del sitio:</td>
		      	        <td><input type="text" name="nombre_sitio" value="<?=$configuracion['config_nombre_sitio']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Ruta del proyecto:</td>
		      	        <td><input type="text" name="path_absoluta" size="50" value="<?=$configuracion['config_path_absoluta']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Ruta del admin:</td>
		      	        <td><input type="text" name="path_admin" size="50" value="<?=$configuracion['config_path_admin']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Ruta de los archivos multimedia:</td>
		      	        <td><input type="text" name="path_ficheros" size="50" value="<?=$configuracion['config_path_ficheros']?>"></td>
		      	      </tr>
		      	    </table>
	      	      </fildset>
              	</td>
              </tr>
              <tr>
              	<td>
              	  <fieldset><legend>Base de datos</legend> 
		            <table>
		      	      <tr>
		      	        <td>Host:</td>
		      	        <td><input type="text" name="host" value="<?=$configuracion['config_host']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Esquema de la base de datos:</td>
		      	        <td><input type="text" name="db" value="<?=$configuracion['config_db']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Usuario:</td>
		      	        <td><input type="text" name="user" value="<?=$configuracion['config_user']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Contraseña:</td>
		      	        <td><input type="text" name="password" value="<?=$configuracion['config_password']?>"></td>
		      	      </tr>
		      	    </table>
	      	      </fildset>
              	</td>
              </tr>
              <tr>
              	<td>
              	  <fieldset><legend>Otros</legend> 
		            <table>
		              <tr>
		      	        <td>Tags predeterminados:</td>
		      	        <td><input type="text" name="tags" size="100" value="<?=$configuracion['config_tags']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Registros de los paginadores:</td>
		      	        <td><input type="text" name="registros_paginador" size="3" value="<?=$configuracion['config_registros_paginador']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Número de noticias en la portada:</td>
		      	        <td><input type="text" name="num_noticias_portada" size="3" value="<?=$configuracion['config_num_noticias_portada']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Número de noticias en las secciones:</td>
		      	        <td><input type="text" name="num_noticias_secciones" size="3" value="<?=$configuracion['config_num_noticias_secciones']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Número de noticias de "Lo mas visto":</td>
		      	        <td><input type="text" name="num_noticias_lo_mas" size="3" value="<?=$configuracion['config_num_noticias_lo_mas']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Número de comentarios del paginador de comentarios:</td>
		      	        <td><input type="text" name="paginador_comentarios" size="3" value="<?=$configuracion['config_paginador_comentarios']?>"></td>
		      	      </tr>
		      	      <tr>
		      	        <td>Número de noticias del rotador:</td>
		      	        <td><input type="text" name="noticias_rotador" size="3" value="<?=$configuracion['config_noticias_rotador']?>">&nbsp; * 0 para no mostrar rotador</td>
		      	      </tr>
		      	      <!--<tr>
		      	        <td>Permitir votación de art&iacute;culos:</td>
		      	        <td><input type="radio" name="permite_votacion" align="middle" value="0" <? if ($configuracion['config_permite_votacion'] == 0) echo "checked";?>>No
		      	        	&nbsp;<input type="radio" name="permite_votacion" align="middle" value="1" <? if ($configuracion['config_permite_votacion'] == 1) echo "checked";?>>Si</td>
		      	      </tr>
		      	      <tr>
		      	        <td>Mostrar nombre de los autores:</td>
		      	        <td><input type="radio" name="muestra_autor" align="middle" value="0" <? if ($configuracion['config_muestra_autor'] == 0) echo "checked";?>>No
		      	        	&nbsp;<input type="radio" name="muestra_autor" align="middle" value="1" <? if ($configuracion['config_muestra_autor'] == 1) echo "checked";?>>Si</td>
		      	      </tr>-->
		      	    </table>
	      	      </fildset>
	      	      </form>
              	</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
</tr>