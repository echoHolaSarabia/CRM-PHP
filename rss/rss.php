<?php

include('../configuracion.php');
include('../admin/includes/conexion.inc.php');
include('../clases/general.class.php');
include('../clases/html.class.php');
$html = new Html();
$url = "http://" . $_SERVER['HTTP_HOST'];
if (isset($_GET['sec'])) {
  $seccion = $_GET['sec'];

  $tabla = "noticias";

  $aux = mysql_fetch_array(mysql_query("SELECT titulo as nombre FROM secciones WHERE id=" . $seccion));

  $nombre_seccion = $aux["nombre"];
  $r = mysql_query("SELECT * FROM planillas WHERE fecha_publicacion < NOW() AND seccion = " . $seccion . " ORDER BY fecha_publicacion DESC LIMIT 0,1");
  $planilla = mysql_fetch_assoc($r);
  $id_planilla = $planilla['id'];
  $c = "SELECT * FROM planillas_elementos pe, " . $tabla . " n WHERE pe.id_elemento=n.id AND pe.tabla_elemento='" . $tabla . "' AND pe.id_planilla=" . $id_planilla . " AND pe.planificado=1 order by n.fecha_publicacion DESC LIMIT 0,10";
  $r = mysql_query($c);
}


header('Content-Type: text/xml'); //Indicamos al navegador que es un documento en XML
//Versión y juego de carácteres de nuestro documento
echo '<?xml version="1.0" encoding="iso-8859-1"?>';

// Y generamos nuestro documento
echo '<rss version="2.0">
<channel>
<title>PadelSpain - ' . $nombre_seccion . '</title>
<link>http://www.padelspain.net</link>
<description><![CDATA[padelspain.net]]></description>
<language>es-ES</language>
<pubDate>' . date("r", time()) . '</pubDate>
<lastBuildDate>' . date("r", time()) . '</lastBuildDate>
<ttl>10</ttl>
';
while ($row = mysql_fetch_array($r)) {
  $noticia = mysql_fetch_assoc(mysql_query("SELECT n.id,n.titulo,n.autor, n.entradilla,n.seccion,n.fecha_modificacion,n.fecha_publicacion,s.titulo as nombre_seccion,sb.titulo as nombre_subseccion,n.tags FROM " . $tabla . " as n, secciones as s, secciones as sb WHERE ((s.id=n.seccion AND sb.id = n.subseccion AND n.subseccion <> 0 AND n.seccion <> 4) OR ( s.id=n.seccion AND sb.id = n.seccion AND (n.subseccion = 0 OR n.seccion = 4 ))) AND n.id=" . $row['id_elemento']));
  echo '
      <item>
              <title><![CDATA[' . html_entity_decode($noticia['titulo']) . ']]></title>
              <link>' . $url . $html->enlace_sin($noticia) . '</link>
              <author><![CDATA[' . $noticia['autor'] . ' <info@padelspain.net>]]></author>
              <guid isPermaLink="true">' . $url . $html->enlace_sin($noticia) . '</guid>
              <pubDate>' . date("r", strtotime($noticia['fecha_modificacion'])) . '</pubDate>
              <description><![CDATA[' . nl2br($noticia['entradilla']) . ']]></description>
      </item>';
}
echo '
</channel>
</rss>';