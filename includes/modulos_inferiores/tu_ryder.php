<?php
$parte = 3;
$titulo = "TU RYDER";
$enlace = "/tu-ryder";
$imagen = "tu_ryder_titulo.gif";

$query = "SELECT *
FROM `noticias`
WHERE `seccion` =208
OR `subseccion` =208
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