<?php

class Funciones extends General{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "rs_comentarios";
		$this->url_home = "index2.php?modulo=mod_rs_comentarios";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "update":
						$this->update($this->datos,$this->control['id']);
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
	
	function update($arr_datos,$id){

		//Query
		$excluir = array();
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
		//Redireccionar
		$this -> redireccionar();
	}
	
	/*
	Borra un comentario
	*/
	function delete($elementos){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
		  	$c = "SELECT id_categoria,tabla FROM ".$this->tabla." WHERE id = ".$valor.";";
		  	$r = mysql_query($c);
		  	$fila = mysql_fetch_assoc($r);
		  	if ($fila['tabla'] == "album")
		  		mysql_query("UPDATE rs_galerias_fotos SET num_comentarios=num_comentarios-1 WHERE id=".$fila['id_categoria']);
		  	else if ($fila['tabla'] == "videos")
		  		mysql_query("UPDATE rs_videos SET num_comentarios=num_comentarios-1 WHERE id=".$fila['id_categoria']);
		  	else mysql_query("UPDATE rs_categorias SET num_comentarios=num_comentarios-1 WHERE id=".$fila['id_categoria']);
			mysql_query("DELETE FROM ".$this->tabla." WHERE id = ".$valor.";");
		  }
		}
		$this->redireccionar();
	}

//OBTENCIÓN DE DATOS	
	function get_comentarios ($palabra,$page,$items,$orden,$tipo_orden,$desde,$hasta,$tabla,$id_post){
		$sqlStr = "SELECT * FROM ".$this->tabla." WHERE 1=1 ";
		if ($id_post != "")
			$sqlStr .= " AND id_categoria=".$id_post." ";
		if ($tabla != "")
			$sqlStr .= " AND tabla='".$tabla."' ";
		if ($palabra != "")
			$sqlStr .= " AND comentario LIKE '%".$palabra."%' ";
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
	
	function get_num_comentarios ($palabra,$desde,$hasta,$tabla,$id_post){
		$sqlStr = "SELECT * FROM ".$this->tabla." WHERE 1=1 ";
		if ($id_post != "")
			$sqlStr .= " AND id_categoria=".$id_post." ";
		if ($tabla != "")
			$sqlStr .= " AND tabla='".$tabla."' ";
		if ($palabra != "")
			$sqlStr .= " AND comentario LIKE '%".$palabra."%' ";
		if ($desde != "")
			$sqlStr .= " AND fecha_publicacion >= '".$desde." 00:00:00' ";
		if ($hasta != "")
			$sqlStr .= " AND fecha_publicacion <= '".$hasta." 23:59:59' ";
		$res = mysql_query($sqlStr);
		$num_miembros = mysql_num_rows($res);
		return $num_miembros;
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