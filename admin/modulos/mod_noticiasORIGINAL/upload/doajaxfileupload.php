<?php

function get_carpeta() {

  include("../conf.php");
  //$ruta2 = "";
  $ruta = "../../../../userfiles/" . date("Y");
  if ($dir = opendir($ruta)) {
    $ruta2 = $ruta . "/" . date('M') . "_" . date("d");
    if ($dir2 = opendir($ruta2)) {

    } else {
      umask(0000);
      mkdir($ruta2, 0777);
      foreach ($recorte_fotografico as $clave => $valor) {
        foreach ($valor["anchos"] as $ancho) {
          umask(0000);
          mkdir($ruta2 . "/" . $clave . "_" . $ancho, 0777);
        }
      }
    }
  } else {
    umask(0000);
    mkdir($ruta, 0777);
    $ruta2 = $ruta . "/" . date('M') . "_" . date("d");
    if ($dir2 = opendir($ruta2)) {

    } else {
      umask(0000);
      mkdir($ruta2, 0777);
      umask(0000);
      mkdir($ruta2 . "/recorte", 0777);
      foreach ($recorte_fotografico as $clave => $valor) {
        foreach ($valor as $ancho) {
          umask(0000);
          mkdir($ruta2 . "/recorte", 0777);
          mkdir($ruta2 . "/" . $clave . "_" . $ancho, 0777);
        }
      }
    }
  }
  return $ruta2;
}

function sube_foto($foto) {
  $carpeta = get_carpeta() . "/";

  if (is_uploaded_file($foto['tmp_name']) && ($foto['type'] == "image/jpeg" || $foto['type'] == "image/pjpeg" || $foto['type'] == "image/gif" || $foto['type'] == "image/png")) {
    $pref = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "_";
    $nombre_imagen = $carpeta . $pref . $this->quita_caracteres_especiales($foto['name']);
    if (move_uploaded_file($foto['tmp_name'], $nombre_imagen)) {

    }
  }
  return $nombre_imagen;
}

$error = "";
$msg = "";

$fileElementName = 'fileToUpload';
if (!empty($_FILES[$fileElementName]['error'])) {
  switch ($_FILES[$fileElementName]['error']) {

    case '1':
      $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
      break;
    case '2':
      $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
      break;
    case '3':
      $error = 'The uploaded file was only partially uploaded';
      break;
    case '4':
      $error = 'No file was uploaded.';
      break;

    case '6':
      $error = 'Missing a temporary folder';
      break;
    case '7':
      $error = 'Failed to write file to disk';
      break;
    case '8':
      $error = 'File upload stopped by extension';
      break;
    case '999':
    default:
      $error = 'No error code avaiable';
  }
} elseif (empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none') {
  $error = 'No file was uploaded..';
} else {
  $carpeta = get_carpeta() . "/";
  $nombre_imagen = substr($_FILES['fileToUpload']['name'], 0, strripos($_FILES['fileToUpload']['name'], '.'));
  $extension = substr($_FILES['fileToUpload']['name'], strripos($_FILES['fileToUpload']['name'], '.'));
  //Si el archivo ya existe crea uno nuevo con un aleatorio despues del nombre
  if (($_FILES['fileToUpload']['type'] == "image/jpeg" || $_FILES['fileToUpload']['type'] == "image/pjpeg" || $_FILES['fileToUpload']['type'] == "image/gif" || $_FILES['fileToUpload']['type'] == "image/png")) {
    $nombre_imagen = $nombre_imagen . "_" . (rand(1, 100));
    $nombre_imagen = $carpeta . $nombre_imagen . "_original" . $extension;
    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $nombre_imagen);
    $msg = "Su imagen ha sido subida correctamente al servidor";
  }else
    $msg = "Error";
}
echo "{";
echo "error: '" . $error . "',\n";
echo "msg: '" . $msg . "'\n";
echo "}";