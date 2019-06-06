<?php

class Funciones extends General{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "rs_videos";
		$this->url_home = "index2.php?modulo=mod_rs_videos";
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
	
	function update($arr_datos,$id){
		//Video	
		if (isset($arr_datos['eliminar_video']) && $arr_datos['eliminar_video'] == "si")
			mysql_query("UPDATE ".$this->tabla." SET cod_video='' WHERE id=".$id);
		if ($arr_datos['cod_video'] != "") 
			mysql_query("UPDATE ".$this->tabla." SET cod_video='".$arr_datos['cod_video']."' WHERE id=".$id);
			
		//Query
		$excluir = array("cod_video","eliminar_video");
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
	Borra una newsletter incluyendo los enlaces que la relacionan con las noticias
	*/
	function delete($elementos){
		include("../intranet/clases/video.php");
		$video = new Video();
		foreach($elementos as $clave => $valor){
			if ($clave != "seleccion"){
				$video->eliminar($valor);
			}
		};
		$this->redireccionar();
	}
	
	function enviarARecurso($id){
		mysql_query("UPDATE ".$this->tabla." SET en_recurso=0");
		mysql_query("UPDATE ".$this->tabla." SET en_recurso=1 WHERE id=".$id);
		$this->redireccionar();
	}

//OBTENCIÓN DE DATOS	
	function get_videos ($palabra,$page,$items,$orden,$tipo_orden,$desde,$hasta){
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
	
	function get_num_videos ($palabra,$desde,$hasta){
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
	
	function muestra_video($codigo,$nombre_campo){
		if ($codigo != ""){
			echo stripslashes($codigo)."<input type='checkbox' name='eliminar_video' value='si'>Eliminar video<br>
			Reemplazar por:&nbsp;<textarea name='".$nombre_campo."' cols='70'></textarea>
			";
		}else{
			echo "<textarea name='".$nombre_campo."' cols='70'></textarea>";
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