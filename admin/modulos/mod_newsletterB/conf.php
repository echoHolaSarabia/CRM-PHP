<?
define("MODULO","mod_newsletter");
$tablas_con_previsualizaion = array("noticias_newsletter","banners_newsletter");

/*Array que contiene los títulos para cada elemento planificable. Habrá que modificarlo dependiendo de los elementos planificaalbes
de cada proyecto*/
$titulos_planificables = array(
'noticias_newsletter' => 'Noticias',
'banners_newsletter' => 'Banners',
);

/*Array que contiene los estilos para cada elemento planificable. Habrá que modificarlo dependiendo de los elementos planificaalbes
de cada proyecto*/
$estilos = array(
'noticias_newsletter' => 'background-color:#DFF4FF;border:1px solid #1C94D5;list-style-type: none;width:auto;margin-bottom:5px;',
'banners_newsletter' => 'background-color:#FFCFE7;border:1px solid #DF0072;list-style-type: none;width:auto;margin-bottom:5px',
);

/*Array que contiene los estilos del recuadro de acciones sobre los elementos, para cada elemento planificable. Habrá que modificarlo dependiendo de los elementos planificaalbes
de cada proyecto*/
$estilos_helper = array(
'noticias_newsletter' => 'width:100%;background-color:#096D9F;color:#FFFFFF',
'banners_newsletter' => 'width:100%;background-color:#DF0072;color:#FFFFFF'
);

$tipos_newsletter = array(array("Newsletter","estado_1","n_1"));
?>