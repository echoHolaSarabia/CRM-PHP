<?php
define("NUM_SUBPLANILLAS",5);
define("MODULO","mod_planillas");
$tablas_con_previsualizaion = array("noticias","eventos","formacion");

/*Array que contiene los títulos para cada elemento planificable. Habrá que modificarlo dependiendo de los elementos planificaalbes
de cada proyecto*/
$titulos_planificables = array(
'noticias' => 'Noticias',
'eventos' => 'Eventos',
'formacion' => 'Formacion',
'modulosrosas' => 'Módulos especiales',
'modulosnegros' => 'Módulos Negros, no debería salir'
);

/*Array que contiene los estilos para cada elemento planificable. Habrá que modificarlo dependiendo de los elementos planificaalbes
de cada proyecto*/
$estilos = array(
'programada' => 'background-color:#FF9D4F;border:1px solid #FF7200;list-style-type: none;width:auto;margin-bottom:5px;',
'noticias' => 'background-color:#DFF4FF;border:1px solid #1C94D5;list-style-type: none;width:auto;margin-bottom:5px;',
'eventos' => 'background-color:#88ffaa;border:1px solid #1C94D5;list-style-type: none;width:auto;margin-bottom:5px;',
'formacion' => 'background-color:#88ffaa;border:1px solid #1C94D5;list-style-type: none;width:auto;margin-bottom:5px;',
'modulosrosas' => 'background-color:#FFCFE7;border:1px solid #DF0072;list-style-type: none;width:auto;margin-bottom:5px',
'modulosrosas_banner' => 'background-color:#ECAFFF;border:1px solid #BD1DEF;list-style-type: none;width:auto;margin-bottom:5px',
'modulosnegros' => 'color:#ffffff;background-color:#000000;border:1px solid #DF0072;list-style-type: none;width:auto;margin-bottom:5px'
);

/*Array que contiene los estilos del recuadro de acciones sobre los elementos, para cada elemento planificable. Habrá que modificarlo dependiendo de los elementos planificaalbes
de cada proyecto*/
$estilos_helper = array(
'noticias' => 'width:100%;background-color:#096D9F;color:#FFFFFF',
'eventos' => 'width:100%;background-color:#00AA33;color:#FFFFFF',
'formacion' => 'width:100%;background-color:#00AA33;color:#FFFFFF',
'modulosrosas' => 'width:100%;background-color:#DF0072;color:#FFFFFF',
'modulosrosas_banner' => 'width:100%;background-color:#BD1DEF;color:#FFFFFF',
'modulosnegros' => 'width:100%;background-color:#000000;color:#FFFFFF'
);