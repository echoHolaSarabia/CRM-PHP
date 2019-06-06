<?php
	
if ($_GET['action'] == 'crop') {
	$w = $_GET['w'];
	$h = $_GET['h'];
	$x = $_GET['x'];
	$y = $_GET['y'];
	$src = $_GET['src'];
	$anchos = explode("-",$_GET['anchos']);
	$nombre = $_GET['nombre'];
	$q = 90;
	//Extraigo los datos de la ruta y el nombre de la imagen
	$ruta = substr($src,0,strrpos($src,"/")+1);
	$imagen = substr($src,strrpos($src,"/")+1,strlen($src)-4);
	$tipo = getimagesize($src);
	$tipo = $tipo['mime'];
	
	//$nombre_imagen = substr($imagen,0,strripos($imagen, '.')-9);
	
	//Nos quedamos con el nombre de la foto ej: foto.jpg --> $nombre_imagen = foto
	$nombre_imagen = substr($imagen,0,strripos($imagen, '.'));
	
	//Nos quedamos con la extension de la foto ej: foto.jpg --> $extension = jpg
	$extension = substr($imagen,strripos($imagen, '.'));

	//Creo la imagen dependiendo del tipo
	if ($tipo == "image/jpeg") $img = @imagecreatefromjpeg($src);
		else if ($tipo == "image/pjpeg") $img = @imagecreatefromjpeg($src);
			 else if ($tipo == "image/gif") $img = @imagecreatefromgif($src);
		     	  else if ($tipo == "image/png") $img = @imagecreatefrompng($src);
		     	  	   else if ($tipo == "image/x-png") $img = @imagecreatefrompng($src);
	//$img = imagecreatefromjpeg($src);
	
	
	$dst = ImageCreateTrueColor($w, $h);
	imagecopyresampled($dst, $img, 0, 0, $x, $y, $w, $h, $w, $h);

	//header('Content-type: image/jpeg');
	//imagejpeg($dst, null, $q);
	
	//$nombre_imagen = substr($imagen,0,strripos($imagen, '.')-9);
	$nombre_imagen = substr($imagen,0,strripos($imagen, '.'));
	$extension = substr($imagen,strripos($imagen, '.'));
	if ($tipo == "image/jpeg") imagejpeg($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
		else if ($tipo == "image/pjpeg") imagejpeg($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
			 else if ($tipo == "image/gif") imagegif($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
				  else if ($tipo == "image/png") imagepng($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
				  	   else if ($tipo == "image/x-png") imagepng($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
				  	   
	//imagejpeg($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
	
	//RECORTE REDIMENSIONADO
	if ($tipo == "image/jpeg") $img = @imagecreatefromjpeg($ruta.$nombre_imagen."_".$w."x".$h.$extension);
		else if ($tipo == "image/pjpeg") $img = @imagecreatefromjpeg($ruta.$nombre_imagen."_".$w."x".$h.$extension);
			 else if ($tipo == "image/gif") $img = @imagecreatefromgif($ruta.$nombre_imagen."_".$w."x".$h.$extension);
		     	  else if ($tipo == "image/png") $img = @imagecreatefrompng($ruta.$nombre_imagen."_".$w."x".$h.$extension);
		     	  	   else if ($tipo == "image/x-png") $img = @imagecreatefrompng($ruta.$nombre_imagen."_".$w."x".$h.$extension);
	//$img = imagecreatefromjpeg($ruta.$nombre_imagen."_".$w."x".$h.$extension);
	
	$width = imagesx($img);
	$height = imagesy($img);
	// New width and height
	$new_width = $anchos[0];
	$new_height = ($height * $new_width) / $width ;
	// Resample
	$dst = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($dst, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	//unlink($ruta.$nombre_imagen."_".$w."x".$h.$extension);
	//header('Content-type: '.$tipo);
	if ($tipo == "image/jpeg") imagejpeg($dst, null, $q);
		else if ($tipo == "image/pjpeg") imagejpeg($dst, null, $q);
			 else if ($tipo == "image/gif") imagegif($dst, null, $q);
				  else if ($tipo == "image/png") imagepng($dst, null, $q);
				  	   else if ($tipo == "image/x-png") imagepng($dst, null, $q);
	//imagejpeg($dst, null, $q);
	if ($tipo == "image/jpeg") imagejpeg($dst, $ruta.$nombre_imagen."_".$nombre.$extension, $q);
		else if ($tipo == "image/pjpeg") imagejpeg($dst, $ruta.$nombre_imagen."_".$nombre.$extension, $q);
			 else if ($tipo == "image/gif") imagegif($dst, $ruta.$nombre_imagen."_".$nombre.$extension, $q);
				  else if ($tipo == "image/png") imagepng($dst, $ruta.$nombre_imagen."_".$nombre.$extension, $q);
				  	   else if ($tipo == "image/x-png") imagepng($dst, $ruta.$nombre_imagen."_".$nombre.$extension, $q);
	//imagejpeg($dst, $ruta.$nombre_imagen."_".$new_width."x".number_format($new_height,0).$extension, $q);
	
	
	
	
	for($i=0;$i<count($anchos);$i++){
		//Creo la imagen dependiendo del tipo
			if ($tipo == "image/jpeg") $img = @imagecreatefromjpeg($src);
				else if ($tipo == "image/pjpeg") $img = @imagecreatefromjpeg($src);
					 else if ($tipo == "image/gif") $img = @imagecreatefromgif($src);
				     	  else if ($tipo == "image/png") $img = @imagecreatefrompng($src);
				     	  	   else if ($tipo == "image/x-png") $img = @imagecreatefrompng($src);
			//$img = imagecreatefromjpeg($src);
			
			
			$dst = ImageCreateTrueColor($w, $h);
			imagecopyresampled($dst, $img, 0, 0, $x, $y, $w, $h, $w, $h);
		
			//header('Content-type: image/jpeg');
			//imagejpeg($dst, null, $q);
			
			$extension = substr($imagen,strripos($imagen, '.'));
			if ($tipo == "image/jpeg") imagejpeg($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
				else if ($tipo == "image/pjpeg") imagejpeg($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
					 else if ($tipo == "image/gif") imagegif($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
						  else if ($tipo == "image/png") imagepng($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
						  	   else if ($tipo == "image/x-png") imagepng($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
						  	   
			//imagejpeg($dst, $ruta.$nombre_imagen."_".$w."x".$h.$extension, $q);
			
			//RECORTE REDIMENSIONADO
			if ($tipo == "image/jpeg") $img = @imagecreatefromjpeg($ruta.$nombre_imagen."_".$w."x".$h.$extension);
				else if ($tipo == "image/pjpeg") $img = @imagecreatefromjpeg($ruta.$nombre_imagen."_".$w."x".$h.$extension);
					 else if ($tipo == "image/gif") $img = @imagecreatefromgif($ruta.$nombre_imagen."_".$w."x".$h.$extension);
				     	  else if ($tipo == "image/png") $img = @imagecreatefrompng($ruta.$nombre_imagen."_".$w."x".$h.$extension);
				     	  	   else if ($tipo == "image/x-png") $img = @imagecreatefrompng($ruta.$nombre_imagen."_".$w."x".$h.$extension);
			//$img = imagecreatefromjpeg($ruta.$nombre_imagen."_".$w."x".$h.$extension);
			
			$width = imagesx($img);
			$height = imagesy($img);
			// New width and height
			$new_width = $anchos[$i];
			$new_height = ($height * $new_width) / $width ;
			// Resample
			$dst = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($dst, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			//unlink($ruta.$nombre_imagen."_".$w."x".$h.$extension);
			//header('Content-type: '.$tipo);
			if ($tipo == "image/jpeg") imagejpeg($dst, null, $q);
				else if ($tipo == "image/pjpeg") imagejpeg($dst, null, $q);
					 else if ($tipo == "image/gif") imagegif($dst, null, $q);
						  else if ($tipo == "image/png") imagepng($dst, null, $q);
						  	   else if ($tipo == "image/x-png") imagepng($dst, null, $q);
			//imagejpeg($dst, null, $q);
			echo $nombre;
			if ($tipo == "image/jpeg") imagejpeg($dst, $ruta.$nombre."_".$anchos[$i]."/".$nombre_imagen."_".$nombre.$extension, $q);
				else if ($tipo == "image/pjpeg") imagejpeg($dst, $ruta.$nombre."_".$anchos[$i]."/".$nombre_imagen."_".$nombre.$extension, $q);
					 else if ($tipo == "image/gif") imagegif($dst, $ruta.$nombre."_".$anchos[$i]."/".$nombre_imagen."_".$nombre.$extension, $q);
						  else if ($tipo == "image/png") imagepng($dst, $ruta.$nombre."_".$anchos[$i]."/".$nombre_imagen."_".$nombre.$extension, $q);
						  	   else if ($tipo == "image/x-png") imagepng($dst, $ruta.$nombre."_".$anchos[$i]."/".$nombre_imagen."_".$nombre.$extension, $q);
			//imagejpeg($dst, $ruta.$nombre_imagen."_".$new_width."x".number_format($new_height,0).$extension, $q);		
	}
	unlink($ruta.$nombre_imagen."_".$w."x".$h.$extension);
	exit;
}
?>