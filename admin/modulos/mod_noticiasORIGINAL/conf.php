<?php
$tipos_de_recorte = array("rotador","horizontal","vertical","cuadrada","ampliada","newsletter");
$recorte_fotografico['rotador'] = array(
										"anchos" => array(370),
										"proporciones" => array(0.91));
$recorte_fotografico['horizontal'] = array(
										"anchos" => array(370,260,230,220),
										"proporciones" => array(2.20));
$recorte_fotografico['vertical'] = array(
										"anchos" => array(370,116),
										"proporciones" => array(0.61));
$recorte_fotografico['cuadrada'] = array(
										"anchos" => array(370,151,129,54),
										"proporciones" => array(1));
$recorte_fotografico['ampliada'] = array(
										"anchos" => array(370),
										"proporciones" => array(1.20));
$recorte_fotografico['newsletter'] = array(
										"anchos" => array(370),
										"proporciones" => array(1.0));
							
// Recorte rotador
$recorte_fotografico['foto'] = array(
										"anchos" => array(370,129),
										"proporciones" => array(1.0));
										
?>