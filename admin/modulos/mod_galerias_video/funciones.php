<?php

class Funciones extends General{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la funciÃ³n a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarÃ¡n los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "videos";
		$this->url_home = "index2.php?modulo=mod_galerias_video";
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
				
				case "insert_seccion":
						$this->insert_seccion($this->datos);
						break;
				case "update_seccion":
						$this->update_seccion($this->control['id'],$this->datos);
						break;
				case "delete_secciones":
						$this->delete_secciones($this->datos);
						break;
				case "estado_seccion":
						$this->cambia_estado_seccion($this->control['id'],$this->control['campo']);
						break;
				case "borra_seccion":
						$this->borrar_seccion($this->control['id'],$this->control['video']);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	// funciones para secciones
	
	function get_secciones(){
		$filas = mysql_query("SELECT * FROM videos_secciones");
		if (mysql_num_rows($filas) > 0){
			while ($fila=mysql_fetch_assoc($filas)){
				$resutado[] = $fila;
			}
			return $resutado;
		}		
		
	}
	
	
	
	
	
	// fin funciones para secciones
	
	function get_query_palabra ($page,$items,&$q,$seccion,&$sqlStr,&$sqlStrAux,&$limit,$orden,$tipo_orden,$no_publicadas){
		if(isset($page) and is_numeric($page))
			$limit = " LIMIT ".(($page-1)*$items).",$items";
		else
			$limit = " LIMIT $items";
		
		if(isset($q) and !eregi('^ *$',$q)){
			$q = sql_quote($q); //para ejecutar consulta
			$busqueda = htmlentities($q); //para mostrar en pantalla
			
			$sqlStr = "SELECT * FROM ".$this->tabla." WHERE 1=1 ";	
			if ($no_publicadas != "")
				$sqlStr .= " AND fecha_publicacion>NOW()";	
			if ($seccion != "")
				$sqlStr .= " AND seccion = ".$seccion."";
			$sqlStr .= " AND titulo LIKE '%$q%' ORDER BY ".$orden." ".$tipo_orden."";
			
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla." WHERE 1=1 ";
			if ($no_publicadas != "")
				$sqlStrAux .= " AND fecha_publicacion>NOW()";
			if ($seccion != "")
				$sqlStrAux .= " AND seccion = ".$seccion."";
			$sqlStrAux .= " AND titulo LIKE '%$q%'";
		}else{
			$sqlStr = "SELECT * FROM ".$this->tabla." WHERE 1=1 ";
			if ($no_publicadas != "")
				$sqlStr .= " AND fecha_publicacion>NOW()";
			if ($seccion != "")
				$sqlStr .= " AND seccion = ".$seccion."";
			$sqlStr .= " ORDER BY ".$orden." ".$tipo_orden."";
			
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla." WHERE  1=1 ";
			if ($no_publicadas != "")
				$sqlStrAux .= " AND fecha_publicacion>NOW()";
			if ($seccion != "")
				$sqlStrAux .= " AND seccion = ".$seccion."";
		}
	}
	
	function get_nombre_seccion($id_seccion){
		$query = mysql_query("SELECT nombre FROM videos_secciones WHERE id=".$id_seccion);
		if (mysql_num_rows($query)>0){
			$seccion=mysql_fetch_array($query);
			return $seccion["nombre"];
		}
		else return "Sin sección asignada";
	}
	
	function insert($arr_datos,$files){

				
		$c="INSERT INTO ".$this->tabla." (id";
		foreach ($arr_datos as $clave => $valor){
			$c.=",".$clave;
		}
		$c.=") VALUES (null";
		foreach ($arr_datos as $clave => $valor){
			if (ereg("fecha_*",$clave)){
				if (($clave == "fecha_publicacion") && $valor == "")
						$c .= ",NOW()";
				else $c .= ",'".formatea_fecha_hora($valor)."'";
			}else if (is_numeric($valor)){
					$c.=",".$valor;
				  } else {
					$c.=",'".addslashes($valor)."'";
				  }
		  
		}
		$c.=");";
		mysql_query($c);
		echo mysql_error();
		$id_video = mysql_insert_id();
		
	
		$id = mysql_insert_id();
		//$this->subir_imagen($id,$files);
			
		$this->redireccionar();
	}
	
	function update($id,$arr_datos,$files){
		
				
		$iteracion = 1;
		$c="UPDATE ".$this->tabla." SET ";
		foreach ($arr_datos as $clave => $valor){
		  if ($clave!='activo')
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
		$c.=" WHERE id=".$id;
		
		mysql_query($c);//Comprobar que no se produce error
		
		//$this->subir_imagen($id,$files);
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
	
	function cambia_estado_seccion($id,$campo){
		$c = "SELECT activo FROM videos_secciones WHERE id=".$id;
		$r = mysql_query($c);
		$estado = mysql_fetch_array($r);
		if ($estado["activo"] == 1){
			$nuevo_estado = 0;
		} else {
			$nuevo_estado = 1;
		}
		$c="UPDATE videos_secciones SET activo=".$nuevo_estado." WHERE id=".$id;
		mysql_query($c);
		$this->redireccionar_secciones();
	}
	
	
	function insert_seccion($arr_datos){
		$query = "INSERT INTO videos_secciones (id, nombre, activo) VALUES (null,'".$arr_datos["nombre"]."',".$arr_datos["activo"].")";
		mysql_query($query);
		$this->redireccionar_secciones();
	}
	
	function update_seccion($id,$arr_datos){
		$query = "UPDATE videos_secciones SET nombre='".$arr_datos["nombre"]."', activo=".$arr_datos["activo"]." WHERE id = ".$id;
		mysql_query($query);
		$this->redireccionar_secciones();
	}
	
	function delete_secciones($arr_datos){
		foreach ($arr_datos as $clave => $valor){
			$query = "DELETE FROM videos_secciones WHERE id = ".$valor;			
			mysql_query($query);
		}		
		$this->redireccionar_secciones();
	}
	
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar (){
		header("Location: ".$this->url_home);
	}
	
	function redireccionar_secciones (){
		header("Location: ".$this->url_home."&ver=secciones");
	}
	
	
}
?>