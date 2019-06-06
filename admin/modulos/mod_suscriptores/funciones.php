<?php

class Funciones{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "suscriptores";
		$this->url_home = "index2.php?modulo=mod_suscriptores";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "insert":
						$this->insert($this->datos);
						break;
				case "update":
						$this->update($this->datos,$this->control['id']);
						break;
				case "delete":
						$this->delete($this->datos);
						break;
				case "estado":
						$this->cambia_estado($this->control['id'],$this->control['campo'],$this->control['page']);
						break;
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	/*
	Inserta una nueva newsletter en la base de datos. Primero inserta la newsletter en si y despues las noticias asociadas a esta
	newsletter en su tabla correspondiente
	*/
	function insert($arr_datos){
		$c="INSERT INTO ".$this->tabla." (id";
		foreach ($arr_datos as $clave => $valor){
			$c.=",".$clave;
		}
		$c.=") VALUES (null";
		foreach ($arr_datos as $clave => $valor){
				if (is_numeric($valor)){
					$c.=",'".$valor."'";
				}
				else $c.=",'".addslashes($valor)."'";
		}
		$c.=");";
		//echo $c;
		mysql_query($c);//Comprobar que no se produce error
		
		$this->redireccionar();
	}
	
	function update($arr_datos,$id){
		$excluir = array("dia","mes","anyo","n_1","n_2","n_3","n_4");
		$fecha = $arr_datos['anyo']."-".$arr_datos['mes']."-".$arr_datos['dia'];
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
		if ($arr_datos['n_1'] != ""){
			$c.=",n_1=".$arr_datos['n_1'];
		} else {
				$c.=",n_1=0";
		}
		if ($arr_datos['n_2'] != ""){
			$c.=",n_2=".$arr_datos['n_2'];
		} else {
				$c.=",n_2=0";
		}
		if ($arr_datos['n_3'] != ""){
			$c.=",n_3=".$arr_datos['n_3'];
		} else {
				$c.=",n_3=0";
		}
		if ($arr_datos['n_4'] != ""){
			$c.=",n_4=".$arr_datos['n_4'];
		} else {
				$c.=",n_4=0";
		}
		
		$c.=" WHERE id=".$id;
		mysql_query($c);
		mysql_query("UPDATE ".$this->tabla." SET fecha = '".$fecha."' WHERE id=".$id);
		$this->redireccionar();
	}
	
	/*
	Borra una newsletter incluyendo los enlaces que la relacionan con las noticias
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
	
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($pagina = ""){
		header("Location: ".$this->url_home."&page=".$pagina);
	}
	
	/*
	Obtiene la query para listar las noticias con el buscador AJAX.
	Devuelve por referencia los parámetros necesarios.	
	*/
	function get_suscriptores ($page,$items,&$q,&$sqlStr,&$sqlStrAux,&$limit,$orden,$tipo_orden,$tipo_newsletter){
		if(isset($page) and is_numeric($page))
			$limit = " LIMIT ".(($page-1)*$items).",$items";
		else
			$limit = " LIMIT $items";
			
		if ($tipo_newsletter!="")
			$q_tipo_newsletter = " AND ".$tipo_newsletter."=1 ";
		else $q_tipo_newsletter = "";
		
	
		if(isset($q) and !eregi('^ *$',$q)){
			$q = sql_quote($q); //para ejecutar consulta
			$busqueda = htmlentities($q); //para mostrar en pantalla
	
			$sqlStr = "SELECT * FROM ".$this->tabla." WHERE  email LIKE '%$q%' ".$q_tipo_newsletter." ORDER BY ".$orden." ".$tipo_orden."";
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla." WHERE email LIKE '%$q%' ".$q_tipo_newsletter." ";
		}else{
			$sqlStr = "SELECT * FROM ".$this->tabla." WHERE 1=1  ".$q_tipo_newsletter."  ORDER BY ".$orden." ".$tipo_orden."";
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla." WHERE 1=1 ".$q_tipo_newsletter." ";
		}
	}
	
	function ValidaMail($pMail) {
    	if (ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$", $pMail ) ) {
       		return true;
    	} else {
       		return false;
    	}
	}
	
	function cambia_estado($id,$campo,$pagina){
		//Obtengo algunos datos de la noticia a la que voy a cambiar el estado
		$r = mysql_query("SELECT n_1,n_2,n_3,n_4 FROM ".$this->tabla." WHERE id=".$id);
		$estado = mysql_fetch_array($r);
		
		//Veo cual va a ser el estado final
		if ($estado[$campo] == 1){
			$nuevo_estado = 0;
		} else {
			$nuevo_estado = 1;
		}
		
		//Actualizo el estado en la tabla de noticias
		$c = "UPDATE ".$this->tabla." SET ".$campo."=".$nuevo_estado." WHERE id=".$id;
		mysql_query($c);
		$this->redireccionar();
	}

}
?>