<ul id="navmenu-h">
  <?php //if (tiene_permisos("mod_index",$_SESSION['permisos'])){?>
  <!--<li><a href="index2.php">Inicio</a></li>-->
  <?php //} ?>
  <?php if (tiene_permisos("mod_noticias", $_SESSION['permisos'])) { ?>
    <li><a href="index2.php?modulo=mod_noticias">Listado de noticias</a></li>
  <?php } ?>
  <?php if (tiene_permisos("mod_planillas", $_SESSION['permisos'])) { ?>
    <li><a href="#">Planillas +</a>
      <ul>
        <?php
        $r = get_secciones_activas();
        while ($seccion = mysql_fetch_array($r)) {
          $r2 = get_subsecciones_activas($seccion['id']);
          $num_subsecciones = mysql_num_rows($r2)
          ?>
          <li><a href="index2.php?modulo=mod_planillas&seccion=<?php echo $seccion['id'] ?>"><?php echo $seccion['titulo'] ?> <?php if ($num_subsecciones > 0)
        echo "+"; ?></a>
            <ul>
              <?php
              while ($subseccion = mysql_fetch_array($r2)) {
                ?>
                <li><a href="index2.php?modulo=mod_planillas&seccion=<?php echo $subseccion['id'] ?>"><?php echo $subseccion['titulo'] ?></a></li>
              <?php } ?>
            </ul>
          </li>
          <?php
        }
        ?>
        <li><a href="index2.php?modulo=mod_planillas&ampliada=si">Noticia ampliada</a></li>
        <li><a href="index2.php?modulo=mod_planillas&ampliada=si&newsletter=si">Registro Newsletter</a></li>
      </ul>
    </li>
  <?php } ?>
  
  <?php 
		$resultado = mysql_query("SELECT * FROM comentarios WHERE publicado = '0'");
		$num_rows = mysql_num_rows($resultado);
					
					
    
    ?>
    <li><?php if ($num_rows > 0){ echo '<a href="#" style="background: rgb(129, 236, 129);">Contenido +</a>'; }else{?><a href="#">Contenido +</a>
    <?php } ?>
     
    <ul>
      <?php if (tiene_permisos("mod_noticias", $_SESSION['permisos'])) { ?>
        <li><a href="index2.php?modulo=mod_noticias&accion=nuevo">Nueva noticia</a></li>
      <?php } ?>
      <li><a href="index2.php?modulo=mod_galerias_video">Vídeos</a></li>
      <?php /*
        <li><a href="index2.php?modulo=mod_multimedia">Multimedia +</a>
        <ul>
        <?php if (tiene_permisos("mod_galerias_imagenes",$_SESSION['permisos'])){?>
        <li><a href="index2.php?modulo=mod_galerias_imagenes">Galerias de imágenes</a></li>
        <?php}?>
        <?php if (tiene_permisos("mod_galerias_imagenes",$_SESSION['permisos'])){?>
        <li><a href="#">Galerias de videos +</a>
        <ul>
        <li><a href="index2.php?modulo=mod_galerias_video">Vídeos</a></li>
        <li><a href="index2.php?modulo=mod_galerias_video&ver=secciones">Secciones</a></li>
        </ul>
        </li>
        <?php}?>
        </ul>
        </li> */ ?>
      <?php if (tiene_permisos("mod_comentarios", $_SESSION['permisos'])) { ?>
        <li><a href="index2.php?modulo=mod_comentarios">Comentarios <?php if ($num_rows > 0){ echo "(".$num_rows." sin leer)"; }?></a></li>
      <?php } ?>
      <?php if (tiene_permisos("mod_ficheros", $_SESSION['permisos'])) { ?>
        <li><a href="index2.php?modulo=mod_ficheros">Ficheros</a></li>
      <?php } ?>
      <?php if (tiene_permisos("mod_titulos", $_SESSION['permisos'])) { ?>
        <li><a href="index2.php?modulo=mod_titulos">Títulos y descripciones</a></li>
      <?php } ?>
      <?php if (tiene_permisos("mod_rosas", $_SESSION['permisos'])) { ?>
        <li><a href="index2.php?modulo=mod_rosas">Módulos especiales</a></li>
      <?php } ?>
      <?php if (tiene_permisos("mod_autores", $_SESSION['permisos'])) { ?>
        <li><a href="index2.php?modulo=mod_autores">Autores</a></li>
      <?php } ?>
    </ul>
  </li>
  <?php //if (tiene_permisos("mod_hemeroteca",$_SESSION['permisos'])){?>
  <!--<li><a href="index2.php?modulo=mod_hemeroteca">Hemeroteca</a></li>-->
  <?php //} ?>
  <?php /* if (tiene_permisos("mod_secciones",$_SESSION['permisos'])){?>
    <li><a href="#">Secciones +</a>
    <ul>
    <li><a href="index2.php?modulo=mod_secciones">Ver secciones</a></li>
    <li><a href="index2.php?modulo=mod_secciones&accion=nuevo">Nueva secci&oacute;n</a></li>
    </ul>
    </li>
    <?php } */ ?>
  <?php if (tiene_permisos("mod_newsletter", $_SESSION['permisos'])) { ?>
    <li><a href="#">Newsletter +</a>
      <ul>
        <li><a href="index2.php?modulo=mod_banners_newsletter">Banners</a></li>
        <li><a href="index2.php?modulo=mod_noticias_newsletter">Ver noticias</a></li>
        <li><a href="index2.php?modulo=mod_newsletter">Ver newsletters</a></li>
        <li><a href="index2.php?modulo=mod_newsletter&accion=nuevo">Crear Newsletter</a></li>
        <li><a href="index2.php?modulo=mod_suscriptores">Suscriptores</a>
          <!--      	<li><a href="index2.php?modulo=mod_newsletter_estadisticas">Estad&iacute;sticas</a>-->
      </ul>
    </li>
  <?php } ?>
  <?php if (tiene_permisos("mod_configuracion", $_SESSION['permisos'])) { ?>
    <li><a href="index2.php?modulo=mod_configuracion">Configuraci&oacute;n</a></li>
  <?php } ?>
  <li><a href="#">Publicidad +</a>
    <ul>
      <li><a href="../openx" target="_blank">OpenX</a></li>
    </ul>
  </li>
  <!--<li><a href="index2.php?modulo=mod_estadisticas">Estad&iacute;sticas</a></li>-->
  <?php if (tiene_permisos("mod_encuestas", $_SESSION['permisos'])) { ?>
    <li><a href="#">Encuestas +</a>
      <ul>
        <li><a href="index2.php?modulo=mod_encuestas">Ver todas</a></li>
        <li><a href="index2.php?modulo=mod_encuestas&accion=nuevo">Crear encuesta</a></li>
      </ul>
    </li>
  <?php } ?>
  <li><a href="#">Usuarios +</a>
    <ul>
      <?php if (tiene_permisos("mod_administradores", $_SESSION['permisos'])) { ?>
        <li><a href="index2.php?modulo=mod_administradores">Administradores</a></li>
      <?php } ?>
      <!--<li><a href="#">Usuarios</a></li>-->
    </ul>
  </li>

  <?php /* if (tiene_permisos("mod_ultima_hora",$_SESSION['permisos'])){?>
    <li><a href="index2.php?modulo=mod_ultima_hora">Última Hora</a></li>
    <?php } */ ?>
</ul>
