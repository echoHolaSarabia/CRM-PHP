<?
if (isset($_GET["ver"]) && ($_GET["ver"]=="secciones"))
	include("modulos/mod_galerias_video/index_secciones.php");
else
	include("modulos/mod_galerias_video/index_videos.php");
?>