<?php

class Funciones{
	
	var $control;//Array con los datos recibidos por GET y que nos indican la función a realizar
	var $datos;//Array con los datos recibudos del formulario por POST
	var $tabla;//Tabla donde se insertarán los daros
	var $url_home;
	var $extrensiones_permitidas = array("jpg","gif","png","flv","avi","mpg","mov");
	
	function funciones($control_recibido = "",$datos_recibidos = "") {
		$this->control = $control_recibido;
		$this->datos = $datos_recibidos;
		$this->tabla = "newsletters";
		$this->url_home = "index2.php?modulo=mod_newsletter";
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
				case "obtener_portada":
						$this->obtener_portada();
						break;
				case "estado":
						$this->cambia_estado($this->control['id'],$this->control['campo'],$this->control['page']);
						break;
				case "duplicar":
						$this->duplicar($this->datos);
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
	
		$c="INSERT INTO ".$this->tabla." ( id";
		foreach ($arr_datos as $clave => $valor){
		  if (($clave != "redireccion") && ($clave != "listas"))
			$c.=",".$clave;
		}
		$c.=") VALUES (null";
		foreach ($arr_datos as $clave => $valor){
		  if (($clave != "redireccion") && ($clave != "listas")){
		  	if ($clave == "hora_envio")
				 $c .= ",'".strtotime($valor)."' ";
			else if (($clave == "fecha_creacion") || ($clave == "fecha_modificacion")){
				 $c .= ",NOW()";
			}else if (is_numeric($valor)){
					$c.=",".$valor;
				  } else {
					$c.=",'".addslashes($valor)."'";
				  }
		  }
		}
		$c.=");";
		mysql_query($c);
		$id = mysql_insert_id();

		//INSERTAMOS ELEMENTOS EN LA PLANILLA DE LA NEWSLETTER
		$this->inserta_elementos_en_planilla($id,$arr_datos['listas']);

		if ($arr_datos["redireccion"]==-1) echo "";
		else if ($arr_datos["redireccion"]==1) $this->redireccionar2($id);
			 else $this->redireccionar();
		return $id;
	}
	
	function update($arr_datos,$id){
		$iteracion = 1;
		$c="UPDATE ".$this->tabla." SET ";
		foreach ($arr_datos as $clave => $valor){
		  if (($clave != "redireccion") && ($clave != "listas")){
			if ($iteracion == 1)	
			  	if (is_numeric($valor))
					$c.=$clave."=".$valor;
				else $c.=$clave."='".addslashes($valor)."'";
			else if ($clave == "hora_envio")
				 	$c .= ",".$clave."=".strtotime($valor); 
				 else if (ereg("fecha_*",$clave))
						$c.=",".$clave."='".formatea_fecha($valor)."'";
				   	  else if (is_numeric($valor))
							$c.=",".$clave."=".$valor;
							else $c.=",".$clave."='".addslashes($valor)."'";
		  $iteracion ++;
		  }
		}
		$c.=" WHERE id=".$id;
		mysql_query($c);
		echo mysql_error();
		//INSERTAMOS ELEMENTOS EN LA PLANILLA DE LA NEWSLETTER
		$this->inserta_elementos_en_planilla($id,$arr_datos['listas']);
		
		if ($arr_datos["redireccion"]==1) $this->redireccionar2($id);
		else $this->redireccionar();
	}
	
	/*
	Borra una newsletter incluyendo los enlaces que la relacionan con las noticias
	*/
	function delete($elementos){
		foreach($elementos as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
			$c="DELETE FROM ".$this->tabla." WHERE id = ".$valor.";";
			mysql_query($c);
			$c="DELETE FROM noticias_newsletter_relacionadas WHERE id_newsletter = ".$valor.";";
			mysql_query($c);
		  }
		}
		$this->redireccionar();
	}
	
	function inserta_elementos_en_planilla ($id,$datos){
		$query = "DELETE FROM noticias_newsletter_relacionadas WHERE id_newsletter=".$id;
		mysql_query($query);
		$query = "DELETE FROM banners_newsletter_relacionadas WHERE id_newsletter=".$id;
    mysql_query($query);
		
		$num_columnas = 2;
		$primero = 0;	
		$elementos = array();
		$query = "INSERT INTO noticias_newsletter_relacionadas (id,id_newsletter,id_elemento,tabla_elemento";
		for ($i=1;$i<=$num_columnas;$i++){
			$query .= ",orden".$i;
		}
		$query .= ") VALUES ";
		$corte_columnas = explode("#",$datos);
		for ($i=0; $i < count($corte_columnas); $i++){
				$corte_elementos[$i] = explode("/",$corte_columnas[$i]);
				for ($k=0; $k < count($corte_elementos[$i]); $k++){
					$corte_atributos[$k] = explode(",",$corte_elementos[$i][$k]);
					if (isset($corte_atributos[$k][1])){
						if($primero==0) $primero++;
						else $query .=",";
						
						$query .= "(null,".$id.",".$corte_atributos[$k][0].",'".$corte_atributos[$k][1]."_newsletter'";
						for ($m=1;$m<=$num_columnas;$m++){
							if ($m==($i+1)) $query .= ",".($k+1);
							else $query .= ",0"; 
						}
						$query .= ")";
					}		
				}
		}
//		die($query);
		mysql_query($query);
	}
	
	function get_elementos_planificados($id_newsletter,$tablas = array("noticias_newsletter","banners_newsletter")){
		$num_columnas = 2;
		$resultado = array();
		$query = "";
		$j = 0;
		if($id_newsletter=="") $id_newsletter=-1;
		
		foreach ($tablas as $tabla){
			if ($j>0) $query .= " UNION ";
			else $j++;
			$query .= " SELECT " . ( ($tabla == "noticias_newsletter") ? $tabla . ".id_original as original, " : $tabla . ".id as id, " ) . "nnr.id as nnr_id, ".$tabla.".id,".$tabla.".titulo, nnr.tabla_elemento";
			for ($i=1;$i<=$num_columnas;$i++){
				$query .= ",nnr.orden".$i;
			}
			$query .= " FROM noticias_newsletter_relacionadas nnr, ".$tabla;
			$query .= " WHERE ".$tabla.".id=nnr.id_elemento AND nnr.tabla_elemento = '".$tabla."' AND  nnr.id_newsletter=".$id_newsletter."";
		}
		for ($i=1;$i<=$num_columnas;$i++){
				if($i>1) $query .= ",";
				else $query .= " ORDER BY ";
				$query .= "orden".$i;
		}
		
		$r = mysql_query($query);

		if (mysql_num_rows($r) > 0){
			$fila_actual=$num_columnas;
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
	
	function get_elementos_planificables($id_newsletter){
		$tablas = array("noticias_newsletter","banners_newsletter");
		$num_columnas = 2;
		$resultado = array();
		$query = "";
		$j = 0;
		
		foreach ($tablas as $tabla){
			if ($j>0) $query .= " UNION ";
			else $j++;
			$query .= " (SELECT nnr.id as nnr_id, ".$tabla.".id,".$tabla.".titulo, nnr.tabla_elemento";
			for ($i=1;$i<=$num_columnas;$i++){
				$query .= ",nnr.orden".$i;
			}
			$query .= " FROM noticias_newsletter_relacionadas nnr, ".$tabla;
			$query .= " WHERE ".$tabla.".id=nnr.id_elemento AND nnr.tabla_elemento = '".$tabla."' AND  nnr.id_newsletter=".$id_newsletter.")";
		}
		for ($i=1;$i<=$num_columnas;$i++){
				if($i>1) $query .= ",";
				else $query .= " ORDER BY ";
				$query .= "orden".$i;
		}
		$r = mysql_query($query);
		
		if (mysql_num_rows($r) > 0){
			$fila_actual=$num_columnas;
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
	
	function get_noticias($id_newsletter)
	{
		$resultado = array();
		$query = "SELECT n.id, n.img_vertical AS imagen, n.titulo, n.entradilla, n.texto, n.img_horizontal, s.titulo AS seccion, nnr.orden1, nnr.orden2
			FROM noticias AS n, noticias_newsletter AS nn, noticias_newsletter_relacionadas AS nnr, secciones AS s
			WHERE nnr.id_elemento = nn.id
			AND nn.id_original = n.id
			AND nnr.id_newsletter = " . $id_newsletter . "
			AND nnr.tabla_elemento = 'noticias_newsletter'
			GROUP BY n.id
			ORDER BY nnr.orden1";
		$r = mysql_query($query);
		if (mysql_num_rows($r) > 0){
			while ($fila = mysql_fetch_assoc($r)){
				$resultado[] = $fila;
			}
		}
		return $resultado;
	}
	
  function getNoticias($newsletter_id)
  {
    $resultado = array();
    $query = "SELECT n.id, n.img_vertical AS imagen, n.titulo, n.entradilla, n.texto, n.img_horizontal, s.titulo AS seccion, nnr.orden1, nnr.orden2
      FROM noticias AS n, noticias_newsletter AS nn, noticias_newsletter_relacionadas AS nnr, secciones AS s
      WHERE nnr.id_elemento = nn.id
      AND nn.id_original = n.id
      AND nnr.id_newsletter = " . $newsletter_id . "
      AND nnr.tabla_elemento = 'noticias_newsletter'
      GROUP BY n.id
      ORDER BY nnr.orden1";
    $r = mysql_query($query);
    if (mysql_num_rows($r) > 0){
      while ($fila = mysql_fetch_assoc($r)){
        $resultado[$fila["id"]] = $fila;
      }
    }
    return $resultado;
  }
	
	function get_banners($id_newsletter)
  {
    $resultado = array();
    $query = 'SELECT bn.id, bn.titulo, bn.imagen, bn.link, bn.tipo, bn.fuente
			FROM banners_newsletter AS bn
			JOIN noticias_newsletter_relacionadas AS nnr ON nnr.id_elemento = bn.id
			WHERE nnr.id_newsletter = ' . $id_newsletter . '
			AND nnr.tabla_elemento = "banners_newsletter"
      ORDER BY nnr.orden2';
    $r = mysql_query($query);
    if (mysql_num_rows($r) > 0){
      while ($fila = mysql_fetch_assoc($r)){
        $resultado[] = $fila;
      }
    }
    return $resultado;
  }
  
  function getBanners($newsletter_id)
  {
    $resultado = array();
    $query = 'SELECT bn.id, bn.titulo, bn.imagen, bn.link, bn.tipo, bn.fuente
      FROM banners_newsletter AS bn
      JOIN noticias_newsletter_relacionadas AS nnr ON nnr.id_elemento = bn.id
      WHERE nnr.id_newsletter = ' . $newsletter_id . '
      AND nnr.tabla_elemento = "banners_newsletter"
      ORDER BY nnr.orden2';
    $r = mysql_query($query);
    if (mysql_num_rows($r) > 0){
      while ($fila = mysql_fetch_assoc($r)){
        $resultado[$fila["id"]] = $fila;
      }
    }
    return $resultado;
  }
  
  function getMegaBanner($id) {
    $sql = "SELECT bn.id, bn.titulo, bn.imagen, bn.link, bn.tipo, bn.fuente
      FROM banners_newsletter AS bn
      WHERE bn.id = " . $id;
    $res = mysql_query($sql);
    $banner = mysql_fetch_array($res);
    if($banner["fuente"] == "codigo") {
      return $banner["imagen"];
    } else {
      return
      '<a href="' . $banner["link"] . '" title="' . $banner["titulo"] . '" style="text-align: center; text-decoration: none; width: 100%; float: left;">
        <img src="http://www.golfconfidencial.com/userfiles/banners/' . $banner["imagen"] .  '" alt="' . $banner["titulo"] . '" style="border: 0;" />
       </a>';
    }
  }
	
	/*
	Redirecciona al listado despues de hacer alguna operacion sobre la base de datos para no duplicar 
	contenido
	*/
	function redireccionar ($pagina = ""){
		header("Location: ".$this->url_home."&page=".$pagina);
	}
	
	function redireccionar2 ($id){
		header("Location: ".$this->url_home."&accion=editar&id=".$id);
	}
	
	/*
	Obtiene la query para listar las noticias con el buscador AJAX.
	Devuelve por referencia los parámetros necesarios.	
	*/
	function get_query_palabra ($page,$items,&$q,&$sqlStr,&$sqlStrAux,&$limit,$orden,$tipo_orden){
		if(isset($page) and is_numeric($page))
			$limit = " LIMIT ".(($page-1)*$items).",$items";
		else
			$limit = " LIMIT $items";
	
		if(isset($q) and !eregi('^ *$',$q)){
			$q = sql_quote($q); //para ejecutar consulta
			$busqueda = htmlentities($q); //para mostrar en pantalla
	
			$sqlStr = "SELECT * FROM ".$this->tabla." WHERE  nombre LIKE '%$q%' ORDER BY ".$orden." ".$tipo_orden."";
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla." WHERE nombre LIKE '%$q%'";
		}else{
			$sqlStr = "SELECT * FROM ".$this->tabla." ORDER BY ".$orden." ".$tipo_orden."";
			$sqlStrAux = "SELECT count(*) as total FROM ".$this->tabla."";
		}
	}
	
	function get_noticias_newsletter_origen ($id_newsletter,$tipo_newsletter){
		if ($tipo_newsletter=="Newsletter") $tipo ="estado_1";
 		else if ($tipo_newsletter=="Aquí España") $tipo ="estado_2";
 		else if ($tipo_newsletter=="Club FEHR") $tipo ="estado_3";
 		else if ($tipo_newsletter=="Confidencial") $tipo ="estado_4";
		$c = "SELECT * FROM noticias_newsletter ";
		if ($id_newsletter != "")
			$c .= " WHERE ".$tipo."=1 AND id NOT IN (SELECT id FROM noticias_newsletter_relacionadas WHERE id_newsletter=".$id_newsletter.")";
		$r = mysql_query($c);
		return $r;
	}
	
	
	
	function get_noticias_newsletter_destino ($id_newsletter){
		$c = "SELECT * FROM noticias_newsletter nn, noticias_newsletter_relacionadas nnr
			  WHERE nn.id=nnr.id AND nnr.id_newsletter=".$id_newsletter." ORDER BY orden DESC";
		$r = mysql_query($c);
		return $r;
	}
	
	function get_banners_newsletter_origen ($id_newsletter){
		$c = "SELECT * FROM banners_newsletter ";
		if ($id_newsletter != "")
			$c .= " WHERE id NOT IN (SELECT id FROM banners_newsletter_relacionadas WHERE id_newsletter=".$id_newsletter.")";
		$c .= "ORDER BY tipo";
		$r = mysql_query($c);
		return $r;
	}
	
	
	
	function get_banners_newsletter_destino ($id_newsletter){
		$c = "SELECT * FROM banners_newsletter nn, banners_newsletter_relacionadas nnr
			  WHERE nn.id=nnr.id AND nnr.id_newsletter=".$id_newsletter." ORDER BY orden DESC";
		$r = mysql_query($c);
		return $r;
	}
	
	
	
	/*
	Cambia el estado de los campos pasados como parámetros (de activo a inactivo y viceversa)
	*/
	function cambia_estado($id,$campo,$pagina){
		//Obtengo algunos datos de la noticia a la que voy a cambiar el estado
		$r = mysql_query("SELECT preparada FROM ".$this->tabla." WHERE id=".$id);
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
		$this->redireccionar($pagina);
	}
	
	/*
	Duplica todas las entradas con los identificadores parados como parámetro en un array
	*/
	function duplicar($arr_ids){
		foreach($arr_ids as $clave => $valor){
		  if ($clave != "seleccion"){//No incluyo el checkbox que selecciona todos y que tiene valor "on"
			$r = mysql_query("SELECT * FROM ".$this->tabla." WHERE id=".$valor);

			$fila = mysql_fetch_assoc($r);
			/*
			Eliminamos el primer elemento de la matriz (que es "id") para que no se repita en la funcion insert(), con la funcion
			array_slice()
			*/
			$fila["redireccion"]=-1;
			
			$banners = mysql_query("SELECT id FROM banners_newsletter_relacionadas  WHERE id_newsletter=".$valor);

			$cad = "";
			if (mysql_num_rows($banners)>0){
				$a=0;
				while ($linea = mysql_fetch_assoc($banners)) {
						$cad .= $linea["id"].",";
						$a++;
				}
			}
			$fila["banners_relacionados"] = $cad;

			$cad = "";
			
			$noticias = mysql_query("SELECT id FROM noticias_newsletter_relacionadas  WHERE id_newsletter=".$valor);
			
			if (mysql_num_rows($noticias)>0){
				$a=0;
				while ($linea = mysql_fetch_assoc($noticias)) {
						$cad .= $linea["id"].",";
						$a++;
				}
			}
			$fila["noticias_relacionadas"] = $cad;	
			
			$this->insert(array_slice($fila,1),"duplicar","");

			$id_nueva = mysql_insert_id();
			
			
			
		  }
		}
		$this->redireccionar();
	}
	
	
}
?>