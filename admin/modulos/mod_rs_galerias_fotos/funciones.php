<?php

class Funciones extends General{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "rs_galerias_fotos";
		$this->url_home = "index2.php?modulo=mod_rs_galerias_fotos";
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
						$this->enviarARecurso($this->control['id']);
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
		//Eliminar fotos
		$num_fotos = 8;
		$excluir = array();
		for ($i = 1;$i <= $num_fotos; $i ++){
			if (isset($arr_datos['eliminar_foto_'.$i]) && $arr_datos['eliminar_foto_'.$i] == "si")
				$this -> eliminar_archivo($id,"foto".$i);
			$excluir[] = 'eliminar_foto_'.$i;
		}
		//Query	
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
		
		$this->subir_bloque_fotos($id,$files,$num_fotos);
		
		//Redireccionar
		$this -> redireccionar();
	}
	
	/*
	Borra una newsletter incluyendo los enlaces que la relacionan con las noticias
	*/
	function delete($elementos){
		include("../intranet/clases/galeria_fotos.php");
		$galeria = new GaleriaFotos();
		foreach($elementos as $clave => $valor){
			if ($clave != "seleccion"){
				$galeria->eliminar($valor);
			}
		};
		$this->redireccionar();
	}
	
	function enviarARecurso($id){
		mysql_query("UPDATE ".$this->tabla." SET en_recurso=0");
		mysql_query("UPDATE ".$this->tabla." SET en_recurso=1 WHERE id=".$id);
		$this->redireccionar();
	}
	
	function subir_bloque_fotos($id,$files,$num_fotos){
		for ($i = 1; $i<=$num_fotos; $i++){
			$this->subir_foto($id,$files['foto'.$i],"foto".$i);
		}
	}
	
	function subir_foto ($id,$archivo,$campo){
		$carpeta = "../rs_galerias_fotos/";
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
	
	function eliminar_archivo($id,$campo){
		$r = mysql_query("SELECT ".$campo." FROM ".$this->tabla." WHERE id=".$id);
		$fila = mysql_fetch_assoc($r);
		if (file_exists("../rs_galerias_fotos/originales/".$fila[$campo])){
			unlink("../rs_galerias_fotos/originales/".$fila[$campo]);
			unlink("../rs_galerias_fotos/recortes/".$fila[$campo]);
			unlink("../rs_galerias_fotos/thumbnails/88_".$fila[$campo]);
			unlink("../rs_galerias_fotos/thumbnails/441_".$fila[$campo]);
			unlink("../rs_galerias_fotos/thumbnails/287_".$fila[$campo]);
			unlink("../rs_galerias_fotos/thumbnails/112_".$fila[$campo]);
			mysql_query("UPDATE ".$this->tabla." SET ".$campo."='' WHERE id=".$id);
		}
	}
//OBTENCIÓN DE DATOS	
	function get_galerias ($palabra,$page,$items,$orden,$tipo_orden,$desde,$hasta){
		$sqlStr = "SELECT * FROM ".$this->tabla." WHERE 1=1 ";
		if ($palabra != "")
			$sqlStr .= " AND (titulo LIKE '%".$palabra."%' OR comentario LIKE '%".$palabra."%') ";
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
	
	function get_num_galerias ($palabra,$desde,$hasta){
		$sqlStr = "SELECT * FROM ".$this->tabla." WHERE 1=1 ";
		if ($palabra != "")
			$sqlStr .= " AND (titulo LIKE '%".$palabra."%' OR comentario LIKE '%".$palabra."%') ";
		if ($desde != "")
			$sqlStr .= " AND fecha_publicacion >= '".$desde." 00:00:00' ";
		if ($hasta != "")
			$sqlStr .= " AND fecha_publicacion <= '".$hasta." 23:59:59' ";
		$res = mysql_query($sqlStr);
		$num_miembros = mysql_num_rows($res);
		return $num_miembros;
	}
	
	function muestra_foto($url,$nombre_campo,$num){
		if ($url != ""){
			echo "
			<img src='/rs_galerias_fotos/thumbnails/112_".$url."' width='112px'><input type='checkbox' name='eliminar_foto_".$num."' value='si'>Eliminar foto<br>
			";
		}else{
			echo "No hay foto";
			//echo "<input type='file' name='".$nombre_campo."'>";
		}
	}
	
	function get_nombre_miembro ($id){
		$r = mysql_query("SELECT nombre,apellidos FROM rs_miembros WHERE id=".$id);
		$miembro = mysql_fetch_assoc($r);
		return $miembro['nombre']." ".$miembro['apellidos'];
	}
//REDIRECCIÓN
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($pagina = ""){
		header("Location: ".$this->url_home."&page=".$pagina);
	}
}
?>