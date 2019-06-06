<?php

class Funciones extends General{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	var $extrensiones_permitidas = array("jpg","gif","png","flv","avi","mpg","mov");
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "noticias_newsletter";
		$this->url_home = "index2.php?modulo=mod_noticias_newsletter";
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
				case "duplicar":
						$this->duplicar($this->datos,0);
						break;
				case "duplicar_externo":
						$this->duplicar($this->datos,1);
						break;
				case "borra_imagen":
						$this->borrar_imagen($this->control['id'],$this->control['imagen']);
						break;
				case "update_listado":
						$this->actualizar_listado($this->control);
						break;
				case "estado":
						$this->cambiar_estado($this->control);
						break; 
				case "borra_doc":
						$this->borrar_doc($this->control['id'],$this->control['doc'],$this->control['tipo']);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	function insert($arr_datos,$files,$externo){
		$c="INSERT INTO ".$this->tabla." (id";
		foreach ($arr_datos as $clave => $valor){
			$c.=",".$clave;
		}
		$c.=") VALUES (null";
		foreach ($arr_datos as $clave => $valor){
			if($clave == "tipo_newsletter"){
				if($externo)
					$c.=",'Extreno'";
				else{
					$c.=",'".addslashes($valor)."'";
				}
			}
			else{
				if (is_numeric($valor)){
					$c.=",".$valor;
				} else {
					$c.=",'".addslashes($valor)."'";
				}	
			}			
		}
		$c.=");";
		//echo $c;
		mysql_query($c);
		echo mysql_error();
		$id = mysql_insert_id();
		
		$this->subir_imagenes($id,$files);
		$this->subir_documentos($id,$files);
		
		$this->redireccionar();
	}
	
	function update($arr_datos,$id,$files){
		/*if (get_magic_quotes_gpc()){
			
		}*/
		
		$iteracion = 1;
		$c="UPDATE ".$this->tabla." SET ";
		foreach ($arr_datos as $clave => $valor){
			if ($iteracion == 1)	
			  	if (is_numeric($valor))
					$c.=$clave."=".$valor;
				else 
					$c.=$clave."='".addslashes($valor)."'";
			else if (is_numeric($valor))
					$c.=",".$clave."=".$valor;
				 else $c.=",".$clave."='".addslashes($valor)."'";
		  $iteracion ++;
		}		
		$c.=" WHERE id=".$id;
		//echo $c;
		mysql_query($c);
		echo mysql_error();
		
		$this->subir_imagenes($id,$files);
		$this->subir_documentos($id,$files);
		
		$this->redireccionar();
	}
	
	/*
	Borra las secciones, y si tienen descendientes borra también las subsecciones
	*/
	function delete($elementos){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
		  	
		  	$r = mysql_query("SELECT carpeta,foto FROM ".$this->tabla." WHERE id=".$valor);
			$imagen = mysql_fetch_array($r);
		  	if (file_exists($imagen['carpeta'].$imagen['foto']))
			  unlink($imagen['carpeta'].$imagen['foto']);
			$c="DELETE FROM ".$this->tabla." WHERE id = ".$valor.";";
			mysql_query($c);
			$c="DELETE FROM noticias_newsletter_relacionadas WHERE id_noticia = ".$valor.";";
			mysql_query($c);
			
		  }
		}
		$this->redireccionar();
	}
	
	/*
	Duplica todas las entradas con los identificadores parados como parámetro en un array
	*/
	function duplicar($arr_ids,$exteno){
		foreach($arr_ids as $valor){
			$r = mysql_query("SELECT * FROM ".$this->tabla." WHERE id=".$valor);
			$fila = mysql_fetch_assoc($r);
			/*
			Eliminamos el primer elemento de la matriz (que es "id") para que no se repita en la funcion insert(), con la funcion
			array_slice()
			*/
			$id_insertada = $this->insert(array_slice($fila,1),null,$exteno);
			if (file_exists("../adjuntos/000".$fila['id'].".jpg")){
				copy("../adjuntos/000".$fila['id'].".jpg","../adjuntos/000".$id_insertada.".jpg");
			}
		}
	}
	
	/*
	Obtiene los datos de una noticia pasando como parámetro el identificador
	*/
	function get_noticia($id){
		$r = mysql_query("SELECT * FROM ".$this->tabla." WHERE id=".$id);
		$fila = mysql_fetch_array ($r);
		return $fila;
	}
	
	function borrar_imagen($id, $imagen){
		if (file_exists($imagen))
			unlink($imagen);
		mysql_query("UPDATE ".$this->tabla." SET foto='' WHERE id=".$id);
		header("Location: index2.php?modulo=mod_noticias_newsletter&accion=editar&id=".$id);
	}
	
	function get_carpeta (){
		$fecha = date("M_Y");
		$ruta = "../userfiles/".$fecha;
		if ($dir = @opendir($ruta)){
		}else{
			umask(0000);
			mkdir($ruta,0777);
		}
		return $ruta;
	}
	
	/*
	Redimensiona una imagen tomando como referencia el ancho
	*/
	function redimensionar($nombre_imagen, $tipo, $ancho){
				//Cambia tamaño imagen
				if ($tipo == "image/jpeg") $image = @imagecreatefromjpeg($nombre_imagen);
				else if ($tipo == "image/gif") $image = @imagecreatefromgif($nombre_imagen);
					else if ($tipo == "image/png") $image = @imagecreatefrompng($nombre_imagen);
					
					
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
				else if ($tipo == "image/gif") imagegif($image_resized, $nombre_imagen);
					else if ($tipo == "image/png") imagepng($image_resized, $nombre_imagen);	
	}
	
	/*
	Funcion para subir imágenes
	*/
	function subir_imagenes($id, $files){
		
		$carpeta = $this->get_carpeta()."/";
		mysql_query("UPDATE ".$this->tabla." SET carpeta='".$carpeta."' WHERE id=".$id);
		$img_portada = $files['foto'];
		
		if (is_uploaded_file($img_portada['tmp_name']) && ($img_portada['type'] == "image/jpeg"  || $img_portada['type'] == "image/pjpeg" || $img_portada['type'] == "image/gif" || $img_portada['type'] == "image/png")){
			$pref= chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))."_";
			$nombre_imagen = $carpeta . $pref . $this->quita_caracteres_especiales($img_portada['name']);
			if (move_uploaded_file($img_portada['tmp_name'], $nombre_imagen)){
				chmod ($nombre_imagen, 0777);
				$this->redimensionar($nombre_imagen, $img_portada['type'], 100);
				$portada = $pref . $this->quita_caracteres_especiales($img_portada['name']);
				mysql_query("UPDATE ".$this->tabla." SET foto='".$portada."' WHERE id=".$id);
			}
		}
	}
	
	/**
	 * Función que sube los documentos adjuntos de una noticia
	 *
	 * @param int $id 				-> id de la noticia
	 * @param unknown_type $files	-> datos de los archivos mandados por input file
	 */
	function subir_documentos($id, $files){
		//print_r($files);
		$carpeta = "../docs/newsletter/";
		$doc_1 = $files['doc_1'];
		$doc_2 = $files['doc_2'];
		$doc_3 = $files['doc_3'];
		
		if (is_uploaded_file($doc_1['tmp_name'])){
			$pref= chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))."_";
			if (move_uploaded_file($doc_1['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($doc_1['name']))){
				$doc = $pref . $this->quita_caracteres_especiales($doc_1['name']);
				mysql_query("UPDATE ".$this->tabla." SET doc_1='".$doc."' WHERE id=".$id);
			}
		}
		if (is_uploaded_file($doc_2['tmp_name'])){
			$pref= chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))."_";
			if (move_uploaded_file($doc_2['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($doc_2['name']))){
				$doc = $pref . $this->quita_caracteres_especiales($doc_2['name']);
				mysql_query("UPDATE ".$this->tabla." SET doc_2='".$doc."' WHERE id=".$id);
			}
		}
		if (is_uploaded_file($doc_3['tmp_name'])){
			$pref= chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))."_";
			if (move_uploaded_file($doc_3['tmp_name'], $carpeta . $pref . $this->quita_caracteres_especiales($doc_3['name']))){
				$doc = $pref . $this->quita_caracteres_especiales($doc_3['name']);
				mysql_query("UPDATE ".$this->tabla." SET doc_3='".$doc."' WHERE id=".$id);
			}
		}

	}
	
	
	/*
	Obtiene la query para listar las noticias
	Devuelve por referencia los parámetros necesarios.	
	*/
	function get_query_palabra ($page,$items,&$q,$seccion,&$sqlStr,&$sqlStrAux,&$limit,$orden,$tipo_orden){
		if(isset($page) and is_numeric($page))
			$limit = " LIMIT ".(($page-1)*$items).",$items";
		else
			$limit = " LIMIT $items";
		
		if(isset($q) and !eregi('^ *$',$q)){
			$q = sql_quote($q); //para ejecutar consulta
			$busqueda = htmlentities($q); //para mostrar en pantalla
			
			$sqlStr = "SELECT * FROM ".$this->tabla." WHERE ";
			if ($seccion != "")
				$sqlStr .= "seccion = ".$seccion." AND ";
			$sqlStr .= " titulo LIKE '%$q%' ORDER BY ".$orden." ".$tipo_orden."";
			
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla." WHERE ";
			if ($seccion != "")
				$sqlStrAux .= "seccion = ".$seccion." AND";
			$sqlStrAux .= " titulo LIKE '%$q%'";
		}else{
			$sqlStr = "SELECT * FROM ".$this->tabla." ";
			if ($seccion != "")
				$sqlStr .= " WHERE seccion = ".$seccion."";
			$sqlStr .= " ORDER BY ".$orden." ".$tipo_orden."";
			
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla." ";
			if ($seccion != "")
				$sqlStrAux .= " WHERE seccion = ".$seccion."";
		}
	}
	
	/*
	Aclualiza los campos que se modifican desde el listado de noticias
	*/
	function actualizar_listado($control){
		$c = "UPDATE ".$this->tabla." SET titular_alt='".$control['titular_alt']."',antetitulo_alt='".$control['antetitulo_alt']."',entradilla_alt='".$control['entradilla_alt']."' WHERE id=".$control['id'];
		mysql_query($c);
		$this->redireccionar();
	}
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($pagina = ""){
		header("Location: ".$this->url_home."&page=".$pagina);
	}
	
	function get_all_secciones (){
		$c = "SELECT * FROM secciones";
		$r = mysql_query($c);
		return $r;
	}
	
	function get_subsecciones($seccion){
		$c = "SELECT * FROM secciones WHERE id_padre=".$seccion.";";
		$r = mysql_query($c);
		return $r;
	}
	
	function get_noticias_relacionadas ($id){
		$c = "SELECT titulo FROM noticias n,noticias_relacionadas nr WHERE n.id = nr.id_relacionada AND nr.id_noticia = ".$id;
		$r = mysql_query($c);
		return $r;
	}
	
	function cambiar_estado($control){
		$query = "SELECT estado_".$control["campo"]." as estado FROM noticias_newsletter WHERE id=".$control["id"];
		echo $query."<br>";
		$r = mysql_fetch_array(mysql_query($query));
		$estado = ($r["estado"]+1)%3;
		mysql_query("UPDATE noticias_newsletter SET estado_".$control["campo"]."=".$estado." where id=".$control["id"]);
		echo "UPDATE noticias_newsletter SET estado_".$control["campo"]."=".$estado;
		$this->redireccionar();
	}
	
	function borrar_doc($id, $doc, $tipo){
		$carpeta = "../docs/newsletter/";
		if (file_exists($carpeta.$doc))
			unlink($carpeta.$doc);
		mysql_query("UPDATE ".$this->tabla." SET ".$tipo."='',nombre_".$tipo."='' WHERE id=".$id);
		header("Location: index2.php?modulo=mod_noticias_newsletter&accion=editar&id=".$id);
	}

}
?>