<?
if (!isset($html)) $html = new Html();
$encuesta = $html->get_encuesta(); 
$id_encuesta = $encuesta["id"];
if (!isset($_SESSION["votado".$id_encuesta])){
	if (isset($_SESSION["votado".$id_encuesta]) && ($_SESSION["votado".$id_encuesta]==1))
		$_SESSION["votado".$id_encuesta]=1;
	else $_SESSION["votado".$id_encuesta]=0;
}
?>
<script type="text/javascript">
	var votado_inc = <?=$_SESSION["votado".$id_encuesta];?>;
	function enviar_votacion(){
		if (votado_inc) alert("Ya ha votado");
		else {
			seleccion = false;
			for (i=0;i<document.getElementById("contenido-encuesta").resp.length;i++) 
				if (document.getElementById("contenido-encuesta").resp[i].checked) seleccion = true;
			if (seleccion)	document.getElementById("contenido-encuesta").submit();
			else alert("Seleccione una respuesta.");
		}		
	}
</script>

<!-- ENCUESTAS -->

<div class="gContIncludes">

    <div class="gBckTitIncludes">
        <div class="gTitIncludes">
            <div class="gTxtTitIncludes">Encuestas</div>
            <div class="gPuntaIncludes"><img src="/sitefiles/img/puntaTitIncludes.jpg" width="21" alt="" /></div>
        </div>
    </div>
    <div class="gEnRenglonTit">
        <?=$encuesta["pregunta"]?>
    </div>
    <form id="contenido-encuesta" name="contenido-encuesta" method="post" action="/encuestas/<?=$encuesta["id"]?>">
    <?
	for($i=1;$i<=$encuesta["num_respuestas"];$i++){
	?>
		<div class="gEnRenglonItem">
    		<input id="resp" type="radio" name="resp" value="<?=$i?>"/><?=$encuesta["r".$i]?>
    	</div>
	<?  	
	}
	?>
	</form>
     <div class="gEnTodas" align="center">
        <a href="javascript:enviar_votacion()">VOTAR</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/encuestas/<?=$encuesta["id"]?>">VER RESULTADOS</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/encuestas">VER MÁS ENCUESTAS</a>
    </div>

</div>
