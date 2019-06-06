<?php
if (array_key_exists('ruta',$_GET)){
	$ruta = $_GET['ruta'];
	echo "<img src='../../".$ruta."'>";
}
?>