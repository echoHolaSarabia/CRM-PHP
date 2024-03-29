<?php

class Funciones extends General {
	
	var $control;//Array con los datos recibidos por GET y que nos indican la funci�n a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertar�n los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "banners_newsletter";
		$this->url_home = "index2.php?modulo=mod_banners_newsletter";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "insert":
						$this->insert($this->datos,$_FILES);
						break;
				case "update":
						$this->update($this->control['id'],$this->datos);
						break;
				case "borrar_foto":
						$this->borrar_foto($this->control['id']);
						header("location:index2.php?modulo=mod_banners_newsletter&accion=editar&id=".$this->control['id']);
						break;
				case "delete":
						$this->delete($this->datos);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	/*
	Inserta una nueva newsletter en la base de datos. Primero inserta la newsletter en si y despues las noticias asociadas a esta
	newsletter en su tabla correspondiente
	*/
	function insert($arr_datos,$files){
		$c="INSERT INTO ".$this->tabla." (id";
		foreach ($arr_datos as $clave => $valor){
			$c.=",".$clave;
		}
		$c.=") VALUES (null";
		foreach ($arr_datos as $clave => $valor){
			$c.=",'".addslashes($valor)."'";
		}
		$c.=");";
		mysql_query($c);
		$id = mysql_insert_id();
		$this->subir_imagenes($id,$files);
		$this->redireccionar();
	}
	function update($id,$arr_datos){
		$iteracion = 1;
		$c="UPDATE ".$this->tabla." SET ";
		foreach ($arr_datos as $clave => $valor){
		  if ($iteracion == 1){	
			if (is_numeric($valor)){
				$c.=$clave."=".$valor;
			} else {
				$c.=$clave."='".addslashes($valor)."'";
			}
		  }else{
		  	if (is_numeric($valor)){
				$c.=",".$clave."=".$valor;
			} else {
				$c.=",".$clave."='".addslashes($valor)."'";
			}
		  }
		  $iteracion ++;
		}
		$c.=" WHERE id=".$id;
		mysql_query($c);//Comprobar que no se produce error
		$this->subir_imagenes($this->control['id'],$_FILES);
		$this->redireccionar();
	}
	
	
	
	
	
	/*
	Borra una newsletter incluyendo los enlaces que la relacionan con las noticias
	*/
	function delete($elementos){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
		  	
		  	$r = mysql_query("SELECT * FROM ".$this->tabla."  WHERE id = ".$valor.";");
		  	$imagen = mysql_fetch_array($r);  	
			if (file_exists("../userfiles/banners/".$imagen['imagen']))
			  unlink("../userfiles/banners/".$imagen['imagen']);
			  
			$c="DELETE FROM ".$this->tabla." WHERE id = ".$valor.";";
			mysql_query($c);

		  }
		}
		$this->redireccionar();
	}
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar (){
		header("Location: ".$this->url_home);
	}
	
	/*
	Redimensiona una imagen tomando como referencia el ancho
	*/
	function redimensionar($nombre_imagen, $tipo, $ancho){
				//Cambia tama�o imagen
				// Load image
				/*if ($img_portada['type'] == "image/jpeg") $image = @imagecreatefromjpeg($nombre_imagen);
				else if ($img_portada['type'] == "image/gif") $image = @imagecreatefromgif($nombre_imagen);
					else if ($img_portada['type'] == "image/png") $image = @imagecreatefrompng($nombre_imagen);
				*/
				if ($tipo == "image/jpeg") $image = @imagecreatefromjpeg($nombre_imagen);
				else if ($tipo == "image/gif") $image = @imagecreatefromgif($nombre_imagen);
					else if ($tipo == "image/png") $image = @imagecreatefrompng($nombre_imagen);
					
					
				if ($image === false) die ('Unable to open image');
				// Get original width and height
				$width = imagesx($image);
				$height = imagesy($image);
				// New width and height
				$new_width = $ancho;
				$new_height = ($height * $ancho) / $width ;
			
				// Resample
				$image_resized = imagecreatetruecolor($new_width, $new_height);
				imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				
				if ($tipo == "image/jpeg") imagejpeg($image_resized, $nombre_imagen);
				else if ($tipo == "image/gif") imagegif($image_resized, $nombre_imagen);
					else if ($tipo == "image/png") imagepng($image_resized, $nombre_imagen);	
	}
	
	/*
	Funcion para subir im�genes
	*/
	function subir_imagenes($id, $files){	
		$carpeta = "../userfiles/banners/";
		$imagen = $files['imagen'];
		
		if (is_uploaded_file($imagen['tmp_name']) && ($imagen['type'] == "image/jpeg"  || $imagen['type'] == "image/pjpeg" || $imagen['type'] == "image/gif" || $imagen['type'] == "image/png")){
			$pref= chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))."_";
			$nombre_imagen = $carpeta . $pref . $this->quita_caracteres_especiales($imagen['name']);
			if (move_uploaded_file($imagen['tmp_name'], $nombre_imagen)){
				chmod ($nombre_imagen, 0777);
				$portada = $pref . $this->quita_caracteres_especiales($imagen['name']);
				mysql_query("UPDATE ".$this->tabla." SET imagen='".$portada."' WHERE id=".$id);
			}
		}
	}
	
	function borrar_foto($id){
		mysql_query("UPDATE banners_newsletter SET imagen='' WHERE id=".$id);
		
	}
}
?>