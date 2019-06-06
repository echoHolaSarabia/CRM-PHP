<?php
if ( isset($_POST["suscriptores"]) && $_POST["suscriptores"] )
{
  include ("../../../configuracion.php");
  include ("../../includes/conexion.inc.php");

  $suscriptors = explode(",",str_replace(" ", "", $_POST["suscriptores"]));
  echo "Suscriptores: ";
  foreach ($suscriptors as $suscriptor){ echo $suscriptor . ", "; }
//  print_r($suscriptors);
  echo "<br/>";
  
  $invalid_mails = array();
  $valid_mails = array();
  $db_mails = array();
  $exist_mails = array();
  $available = array();
  
//  Filtro de los email validos
  foreach ($suscriptors as $suscriptor) {
    if(validarCorreo($suscriptor)) {
      $valid_mails[] = $suscriptor;
    } else {
      $invalid_mails[] = $suscriptor;
    }
  }
  echo "Emails invalidos: ";
  foreach ($invalid_mails as $mail){ echo $mail . ", "; }
//  print_r($invalid_mails);
  echo "<br/>";
  
  echo "Emails validos: ";
  foreach ($valid_mails as $mail){ echo $mail . ", "; }
//  print_r($valid_mails);
  echo "<br/>";
  
//  Busqueda de los email en la BD
  $q = "SELECT `email` FROM `suscriptores`";
  $r = mysql_query($q);
  while($actual = mysql_fetch_array($r)) {
    $db_mails[] = $actual["email"];
  }
//  echo "Emails de la BD ";
//  print_r($db_mails);
//  echo "<br/>";
  
//  Generar los emails disponibles
  $n = 0;
  $q = "INSERT INTO `suscriptores` ( `email` ) VALUES ";
  foreach ($valid_mails as $suscriptor) {
    if(in_array($suscriptor,$db_mails)) {
      $exist_mails[] = $suscriptor;
    } else {
      $available[] = $suscriptor;
      $q .= ($n) ? "," : "" ;
      $q .=  "( '" . $suscriptor . "' )";
      $n++;
    }
  }
  echo "Emails que ya existen: ";
  foreach ($exist_mails as $mail){ echo $mail . ", "; }
//  print_r($exist_mails);
  echo "<br/>";
  
  echo "Emails a insertar: ";
  foreach ($available as $mail){ echo $mail . ", "; }
//  print_r($available);
//  echo "<br/>";
//  die($q);
  if($n) { mysql_query($q); }
//  die("fin");  
}

function validarCorreo($email)
{
  return preg_match( '/^[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,6}+$/', $email );
}
?>