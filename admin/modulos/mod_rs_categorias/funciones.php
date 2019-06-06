<?php

class Funciones extends General{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "rs_categorias";
		$this->url_home = "index2.php?modulo=mod_rs_categorias";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "update":
						$this->update($this->datos,$this->control['id'],$_FILES);
						break;
				case "delete":
						$this->delete($this->datos);
						break;
				case "activar":
						$this->enviarARecurso($this->control['id'],$this->control['tipo']);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	function update($arr_datos,$id,$files){
		//Archivos
		if (isset($arr_datos['eliminar_foto']) && $arr_datos['eliminar_foto'] == "si")
			$this -> eliminar_archivo($id,"url_foto");
		if (isset($arr_datos['eliminar_archivo']) && $arr_datos['eliminar_archivo'] == "si")
			$this -> eliminar_archivo($id,"url_archivo");
		if (isset($arr_datos['eliminar_foto_alt']) && $arr_datos['eliminar_foto_alt'] == "si")
			$this -> eliminar_archivo($id,"foto_alt");
		//Video	
		if (isset($arr_datos['eliminar_video']) && $arr_datos['eliminar_video'] == "si")
			mysql_query("UPDATE ".$this->tabla." SET cod_video='' WHERE id=".$id);
		if ($arr_datos['cod_video'] != "") 
			mysql_query("UPDATE ".$this->tabla." SET cod_video='".$arr_datos['cod_video']."' WHERE id=".$id);
		//Query
		$excluir = array("eliminar_foto","eliminar_video","eliminar_archivo","eliminar_foto_alt","cod_video","tipo");
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
		//Subir archivos
		$this -> subir_foto ($id,$files['url_foto'],"url_foto");
		$this -> subir_archivo ($id,$files['url_archivo']);
		$this -> subir_foto ($id,$files['foto_alt'],"foto_alt");
		//Redireccionar
		//$this -> redireccionar();
		header("Location: ".$this->url_home."&tipo=".$arr_datos['tipo']);
	}
	
	/*
	Borra una newsletter incluyendo los enlaces que la relacionan con las noticias
	*/
	function delete($elementos){
		include("../intranet/clases/post.php");
		$post = new Post();
		foreach($elementos as $clave => $valor){
			if ($clave != "seleccion"){
				$post->eliminar($valor);
			}
		};
		$this->redireccionar();
	}

//ARCHIVOS Y FOTOGRAFIA
	function subir_foto ($id,$archivo,$campo){
		$carpeta = "../rs_fotos/";
		if (is_uploaded_file($archivo['tmp_name']) && ($archivo['type'] == "image/jpeg"  || $archivo['type'] == "image/pjpeg" || $archivo['type'] == "image/gif" || $archivo['type'] == "image/png")){
			$this -> eliminar_archivo($id,$campo);
			$pref= date("Ymdhis").$id.rand(10,99).".".$this -> getExtension($archivo['name']);
			if (move_uploaded_file($archivo['tmp_name'], $carpeta . $pref)){			
				$nombre = $carpeta . $pref;
				$this -> redimensiona($archivo,250,$nombre);
				mysql_query("UPDATE ".$this->tabla." SET ".$campo."='".$nombre."' WHERE id=".$id);
			}
		}	
	}
	
	function enviarARecurso($id,$tipo){
		mysql_query("UPDATE ".$this->tabla." SET en_recurso=0 WHERE tipo='".$tipo."'");
		mysql_query("UPDATE ".$this->tabla." SET en_recurso=1 WHERE tipo='".$tipo."' AND  id=".$id);
		$this->redireccionar();
	}
	
	function subir_archivo ($id,$archivo){
		$carpeta = "../rs_archivos/";
		if (is_uploaded_file($archivo['tmp_name'])){
			$this -> eliminar_archivo($id,"url_archivo");
			$pref= date("Ymdhis").$id.rand(10,99).".".$this -> getExtension($archivo['name']);
			if (move_uploaded_file($archivo['tmp_name'], $carpeta . $pref)){			
				$nombre = $carpeta . $pref;
				mysql_query("UPDATE ".$this->tabla." SET url_archivo='".$nombre."' WHERE id=".$id);
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

//OBTENCIÓN DE DATOS	
	function get_posts ($palabra,$page,$items,$orden,$tipo_orden,$desde,$hasta,$tipo){
		$sqlStr = "SELECT * FROM ".$this->tabla." WHERE tipo='".$tipo."' ";
		if ($palabra != "")
			$sqlStr .= " AND (titulo LIKE '%".$palabra."%' OR comentario LIKE '%".$palabra."%')";
		if ($desde != "")
			$sqlStr .= " AND fecha_publicacion >= '".$desde." 00:00:00' ";
		if ($hasta != "")
			$sqlStr .= " AND fecha_publicacion <= '".$hasta." 23:59:59' ";
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
	
	function get_num_posts ($palabra,$desde,$hasta,$tipo){
		$sqlStr = "SELECT * FROM ".$this->tabla." WHERE tipo='".$tipo."' ";
		if ($palabra != "")
			$sqlStr .= " AND (titulo LIKE '%".$palabra."%' OR comentario LIKE '%".$palabra."%')";
		if ($desde != "")
			$sqlStr .= " AND fecha_publicacion >= '".$desde." 00:00:00' ";
		if ($hasta != "")
			$sqlStr .= " AND fecha_publicacion <= '".$hasta." 23:59:59' ";
		$res = mysql_query($sqlStr);
		$num_miembros = mysql_num_rows($res);
		return $num_miembros;
	}
	
	function muestra_foto($url,$nombre_campo){
		if ($url != ""){
			echo "
			<img src='".$url."' width='150px'><input type='checkbox' name='eliminar_foto' value='si'>Eliminar foto<br>
			Reemplazar por:&nbsp;<input type='file' name='".$nombre_campo."'>
			";
		}else{
			echo "<input type='file' name='".$nombre_campo."'>";
		}
	}
	
	function muestra_archivo($url,$nombre_campo){
		if ($url != ""){
			echo "<a href='".$url."' targer='_blank'>".$url."</a><input type='checkbox' name='eliminar_archivo' value='si'>Eliminar archvo<br>
			Reemplazar por:&nbsp;<input type='file' name='".$nombre_campo."'>
			";
		}else{
			echo "<input type='file' name='".$nombre_campo."'>";
		}
	}
	
	function muestra_video($codigo,$nombre_campo){
		if ($codigo != ""){
			echo stripslashes($codigo)."<input type='checkbox' name='eliminar_video' value='si'>Eliminar video<br>
			Reemplazar por:&nbsp;<textarea name='".$nombre_campo."' cols='70'></textarea>
			";
		}else{
			echo "<textarea name='".$nombre_campo."' cols='70'></textarea>";
		}
	}
	
	function muestra_foto_alt($url,$nombre_campo){
		if ($url != ""){
			echo "
			<img src='".$url."' width='250px'><input type='checkbox' name='eliminar_foto_alt' value='si'>Eliminar foto<br>
			Reemplazar por:&nbsp;<input type='file' name='".$nombre_campo."'>
			";
		}else{
			echo "<input type='file' name='".$nombre_campo."'>";
		}
	}
	
	function get_nombre_miembro ($id){
		$r = mysql_query("SELECT nombre,apellidos FROM rs_miembros WHERE id=".$id);
		$miembro = mysql_fetch_assoc($r);
		return $miembro['nombre']." ".$miembro['apellidos'];
	}
	
	function get_num_comentarios($id_post,$tabla){
		$c = "SELECT COUNT(*) FROM rs_comentarios WHERE id_categoria=".$id_post." AND tabla='".$tabla."'";
		$r = mysql_query($c);
		$fila = mysql_fetch_row($r);
		return $fila[0];
	}
//REDIRECCIÓN
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($pagina = ""){
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}
}
?>