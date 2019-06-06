<?php
$parte = 2;
$titulo = "AGENDA";
$enlace = "/agenda";
$imagen = "agenda_titulo.png";

$query = "SELECT *
FROM `noticias`
WHERE `seccion` =151
OR `subseccion` =151
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