<?
session_start();

/*AQUI VAN LOS INCLUDES*/
include ("../../../configuracion.php");
include ("../../includes/conexion.inc.php");
include ("../../includes/funciones.inc.php");
include ("../../includes/acceso.inc.php");
include ("../../clases/general.php");
include ("../../modulos/mod_planillas/funciones.php");
include ("../../modulos/mod_planillas/conf.php");
es_valido();
$funciones = new Funciones();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="estilos.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?
if (isset($_GET['id']))
	$elementos_planificados = $funciones->get_elementos_planificados($_GET['id']);
else echo "<script>window.close();</script>"
?>

<?
/*
Esta función muestra el html de un elemento de cualquier tipo.
$estilo -> Es un string con las variables css para de como se va a mostrar el recuadro. Su contenido está en conf.php
$estilo_helper -> Es un string con las variables css para mostrar el recuadro oscuro de arriba donde esta el atributo de bloquear, de +info, etc...
				  Su contenido está en conf.php
$titulo -> Es un string con el título del elemento
$descripcion -> Es un string con la descripción del elemento
$num -> Es un int. Contiene un valór único para diferenciar el elemento de todos los demás.
*/
function mostrar_recuadro_noticia ($estilo,$estilo_helper,$tabla,$id,$titulo,$descripcion,$bloqueado,$num){
	global $tablas_con_previsualizaion;
?>
<li id="item_<?=$id?>,<?=$tabla?>" style="<?=$estilo?>" value="<?=$tabla?>" title="<?=$tabla?>">
	<div style="<?=$estilo_helper?>">
		<table width="100%">
			<tr>
				<td>Bloquear:<input type="checkbox" id="bloqueado-<?=$tabla."-".$id?>" <?if($bloqueado == 1) echo "checked='checked'"?>></td>
				<?if(in_array($tabla,$tablas_con_previsualizaion)){?>
				<td onclick="javascript:muestra_contenido(<?=$id?>,'<?=$tabla?>',<?=$num?>)" style="cursor:pointer" align="right" id="abiertocerrado-<?=$tabla."-".$id?>">[+]</td>
				<td onclick="javascript:eliminar_elemento_de_planilla(<?=$id?>,'<?=$tabla?>')" style="cursor:pointer" align="right">X</td>
				<?}?>
			</tr>
		</table>
	</div>
	<div class="titulo"><?=$titulo?></div>
	<div id="prev-<?=$tabla."-".$id?>"></div>
</li>
<?
}

function muestra_columna ($elementos,$num_columna,$estilos,$estilos_helper,$limite_inferior,$limite_superior,&$num){
	if (!empty($elementos[$num_columna])){
		$num_elemento=0;
		foreach ($elementos[$num_columna] as $unElemento){
			if ($unElemento['programado'] == 1) $estilo = $estilos['programada'];
			else $estilo = $estilos[$unElemento['tabla_elemento']];
			if ($unElemento['orden'.$num_columna]>$limite_inferior && $unElemento['orden'.$num_columna]<$limite_superior){
				mostrar_recuadro_noticia($estilo,$estilos_helper[$unElemento['tabla_elemento']],$unElemento['tabla_elemento'],$unElemento['id'],$unElemento['titulo'],"",$unElemento['bloqueado'],$num);
				$num_elemento++;
			}
			$num++;
		}
	}
}
?>
<!-- PLANILLA -->
<!-- AREA DE LA PLANILLA ACTUAL -->
<div class="tabla_columnas">
<?
//El orden en planillas_elementos se incrementa en 100 cuando cambias de página, es decir, en la página 0 el orden vá de 1 a 100, en la 2 de 100 a 200, etc...
//$limite_inferior y $limite_superior sirven para ir controlando esos valores
$limite_inferior = 0;
$limite_superior = 100;
//$num es un contador que va contando los elementos de cada planilla para darles un identificador único.
$num = 0;
?>

<table  border="0"  id="tabla_contenedor0">
	<tr>
		<td>Pestaña rotador</td>
	</tr>
	<tr>
		<td>
			<div>
				<div class="rotador">
					<h2>ROTADOR</h2>
					<ul id="destino10" style="padding-bottom:5px">
					<?muestra_columna($elementos_planificados,0,$estilos,$estilos_helper,$limite_inferior,$limite_superior,$num);?>
					</ul>
				</div>
			</div>
		</td>
	</tr>
</table>
<?
for ($i=1;$i<=NUM_SUBPLANILLAS;$i++){?>
<table  border="0" id="tabla_contenedor<?=$i?>">
	<tr>
		<td>Pestaña <?=$i?></td>
	</tr>
	<tr>
		<td>
			<div>
				<div class="columna1">
					<h2>Columna 1</h2>
					<ul id="destino1<?=$i?>">
					<?muestra_columna($elementos_planificados,1,$estilos,$estilos_helper,$limite_inferior,$limite_superior,$num);?>
					</ul>
				</div>
				<div class="columna2">
					<h2>Columna 2</h2>
					<ul id="destino2<?=$i?>">
					<?muestra_columna($elementos_planificados,2,$estilos,$estilos_helper,$limite_inferior,$limite_superior,$num);?>
					</ul>
				</div>
				<div class="columna3">
					<h2>Columna 3</h2>
					<ul id="destino3<?=$i?>">
					<?muestra_columna($elementos_planificados,3,$estilos,$estilos_helper,$limite_inferior,$limite_superior,$num);?>
					</ul>
				</div>
				<div class="salto"></div>
				<div class="columna4">
					<h2>Columna 1 y 2</h2>
					<ul id="destino4<?=$i?>">
					<?muestra_columna($elementos_planificados,4,$estilos,$estilos_helper,$limite_inferior,$limite_superior,$num);?>
					</ul>
				</div>
				<div class="columna5">
					<h2>Columna 2 y 3</h2>
					<ul id="destino5<?=$i?>">
					<?muestra_columna($elementos_planificados,5,$estilos,$estilos_helper,$limite_inferior,$limite_superior,$num);?>
					</ul>
				</div>
				<div class="columna6">
					<h2>Columna 1, 2 y 3</h2>
					<ul id="destino6<?=$i?>">
					<?muestra_columna($elementos_planificados,6,$estilos,$estilos_helper,$limite_inferior,$limite_superior,$num);?>
					</ul>
				</div>
			</div>
		</td>
	</tr>
</table>
<?
$limite_superior += 100;
$limite_inferior += 100;
}?>
</div>