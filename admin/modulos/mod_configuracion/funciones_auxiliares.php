<?php

function getVariables ($df){
    while (!feof($df)){
        $linea = fgets($df,999);
        /* La expresion regular indica que se deben coger todas las lineas que empiecen por "$config_" */
        if (ereg('\$config\_*',$linea)){
        	/* Divido en una matriz por el símbolo "=" */
        	$nombre_valor = explode("=",$linea);
        	/* Le quito el $ y los espacios delante y detras*/
        	$clave = trim(substr($nombre_valor[0],1));
        	/* Le quito las "" y el ; para obtener el valor de la variable del fichero de configuración*/
        	$valor = trim(str_replace(";","",str_replace("\"","",$nombre_valor[1])));
        	/* Inserto en una matriz asociativa los campos lcave y valor*/
        	$configuracion[$clave]=$valor;
        }
    }
    return $configuracion;
}

?>