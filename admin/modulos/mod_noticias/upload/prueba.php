<?php
include("../conf.php");
		//$ruta2 = "";
		$ruta = "../../../../userfiles/".date("Y");
		if ($dir = opendir($ruta)){
			$ruta2 = $ruta."/".date('M')."_".date("d");
			if ($dir2 = opendir($ruta2)){
			}else{
				umask(0000);
				mkdir($ruta2,0777);
				foreach($recorte_fotografico as $clave => $valor){
					foreach ($valor["anchos"] as $ancho){
						umask(0000);
						mkdir($ruta2."/".$clave."_".$ancho,0777);
					}
				}
			}
		}else{
			umask(0000);
			mkdir($ruta,0777);
			$ruta2 = $ruta."/".date('M')."_".date("d");
			if ($dir2 = opendir($ruta2)){
			}else{
				umask(0000);
				mkdir($ruta2,0777);
				umask(0000);
				mkdir($ruta2."/recorte",0777);
				foreach($recorte_fotografico as $clave => $valor){
					foreach ($valor as $ancho){
						umask(0000);
						mkdir($ruta2."/recorte",0777);
						mkdir($ruta2."/".$clave."_".$ancho,0777);
					}
				}
			}
		}
		echo $ruta2;
?>