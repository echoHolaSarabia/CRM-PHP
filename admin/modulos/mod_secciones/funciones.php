<?php

class Funciones extends General {
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "secciones";
		$this->url_home = "index2.php?modulo=mod_secciones";
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
		$this->redireccionar();
	}
	
	/*
	Borra las secciones, y si tienen descendientes borra también las subsecciones
	*/
	function delete($elementos){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
			$c="DELETE FROM ".$this->tabla." WHERE id = ".$valor." OR id_padre = ".$valor.";";
			mysql_query($c);
		  }
		}
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
Funciones para la obtenci�n de datos que se usar�n en el listado
*/
	function get_num_secciones(){
		$r = mysql_query("SELECT * FROM ".$this->tabla.";");
		$num_filas = mysql_num_rows($r);
		return $num_filas;
	}
	
	/*function get_secciones_raiz(){
        $r = mysql_query("SELECT * FROM ".$this->tabla." WHERE id_padre=-1 ORDER BY id ASC;");
        return $r;
	}*/
	
	/*function get_secciones_hijas($id_padre){
        $r = mysql_query("SELECT * FROM ".$this->tabla." WHERE id_padre=".$id_padre."");
        return $r;
	}*/
	
	function get_num_subsecciones($seccion){
		$r = mysql_query("SELECT id FROM secciones WHERE id_padre=".$seccion);
		return mysql_num_rows($r);
	}
	
	/*function get_num_noticias_seccion ($seccion){
		$r = mysql_query("SELECT * FROM noticias WHERE seccion=".$seccion);
		$num_noticias = mysql_num_rows($r);
		return $num_noticias;
	}*/

	/*function get_num_noticias_subseccion ($seccion){
		$r = mysql_query("SELECT * FROM noticias WHERE subseccion=".$seccion);
		$num_noticias = mysql_num_rows($r);
		return $num_noticias;
	}*/
}
?>