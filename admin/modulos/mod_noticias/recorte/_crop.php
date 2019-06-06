<?php
if ($_GET['action'] == 'crop') {
	$w = $_GET['w'];
	$h = $_GET['h'];
	$x = $_GET['x'];
	$y = $_GET['y'];
	$src = $_GET['src'];
	$q = 90;
	
	$imagen = substr($src,strrpos($src,"/")+1,strlen($src)-4);

	$img = imagecreatefromjpeg($src);
	
	$dst = ImageCreateTrueColor($w, $h);

	imagecopyresampled($dst, $img, 0, 0, $x, $y, $w, $h, $w, $h);

	header('Content-type: image/jpeg');
	imagejpeg($dst, null, $q);
	
	$nombre_imagen = substr($imagen,0,strripos($imagen, '.')-9);
	$extension = substr($imagen,strripos($imagen, '.'));
		  
	//imagejpeg($dst, "../../../../userfiles/2008/43/".$nombre_imagen."_".$w."x".$h.$extension, $q);
	exit;
}
?>