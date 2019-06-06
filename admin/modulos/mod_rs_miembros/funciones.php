<?php

class Funciones extends General{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "rs_miembros";
		$this->url_home = "index2.php?modulo=mod_rs_miembros";
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
				case "desactivar":
						$this->desactivar($this->control['id']);
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
		$arr_datos['periodista'] = 1;
		$arr_datos['activo'] = 1;
		$arr_datos['password'] = $this->codifica_password($arr_datos['password']);
		$excluir = array("dia","mes","anyo","password2");
		$fecha = $arr_datos['anyo']."-".$arr_datos['mes']."-".$arr_datos['dia'];
		$c="INSERT INTO ".$this->tabla." (id";
		foreach ($arr_datos as $clave => $valor){
			if (!in_array($clave,$excluir))
				$c.=",".$clave;
		}
		$c.=") VALUES (null";
		foreach ($arr_datos as $clave => $valor){
			if (!in_array($clave,$excluir)){
				if (is_numeric($valor)){
					$c.=",".$valor;
				} else {
					$c.=",'".addslashes($valor)."'";
				}
			}
		}
		$c.=");";
		mysql_query($c);
		$id = mysql_insert_id();
		mysql_query("UPDATE ".$this->tabla." SET fecha_nacimiento = '".$fecha."' WHERE id=".$id);
		$this -> subir_foto ($id,$files['url_foto'],"url_foto");
		$this->redireccionar();
	}
	
	function update($arr_datos,$id,$files){
		if (isset($arr_datos['eliminar_foto']) && $arr_datos['eliminar_foto'] == "si")
			$this -> eliminar_archivo($id,"url_foto");
		
		$excluir = array("dia","mes","anyo","eliminar_foto");
		$fecha = $arr_datos['anyo']."-".$arr_datos['mes']."-".$arr_datos['dia'];
		$iteracion = 1;
		$c="UPDATE ".$this->tabla." SET ";
		foreach ($arr_datos as $clave => $valor){
			if (!in_array($clave,$excluir)){
			  if ($iteracion == 1){	
					$c.=$clave."='".addslashes($valor)."'";
			  }else{
					$c.=",".$clave."='".addslashes($valor)."'";
			  }
			}
		  $iteracion ++;
		}
		$c.=" WHERE id=".$id;
		mysql_query($c);
		mysql_query("UPDATE ".$this->tabla." SET fecha_nacimiento = '".$fecha."' WHERE id=".$id);
		$this -> subir_foto ($id,$files['url_foto'],"url_foto");
		$this->redireccionar();
	}
	
	/*
	Borra una newsletter incluyendo los enlaces que la relacionan con las noticias
	*/
	function delete($elementos){
		include("../intranet/clases/post.php");
		$post = new Post();
		include("../intranet/clases/galeria_fotos.php");
		$galeria = new GaleriaFotos();
		include("../intranet/clases/video.php");
		$video = new Video();
		include("../intranet/clases/comentario.php");
		$comentario = new Comentario();
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
		  	//Eliminamos los posts
		  	$r = mysql_query("SELECT * FROM rs_categorias WHERE id_miembro=".$valor);
		  	while ($fila = mysql_fetch_assoc($r)){
							$post->eliminar($fila['id']);
		  	}
		  	//Eliminamos las galerias
		  	$r = mysql_query("SELECT * FROM rs_galerias_fotos WHERE id_miembro=".$valor);
		  	while ($fila = mysql_fetch_assoc($r)){
							$galeria->eliminar($fila['id']);
		  	}
		  	//Eliminamos los videos
				$r = mysql_query("SELECT * FROM rs_videos WHERE id_miembro=".$valor);
		  	while ($fila = mysql_fetch_assoc($r)){
							$video->eliminar($fila['id']);
		  	}
		  	//Eliminamos los comentarios
				$r = mysql_query("SELECT * FROM rs_comentarios WHERE id_miembro=".$valor);
		  	while ($fila = mysql_fetch_assoc($r)){
							$comentario->eliminar($fila['id']);
		  	}
		  	//Eliminamos los comentarios en el periodico
		  	mysql_query("DELETE FROM comentarios WHERE id_miembro=".$valor);
		  	//Eliminamos el correo
		  	mysql_query("DELETE FROM rs_buzon_entrada WHERE para=".$valor);
		  	mysql_query("DELETE FROM rs_buzon_salida WHERE de=".$valor);
		  	//Eliminamos los miembors que me siguen - sigo
		  	mysql_query("DELETE FROM rs_seguidores_seguidos WHERE id_seguidor=".$valor);
		  	mysql_query("DELETE FROM rs_seguidores_seguidos WHERE id_seguido=".$valor);
		  	//Eliminamos la actividad
		  	mysql_query("DELETE FROM rs_actividad WHERE id_miembro=".$valor);
		  	//Eliminamos al miembro
		  	$tamanios = array(65,114);
		  	$miembro = mysql_fetch_assoc(mysql_query("SELECT url_foto FROM ".$this->tabla." WHERE id=".$valor));
		  	if (file_exists("../rs_fotos_miembros/".$miembro['url_foto']))
		  		unlink("../rs_fotos_miembros/".$miembro['url_foto']);
		  	if (file_exists("../rs_fotos_miembros/recortes/".$miembro['url_foto']))
		  		unlink("../rs_fotos_miembros/recortes/".$miembro['url_foto']);
		  	foreach ($tamanios as $elTamanio){
		  		if (file_exists("../rs_fotos_miembros/thumbnails/".$elTamanio."_".$miembro['url_foto']))
		  			unlink("../rs_fotos_miembros/thumbnails/".$elTamanio."_".$miembro['url_foto']);
		  	}
				mysql_query("DELETE FROM ".$this->tabla." WHERE id = ".$valor);
		  }
		}
		$this->redireccionar();
	}
	
	function desactivar($id){
		$c = "SELECT activo FROM ".$this->tabla." WHERE id=".$id;
		$r = mysql_query($c);
		$estado = mysql_fetch_array($r);
		if ($estado['activo'] == 1){
			$nuevo_estado = 0;
		} else {
			$nuevo_estado = 1;
		}
		//Para desactivar al miembro se le marca como inactivo y se le pone una clave de activacion rara.
		$c="UPDATE ".$this->tabla." SET activo=".$nuevo_estado.",clave_activacion='1234567890abcd' WHERE id=".$id;
		mysql_query($c);
		$this->eliminar_datos($id);
		$this->redireccionar();
	}
	
	function eliminar_datos($id){
		include("../intranet/clases/post.php");
		$post = new Post();
		include("../intranet/clases/galeria_fotos.php");
		$galeria = new GaleriaFotos();
		include("../intranet/clases/video.php");
		$video = new Video();
		include("../intranet/clases/comentario.php");
		$comentario = new Comentario();
		//Eliminamos los posts
		 $r = mysql_query("SELECT * FROM rs_categorias WHERE id_miembro=".$id);
		 while ($fila = mysql_fetch_assoc($r)){
			$post->eliminar($fila['id']);
		 }
		 //Eliminamos las galerias
		 $r = mysql_query("SELECT * FROM rs_galerias_fotos WHERE id_miembro=".$id);
		 while ($fila = mysql_fetch_assoc($r)){
			$galeria->eliminar($fila['id']);
		 }
		 //Eliminamos los videos
		 $r = mysql_query("SELECT * FROM rs_videos WHERE id_miembro=".$id);
		 while ($fila = mysql_fetch_assoc($r)){
			$video->eliminar($fila['id']);
		 }
		 //Eliminamos los comentarios
		 $r = mysql_query("SELECT * FROM rs_comentarios WHERE id_miembro=".$id);
		 while ($fila = mysql_fetch_assoc($r)){
			$comentario->eliminar($fila['id']);
		 }
		 //Eliminamos los comentarios en el periodico
		 mysql_query("DELETE FROM comentarios WHERE id_miembro=".$id);
		 //Eliminamos el correo
		 mysql_query("DELETE FROM rs_buzon_entrada WHERE para=".$id);
		 mysql_query("DELETE FROM rs_buzon_salida WHERE de=".$id);
		 //Eliminamos los miembors que me siguen - sigo
		 mysql_query("DELETE FROM rs_seguidores_seguidos WHERE id_seguidor=".$id);
		 mysql_query("DELETE FROM rs_seguidores_seguidos WHERE id_seguido=".$id);
		 //Eliminamos la actividad
		 mysql_query("DELETE FROM rs_actividad WHERE id_miembro=".$id);
		 //Eliminamos al miembro
		 /*$tamanios = array(65,114);
		 $miembro = mysql_fetch_assoc(mysql_query("SELECT url_foto FROM ".$this->tabla." WHERE id=".$id));
		 if (file_exists("../rs_fotos_miembros/".$miembro['url_foto']))
		  	unlink("../rs_fotos_miembros/".$miembro['url_foto']);
		 if (file_exists("../rs_fotos_miembros/recortes/".$miembro['url_foto']))
		  	unlink("../rs_fotos_miembros/recortes/".$miembro['url_foto']);
		 foreach ($tamanios as $elTamanio){
		  	if (file_exists("../rs_fotos_miembros/thumbnails/".$elTamanio."_".$miembro['url_foto']))
		  		unlink("../rs_fotos_miembros/thumbnails/".$elTamanio."_".$miembro['url_foto']);
		 }
		 mysql_query("DELETE FROM ".$this->tabla." WHERE id = ".$id);*/
	}
	
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($pagina = ""){
		header("Location: ".$this->url_home."&page=".$pagina);
	}
	
	function get_miembros ($palabra,$sexo,$edad,$tipo_seguidor,$page,$items,$orden,$tipo_orden){
		$sqlStr = "SELECT * FROM ".$this->tabla." WHERE 1=1 ";
		if ($palabra != "")
			$sqlStr .= " AND (email LIKE '%".$palabra."%' OR nombre LIKE '%".$palabra."%')";
		if ($sexo != "")
			$sqlStr .= " AND sexo=".$sexo;
		if ($edad != ""){
			$anyo = date("Y")-$edad;
			$fecha = $anyo."-".date("m")."-".date("d"); 
			$sqlStr .= " AND fecha_nacimiento<'".$fecha."'";
		}
		if ($tipo_seguidor != "")
			$sqlStr .= " AND tipo_seguidor='".$tipo_seguidor."'";
		$sqlStr .= " ORDER BY ".$orden." ".$tipo_orden;
		
		if(isset($page) and is_numeric($page))
			$limit = " LIMIT ".(($page-1)*$items).",$items";
		else
			$limit = " LIMIT $items";
		$sqlStr = $sqlStr.$limit;
		$res = mysql_query($sqlStr);
		$miembros = array();
		while ($fila = mysql_fetch_assoc($res)){
			$miembros[] = $fila;
		}
		return $miembros;
	}
	
	function get_num_miembros ($palabra,$sexo,$edad,$tipo_seguidor){
		$sqlStr = "SELECT * FROM ".$this->tabla." WHERE 1=1 ";
		if ($palabra != "")
			$sqlStr .= " AND (email LIKE '%".$palabra."%' OR nombre LIKE '%".$palabra."%')";
		if ($sexo != "")
			$sqlStr .= " AND sexo=".$sexo;
		if ($tipo_seguidor != "")
			$sqlStr .= " AND tipo_seguidor='".$tipo_seguidor."'";
		$res = mysql_query($sqlStr);
		$num_miembros = mysql_num_rows($res);
		return $num_miembros;
	}
	
	function muestra_foto($url,$nombre_campo){
		if ($url != ""){
			echo "
			<img src='".$url."' width='65px'><input type='checkbox' name='eliminar_foto' value='si'>Eliminar foto<br>
			Reemplazar por:&nbsp;<input type='file' name='".$nombre_campo."'>
			";
		}else{
			echo "<input type='file' name='".$nombre_campo."'>";
		}
	}
	
	function codifica_password ($pass){
		return md5(ord($pass.$pass));
	}
	
//ARCHIVOS Y FOTOGRAFIA
	function subir_foto ($id,$archivo,$campo){
		$carpeta = "../rs_fotos_miembros/";
		if (is_uploaded_file($archivo['tmp_name']) && ($archivo['type'] == "image/jpeg"  || $archivo['type'] == "image/pjpeg" || $archivo['type'] == "image/gif" || $archivo['type'] == "image/png")){
			$this -> eliminar_archivo($id,$campo);
			$pref= date("Ymdhis").$id.rand(10,99).".".$this -> getExtension($archivo['name']);
			if (move_uploaded_file($archivo['tmp_name'], $carpeta . $pref)){			
				$nombre = $carpeta . $pref;
				$this -> redimensiona($archivo,150,$nombre);
				mysql_query("UPDATE ".$this->tabla." SET ".$campo."='".$nombre."' WHERE id=".$id);
			}
		}	
	}

	function eliminar_archivo($id,$campo){
		$r = mysql_query("SELECT ".$campo." FROM ".$this->tabla." WHERE id=".$id);
		$fila = mysql_fetch_assoc($r);
		if (file_exists($fila[$campo])){
			unlink($fila[$campo]);
			mysql_query("UPDATE ".$this->tabla." SET ".$campo."='' WHERE id=".$id);
		}
	}
	
}
?>