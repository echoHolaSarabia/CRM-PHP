<?php
$parte = 1;
$titulo = "GOLF SOLIDARIO";
$enlace = "/golf-solidario";
$imagen = "golf_titulo.jpg";

$query = "SELECT *
FROM `noticias`
WHERE `seccion` =209
OR `subseccion` =209
AND `modulo_inferior` =1";
$result = mysql_query($query);
$notinferiores = array();

if( mysql_num_rows($result) > 0 )
{
  while ($notinferior = mysql_fetch_array($result))
  {
    $notinferiores[] = $notinferior;
  }
}

include 'layout.php';