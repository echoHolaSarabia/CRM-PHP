<?php

class Funciones{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "encuestas";
		$this->url_home = "index2.php?modulo=mod_encuestas";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "insert":
						$this->insert($this->datos);
						break;
				case "update":
						$this->update($this->control['id'],$this->datos);
						break;
				case "delete":
						$this->delete($this->datos);
						break;
				case "estado":
						$this->cambia_estado($this->control['id']);
						break;
				case "publicar":
						$this->cambia_publicado($this->control['id']);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	function insert($arr_datos){
		$c="INSERT INTO ".$this->tabla." (id";
		foreach ($arr_datos as $clave => $valor){
			$c.=",".$clave;
		}
		$c.=") VALUES (null";
		foreach ($arr_datos as $clave => $valor){
			if (is_numeric($valor)){
				$c.=",".$valor;
			} else {
				$c.=",'".addslashes($valor)."'";
			}
		}
		$c.=");";
		//echo $c;
		mysql_query($c);//Comprobar que no se produce error
		$id = mysql_insert_id();
		
		if ($arr_datos['activa'] == 1){
			mysql_query("UPDATE ".$this->tabla." SET activa=0");
			mysql_query("UPDATE ".$this->tabla." SET activa = 1, mostrar=1 WHERE id=".$id);
		}
		
		$this->redireccionar();
	}
	
	function update($id,$arr_datos){
		$iteracion = 1;
		$c="UPDATE ".$this->tabla." SET ";
		foreach ($arr_datos as $clave => $valor){
		  if ($iteracion == 1){	
			if (is_numeric($valor)){
				$c.=$clave."=".$valor;
			} else {
				$c.=$clave."='".addslashes($valor)."'";
			}
		  }else{
		  	if (is_numeric($valor)){
				$c.=",".$clave."=".$valor;
			} else {
				$c.=",".$clave."='".addslashes($valor)."'";
			}
		  }
		  $iteracion ++;
		}
		$c.=" WHERE id=".$id;
		mysql_query($c);//Comprobar que no se produce error
		
		if ($arr_datos['activa'] == 1){
			mysql_query("UPDATE ".$this->tabla." SET activa=0");
			mysql_query("UPDATE ".$this->tabla." SET activa = 1,mostrar = 1 WHERE id=".$id);
		}
		
		$this->redireccionar();
	}
	
	/*
	Borra las secciones, y si tienen descendientes borra también las subsecciones
	*/
	function delete($elementos){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
			$c="DELETE FROM ".$this->tabla." WHERE id = ".$valor.";";
			mysql_query($c);
		  }
		}
		$this->redireccionar();
	}
	
	function cambia_estado($id){
		$c = "SELECT mostrar FROM ".$this->tabla." WHERE id=".$id;
		$r = mysql_query($c);
		$estado = mysql_fetch_array($r);
		if ($estado['mostrar'] == 1){
			$nuevo_estado = 0;
		} else {
			$nuevo_estado = 1;
		}
		$c="UPDATE ".$this->tabla." SET mostrar=".$nuevo_estado." WHERE id=".$id;
		mysql_query($c);
		$this->redireccionar();
	}
	
	
	function cambia_publicado($id){
		$c="UPDATE ".$this->tabla." SET activa=1 WHERE id=".$id;
		mysql_query($c);
		$c="UPDATE ".$this->tabla." SET activa=0 WHERE id<>".$id;
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
	
	/*
	Devuleve los datos de una encuesta
	*/
	function get_encuestas(){
		$r = mysql_query("SELECT * FROM ".$this->tabla.";");
		return $r;
	}

}
?>