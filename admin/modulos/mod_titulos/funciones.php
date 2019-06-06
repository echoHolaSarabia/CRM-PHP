<?php

class Funciones{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "titulos";
		$this->url_home = "index2.php?modulo=mod_titulos";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "update":
						$this->update($this->control['id'],$this->datos);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
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
		$c.=" WHERE id_seccion=".$id;
		mysql_query($c);//Comprobar que no se produce error
		$this->redireccionar();
	}
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar (){
		header("Location: ".$this->url_home);
	}
	
	function get_secciones_raiz(){
        $r = mysql_query("SELECT * FROM secciones WHERE id_padre=-1 ORDER BY id ASC;");
        return $r;
	}
	
}
?>