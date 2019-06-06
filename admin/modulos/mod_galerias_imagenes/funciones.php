<?php

class Funciones{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "galerias_imagenes";
		$this->url_home = "index2.php?modulo=mod_galerias_imagenes";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "insert":
						$this->insert($this->datos,$_FILES);
						break;
				case "update":
						$this->update($this->datos,$this->control['id'],$_FILES);
						break;
				case "delete":
						$this->delete($this->datos);
						break;
				case "estado":
						$this->cambiar_estado($this->control['id']);
						break;
				case "guardar_listado":
						$this->guadar_listado($this->datos);
						break;
				case "borra_imagen":
						$this->borrar_imagen($this->control['id'],$this->control['imagen']);
						header("Location: index2.php?modulo=mod_galerias_imagenes&accion=editar&id=".$this->control['id']);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}	
	
	
	function guadar_listado($arr_datos){
		foreach($arr_datos as $clave => $valor){
			$pos = strpos($clave,"nuevo_nombre");
			if (($pos > -1) && ($valor!="")){
				$id=substr($clave,0,$pos);
				$q="UPDATE galerias_imagenes_secciones SET nombre='".$valor."'  WHERE id=".$id;
				echo $q;
				mysql_query($q);				
			}
		}//die;
		$datos = explode(",", $arr_datos["cadena"]);
		$j=-1;
		for ($i=0;$i<(count($datos));$i++){
			if (strpos($datos[$i],"seccion") === false){//galeria
				$galerias[$j][$k] = substr($datos[$i],7);
				$k++;
			}
			else{//seccion
				$j++;
				$s = explode("seccion",$datos[$i]);
				$galerias[$j][0]=$s[0];
				$k=1;
			}
		}
		$this->redireccionar();
	}
	 
	function insert($arr_datos,$files){
		$q = "INSERT INTO ".$this->tabla." (id,titulo,descripcion,fecha_creacion,fecha_publicacion) VALUES (null,'".$arr_datos['titulo']."','".$arr_datos['descripcion']."',NOW(),'".$arr_datos["fecha_publicacion"]."');";
		mysql_query($q);
		$id = mysql_insert_id();
		$this->subir_ficheros($id,$files['archivos']);
		//$this->subir_ficheros($id);
		$this->redireccionar();
	}
	
	/*
	Redimensiona una imagen tomando como referencia el ancho
	*/


	function subir_ficheros($id_galeria,$files){
		
		function recortar_cuadrado($nombre_imagen, $tipo){
            if ($tipo == "image/jpeg") $image = @imagecreatefromjpeg($nombre_imagen);
            else if ($tipo == "image/pjpeg") $image = @imagecreatefromjpeg($nombre_imagen);
                        else if ($tipo == "image/gif") $image = @imagecreatefromgif($nombre_imagen);
                          else if ($tipo == "image/png") $image = @imagecreatefrompng($nombre_imagen);
                                       else if ($tipo == "image/x-png") $image = @imagecreatefrompng($nombre_imagen);


			if ($image === false) die ('No se puedo abrir la imagen');
			
				// Get original width and height
				$width = imagesx($image);
				$height = imagesy($image);
				
				// New width and height
				if ($width>$height){
					$tam = $height;
					$resta =  $width - $tam;
					$principio = $resta / 2;
				}
				else{					
					$tam = $width;
				}
				
				// Resample
				$image_resized = imagecreatetruecolor($tam, $tam);

				imagecopyresampled($image_resized, $image, 0, 0, $principio, 0, $tam, $tam, $tam, $tam);
				
				$ruta = explode("/",$nombre_imagen);
				$ruta_peque = $ruta[0]."/".$ruta[1]."/recortes/".$ruta[2];
				
				if ($tipo == "image/jpeg") imagejpeg($image_resized, $ruta_peque);
                        else if ($tipo == "image/pjpeg") imagejpeg($image_resized, $ruta_peque);
                              else if ($tipo == "image/gif") imagegif($image_resized, $ruta_peque);
                                   else if ($tipo == "image/png") imagepng($image_resized, $ruta_peque);
                                            else if ($tipo == "image/x-png") imagepng($image_resized, $ruta_peque);

					
		}
		function redimensionar($nombre_imagen, $tipo, $ancho){
				//Cambia tamaño imagen
				
                if ($tipo == "image/jpeg") $image = @imagecreatefromjpeg($nombre_imagen);
                else if ($tipo == "image/pjpeg") $image = @imagecreatefromjpeg($nombre_imagen);
                            else if ($tipo == "image/gif") $image = @imagecreatefromgif($nombre_imagen);
                              else if ($tipo == "image/png") $image = @imagecreatefrompng($nombre_imagen);
                                           else if ($tipo == "image/x-png") $image = @imagecreatefrompng($nombre_imagen);	
				if ($image === false) die ('No se puedo abrir la imagen');
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
	                    else if ($tipo == "image/pjpeg") imagejpeg($image_resized, $nombre_imagen);
	                                else if ($tipo == "image/gif") imagegif($image_resized, $nombre_imagen);
	                                             else if ($tipo == "image/png") imagepng($image_resized, $nombre_imagen);
	                                                          else if ($tipo == "image/x-png") imagepng($image_resized, $nombre_imagen);

				chmod($nombre_imagen, 0777);
				
				//recortar_cuadrado($nombre_imagen,$tipo);	
		} 
		$carpeta = "../img_galerias/";
		//$numArchivos = count($files['name']);
		$numArchivos = 8;
		
		$orden = explode(",",$_POST["orden"]);
		
		$aaa=0;
		for ($aa=0;$aa<$numArchivos;$aa++){
			if ($_POST["id".$orden[$aa]] != ""){
				$nuevo_orden[$aaa]=$_POST["id".$orden[$aa]];
				$aaa++;
			}
		}
		
		
		for ($i = 0; $i < $numArchivos; $i++){
		  if (is_uploaded_file($files['tmp_name'][$i]) && ($files['type'][$i] == "image/jpeg"  || $files['type'][$i] == "image/pjpeg" || $files['type'][$i] == "image/gif" || $files['type'][$i] == "image/png")){
			$pref= chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))."_";
			if (move_uploaded_file($files['tmp_name'][$i], $carpeta . $pref . $files['name'][$i])){
				chmod($carpeta . $pref . $files['name'][$i], 0777);
				
				copy ($carpeta . $pref . $files['name'][$i],"../img_galerias/112/". $pref . $files['name'][$i]);
				chmod("../img_galerias/112/". $pref . $files['name'][$i], 0777);
				
				copy ($carpeta . $pref . $files['name'][$i],"../img_galerias/441/". $pref . $files['name'][$i]);
				chmod("../img_galerias/441/". $pref . $files['name'][$i], 0777);
				
				copy ($carpeta . $pref . $files['name'][$i],"../img_galerias/645/". $pref . $files['name'][$i]);
				chmod("../img_galerias/645/". $pref . $files['name'][$i], 0777);
					
				redimensionar("../img_galerias/441/" . $pref . $files['name'][$i], $files['type'][$i], 441);							
				redimensionar("../img_galerias/645/" . $pref . $files['name'][$i], $files['type'][$i], 645);
				redimensionar("../img_galerias/112/" . $pref . $files['name'][$i], $files['type'][$i], 112);
				
				//recortar_cuadrado("../img_galerias/130/" . $pref . $files['name'][$i], $files['type'][$i]);
				
				$nombre_imagen = $pref . $files['name'][$i];
				
				mysql_query("INSERT INTO imagenes (id,nombre,titulo,entradilla,id_galeria,orden) VALUES (null,'".$nombre_imagen."','".$_POST["titulo".$i]."','".$_POST["entradilla".$i]."',".$id_galeria.",".$i.");");
			}
		  }
		  else{
		  	$query = "UPDATE imagenes SET titulo = '".$_POST["titulo".$i]."' , entradilla='".$_POST["entradilla".$i]."' WHERE id = ".$_POST["id".$i];
		  	mysql_query($query);
		  }		  
		}
		//$query = "SELECT * FROM imagenes WHERE id_galeria=".$id_galeria." ORDER BY orden"; 
		//$rel = mysql_query($query);
		//echo $rel;
		
		for ($aa=0;$aa<count($nuevo_orden);$aa++){
			$query = "UPDATE imagenes SET orden = ".$aa." WHERE id_galeria = ".$id_galeria." AND id = ".$nuevo_orden[$aa];
			mysql_query($query);
			echo "<br>".$query;
			
		}
		//if (mysql_num_rows($rel) > 0){
		//	while ($imagen=mysql_fetch_array($rel)){
		//		$nueva_pos = array_search($imagen["orden"],$orden);
		//		$query = "UPDATE imagenes SET orden = ".$nueva_pos." WHERE id_galeria = ".$id_galeria." AND id = ".$imagen["id"];
				
		//		mysql_query($query);
		//		echo $query."<br>";				
		//	}
		//}
	}
	
	function update($arr_datos,$id,$files){
		mysql_query("UPDATE ".$this->tabla." SET titulo='".$arr_datos['titulo']."', descripcion='".$arr_datos['descripcion']."',fecha_publicacion='".$arr_datos['fecha_publicacion']."'  WHERE id=".$id);
		$this->subir_ficheros($id,$files['archivos']);
		$this->redireccionar();
	}
	
	/*
	Borra las secciones, y si tienen descendientes borra también las subsecciones
	*/
	function delete($elementos){
		foreach($elementos as $clave => $valor){
		  if (($clave != "seleccion") && ($clave != "seleccion_s")){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
		  	if ((strpos($clave,"_s") === false) && (strpos($clave,"seleccion") > -1)){
		  	$r = mysql_query("SELECT * FROM imagenes WHERE id_galeria=".$valor." ");
		  	while ($fila = mysql_fetch_array($r)){
				$this->borrar_imagen($valor,$fila['nombre']);
		  	}
			$c="DELETE FROM ".$this->tabla." WHERE id = ".$valor.";";
			mysql_query($c);
		  	}
		  	else if (strpos($clave,"seleccion_s") > -1){
		  				$c="DELETE FROM galerias_imagenes_secciones WHERE id = ".$valor;
		  				mysql_query($c);
		  		}
		  }
		}
		$this->redireccionar();
	}
	
	/*
	Obtiene la query para listar las noticias con el buscador AJAX.
	Devuelve por referencia los parámetros necesarios.	
	*/
	function get_query_palabra ($page,$items,&$q,&$sqlStr,&$sqlStrAux,&$limit,$orden,$tipo_orden){
		if(isset($page) and is_numeric($page))
			$limit = " LIMIT ".(($page-1)*$items).",$items";
		else
			$limit = " LIMIT $items";
	
		if(isset($q) and !eregi('^ *$',$q)){
			$q = sql_quote($q); //para ejecutar consulta
			$busqueda = htmlentities($q); //para mostrar en pantalla
	
			$sqlStr = "SELECT * FROM ".$this->tabla." WHERE texto LIKE '%$q%' ORDER BY ".$orden." ".$tipo_orden."";
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla." WHERE texto LIKE '%$q%'";
		}else{
			$sqlStr = "SELECT * FROM ".$this->tabla." ORDER BY ".$orden." ".$tipo_orden."";
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla."";
		}
	}
	
	function borrar_imagen($id,$imagen){
		$carpeta = "../img_galerias/";
		if (file_exists($carpeta.$imagen))
			unlink($carpeta.$imagen);
		$carpeta = "../img_galerias_real/";
		if (file_exists($carpeta.$imagen))
			unlink($carpeta.$imagen);
		$carpeta = "../img_galerias/recorte/";
		if (file_exists($carpeta.$imagen))
			unlink($carpeta.$imagen);
		$r = mysql_fetch_array(mysql_query("SELECT * FROM imagenes WHERE id=".$imagen." AND id_galeria=".$id));
		$orden = $r["orden"];
		mysql_query("UPDATE imagenes set orden = orden-1 WHERE orden > ".$orden." AND id_galeria=".$id );
		mysql_query("DELETE FROM imagenes WHERE id=".$imagen." AND id_galeria=".$id);
		mysql_query("DELETE FROM imagenes WHERE id='".$imagen."'");
		
	}
	
	function cambiar_estado($id){
		$query = "SELECT activo FROM galerias_imagenes WHERE id=".$id;
		$r = mysql_fetch_array(mysql_query($query));
		if ($r["activo"]==1) $activo = 0;
		else $activo = 1;
		mysql_query("UPDATE galerias_imagenes SET activo=".$activo." WHERE id=".$id);
		$this->redireccionar();
	}
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($pagina = ""){
		header("Location: ".$this->url_home."&page=".$pagina);
	}
	
}
?>