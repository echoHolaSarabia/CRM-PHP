<?php

class Funciones extends General{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la funciÃ³n a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarÃ¡n los daros
	var $url_home;
	
	function funciones($control_recibido,$datos_recibidos) {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "multimedia";
		$this->url_home = (isset($datos_recibidos) && array_key_exists('referer',$datos_recibidos)) ? $datos_recibidos['referer'] : "index2.php?modulo=mod_multimedia";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "insert":
						$this->insert($this->datos,$_FILES);
						break;
				case "update":
						$this->update($this->control['id'],$this->datos,$_FILES);
						break;
				case "delete":
						$this->delete($this->datos);
						break;
				case "estado":
						$this->cambia_estado($this->control['id'],$this->control['campo']);
						break;
				case "borra_video":
						$this->borrar_video($this->control['id'],$this->control['video']);
						break;
				case "borra_imagen":
						$this->borrar_imagen($this->control['id'],$this->control['imagen']);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	function insert($arr_datos,$files){

		if ($arr_datos['codigo']!=""){//Hay video, se cambian los tamaños
			$arr_datos['codigo'] = preg_replace("/width=.[0-9]*./", "width=\"310\"", $arr_datos['codigo']);
			$arr_datos['codigo'] = preg_replace("/height=.[0-9]*./", "height=\"250\"", $arr_datos['codigo']);		
		}
		
		$c="INSERT INTO ".$this->tabla." (id";
		foreach ($arr_datos as $clave => $valor){
		  if (($clave != "referer")&&($clave != "id_galeria"))
			$c.=",".$clave;
		}
		$c.=") VALUES (null";
		foreach ($arr_datos as $clave => $valor){
		  if (($clave != "referer")&& ($clave != "id_galeria"))
			if (ereg("fecha_*",$clave)){
				if (($clave == "fecha_creacion") || ($clave == "fecha_modificacion"))
					$c .= ",NOW()";
				else if (($clave == "fecha_publicacion") && $valor == "")
						$c .= ",NOW()";
					 else $c .= ",'".formatea_fecha_hora($valor)."'";
			}else if (is_numeric($valor)){
					$c.=",".$valor;
				  } else {
					$c.=",'".addslashes($valor)."'";
				  }
		  
		}
		$c.=");";
		echo $c;
		mysql_query($c);
		echo mysql_error();
		$id_video = mysql_insert_id();
		if (isset($arr_datos["id_galeria"]) && $arr_datos["id_galeria"]!=""){
			mysql_query("INSERT INTO galerias_videos_relacion (id_galeria, id_video) values (".$arr_datos["id_galeria"].",".$id_video.")");
			echo "INSERT INTO galerias_videos_relacion (id_galeria, id_video) values (".$arr_datos["id_galeria"].",".$id_video.")";
		}
	
		$id = mysql_insert_id();
		$this->subir_imagen($id,$files);
		if ($arr_datos['tipo_fuente'] == "video_externo"){
			$this->sube_video($id, $files['video']);
		}	
		$this->redireccionar();
	}
	
	function update($id,$arr_datos,$files){
		
		
		
		if ($arr_datos['codigo']!=""){//Hay video, se cambian los tamaños
			$arr_datos['codigo'] = preg_replace("/width=.[0-9]*./", "width=\"310\"", $arr_datos['codigo']);
			$arr_datos['codigo'] = preg_replace("/height=.[0-9]*./", "height=\"250\"", $arr_datos['codigo']);		
		}
		
		$iteracion = 1;
		$c="UPDATE ".$this->tabla." SET ";
		foreach ($arr_datos as $clave => $valor){
		  if ($clave!='activo' && $clave!='portada' && $clave != 'referer')
			  if ($iteracion == 1)	
			  	if (is_numeric($valor))
					$c.=$clave."=".$valor;
				else 
					$c.=$clave."='".addslashes($valor)."'";
			  else if (ereg("fecha_*",$clave))
						$c.=",".$clave."='".formatea_fecha($valor)."'";
				   else if (is_numeric($valor))
							$c.=",".$clave."=".$valor;
						else $c.=",".$clave."='".addslashes($valor)."'";
		  $iteracion ++;
		}
		//Los campos CHECKBOX hay que tratarlos a parte, porque cuando no estan checkados no se envían
		if ($arr_datos['activo'] != ""){
			$c.=",activo=".$arr_datos['activo'];
		} else {
				$c.=",activo=0";
		}
		if ($arr_datos['portada']!="")
			$c.=",portada=".$arr_datos['portada'];
		else $c.=",portada=0";		
		$c.=" WHERE id=".$id;
		//echo $c;
		mysql_query($c);//Comprobar que no se produce error
		
		$this->subir_imagen($id,$files);
		if ($arr_datos['tipo_fuente'] == "video_externo"){
			$this->sube_video($id, $files['video']);
		}
		$this->redireccionar();
	}
	
	/*
	Borra las secciones, y si tienen descendientes borra tambiÃ©n las subsecciones
	*/
	function delete($elementos){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
		  	$r = mysql_query("SELECT tipo_fuente,codigo FROM ".$this->tabla." WHERE id = ".$valor.";");
		  	$fila = mysql_fetch_array($r);
		  	if ($fila['tipo_fuente'] == "video_externo"){
		  		if (file_exists("../videos/".$fila['codigo']))
		  			unlink("../videos/".$fila['codigo']);
		  	}
			$c = "DELETE FROM ".$this->tabla." WHERE id = ".$valor.";";
			mysql_query($c);
		  }
		}
		$this->redireccionar();
	}
	
	/*
	Funcion para subir imágenes
	*/
	function subir_imagen($id, $files){
		$carpeta = "../videos/thumbnails/";
		$thumbnail = $files['thumbnail'];
		
		if (is_uploaded_file($thumbnail['tmp_name']) && ($thumbnail['type'] == "image/jpeg"  || $thumbnail['type'] == "image/pjpeg" || $thumbnail['type'] == "image/gif" || $thumbnail['type'] == "image/png")){
			$pref= chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))."_";
			if (move_uploaded_file($thumbnail['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($thumbnail['name']))){
				$imagen = $pref . $this->quita_caracteres_especiales($thumbnail['name']);
				mysql_query("UPDATE ".$this->tabla." SET thumbnail='".$imagen."' WHERE id=".$id);
			}
		}
	}
	
	function borrar_imagen($id, $imagen){
		$carpeta = "../videos/thumbnails/";
		if (file_exists($carpeta.$imagen))
			unlink($carpeta.$imagen);
		mysql_query("UPDATE ".$this->tabla." SET thumbnail='' WHERE id=".$id);
		header("Location: index2.php?modulo=mod_multimedia&accion=editar&id=".$id);
	}
	
	function sube_video ($id, $video){
		$carpeta = "../videos/";
		print_r($video);
		if (is_uploaded_file($video['tmp_name']) && ($video['type'] == "application/octet-stream")){
			$pref= chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))."_";
			if (move_uploaded_file($video['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($video['name']))){
				$nombre_video = $pref . $this->quita_caracteres_especiales($video['name']);
				mysql_query("UPDATE ".$this->tabla." SET video='".$nombre_video."' WHERE id=".$id);
			}
		}
	}
	
	function borrar_video($id, $video){
		$carpeta = "../videos/";
		if (file_exists($carpeta.$video))
			unlink($carpeta.$video);
		mysql_query("UPDATE ".$this->tabla." SET video='' WHERE id=".$id);
		header("Location: index2.php?modulo=mod_multimedia&accion=editar&id=".$id);
	}
	
	function cambia_estado($id,$campo){
		$c = "SELECT ".$campo." FROM ".$this->tabla." WHERE id=".$id;
		$r = mysql_query($c);
		$estado = mysql_fetch_array($r);
		if ($estado[$campo] == 1){
			$nuevo_estado = 0;
		} else {
			$nuevo_estado = 1;
		}
		$c="UPDATE ".$this->tabla." SET ".$campo."=".$nuevo_estado." WHERE id=".$id;
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
}
?>