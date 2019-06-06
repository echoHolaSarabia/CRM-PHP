<?php
class Funciones{
	function get_noticias_mas_leidas(){
		$c = "SELECT * FROM noticias WHERE en_hemeroteca = 0 ORDER BY hits DESC LIMIT 0,10";
		$r = mysql_query($c);
		return $r;
	}
}
?>