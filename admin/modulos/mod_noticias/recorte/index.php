<?
/*if (isset($_GET['nombre']) && $_GET['nombre'] != ""){
	if ($_GET['nombre'] == "port_sobretitular"){
		$capa = "im1";
		$proporcion = 1.73;
		$titulo = "imagen de portada sobre/bajo el titular";
	}
	if ($_GET['nombre'] == "port_izqtitular"){
		$capa = "im2";
		$proporcion = 0.69;
		$titulo = "imagen de portada a la izquierda del texto";
	}
	if ($_GET['nombre'] == "port_destacada_peq"){
		$capa = "im3";
		$proporcion = 1.17;
		$titulo = "imagen del rotador";
	}
	if ($_GET['nombre'] == "port_tacos"){
		$capa = "im4";
		$proporcion = 1.48;
		$titulo = "imagen de los tacos";
	}
	if ($_GET['nombre'] == "port_recurso"){
		$capa = "im5";
		$proporcion = 0.8;
		$titulo = "imagen del recurso tipo destacado";
	}
	if ($_GET['nombre'] == "port_recurso_grande"){
		$capa = "im6";
		$proporcion = 2.56;
		$titulo = "imagen del recurso tipo hoy en...";
	}
	if ($_GET['nombre'] == "ampliada"){
		$capa = "im7";
		$proporcion = 0.83;
		$titulo = "imagen de notici ampliada";
	}
	if ($_GET['nombre'] == "port_destacada_grande"){
		$capa = "im8";
		$proporcion = 1.71;
		$titulo = "imagen de noticia destagada enorme";
	}
	if ($_GET['nombre'] == "newsletter"){
		$capa = "im9";
		$proporcion = 2;
		$titulo = "imagen para la newsletter";
	}
	//Calculo la ruta de la imagen para guardarla en la bbdd
	$nombre_imagen = substr($_GET['foto'],0,strripos($_GET['foto'], '.')-9)."_".$_GET['nombre'];
	$extension = substr($_GET['foto'],strripos($_GET['foto'], '.'));
	$ruta_imagen = "../".$nombre_imagen.$extension;
}*/
include("../conf.php");
if (isset($_GET['tipo']) && $_GET['tipo'] != ""){
	//Obtengo el nombre que va a tener la imagen despues de realizar el recorte
	$extension = substr($_GET['foto'],strripos($_GET['foto'], '.'));
	$imagen = substr($_GET['foto'],0,strripos($_GET['foto'], '_'));
	$nombre_imagen = $imagen."_".$_GET['tipo'].$extension;
	//Tipo de recorte que se está haciendo
	$tipo = $_GET['tipo'];
	//Nombre de la capa donde se va a mostrar la miniatura
	$capa = "miniatura_".$_GET['tipo'];
	//Ruta relativa de la imagen para crop.php
	$ruta_imagen = "../../../".$_GET['foto'];
	//Ancho mï¿½ximo del recorte
	$ancho = $recorte_fotografico[$_GET['tipo']]["anchos"][0];
	//Anchos mï¿½ximos de los recortes de este tipo
	$anchos = implode("-",$recorte_fotografico[$_GET['tipo']]["anchos"]);
	//Proporcion del recorte
	$proporcion = $recorte_fotografico[$_GET['tipo']]["proporciones"][0];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Recorte</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="mootools.js"></script>
<script type="text/javascript" src="MooCrop.js"></script>
<script type="text/javascript">
var recortada = false;
window.addEvent('load',function(){
	var crop = new MooCrop('imgcrop');
	var indicator = new Element('span',{
	'styles' : {
		'position' : 'absolute',
		'display' : 'none',
		'padding' : '4px',
		'opacity' : '.7',
		'background' : '#ffffff',
		'border' : '1px solid #525252',
		'font-size' : '11px'
	}
 }).injectInside(crop.wrapper);
 
 // when dragging/resizing begins show indicator
 crop.addEvent( 'onBegin' , 
 	function(imgsrc,crop,bound,handle){
		indicator.setStyle('display' , 'block');
 });

 // during the event update label
 crop.addEvent('onCrop' , 
 	function(imgsrc,crop,bound,handle){
		indicator.setStyles({
			'top' : crop.bottom + 10,
			'left' : crop.left
		}).setText("w: "+crop.width+
			  " h: "+crop.height);
		document.getElementById('tamancho').innerHTML = crop.width;
		document.getElementById('tamalto').innerHTML = Math.round((crop.width/<?=$proporcion?>));
  });

	crop.addEvent('onDblClk', function(img, crop, bound){
		recortada = true;
		$('cropped').src = "crop.php?action=crop&w="+crop.width+"&h="+crop.height+"&x="+crop.left+"&y="+crop.top+"&anchos=<?=$anchos?>&nombre=<?=$tipo?>&src=<?=$ruta_imagen?>";
		alert('La foto ha sido recortada');
		//opener.document.getElementById('carpeta').selectedIndex=0;
		//opener.location.reload();
 	});
});

function cerrar (){
	alert('<?=$nombre_imagen?>');
	var nombre_imagen = '<?=$_GET['foto']?>';
	if (recortada) nombre_imagen = '<?=$nombre_imagen?>';
	window.opener.document.getElementById('<?=$capa?>').innerHTML = '<img src="'+nombre_imagen+'" width="300px">';
	window.opener.document.form_noticia.img_<?=$tipo?>.value = nombre_imagen;
	//window.opener.opener.document.getElementById('<?=$capa?>').innerHTML = '<img src="<?=$ruta_imagen?>" >';
	//window.opener.opener.document.getElementById('<?="h".$capa?>').value = '<?=$ruta_imagen?>';
	//opener.close();
	window.close();
}
</script>
</head>
<body>
<h4>Recorte fotográfico <?=$tipo?></h4>
<b>Ancho seleccionado:&nbsp;</b><div id="tamancho"></div>
<b>Alto recomendado:&nbsp;</b><div id="tamalto"></div>
<p>Original</p>
<img src="<?=$ruta_imagen?>" id="imgcrop" />
<p>Resultado</p>
<img src="<?=$ruta_imagen?>" id="cropped" />
<input type="button" onclick="cerrar()" value="Seleccionar">
</body>
</html>