<?php


class Funciones{
	
	
	/////////////-------- VARIABLES---------/////////////////////////////////////////////////////////
	
	var $control;												//Array con los datos recibidos por GET y que nos indican la funcion a realizar
	var $datos;													//Array con los datos recibudos del formulario por POST
	var $tabla = "planillas";									//Tabla donde se insertarían los datos
	var $tablas = array(0=>"noticias", 1 => "modulosrosas");	//Tablas afectadas
	var $num_columnas = 7;										//Número de columnas y filas en una planilla
	var $max_por_fila = 10;										//Número máximo de elementos verticales en una fila o columna 
	var $url_home;
	
	
	
	
	/////////////-------- MÉTODOS---------///////////////////////////////////////////////////////////
	/*
	Funciones de inserción y actualización de las tablas planilla y planillas_elementos:
		1. insert($seccion)
		2. update($id,$datos)
		3. reprogramar_planilla
		4. duplicar()
		5. set_elemento_planificable()
		6. set_elemento_planificado($id_elemento,$id_)
		7. unset_elemento_planificable()
		8. actualizar_planillas($id_planilla = -1)
		9. get_elementos_planificados($id_planilla,$programados = 1)
		10. get_elementos_planificables($id_planilla)
		11. get_elementos_planilla($id_planilla)
																								   */
	/////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	
	
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->url_home = "index2.php?modulo=mod_planillas";
	}
	
	function start(){
		$accion = $this->control['accion'];
		if ($accion != ''){
			switch ($accion){
				case "insert":
						$id_planilla = $this->insert($this->control["seccion"]);
						$this->control["id"] = $id_planilla;
				case "update":
						$this->update($this->control["id"],$this->datos);
						$this->redireccionar($this->control["id"]);
						break;
				
				default:
						return false;
						break;
			}
		} else {
			return false;
		}
	}
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//
	//	Funciones de inserción y actualización de las tablas planilla y planillas_elementos
	//	
	//	
	//
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Inserta una nueva entrada en la tabla PLANILLAS con los valores por defecto
	// Devuelve el nuevo id
	//
	
	function insert($seccion){
		
		$c="INSERT INTO ".$this->tabla." (id,seccion,fecha_creacion) VALUE (null,".$seccion.", NOW())";
		mysql_query($c);
		
		return mysql_insert_id();
	}
	
	
	
	///////////////////////////////////////////////////////////////////////////////////////////////////7
	// 1. Actualiza los datos de una planilla en la tabla PLANILLAS
	// 2. Borra los datos de esa planilla en la tabla PLANILLAS_ELEMENTOS
	// 3. Inserta los nuevos datos en la tabla PLANILLAS_ELEMENTOS sin importar campos programado
	// 4. Inserta los elementos planificables
	// 5. Llama a reprogramar_planilla que escanea PLANILLAS_ELEMENTOS según el id_planilla
	//    y usando los campos fecha_publicacion y activo de $tablas actualiza el atributo programado
	//
	// $datos["listas"] es una lista con los siguentes separadores:
	// # separa columnas
	// @ separa hojas dentro de las columnas
	// / separa elementos dentro de las hojas
	// , separa atributos dentro de los elementos
	
	function update($id,$datos){
		
		$num_columnas = $this->num_columnas;
		
		// 1. Actualiza los datos de una planilla en la tabla PLANILLAS

		$query = "UPDATE ".$this->tabla." SET fecha_publicacion='".$datos['fecha_publicacion']."', tipo='".$datos['tipo']."' WHERE id=".$id;
		mysql_query($query);
		
		
		// 2. Borra los datos de esa planilla en la tabla PLANILLAS_ELEMENTOS
	
		$query = "DELETE FROM planillas_elementos WHERE id_planilla=".$id;
		mysql_query($query);
		
		
		// 3. Inserta los nuevos datos en la tabla PLANILLAS_ELEMENTOS sin importar campos programado
			
		$primero = 0;	
		$elementos = array();
		$query = "INSERT INTO planillas_elementos (id,id_planilla,id_elemento,tabla_elemento,planificado,bloqueado,programado";
		for ($i=0;$i<$num_columnas;$i++){
			$query .= ",orden".$i;
		}
		$query .= ") VALUES ";

		$corte_columnas = explode("#",$datos["listas"]);
		for ($i=0; $i < count($corte_columnas); $i++){
			$corte_hojas[$i] = explode("@",$corte_columnas[$i]);
			for ($j=0; $j < count($corte_hojas[$i]); $j++){
				$corte_elementos[$j] = explode("/",$corte_hojas[$i][$j]);
				for ($k=0; $k < count($corte_elementos[$j]); $k++){
					$corte_atributos[$k] = explode(",",$corte_elementos[$j][$k]);
					if (isset($corte_atributos[$k][2])){
						if($primero==0) $primero++;
						else $query .=",";
						
						$query .= "(null,".$id.",".$corte_atributos[$k][0].",'".$corte_atributos[$k][1]."',1,".$corte_atributos[$k][2].",0";
						for ($m=0;$m<$num_columnas;$m++){
							if ($m==$i) $query .= ",".((100*$j)+$k+1);
							else $query .= ",0"; 
						}
						$query .= ")";					
					}
				}			
			}			
		}
		
		if ($primero){
			mysql_query($query);
			
		// 4. Inserta los elementos planificables
		if ($datos["no_planificadas"] != ""){
			$query = "INSERT INTO planillas_elementos (id,id_planilla,id_elemento,tabla_elemento) VALUES ";
			$elementos = explode("/",$datos["no_planificadas"]);
			$i = 0;
			foreach ($elementos as $registro) {
				if ($registro != ""){
					if ($i) $query .= ",";
					else $i++;
					echo $registro."<br>";
					$elemento = explode(",",$registro);
					$query .= " (null,".$id.",".$elemento[0].",'".$elemento[1]."')";		
				}
				
			}
			mysql_query($query);
		}

			// 5. Llama a reprogramar_planilla
			$this->reprogramar_planilla($id);
			
		}
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Escanea PLANILLAS_ELEMENTOS según un id_planilla
	// y usando los campos fecha_publicacion y activo de $tablas actualiza el atributo programado
	
	function reprogramar_planilla($id_planilla){
		$query = "";
		$j = 0;
		foreach ($this->tablas as $tabla){
			if ($j>0) $query .= " UNION ";
			else $j++;
			$query .= " (SELECT planillas_elementos.id ";
			$query .= " FROM planillas_elementos, ".$tabla;
			$query .= " WHERE ".$tabla.".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '".$tabla."' AND  planillas_elementos.id_planilla=".$id_planilla." AND (".$tabla.".fecha_publicacion > NOW() OR ".$tabla.".activo = 0  ) )";
		}
		$r = mysql_query($query);
		if (mysql_num_rows($r) > 0){
			while ($elemento = mysql_fetch_assoc($r)){
				$query = "UPDATE planillas_elementos SET programado = 1 WHERE id = ".$elemento["id"];
				mysql_query($query);
			}		
		}
	}
	
		
	
	// Duplica una planilla
	
	function duplicar(){
		
	}
	
	// Función pública
	// Inserta como planificable un elemento desde otro módulo
	
	function set_elemento_planificable($id_planilla,$id_elemento,$tabla){
		$query = "SELECT id FROM planillas_elementos WHERE id_planilla=".$id_planilla." AND id_elemento=".$id_elemento." AND tabla_elemento like '".$tabla."'";
		$r = mysql_query($query);
		if (!(mysql_query($r)>0)){
			$query = "INSERT INTO planillas_elementos (id,id_planilla,id_elemento,tabla_elemento,planificado,programado) ";
			$query .= " VALUES (null,".$id_planilla.",".$id_elemento.",'".$tabla."',0,0)";
			mysql_query($query);
		}
	}
	
	// Función pública
	// Inserta como planificado un elemento desde otro módulo con los atributos planificado y programado a 1
	// Al final llama a actualizar_planillas
	
	function set_elemento_planificado($id_planilla,$id_elemento,$tabla){
		$query = "SELECT id FROM planillas_elementos WHERE id_planilla=".$id_planilla." AND id_elemento=".$id_elemento." AND tabla_elemento like '".$tabla."'";
		$r = mysql_query($query);
		if (!(mysql_query($r)>0)){
			$query = "UPDATE planillas_elementos SET orden1 = orden1 + 1 WHERE id_planilla=".$id_planilla." AND orden1 > 0 AND orden1 < 100";
			mysql_query($query);
			$query = "INSERT INTO planillas_elementos (id,id_planilla,id_elemento,tabla_elemento,orden1,planificado,programado) ";
			$query .= " VALUES (null,".$id_planilla.",".$id_elemento.",'".$tabla."',1,1,1)";
			mysql_query($query);
		}
		$this->actualizar_planilla($id_planilla);
	}
	
	
	// Función pública
	// Elimina un elemento de una planilla desde otro módulo
	
	function unset_elemento_planificable($id_planilla,$id_elemento,$tabla){
		
		$num_columnas = $this->num_columnas;
		
		$this->actualizar_planilla($id_planilla,$id_elemento,$tabla);
		
		//die;
		
		$query = "SELECT planificado";
		for ($i=0;$i<$num_columnas;$i++){
				$query .= ",orden".$i;
		}
		$query .= " FROM planillas_elementos WHERE id_planilla=".$id_planilla." AND id_elemento=".$id_elemento." AND tabla_elemento like '".$tabla."'";
		$r = mysql_query($query);
		
		if ((mysql_num_rows($r)>0)){
			$ordenes = mysql_fetch_assoc($r);
			if($ordenes["planificado"]==1){
				for($i=0;$i<$num_columnas;$i++){
					if($ordenes["orden".$i]>0) {
						$nivel = round($ordenes["orden".$i]/100);
						break;
					}
				}
				$query = "UPDATE planillas_elementos SET orden".$i." = orden".$i." - 1 WHERE id_planilla=".$id_planilla." AND orden".$i." > ".$ordenes["orden".$i]." AND orden".$i." < ".(($nivel+1)*100);
				mysql_query($query);
			}
			$query = "DELETE FROM planillas_elementos WHERE id_planilla=".$id_planilla." AND id_elemento=".$id_elemento." AND tabla_elemento like '".$tabla."' ";
			mysql_query($query);
		}
	}
	
	
	
	// Función pública
	// Elimina un elemento de una planilla desde otro módulo
	
	function unset_elemento_planificado($id_planilla,$id_elemento,$tabla){
		
		$this->unset_elemento_planificable($id_planilla,$id_elemento,$tabla);
		$this->set_elemento_planificable($id_planilla,$id_elemento,$tabla);
	}
	
	
	// Actualiza la tabla ELEMENTOS_PLANILLAS
	// Pone a 0 el atributo programado de los elementos con fecha_publicacion < NOW() y atributo activo a 1 
	// Si pone alguno a 0, desplaza hacia abajo los elementos inferiores no bloqueados
	// Si pone alguno a 1, desplaza hacia arriba los elementos inferiores no bloqueados
	//
	// 
	
	function actualizar_planilla($id_planilla,$id_elemento = -1,$tabla = ""){
		
		echo $id_planilla.$tabla;
		
		$num_columnas = $this->num_columnas;
		$query_0 = "UPDATE planillas_elementos SET programado=0 WHERE id=-1";  // Para poner a 0 el atributo programado
		
		
		// Actualiza elementos planificables
		/*
		$elementos = $this->get_elementos_planificables($id_planilla); // array con los elementos de la planilla
		
		foreach ($elementos as $ele){
			if ($ele["fecha_publicacion"] < date('Y-m-d H:i:s') && $ele["activo"] == 1 )
						$a_activar = 1;
					else $a_activar = 0;
					
					if($ele["programado"] == $a_activar){ // Cambiar estado
						if ($elementos[$i][$j]["programado"]==1){
							// Se pone a 0 y se bajan los elementos inferiores no bloqueados
							$query_0 .= " OR id=".$elementos[$i][$j]["planillas_elementos_id"] ;
						}
					}
		}	
		*/
		
		
		// Actualiza elementos planificados
		
		$elementos = $this->get_elementos_planificados($id_planilla); // array con los elementos de la planilla
		
		if ($id_elemento==-1){ 
			// No se está tratando un elemento en concreto ==> Se hace regularmente y sólo pueden activarse elementos programados
			//$query_1 = "UPDATE planillas_elementos SET programado=1 WHERE id=-1";  // Para poner a 1 el atributo programado => Ya no se usa aquí			
								
			for ($i=0; $i<$num_columnas; $i++){
				if (!isset($elementos[$i])) $elementos[$i] = array();
				$posiciones = array();
				$desplazamiento = 0;
				
				for($j=0; $j < count($elementos[$i]); $j++){
					
					if ($elementos[$i][$j]["fecha_publicacion"] < date('Y-m-d H:i:s') && $elementos[$i][$j]["activo"] == 1 )
						$a_activar = 1;
					else $a_activar = 0;
					
					if($elementos[$i][$j]["programado"] == $a_activar){ // Cambiar estado
						if ($elementos[$i][$j]["programado"]==1){
							// Se pone a 0 y se bajan los elementos inferiores no bloqueados
							$query_0 .= " OR id=".$elementos[$i][$j]["planillas_elementos_id"] ;
							if ($elementos[$i][$j]["bloqueado"]==0)			
								$desplazamiento++;							
						}
					}
								
					$elemento["planillas_elementos_id"] = $elementos[$i][$j]["planillas_elementos_id"];
					$elemento["nivel"] = $this->obtener_nivel($elementos,$i,$j,$posiciones,$desplazamiento);

					if (($elementos[$i][$j]["bloqueado"]==1)){
						$this->colocar_bloqueado($elemento,$posiciones,$desplazamiento);	
					}
					else {
						$this->meter_en_hueco($elemento,$posiciones,$desplazamiento);
					}
				}
				$nivel_actual = -1;
				foreach ($posiciones as $ele){
					if (is_array($ele)){
						if ($ele["nivel"] != $nivel_actual){
							$nivel_actual = $ele["nivel"];
							$pos=1;
						}
						$query_update = "UPDATE planillas_elementos SET orden".$i."=".((($nivel_actual)*100)+$pos)."  WHERE id=".$ele["planillas_elementos_id"];
						mysql_query($query_update);
						echo $query_update."<br>";
						$pos++;
					}
				}
			}			
		}
		else{	// Se activa cuando el usuario realiza una acción con un elemento
			
			$query_1 = "UPDATE planillas_elementos SET programado=1 WHERE id=-1";  // Para poner a 1 el atributo programado => Ya no se usa aquí			
		
			for ($i=0; $i<$num_columnas; $i++){
				if (!isset($elementos[$i])) $elementos[$i] = array();
				$posiciones = array();
				$desplazamiento = 0;
				
				for($j=0; $j < count($elementos[$i]); $j++){

					if ($elementos[$i][$j]["fecha_publicacion"] < date('Y-m-d H:i:s') && $elementos[$i][$j]["activo"] == 1 )
						$a_activar = 1;
					else $a_activar = 0;
										
					if (($elementos[$i][$j]["id"]==$id_elemento) && 
						($elementos[$i][$j]["tabla_elemento"]==$tabla) && 
						($elementos[$i][$j]["programado"]==0)){
							
							$query_1 .= " OR id=".$elementos[$i][$j]["planillas_elementos_id"] ;
							$elementos[$i][$j]["bloqueado"]=0;
							$desplazamiento--;	
							$desnivel = -1;
					}
								
					$elemento["planillas_elementos_id"] = $elementos[$i][$j]["planillas_elementos_id"];
					$elemento["nivel"] = $this->obtener_nivel($elementos,$i,$j,$posiciones,$desplazamiento);
	
	
					if (($elementos[$i][$j]["bloqueado"]==1)){
						$this->colocar_bloqueado($elemento,$posiciones,$desnivel);	
					}
					else {
						$this->meter_en_hueco($elemento,$posiciones,$desplazamiento,$elementos[$i][$j]["programado"]);
					}
				}
				$nivel_actual = -1;
				foreach ($posiciones as $ele){
					if (is_array($ele)){
						if ($ele["nivel"] != $nivel_actual){
							$nivel_actual = $ele["nivel"];
							$pos=1;
						}
						$query_update = "UPDATE planillas_elementos SET orden".$i."=".((($nivel_actual)*100)+$pos)."  WHERE id=".$ele["planillas_elementos_id"];
						mysql_query($query_update);
						echo $query_update."<br>";
						$pos++;
					}
				}
			}
			mysql_query($query_1);			
		} // FIN Se activa cuando el usuario realiza una acción con un elemento
		
		mysql_query($query_0);
	}	
	
	
	function obtener_nivel($elementos,$i,$j,$posiciones,$desplazamiento){

		//Mirará otros niveles y actualizara $nivel
		
		$nivel = round($elementos[$i][$j]["orden".$i] / 100);
		
		// Si el elemento esta bloqueado o no está en las columnas 1, 2 ó 3 no cambia de nivel
		
		if (($elementos[$i][$j]["bloqueado"]==1) || ($i==0) || ($i==4) || ($i==5) || ($i==6))  return $nivel;
		
		
		// Si desplazamiento no > 0 los objetos no se desplazan para abajo pero lo pueden hacer para arriba
		
		if(($nivel>0) && ($desplazamiento<1)) {
			if ($this->anterior_bloqueado($elementos,$nivel-1,$i)){
					// Columna 1 => Miro si está bloqueado el primero de Columna 1 y 2 - Columna 1,2 y 3 de la hoja anterior
					// Columna 1 => Miro si está bloqueado el primero de Columna 1 y 2 - Columna 2 y 3 - Columna 1,2 y 3 de la hoja anterior
					// Columna 1 => Miro si está bloqueado el primero de Columna 2 y 3 - Columna 1,2 y 3 de la hoja anterior				
					$nivel = $nivel*(-1); // Si $nivel negativo, puede subir de nivel
			}
		}
		// Puede desplazarse para arriba
		else if(($nivel<4) && ($desplazamiento>0)) {

				for($k=$j+1;$k<=count($elementos[$i]);$k++){
					if (isset($elementos[$i][$k]) && (($elementos[$i][$k]["bloqueado"]==1) || ($elementos[$i][$k]["programado"]==1)) ){
						echo "";
					}
					else{
						if (!isset($elementos[$i][$k]) ||
							(isset($elementos[$i][$k]) && ($nivel < round($elementos[$i][$k]["orden".$i] / 100)))){
								if (isset($elementos[$i][$k])) $ultimo_nivel = round($elementos[$i][$k]["orden".$i] / 100);
								else $ultimo_nivel=4;
								// Columna 1 => Miro si está bloqueado el primero de Columna 1 y 2 - Columna 1,2 y 3 de la hoja anterior
								// Columna 1 => Miro si está bloqueado el primero de Columna 1 y 2 - Columna 2 y 3 - Columna 1,2 y 3 de la hoja anterior			
								// Columna 1 => Miro si está bloqueado el primero de Columna 2 y 3 - Columna 1,2 y 3 de la hoja anterior
								$nivel = $this->siguente_bloqueado($elementos,$nivel,$ultimo_nivel,$i);
						}
						break;
					}					
				}
		}
		return $nivel;	
	}
	
	function meter_en_hueco($elemento,&$posiciones,&$desplazamiento,$programado=0){
		
		if ($elemento["nivel"]<0){
			$elemento["nivel"]=-$elemento["nivel"];
			if($desplazamiento<0){
				$nivel_posible = $elemento["nivel"]-1;
				if (($desplazamiento<0)&& ($programado==0)){
					$desplazamiento++;
					$elemento["nivel"] = $elemento["nivel"]-1;
				}
			}
			else $nivel_posible = $elemento["nivel"];
		}
		else $nivel_posible = $elemento["nivel"];
		
		if (count($posiciones)==0) $posiciones[0] = $elemento;
		else{
			$metido = 0;
			for ($m=0;$m<count($posiciones);$m++){
				if (!is_array($posiciones[$m])){
						if ($posiciones[$m]==$nivel_posible){
							$posiciones[] = $elemento["nivel"];
							$elemento["nivel"]=$nivel_posible;
							$posiciones[$m] = $elemento;							
							$metido=1;
							break;	
						}					
				}				
			}
			if ($metido==0){
				$posiciones[] = $elemento;
			}
		}
	}
	
	function colocar_bloqueado($elemento,&$posiciones,&$desplazamiento){
		
		if (($desplazamiento==0) || (isset($posiciones[count($posiciones)-1]) && $posiciones[count($posiciones)-1]["nivel"] < $elemento["nivel"] )){
			$posiciones[] = $elemento;
			$desplazamiento = 0;
		}		
		else if($desplazamiento<1){
			for($desplazamiento;$desplazamiento<0;$desplazamiento++){
				$posiciones[] = $elemento["nivel"];
			}
			$posiciones[] = $elemento;
		}
		else {			
			$max = count($posiciones)-1;
			for($m = 0;$m < $desplazamiento; $m++){
				$aux = $posiciones[$max-$m];
				$posiciones[($max-$m)+1] = $aux;
			}
			$posiciones[($max-$m)+1] = $elemento;
		}
	}
	
	function anterior_bloqueado($nivel,$columna){
		return 1;
	}
	
	
	// Devuelve el nivel hasta el que tiene que bajar un elemento
	
	function siguente_bloqueado($elementos,$nivel,$nivel_ultimo,$columna){
		
		echo "@".$columna."@";
		
		$nivel_aux = $nivel;
		
		$nivel_aux1 = 1000;
		$nivel_aux2 = 1000;
		$nivel_aux3 = 1000;
		
		
		if (isset($elementos[6])){
			for($kk=0;$kk<count($elementos[6]);$kk++){
				if (round($elementos[6][$kk]["orden6"] / 100)>=$nivel_ultimo) break;
				if(($nivel_aux)==round($elementos[6][$kk]["orden6"] / 100)){
					echo "c";
					if($elementos[6][$kk]["bloqueado"]){
						$nivel_aux++;
						$nivel_aux1 = $nivel_aux;
					}
					else
						break;			
				}
			}
		}
		
		$nivel_aux = $nivel;
		
		if((($columna==1) || ($columna==2)) && (isset($elementos[4]))){
			echo "bof".$nivel_ultimo;
			for($kk=0;$kk<count($elementos[4]);$kk++){
				if (round($elementos[4][$kk]["orden4"] / 100)>=$nivel_ultimo) break;
				if(($nivel_aux)==round($elementos[4][$kk]["orden4"] / 100)){
					if($elementos[4][$kk]["bloqueado"]){
						$nivel_aux++;
						$nivel_aux2 = $nivel_aux;
					}
					else
						break;			
				}
			}
		}
		
		$nivel_aux = $nivel;
		
		if((($columna==3) || ($columna==2)) && (isset($elementos[5]))){
			for($kk=0;$kk<count($elementos[5]);$kk++){
				if (round($elementos[5][$kk]["orden5"] / 100)>=$nivel_ultimo) break;
				if(($nivel_aux)==round($elementos[5][$kk]["orden5"] / 100)){
					if($elementos[5][$kk]["bloqueado"]){
						$nivel_aux++;
						$nivel_aux3 = $nivel_aux;
					}
					else
						break;			
				}
			}
		}
		
		echo $nivel_aux1." --_ ".$nivel_aux2." -_- ".$nivel_aux3."<br>";
		
		if ($nivel_aux1<$nivel_aux2)
			$nivel_aux=$nivel_aux1;
		else 
			$nivel_aux=$nivel_aux2;
		if ($nivel_aux3<$nivel_aux)
			$nivel_aux=$nivel_aux3;
		if ($nivel_aux==1000)
			return $nivel;
		else return $nivel_aux;

	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////	////////////////
	//
	//	Funciones para obtener información de las tablas planilla, planillas_elementos
	//	y las tablas el archivo de configuración
	//	
	////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	// Devuelve los elementos planificados en una planilla sacados de todas las tablas 	configuradas
	// Si programados se pone a 0 devuelve sólo los elementos no programados (Ya publicados)

	
	function get_elementos_planificados($id_planilla,$programados = 1){
		
		$num_columnas = $this->num_columnas;
		$resultado = array();
		$query = "";
		$j = 0;
		
		if ($programados) $where_prog = "";
		else $where_prog="AND planillas_elementos.programado = 0";
		
		foreach ($this->tablas as $tabla){
			if ($j>0) $query .= " UNION ";
			else $j++;
			$query .= " (SELECT planillas_elementos.id as planillas_elementos_id, ".$tabla.".id,".$tabla.".titulo,".$tabla.".fecha_publicacion,".$tabla.".activo, planillas_elementos.tabla_elemento";
			for ($i=0;$i<$num_columnas;$i++){
				$query .= ",planillas_elementos.orden".$i;
			}
			$query .= ",planillas_elementos.programado,planillas_elementos.bloqueado FROM planillas_elementos, ".$tabla;
			$query .= " WHERE ".$tabla.".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '".$tabla."' AND  planillas_elementos.id_planilla=".$id_planilla." AND planillas_elementos.planificado=1 ".$where_prog." )";
		}
		for ($i=0;$i<$num_columnas;$i++){
				if($i>0) $query .= ",";
				else $query .= " ORDER BY ";
				$query .= "orden".$i;
		}
		$r = mysql_query($query);
		
		if (mysql_num_rows($r) > 0){
			$fila_actual=$num_columnas-1;
			while ($fila = mysql_fetch_assoc($r)){
				if ($fila["orden".$fila_actual]==0){
					for($k=$fila_actual-1;$k>=0;$k--){
						if ($fila["orden".$k] > 0){
							$fila_actual=$k;
							break;
						}
					}					
				}
				$resultado[$fila_actual][] = $fila;
			}
		}	
		return $resultado;
	}
	
	// Devuelve los elementos planificables de una planilla sacados de todas las tablas configuradas
	
	function get_elementos_planificables($id_planilla){
		
		$resultado = array();
		$query = "";
		$j=0;
		foreach ($this->tablas as $tabla){
			if ($j>0) $query .= " UNION ";
			else $j++;
			$query .= "(SELECT ".$tabla.".id,".$tabla.".titulo, planillas_elementos.tabla_elemento, planillas_elementos.bloqueado, planillas_elementos.programado FROM planillas_elementos, ".$tabla;
			$query .= " WHERE ".$tabla.".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '".$tabla."' AND  planillas_elementos.id_planilla=".$id_planilla." AND planillas_elementos.planificado=0)";
		}
		$query .= " ORDER BY tabla_elemento";

		$r = mysql_query($query);
		if (mysql_num_rows($r) > 0){
			while ($fila = mysql_fetch_assoc($r)){
				$resultado[] = $fila;		
			}
		}	
		return $resultado;
		
	}
	
	// Devuelve los elementos planificados y no programados de una planilla sacados de todas las tablas configuradas
	// Para usar en lo público o en previsualización 
	
	function get_elementos_planilla($id_planilla){
		
		$num_columnas = $this->num_columnas;
		$resultado = array();
		$query = "";
		$j = 0;
		foreach ($this->tablas as $tabla){
			if ($j>0) $query .= " UNION ";
			else $j++;
			$query .= " (SELECT ".$tabla.".id,".$tabla.".titulo, planillas_elementos.tabla_elemento";
			for ($i=0;$i<$num_columnas;$i++){
				$query .= ",planillas_elementos.orden".$i;
			}
			$query .= " FROM planillas_elementos, ".$tabla;
			$query .= " WHERE ".$tabla.".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '".$tabla."' AND  planillas_elementos.id_planilla=".$id_planilla." AND planillas_elementos.planificado=0 AND planillas_elementos.programado = 0 )";
		}
		for ($i=0;$i<$num_columnas;$i++){
				if($i>0) $query .= ",";
				else $query .= " ORDER BY ";
				$query .= "orden".$i;
		}

		$r = mysql_query($query);
		if (mysql_num_rows($r) > 0){
			$fila_actual=0;
			while ($fila = mysql_fetch_assoc($r)){
				if ($fila["orden".$fila_actual]==0){
					for($k=$fila_actual+1;$k<$num_columnas;$k++){
						if ($fila["orden".$k] > 0){
							$fila_actual=$k;
							break;
						}
					}					
				}
				$resultado[$fila_actual][] = $fila;
			}
		}	
		return $resultado;
	}

	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	////////////////////////////     		 BASURA					//////////////////////////////////////////
	
	
	// Devuelve los elementos planificados y no programados de una planilla sacados de todas las tablas configuradas
	
	function _get_elementos_planilla($id_planilla){
		
		$num_columnas = $this->num_columnas;
		$resultado = array();
		$query = "";
		
		for ($i=0;$i<$num_columnas;$i++){
			$j = 0;
			if ($i>0) $query .= " UNION  ( ";
			else $query .= " ( ";
			foreach ($this->tablas as $tabla){
				if ($j>0) $query .= " UNION ";
				else $j++;
				$query .= " (SELECT ".$tabla.".id,".$tabla.".titulo, planillas_elementos.tabla_elemento,planillas_elementos.orden".$i." as elorden FROM planillas_elementos, ".$tabla;
				$query .= " WHERE ".$tabla.".id=planillas_elementos.id_elemento AND planillas_elementos.tabla_elemento = '".$tabla."' AND  planillas_elementos.id_planilla=".$id_planilla." AND planillas_elementos.orden".$i." > 0 AND planillas_elementos.programado = 0) ";
			}
			$query .= " ORDER BY elorden  ) ";
		}

		$r = mysql_query($query);
		if (mysql_num_rows($r) > 0){
			while ($fila = mysql_fetch_assoc($r)){
				$resultado[$i][] = $fila;		
			}
		}	
		return $resultado;
	}
	
	function redireccionar ($id_planilla,$pagina = ""){
		header("Location: ".$this->url_home."&accion=editar&id=".$id_planilla."");
	}
	
}

/*
function actualizar_planilla($id_planilla,$id_elemento = -1,$tabla = ""){
		
		$num_columnas = $this->num_columnas;
		
		$elementos = $this->get_elementos_planificados($id_planilla); // array con los elementos de la planilla
		
		if ($id_elemento==-1){ 
			// No se está tratando un elemento en concreto ==> Se hace regularmente y sólo pueden activarse elementos programados
			//$query_1 = "UPDATE planillas_elementos SET programado=1 WHERE id=-1";  // Para poner a 1 el atributo programado => Ya no se usa aquí			
			
			
			for ($i=0; $i<$num_columnas; $i++){
				if (!isset($elementos[$i])) $elementos[$i] = array();
				$posiciones = array();
				$desplazamiento = 0;
				
				for($j=0; $j < count($elementos[$i]); $j++){
					
					if ($elementos[$i][$j]["fecha_publicacion"] < date('Y-m-d H:i:s') && $elementos[$i][$j]["activo"] == 1 )
						$a_activar = 1;
					else $a_activar = 0;
					
					if($elementos[$i][$j]["programado"] == $a_activar){ // Cambiar estado
						$elementos[$i][$j]["bloqueado"]=0;
						if ($elementos[$i][$j]["programado"]==1){
							// Se pone a 0 y se bajan los elementos inferiores no bloqueados
							$query_0 .= " OR id=".$elementos[$i][$j]["planillas_elementos_id"] ;			
							//$elementos[$i][$j]["bloqueado"]=0;
							$desplazamiento++;							
						}
						else{
							// Se pone a 1 y se suben los elementos inferiores no bloqueados
							$query_1 .= " OR id=".$elementos[$i][$j]["planillas_elementos_id"] ;
							$elementos[$i][$j]["bloqueado"]=0;
							$desplazamiento--;
						}
					}
								
					$elemento["planillas_elementos_id"] = $elementos[$i][$j]["planillas_elementos_id"];
					$elemento["nivel"] = $this->obtener_nivel($elementos,$i,$j,$posiciones,$desplazamiento);
	
					if (($elementos[$i][$j]["bloqueado"]==1)){
						$this->colocar_bloqueado($elemento,$posiciones,$desplazamiento);	
					}
					else {
						$this->meter_en_hueco($elemento,$posiciones,$desplazamiento);
					}
				}
				$nivel_actual = -1;
				foreach ($posiciones as $ele){
					if (is_array($ele)){
						if ($ele["nivel"] != $nivel_actual){
							$nivel_actual = $ele["nivel"];
							$pos=1;
						}
						$query_update = "UPDATE planillas_elementos SET orden".$i."=".((($nivel_actual)*100)+$pos)."  WHERE id=".$ele["planillas_elementos_id"];
						mysql_query($query_update);
						echo $query_update."<br>";
						$pos++;
					}
				}
			}
			mysql_query($query_0);
			mysql_query($query_1);
		
			
		}
		else{	// Se activa cuando el usuario realiza una acción con un elemento
			
		}
		
		
		$cadena = "";
		
		for ($i=0; $i<$num_columnas; $i++){
			if (!isset($elementos[$i])) $elementos[$i] = array();
			$posiciones = array();
			$desplazamiento = 0;
			
			for($j=0; $j < count($elementos[$i]); $j++){
				
				if ($elementos[$i][$j]["fecha_publicacion"] < date('Y-m-d H:i:s') && $elementos[$i][$j]["activo"] == 1 )
					$a_activar = 1;
				else $a_activar = 0;
				
				if (($elementos[$i][$j]["id"]==$id_elemento) && 
					($elementos[$i][$j]["tabla_elemento"]==$tabla) && 
					($elementos[$i][$j]["programado"]==0)){
						$query_1 .= " OR id=".$elementos[$i][$j]["planillas_elementos_id"] ;
						$elementos[$i][$j]["bloqueado"]=0;
						$desplazamiento--;	
				}
				else{				
					if($elementos[$i][$j]["programado"] == $a_activar){ // Cambiar estado
						$elementos[$i][$j]["bloqueado"]=0;
						if ($elementos[$i][$j]["programado"]==1){
							// Se pone a 0 y se bajan los elementos inferiores no bloqueados
							$query_0 .= " OR id=".$elementos[$i][$j]["planillas_elementos_id"] ;			
							//$elementos[$i][$j]["bloqueado"]=0;
							$desplazamiento++;							
						}
						else{
							// Se pone a 1 y se suben los elementos inferiores no bloqueados
							$query_1 .= " OR id=".$elementos[$i][$j]["planillas_elementos_id"] ;
							$elementos[$i][$j]["bloqueado"]=0;
							$desplazamiento--;
						}
					}
				}				
				$elemento["planillas_elementos_id"] = $elementos[$i][$j]["planillas_elementos_id"];
				$elemento["nivel"] = $this->obtener_nivel($elementos,$i,$j,$posiciones,$desplazamiento);

				if (($elementos[$i][$j]["bloqueado"]==1)){
					$this->colocar_bloqueado($elemento,$posiciones,$desplazamiento);	
				}
				else {
					$this->meter_en_hueco($elemento,$posiciones,$desplazamiento);
				}
			}
			$nivel_actual = -1;
			foreach ($posiciones as $ele){
				if (is_array($ele)){
					if ($ele["nivel"] != $nivel_actual){
						$nivel_actual = $ele["nivel"];
						$pos=1;
					}
					$query_update = "UPDATE planillas_elementos SET orden".$i."=".((($nivel_actual)*100)+$pos)."  WHERE id=".$ele["planillas_elementos_id"];
					mysql_query($query_update);
					echo $query_update."<br>";
					$pos++;
				}
			}
		}
		mysql_query($query_0);
		mysql_query($query_1);
	}
*/