<?php

class Funciones{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "comentarios";
		$this->url_home = "index2.php?modulo=mod_comentarios";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "update":
						$this->update($this->datos,$this->control['id'],$this->control,$_FILES);
						break;
				case "publicar_uno":
						$this->publicar_uno($this->datos,$this->control,$_FILES);
						break;
				case "cambiar_estado":
						$this->cambiar_estado($this->datos,$this->control);
						break;
				case "delete":
						$this->delete($this->datos,$this->control);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}	
	
	function update($arr_datos,$id,$arr_control,$files){
		/*if (get_magic_quotes_gpc()){
			
		}*/		
		$iteracion = 1;
		$c="UPDATE ".$this->tabla." SET ";
		foreach ($arr_datos as $clave => $valor){
		  if ($iteracion == 1)	
		    $c.=$clave."='".addslashes($valor)."'";
		  else $c.=",".$clave."='".addslashes($valor)."'";
		  $iteracion ++;
		}
		$c.=" WHERE id=".$id;
		//echo $c;
		mysql_query($c);
		echo mysql_error();
		
		if (isset($_GET["tabla_comentado"]))
			$tabla_comentado = $_GET["tabla_comentado"];
		else $tabla_comentado = "";

		if (isset($_GET["id_comentado"]))
			$id_comentado = $_GET["id_comentado"];
		else $id_comentado = "";
		
		$this->redireccionar($tabla_comentado,$id_comentado);
	}
	
	function cambiar_estado($elementos,$arr_control){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
		  	
		  	// Consulta para saber estado actual e id_comentado y tabla_comentado
		  	$c = "SELECT id_comentado, tabla_comentado, publicado FROM ".$this->tabla." WHERE id=".$valor;
		  	$r = mysql_fetch_assoc(mysql_query($c));
		  	if($r["publicado"]==0){
		  		$c="UPDATE ".$r["tabla_comentado"]." SET num_comentarios = num_comentarios + 1  WHERE id = ".$r["id_comentado"].";";
		  		mysql_query($c);
		  		$c="UPDATE ".$this->tabla." SET publicado = 1  WHERE id = ".$valor.";";
		  		mysql_query($c);
		  	}
		  	else{
		  		$c="UPDATE ".$r["tabla_comentado"]." SET num_comentarios = num_comentarios - 1  WHERE id = ".$r["id_comentado"].";";
		  		mysql_query($c);
		  		$c="UPDATE ".$this->tabla." SET publicado = 0  WHERE id = ".$valor.";";
		  		mysql_query($c);
		  	}			
		  }
		}


		if(isset($arr_control["tabla_comentado"]))
			$tabla_comentado = $arr_control["tabla_comentado"];
		else $tabla_comentado = "";
		
		if(isset($arr_control["id_comentado"]))
			$id_comentado = $arr_control["id_comentado"];
		else $id_comentado = "";
		
		if(isset($arr_control["pagina"]))
			$pagina = $arr_control["pagina"];
		else $pagina = "";

		$this->redireccionar($tabla_comentado,$id_comentado,$pagina);
	}
	
	function publicar_uno($arr_datos,$arr_control,$files){
		
		
		$c = "SELECT id_comentado, tabla_comentado, publicado FROM ".$this->tabla." WHERE id=".$arr_control["id"];
	  	$r = mysql_fetch_assoc(mysql_query($c));
	  	if($r["publicado"]==0){
	  		$c="UPDATE ".$r["tabla_comentado"]." SET num_comentarios = num_comentarios + 1  WHERE id = ".$r["id_comentado"].";";
	  		mysql_query($c);
	  		$c="UPDATE ".$this->tabla." SET publicado = 1  WHERE id = ".$arr_control["id"].";";
	  		mysql_query($c);
	  	}
	  	else{
	  		$c="UPDATE ".$r["tabla_comentado"]." SET num_comentarios = num_comentarios - 1  WHERE id = ".$r["id_comentado"].";";
	  		mysql_query($c);
	  		$c="UPDATE ".$this->tabla." SET publicado = 0  WHERE id = ".$arr_control["id"].";";
	  		mysql_query($c);
	  	}	
	
		echo mysql_error();
		
		if(isset($arr_control["tabla_comentado"]))
			$tabla_comentado = $arr_control["tabla_comentado"];
		else $tabla_comentado = "";
		
		if(isset($arr_control["id_comentado"]))
			$id_comentado = $arr_control["id_comentado"];
		else $id_comentado = "";
		
		if(isset($arr_control["pagina"]))
			$pagina = $arr_control["pagina"];
		else $pagina = "";
		
		$this->redireccionar($tabla_comentado,$id_comentado,$pagina);
	}
	
	/*
	Borra las secciones, y si tienen descendientes borra también las subsecciones
	*/
	function delete($elementos,$arr_control){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
		  	
		  	//Decremento el numero de comentarios en la tabla correspondiente
			$r = mysql_query("SELECT id_comentado,tabla_comentado FROM comentarios WHERE id=".$valor);
			$fila = mysql_fetch_assoc($r);
			mysql_query("UPDATE ".$fila['tabla_comentado']." SET num_comentarios=num_comentarios-1 WHERE id=".$fila['id_comentado']);
			
		  	//Se borra de la tabla comentarios	
			$c="DELETE FROM ".$this->tabla." WHERE id = ".$valor.";";
			mysql_query($c);
			
		  }
		}
		
		if(isset($arr_control["tabla_comentado"]))
			$tabla_comentado = $arr_control["tabla_comentado"];
		else $tabla_comentado = "";
		
		if(isset($arr_control["id_comentado"]))
			$id_comentado = $arr_control["id_comentado"];
		else $id_comentado = "";
		
		if(isset($arr_control["pagina"]))
			$pagina = $arr_control["pagina"];
		else $pagina = "";
		
		$this->redireccionar($tabla_comentado,$id_comentado,$pagina);
	}
	
	
	/*
	Obtiene los datos de una noticia pasando como parámetro el identificador
	*/
	function get_noticia($id, $tipo){
		if (ereg("noticia",$tipo)){
			$r = mysql_query("SELECT * FROM noticias WHERE id=".$id);
			$fila = mysql_fetch_array ($r);
		}
		else if (ereg("eventos",$tipo)){
			$r = mysql_query("SELECT * FROM eventos WHERE id=".$id);
			$fila = mysql_fetch_array ($r);
		}
		else if (ereg("formacion",$tipo)){
			$r = mysql_query("SELECT * FROM formacion WHERE id=".$id);
			$fila = mysql_fetch_array ($r);
		}
		else if (ereg("galerias_imagenes",$tipo)){
				$r = mysql_query("SELECT id, titulo FROM galerias_imagenes WHERE id=".$id);
				$fila = mysql_fetch_array ($r);
		}
		else if (ereg("videos",$tipo)){
				$r = mysql_query("SELECT id, titulo FROM videos WHERE id=".$id);
				$fila = mysql_fetch_array ($r);
		}
		else if (ereg("modulosrosas",$tipo)){
				$r = mysql_query("SELECT id, titulo FROM modulosrosas WHERE id=".$id);
				$fila = mysql_fetch_array ($r);
		}
		return $fila;
	}
	
	/*
	Obtiene la query para listar las noticias con el buscador AJAX.
	Devuelve por referencia los parámetros necesarios.	
	*/
	function get_query_palabra ($tabla_comentado,$id_comentado,$page,$items,&$q,&$sqlStr,&$sqlStrAux,&$limit,$orden,$tipo_orden){
		if(isset($page) and is_numeric($page))
			$limit = " LIMIT ".(($page-1)*$items).",$items";
		else
			$limit = " LIMIT $items";
	
		$where = "WHERE 1=1 ";
			
		if($tabla_comentado!="")
			$where .= " AND tabla_comentado like '".$tabla_comentado."'";
		
		if($id_comentado!="")
			$where .= " AND id_comentado = '".$id_comentado."'";
			
			
		if(isset($q) and !eregi('^ *$',$q)){
			$q = sql_quote($q); //para ejecutar consulta
			$busqueda = htmlentities($q); //para mostrar en pantalla
	
			$sqlStr = "SELECT * FROM ".$this->tabla." ".$where." AND texto LIKE '%$q%' ORDER BY ".$orden." ".$tipo_orden."";
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla."  ".$where." AND texto LIKE '%$q%'";
		}else{
			$sqlStr = "SELECT * FROM ".$this->tabla."  ".$where."  ORDER BY ".$orden." ".$tipo_orden."";
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla." ".$where;
		}
	}
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($tabla_comentado,$id_comentado,$pagina = ""){
		
		header("Location: ".$this->url_home."&page=".$pagina."&tabla_comentado=".$tabla_comentado."&id_comentado=".$id_comentado);
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
	
}
?>