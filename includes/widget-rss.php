<?php
include( dirname(__FILE__) . "/../configuracion.php" );
include( dirname(__FILE__) . "/../admin/includes/conexion.inc.php" );
$query="
  SELECT n.`id` , n.`titulo` , s.titulo seccion , s.id_padre
  FROM `noticias` n
  JOIN secciones s ON subseccion=s.id
  WHERE `fecha_publicacion` <= NOW()
  AND n.`activo` =1
  ORDER BY `fecha_publicacion` DESC
  LIMIT 4
  ";
//  $query = 'show tables;';
$result=mysql_query($query) or die(mysql_error());

if(mysql_num_rows($result)) :
function getSlugify($text)
{
  $b= array("á","é","í","ó","ú","ä","ë","ï","ö","ü","à","è","ì","ò","ù","ñ","%"," ",",",".",";",":","¡","!","¿","?",'"',"\"","'");
  $c = array("a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","n","","-","","","","","","","","",'',"","");
  $text = str_replace($b,$c,$text);

  // replace all non letters or digits by -
  $text = preg_replace('/\W+/', '-', $text);

  // trim and lowercase
  $text = strtolower(trim($text, '-'));

  return $text;
}
$web = $_SERVER['HTTP_HOST'];
?>
<style type="text/css">
  #gc-widget-rss{border:1px solid #E5E5E5;font-size:.8em;margin:1em 0 0;width:290px;padding:5px}
  #gc-widget-rss div{border:1px solid #CACACA}
  #gc-widget-rss h3{background:url("http://<?php echo $web ?>/sitefiles/img/bckTitDest02.jpg");color:#AF0504;margin:0;text-transform:uppercase}
  #gc-widget-rss h3 a{background:url("http://<?php echo $web ?>/sitefiles/img/bckTxtIncludes.jpg");border:0;float:left;font-size:14px;margin:0;padding:4px .5em;}
  #gc-widget-rss img{border:0}
  #gc-widget-rss a img{width:100%}
  #gc-widget-rss ul{list-style:none;margin:0;padding:0}
  #gc-widget-rss li{background:#EAEAEA;margin:1px 0 0;padding:0 .5em}
  #gc-widget-rss a{color:#464749;text-decoration:none}
</style>
<div id="gc-widget-rss">
  <div>
    <h3><a href="http://<?php echo $web ?>" target="_blank">en <?php echo $web ?></a><img src="http://<?php echo $web ?>/sitefiles/img/puntaTitDest02.jpg"/></h3>
    <a href="http://<?php echo $web ?>/" target="_blank"><img src="http://<?php echo $web ?>/img/logox300w.gif" alt="Padel Spain"/></a>
    <ul>
      <?php while($news = mysql_fetch_object($result)) : if($news->id_padre == -1) : ?>
      <li><a href="http://<?php echo $web ?>/<?php echo getSlugify($news->seccion) ?>/<?php echo $news->id ?>/" target="_blank"><?php echo $news->titulo ?></a></li>
      <?php
      else :
		  $query = "select titulo from secciones where id=" . $news->id_padre;
		  $result2 = mysql_query($query);
		  $seccion = mysql_fetch_object($result2);
      ?>
      <li><a href="http://<?php echo $web ?>/<?php echo getSlugify($seccion->titulo) ?>/<?php echo getSlugify($news->seccion) ?>/<?php echo $news->id ?>/" target="_blank"><?php echo $news->titulo ?></a></li>
      <?php endif; endwhile; ?>
    </ul>
  </div>
</div>
<?php endif; ?>