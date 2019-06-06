<?php

class Funciones{

	var $control;//Array con los datos recibidos por GET y que nos indican la funciÃ³n a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $url_home;
	
	function funciones($control_recibido,$datos_recibidos) {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->url_home = "index2.php?modulo=mod_configuracion";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "save":
						$this->saveVariables($this->datos);
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
	Vacía el fichero configuracion.php y lo vuelve a completar con las variables del sistema y su valor.
	*/
	function saveVariables ($variables){
		$df = fopen($variables['path_absoluta']."/configuracion.php","w");
		fwrite($df,"<?php\n");
		foreach($variables as $clave => $valor){
			fwrite($df,'$config_'.$clave.' = "'.$valor.'";'."\n");
		}
		fwrite($df,"?>");
		echo "
		<script>
			alert('Se ha guardado la nueva configuración');
			document.location.href='".$this->url_home."';
		</script>";
	}
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar (){
		header("Location: ".$this->url_home);
	}
	
}

?>