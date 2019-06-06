<?php

class Funciones{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la funci�n a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	var $url_iframe;
	var $upload_dir;
	var $nombre_campo;
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "ficheros";
		$this->url_home = "index2.php?modulo=mod_ficheros";
		$this->url_iframe = "ficheros.php";
		$this->upload_dir = "../userfiles/";
		$this->nombre_campo = "archivo";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "subir":
						$this->sube_e_inserta($_FILES,$this->control["dir"]);
						break;
				case "crear_carpeta":
						$this->crea_carpeta($this->datos["carpeta"],$this->control["dir"]);
						break;
				case "borrar":
						$this->borra_fichero($this->control["fichero"],$this->control["dir"]);
						break;
				case "borrar_carpeta":
						$this->borra_carpeta($this->control["carpeta"],$this->control["dir"]);
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
	Sube un fichero al servidor a la ruta ../userfiles/ y guarda una entrada en la BBDD
	*/
	function sube_e_inserta($arr_ficheros,$ruta){
		mysql_query("INSERT INTO ".$this->tabla." (id,nombre,es_carpeta) VALUES (null,'".$ruta.$arr_ficheros[$this->nombre_campo]['name']."',0);");
if (is_uploaded_file($arr_ficheros[$this->nombre_campo]['tmp_name']) && (($arr_ficheros[$this->nombre_campo]['type'] == "application/msword") || ($arr_ficheros[$this->nombre_campo]['type'] == "application/excel") || ($arr_ficheros[$this->nombre_campo]['type'] == "application/pdf")|| ($arr_ficheros[$this->nombre_campo]['type'] == "image/jpeg")|| ($arr_ficheros[$this->nombre_campo]['type'] == "image/pjpeg")|| ($arr_ficheros[$this->nombre_campo]['type'] == "image/gif")|| ($arr_ficheros[$this->nombre_campo]['type'] == "image/png"))){
			if (!move_uploaded_file($arr_ficheros[$this->nombre_campo]['tmp_name'],$ruta.$arr_ficheros[$this->nombre_campo]['name'])){
				echo "ERROR";
			}
		}
		$this->redireccionar($ruta);
	}
	
	/*
	Elimina un fichero y elimina su entrada en la BBDD
	*/
	function borra_fichero ($fichero,$ruta){
		if (file_exists($fichero)){
			$c = "SELECT id FROM noticias WHERE img_portada = '".substr(strrchr($fichero,"/"),1)."'";
			$r = mysql_query($c);
			while ($fila = mysql_fetch_array($r)){
				mysql_query("UPDATE noticias SET img_portada = '' WHERE id = ".$fila['id']."");
			}
			$c = "SELECT id FROM noticias WHERE img_seccion = '".substr(strrchr($fichero,"/"),1)."'";
			$r = mysql_query($c);
			while ($fila = mysql_fetch_array($r)){
				mysql_query("UPDATE noticias SET img_seccion = '' WHERE id = ".$fila['id']."");
			}
			unlink($fichero);
		}
		$this->redireccionar($ruta);
	}
	
	/*
	Elimina una carpeta (FALTA QUE ELIMINE EL CONTENIDO DE LA SUBCARPETA)
	*/
	function borra_carpeta ($carpeta,$ruta){
		if (file_exists($carpeta)){
			mysql_query("DELETE FROM ".$this->tabla." WHERE nombre='".$carpeta."'");
			rmdir($carpeta);
		}
		$this->redireccionar($ruta);
	}
	
	/*
	Crea una carpeta en el nivel actual
	*/
	function crea_carpeta ($nombre,$ruta){
		if (!file_exists($ruta.$nombre)){
			$nombre = str_replace(" ","_",$nombre);
			mysql_query("INSERT INTO ".$this->tabla." (id,nombre,es_carpeta) VALUES (null,'".$ruta.$nombre."',1);");
			echo mysql_error();
			mkdir($ruta.$nombre,0777);
		}
		$this->redireccionar($ruta);
	}
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($ruta){
		$cadena = $this->url_home."&ruta=".$ruta;
		header("Location: ".$cadena);
	}
	
	/*
	Redirecciona al listado de ficheros dentro del iframe
	*/
	function redireccionar_iframe (){
		header("Location: ".$this->url_iframe);
	}
	
}
?>