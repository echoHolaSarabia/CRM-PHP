<?php

class Funciones extends General {
	
	var $control;//Array con los datos recibidos por GET y que nos indican la funciÃ³n a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarÃ¡n los daros
	var $url_home;
	var $seccion_padre = 3;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "autores_opinion";
		$this->url_home = "index2.php?modulo=mod_autores";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "insert":
						$this->insert($this->datos);
						break;
				case "update":
						$this->update($this->control['id'],$this->datos);
						break;
				case "delete":
						$this->delete($this->datos);
						break;
				case "estado":
						$this->cambia_estado($this->control['id']);
						break;
				case "borrar_foto":
						$this->borrar_foto($this->control['id']);
						header("location:index2.php?modulo=mod_autores&accion=editar&id=".$this->control['id']);
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	function borrar_foto($id){
		mysql_query("UPDATE autores_opinion SET foto='' WHERE id=".$id);
	}
	
	function insert($arr_datos){
		$c="INSERT INTO ".$this->tabla." (id";
		foreach ($arr_datos as $clave => $valor){
			if ($clave != "foto")
				$c.=",".$clave;
		}
		$c.=") VALUES (null";
		foreach ($arr_datos as $clave => $valor){
			if ($clave != "foto"){
				if (is_numeric($valor)){
					$c.=",".$valor;
				} else {
					$c.=",'".addslashes($valor)."'";
				}
			}
		}
		$c.=");";
		//echo $c;die;
		mysql_query($c);//Comprobar que no se produce error
		$id = mysql_insert_id();
		$this->subir_imagenes($id,$_FILES);
		$this->redireccionar();
	}
	
	function update($id,$arr_datos){
		$iteracion = 1;
		$c="UPDATE ".$this->tabla." SET ";
		foreach ($arr_datos as $clave => $valor){
			if ($clave != "foto"){
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
		}
		$c.=" WHERE id=".$id;
		mysql_query($c);//Comprobar que no se produce error
		$this->subir_imagenes($id,$_FILES);
		$this->redireccionar();
	}
	
	/*
	Borra las secciones, y si tienen descendientes borra tambiÃ©n las subsecciones
	*/
	function delete($elementos){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
			$c="DELETE FROM ".$this->tabla." WHERE id = ".$valor;
			mysql_query($c);
			$c="DELETE FROM noticias WHERE seccion=".$this->seccion_padre." AND subseccion=".$valor;
			mysql_query($c);
		  }
		}
		$this->redireccionar();
	}
	
	function cambia_estado($id){
		$c = "SELECT activo FROM ".$this->tabla." WHERE id=".$id;
		$r = mysql_query($c);
		$estado = mysql_fetch_array($r);
		if ($estado['activo'] == 1){
			$nuevo_estado = 0;
		} else {
			$nuevo_estado = 1;
		}
		$c="UPDATE ".$this->tabla." SET activo=".$nuevo_estado." WHERE id=".$id;
		mysql_query($c);
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
Funciones para la obtención de datos que se usarán en el listado
*/
	function get_num_autores(){
		$r = mysql_query("SELECT * FROM ".$this->tabla.";");
		$num_filas = mysql_num_rows($r);
		return $num_filas;
	}
	
	function get_autores(){
        $r = mysql_query("SELECT * FROM ".$this->tabla." ORDER BY id ASC;");
        $resul = array();
        while ($fila = mysql_fetch_assoc($r))
        	$resul[] = $fila;
        return $resul;
	}
	
	function get_secciones_hijas($id_padre){
        $r = mysql_query("SELECT * FROM ".$this->tabla." WHERE id_padre=".$id_padre."");
        return $r;
	}
	
	function get_num_subsecciones($seccion){
		$r = mysql_query("SELECT id FROM secciones WHERE id_padre=".$seccion);
		return mysql_num_rows($r);
	}
	
	function get_num_noticias_seccion ($seccion){
		$r = mysql_query("SELECT * FROM noticias WHERE seccion=264 AND subseccion=".$seccion);
		$num_noticias = mysql_num_rows($r);
		return $num_noticias;
	}

	function get_num_noticias_subseccion ($seccion){
		$r = mysql_query("SELECT * FROM noticias WHERE subseccion=".$seccion);
		$num_noticias = mysql_num_rows($r);
		return $num_noticias;
	}
	
	function get_plantilla ($seccion){
		$r = mysql_query("SELECT p.nombre FROM plantillas p,secciones s WHERE s.id_plantilla=p.id AND s.id=".$seccion);
		$fila = mysql_fetch_array($r);
		return $fila['nombre'];
	}
	
	function redimensionar($nombre_imagen, $tipo, $ancho){
				//Cambia tamaño imagen
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
	
	
	function subir_imagenes($id, $files){
		
		$carpeta = "../userfiles/autores/";
		
		$foto = $files['foto'];
		//echo $foto;
		//die;
		if (is_uploaded_file($foto['tmp_name']) && ($foto['type'] == "image/jpeg"  || $foto['type'] == "image/pjpeg" || $foto['type'] == "image/gif" || $foto['type'] == "image/png")){
			$pref= chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))."_";
			$nombre_imagen = $carpeta . $pref . $this->quita_caracteres_especiales($foto['name']);
			if (move_uploaded_file($foto['tmp_name'], $nombre_imagen)){
				chmod ($nombre_imagen, 0777);
				$this->redimensionar($nombre_imagen, $foto['type'], 130);
				$portada = $pref . $this->quita_caracteres_especiales($foto['name']);
				echo $portada;
				mysql_query("UPDATE ".$this->tabla." SET foto='".$portada."' WHERE id=".$id);
			}
		}
	}
}
?>