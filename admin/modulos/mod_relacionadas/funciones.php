<?php

class Funciones extends General{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "noticias";
		$this->url_home = "index2.php?modulo=mod_relacionadas&id=".$this->control['id'];
	}

	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "estado":
						$this->cambia_estado($this->control['id'],$this->control['id_relacionada'],$this->control['elemento'],$this->control['page'],$this->control['q'],$this->control['q_seccion'],$this->control['orden'],$this->control['tipo_orden']);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	function cambia_estado($origen,$destino,$tipo_elemento,$page,$palabra,$seccion,$orden,$tipo_orden){
		$r = mysql_query("SELECT * FROM noticias_relacionadas WHERE id_noticia=".$origen." AND id_relacionada=".$destino." AND tipo_elemento='".$tipo_elemento."'");
		
		if (mysql_num_rows($r) > 0){
			mysql_query("DELETE FROM noticias_relacionadas WHERE id_noticia=".$origen." AND id_relacionada=".$destino." AND tipo_elemento='".$tipo_elemento."'");
			mysql_query("DELETE FROM noticias_relacionadas WHERE id_noticia=".$destino." AND id_relacionada=".$origen." AND tipo_elemento='".$tipo_elemento."'");
		} else {
			mysql_query("INSERT INTO noticias_relacionadas (id_noticia,id_relacionada,tipo_elemento) VALUES (".$origen.",".$destino.",'".$tipo_elemento."')");
			mysql_query("INSERT INTO noticias_relacionadas (id_noticia,id_relacionada,tipo_elemento) VALUES (".$destino.",".$origen.",'".$tipo_elemento."')");
		}
		$this->redireccionar($tipo_elemento,$page,$pagina,$palabra,$seccion,$orden,$tipo_orden);
	}
	
	/*
	Obtiene los datos de una noticia pasando como parámetro el identificador
	*/
	function get_noticia($id){
		$r = mysql_query("SELECT * FROM ".$this->tabla." WHERE id=".$id);
		$fila = mysql_fetch_array ($r);
		return $fila;
	}
	
	function get_strQuery($tipo_elemento,$atributo,$palabra,$seccion,$orden = "id",$tipo_orden = "DESC"){
		$c = "SELECT * FROM ".$tipo_elemento." ";
		if ($palabra != "" || $seccion != "")
			$c .= " WHERE ";
		if ($palabra != "")
			$c .= $atributo." LIKE '%".$palabra."%'";
		if ($palabra != "" && $seccion != "")
			$c .= " AND ";
		if ($seccion != "")
			$c .= " seccion = ".$seccion;
		$c .= " ORDER BY ".$orden." ".$tipo_orden;
		return $c;
	}
	
	function get_strQueryTotal($tipo_elemento,$atributo,$palabra,$seccion){
		$c2 = "SELECT count(*) as total FROM ".$tipo_elemento." ";
		if ($palabra != "" || $seccion != "")
			$c2 .= " WHERE ";
		if ($palabra != "")
			$c2 .= $atributo." LIKE '%".$palabra."%'";
		if ($palabra != "" && $seccion != "")
			$c2 .= " AND ";
		if ($seccion != "")
			$c2 .= " seccion = ".$seccion;
			/*
		if (ereg("titular",$atributo)){
			$c2 = " SELECT count(*) as total FROM ".$tipo_elemento." ";
			if ($palabra != "" || $seccion != "")
				$c2 .= " WHERE ";
			if ($palabra != "")
				$c2 .= $atributo." LIKE '%".$palabra."%'";
			if ($palabra != "" && $seccion != "")
				$c2 .= " AND ";
			if ($seccion != "")
				$c2 .= " seccion = ".$seccion;
			$c2 .= " UNION SELECT count(*) as total FROM ".$tipo_elemento." ";
			if ($palabra != "" || $seccion != "")
				$c2 .= " WHERE ";
			if ($palabra != "")
				$c2 .= $atributo." LIKE '%".$palabra."%'";
			if ($palabra != "" && $seccion != "")
				$c2 .= " AND ";
			if ($seccion != "")
				$c2 .= " seccion = ".$seccion;				
		}*/
		return $c2;
	}
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($elemento = "",$page,$pagina,$palabra,$seccion,$orden,$tipo_orden){
		header("Location: ".$this->url_home."&elemento=".$elemento."&page=".$page."&q=".urlencode($palabra)."&id=".$_GET['id']."&q_seccion=".$seccion."&orden=".$orden."&tipo_orden=".$tipo_orden);
	}
	

	function get_subsecciones($seccion){
		$c = "SELECT * FROM secciones WHERE id_padre=".$seccion.";";
		$r = mysql_query($c);
		return $r;
	}
}
?>