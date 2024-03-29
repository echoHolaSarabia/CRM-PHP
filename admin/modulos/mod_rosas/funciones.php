<?php

class Funciones extends General {
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "modulosrosas";
		$this->url_home = "index2.php?modulo=mod_rosas";
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
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	function insert($arr_datos){
		$c="INSERT INTO ".$this->tabla." (activo";
		foreach ($arr_datos as $clave => $valor){
			$c.=",".$clave;
		}
		$c.=") VALUES (1";
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
		$id_rosa = mysql_insert_id();
		
		$r = mysql_query("SELECT * FROM planillas");
		while ($planilla = mysql_fetch_assoc($r)){
			mysql_query("INSERT INTO planillas_elementos (id_planilla,id_elemento,tabla_elemento,bloqueado,programado)VALUES(".$planilla['id'].",".$id_rosa.",'modulosrosas',0,0)");
		}
		mysql_query("INSERT INTO planillas_elementos_ampliada (id_planilla,id_elemento,tabla_elemento)VALUES(1,".$id_rosa.",'modulosrosas')");
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
			$c="DELETE FROM ".$this->tabla." WHERE id = ".$valor.";";
			//echo $c;
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
	
	function get_modulos_rosas(){
        $r = mysql_query("SELECT * FROM ".$this->tabla." ORDER BY titulo ASC;");
        $resultado = array();
        while ($fila = mysql_fetch_assoc($r)){
        	$resultado[] = $fila;
        }
        return $resultado;
	}
	
	function get_num_modulos_rosas(){
		$r = mysql_query("SELECT * FROM ".$this->tabla.";");
		$num_filas = mysql_num_rows($r);
		return $num_filas;
	}
	
}
?>