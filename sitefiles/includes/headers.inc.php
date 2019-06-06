<?php
// Evitamos la inyeccion SQL
// Modificamos las variables pasadas por URL
foreach ($_GET as $variable => $valor)
  $_GET[$variable] = str_replace("'", "\'", $_GET[$variable]);
// Modificamos las variables de formularios
foreach ($_POST as $variable => $valor)
  $_POST[$variable] = str_replace("'", "\'", $_POST[$variable]);
session_start();

include("configuracion.php");
include("admin/includes/conexion.inc.php");

if (isset($_GET["id_seccion"])) $id_seccion = $_GET["id_seccion"];
else $id_seccion = 1;

if (isset($_GET["id_subseccion"])) $id_subseccion = $_GET["id_subseccion"];
else $id_subseccion = -1;

if (isset($_GET["id_noticia"])) {
  $id_noticia = $_GET["id_noticia"];
  mysql_query("UPDATE noticias SET hits = hits + 1 WHERE id=" . $id_noticia);

  if (isset($_GET['blog']) && $_GET['blog'] == "si") $query = "SELECT n.*,s.titulo as nombre_seccion,s.foto AS foto_autor,s.id AS id_autor FROM noticias as n, autores_opinion as s  WHERE n.id=" . $id_noticia . " AND n.subseccion=s.id";
  else $query = "
    SELECT n.* , s.titulo AS nombre_seccion, sb.titulo AS nombre_subseccion
    FROM noticias AS n, secciones AS s, secciones AS sb
    WHERE n.id = " . $id_noticia;
  $noticia = mysql_fetch_assoc(mysql_query($query));

 	$titulo = $noticia["titulo"];
  	$descripcion = ($noticia["entradilla"]) ? strip_tags($noticia["entradilla"]) : substr(strip_tags($noticia["texto"]), 0, 140) ;
  	$config_tags = $noticia["tags"] . "," . $config_tags;
  	if ($noticia["img_horizontal"] !=""){ $img_horizontal = $noticia["img_horizontal"]; }else{
		if ($noticia["img_ampliada"] !=""){ $img_horizontal = $noticia["img_ampliada"]; }else{
			if ($noticia["img_rotador"] !=""){ $img_horizontal = $noticia["img_rotador"]; }else{
				if ($noticia["img_vertical"] !=""){ $img_horizontal = $noticia["img_vertical"]; }
	}}}
  	
  
} else {
  $config_tags = "P�del, padel, palas padel, World P�del Tour, PPT, PPT calendario, PPT ranking, PPT clasificaci�n, torneo padel, torneos padel, noticias, noticias padel, reportajes padel, videos padel, fotos padel, federacion padel, club padel, clubes padel, pistas padel, 24 horas, p�del online, 24h de p�del online";
  switch ($id_seccion) {
    case 1:      // Portada
      switch ($id_subseccion) {
        case 8:  // Actualidad
          $titulo = "Padelspain.net. Actualidad. 24 horas de p�del online.";
          $descripcion = "Encuentra la �ltima hora del mundo del p�del en el primer peri�dico digital.";
          break;
        case 9:  // Reglamento
          $titulo = "Padelspain.net. Reglamento";
          $descripcion = "Repasa la normativa y las reglas de tu deporte favorito en el primer peri�dico digital del p�del.";
          break;
        default: // Sin subseccion
          //$titulo = "Padelspain.net. El primer peri�dico digital del p�del. 24 horas de p�del online.";
		  $titulo = "Padelspain.net | Noticias e informaci�n. 24 horas de p�del online.";
          $descripcion = "Todas las noticias del World Padel Tour (WPT), la informaci�n m�s completa de los torneos amateurs. Entrevistas, Federaciones, v�deos, materiales y novedades.";
          break;
      }
      break;
    case 2:      // Padel Pro Tour
      switch ($id_subseccion) {
        case 10: // Calendario
          $titulo = "Padelspain.net - Padel Pro Tour. Calendario.";
          $descripcion = "Repasa las fechas en las que se disputar�n los distintos torneos del World Padel Tour (WPT).";
          break;
        case 11: // Circuito Masculino
          $titulo = "Padelspain.net - Padel Pro Tour. Las �ltimas noticias del Circuito Masculino.";
          $descripcion = "Repasa las �ltimas noticias del circuito masculino de World Padel Tour.";
          break;
        case 12: // Circuito Femenino
          $titulo = "Padelspain.net - Padel Pro Tour. Las �ltimas noticias del Circuito Femenino.";
          $descripcion = "Repasa las �ltimas noticias del circuito femenino de World Padel Tour.";
          break;
        case 13: // Torneos Disputados
          $titulo = "Padelspain.net - Padel Pro Tour. Torneos Disputados.";
          $descripcion = "Repasa todos los torneos del Padel Pro Tour ya disputados durante la presente temporada.";
          break;
        case 14: // Ranking
          $titulo = "Padelspain.net - Padel Pro Tour. Ranking.";
          $descripcion = "Repasa la clasificaci�n oficial del ranking World Padel Tour.";
          break;
        default:
          $titulo = "Padelspain.net y World Padel Tour, uni�n de fuerzas en el primer peri�dico digital del p�del.";
          $descripcion = "Todas las noticias del World Padel Tour (WPT), la informaci�n m�s completa de los torneos profesionales tantos masculinos como femeninos. Entrevistas, v�deos,fotos. Si te gusta es p�del, Padelspain.net es tu p�gina de referencia.";
          break;
      }
      break;
    case 3:      // Agenda Amateur
      if ($id_subseccion!= -1) {
        $query = "
          SELECT `titulo`
          FROM `secciones`
          WHERE `id` =" . $id_subseccion . "
          LIMIT 1";
        $result = mysql_query($query);
        $comunidad = mysql_fetch_object($result);

        $titulo = "Padelspain.net - Agenda Amateur. " . ucwords(strtolower($comunidad->titulo)) . ".";
        $descripcion = "Repasa los torneos amateurs que se disputar�n en tu Comunidad Aut�noma.";
      } else {
        $titulo = "Padelspain.net. La agenda de torneos amateurs del primer peri�dico digital del p�del.";
        $descripcion = "Si en tu Comunidad, tu ciudad o en tu club se juega al p�del, aqu� encontrar�s toda la informaci�n. Padelspain.net se vuelca con el p�del y los torneos amateurs.";
      }
      break;
    case 4:      // Torneos Amateurs
      if ($id_subseccion!= -1) {
        $query = "
          SELECT `titulo`
          FROM `secciones`
          WHERE `id` =" . $id_subseccion . "
          LIMIT 1";
        $result = mysql_query($query);
        $comunidad = mysql_fetch_object($result);

        $titulo = "Padelspain.net - Torneos Amateurs. " . ucwords(strtolower($comunidad->titulo)) . ".";
        $descripcion = "Repasa los torneos amateurs que se disputar�n en tu Comunidad Aut�noma.";
      } else {
        $titulo = "Padelspain.net. La cr�nica de los torneos amateurs, en el primer peri�dico digital del p�del.";
        $descripcion = "Si en tu Comunidad, tu ciudad o en tu club se ha jugado un torneo de p�del, aqu� encontrar�s toda la informaci�n. Padelspain.net se vuelca con el p�del y los torneos amateurs.";
      }
      break;
    case 5:      // Entrevistas
      $titulo = "Padelspain.net. Entrevistas.";
      $descripcion = "Repasa las mejores entrevistas con los jugadores profesionales de p�del. Juan Mart�n D�az, Fernando Belastegu�n, Juani Mieres, Pablo Lima, Carolina Navarro, Cecilia Reiter, Ic�ar Montes? Todas las estrellas del mundo del p�del pasar�n por las p�ginas de Padelspain.net.";
      break;
    case 6:      // Federaciones
      switch ($id_subseccion) {
        case 15: // Nacionales
          $titulo = "Padelspain.net - Federaciones Nacionales.";
          $descripcion = "Repasa toda la informaci�n referente a las distintas federaciones nacionales.";
         break;
        case 16: // InterNacionales
          $titulo = "Padelspain.net - Federaciones Internacionales.";
          $descripcion = "Repasa toda la informaci�n referente a las distintas federaciones internacionales.";
         break;
        default:
          $titulo = "Padelspain.net. Federaciones.";
          $descripcion = "Repasa toda la informaci�n referente a las distintas federaciones nacionales e internacionales.";
          break;
      }
      break;
    case 7:      // Material
      $titulo = "Padelspain.net. Material.";
      $descripcion = "Repasa las novedades y los nuevos productos sacados al mercado por las firmas m�s prestigiosas del mundo del p�del.";
      break;
    case 8:      // Foro
      //$titulo = "Padelspain.net. Foro.";
      //$descripcion = "Opina y discute sobre el mundo del p�del. Aqu� tienes tu espacio para dejar tus opiniones y abrir nuevos foros de debate.";
      break;
    default:      // Contacto
      $titulo = "Padelspain.net. Contacto.";
      $descripcion = "Si quieres ponerte en contacto con los miembros de Padelspain.net podr�s hacerlo a trav�s de esta p�gina.";
      break;
  }
}

include(SITE_CLASSES . "/general.class.php");
include(SITE_CLASSES . "/html.class.php");

$html = new Html();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  
  <meta property="og:title" content="<?=$titulo?>" />
<meta property="og:description" content="<?=$descripcion?>" />
<meta property="og:image" content="http://www.padelspain.net/<?=$img_horizontal?>" />


    <title><?php isset ($titulo) and print $titulo ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <meta name="title" content='<?php isset ($titulo) and print $titulo ?>'/>
    <meta name="DC.Title" content='<?php isset ($titulo) and print $titulo ?>'/>
    <meta http-equiv="title" content='<?php isset ($titulo) and print $titulo ?>'/>
    <meta name="keywords" content="<?php echo $config_tags ?>" />
    <meta http-equiv="keywords" content="<?php echo $config_tags ?>" />
    <meta name="description" content='<?php isset ($descripcion) and print $descripcion ?>'/>
    <meta http-equiv="description" content='<?php isset ($descripcion) and print $descripcion ?>'/>
    <meta http-equiv="DC.Description" content='<?php isset ($descripcion) and print $descripcion ?>'/>
    <meta name="author" content="Padel Spain"/>
    <meta name="DC.Creator" content=""/>
    <meta name="vw96.objectype" content="Homepage"/>
    <meta name="resource-type" content="Homepage"/>
    <meta name="distribution" content="all"/>
    <meta name="robots" content="all"/>
    <meta http-equiv="Content-Language" content="es"/>

    <?php
//    Las hojas de estilos general.css e index.css son comunes para todas las paginas.
//    El resto de las hojas de los estilos solo son necesarias ser cargadas en las secciones que correpondan
    ?>
    <link href="/sitefiles/styles/general.css" rel="stylesheet" type="text/css" />
    <link href="/sitefiles/styles/index.css" rel="stylesheet" type="text/css" />

    <link href="/sitefiles/styles/futbol.css" rel="stylesheet" type="text/css" />
    <link href="/sitefiles/styles/resultados.css" rel="stylesheet" type="text/css" />
    <link href="/sitefiles/styles/encuestas.css" rel="stylesheet" type="text/css" />

    <?php if ($id_seccion == 0) : ?>
      <link href="/sitefiles/styles/modulo_inferior.css" rel="stylesheet" type="text/css" />
    <?php endif; ?>

    <script language="JavaScript" type="text/javascript" src="/sitefiles/scripts/scripts.js"></script>
  </head>