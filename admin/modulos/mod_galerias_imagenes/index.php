<?
require('clases/pagination.class.php');

//Parámetros necesarios para construir la consulta
if (isset($_GET['page']) && $_GET['page'] != "")
	$page = $_GET['page'];
else $page = 1;

if (isset($_GET['q']) && $_GET['q'] != "")
	$q = $_GET['q'];
else $q = "";

$items = $config_registros_paginador;
$sqlStr = "";
$sqlStrAux = "";
$limit = "";

if (isset($_GET['orden']) && $_GET['orden'] != ""){
	$orden = $_GET['orden'];
	$tipo_orden = $_GET['tipo_orden'];
} else {
	$orden = "id";
	$tipo_orden = "DESC";
}

$funciones = new Funciones;
$funciones->get_query_palabra($page,$items,$q,$sqlStr,$sqlStrAux,$limit,$orden,$tipo_orden);

$r = mysql_query($sqlStrAux);
$aux = mysql_fetch_assoc($r);
$query = mysql_query($sqlStr.$limit);

?>
<script src="scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.7.1.custom.min.js"></script>
  
  <script>
  
  $(document).ready(function(){
  	
  	$(".galeria").each(function(i) {
	  var li = $(this);
	  //li.attr("title", li.attr("id"));
	});
  	
  	$(".droppable-photos-container").droppable({ 
  		accept: ".img_content", 
  		drop: function(ev, ui) { 
  			ui.draggable.clone().fadeOut("fast", function() { $(this).fadeIn("fast") }).append($(this)); } });

  	
    $(".galeria").draggable({helper: 'clone'});
  
$(".seccion").droppable({
	accept: ".galeria",
	activeClass: 'droppable-active',
	hoverClass: 'droppable-hover',
	drop: function(ev, ui) {
		ui.draggable.clone().fadeOut("fast", function() { $(this).fadeIn("fast") }).appendTo($(this));reconstruir(ui.draggable.clone());
		//$(this).append("<table><tr><td><input type=\"checkbox\" >Dropped!</td></tr></table>");
	}
});
  });

  </script>
  <style>
  
.galeria { 
	width:500px;
	display:inline;
}

.seccion { 
  background-color: #e9b96e;
	border: 3px double #c17d11;

}

.droppable-active {
	opacity: 1.0;
}
.droppable-hover {
	outline: 1px dotted black;
}
.galeria_seccion{
}



.lista_seccion{
}
ul { list-style: none; }
li { 
  color: #FFF;
  font-size: 12px;
  font-family:Arial;
	}
</style>
  
<script src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.core.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.draggable.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.droppable.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.sortable.js"></script>
<script>
$(document).ready(function(){
    $(".lista_seccion").sortable({});
  });
$(document).ready(function(){
    $("#lista_secciones").sortable({});
  });
function serialize()
{
	
	var cadena1 = "";
	var cadena2 = "";
	var cadena3 = "";
	$(".seccion").each(function(i) {
	  var li = $(this) ;
	  cadena1 = cadena1 + li.attr("id") + ",";
	  id = li.attr("id");
	  	$("#"+id+" .galeria_seccion").each(function(i) {
	  	var li = $(this);
	  	cadena1 = cadena1 + li.attr("id") + ",";
	});
	});
	
	$(".galeria").each(function(i) {
	  var li = $(this);
	  cadena2 = cadena2 + li.attr("id") + ",";
	});
	
	//EnviarDatos(cadena1,"contenido_plantilla");
	document.getElementById("form_listado_seleccion").cadena.value = cadena1;
	//alert(document.getElementById("form_listado_seleccion").nombre_nueva.value);
	document.getElementById("form_listado_seleccion").action="index2.php?modulo=mod_galerias_imagenes&fns=1&accion=guardar_listado";
	document.getElementById("form_listado_seleccion").submit();
    return false;
};

function abrir_edicion(id){
	document.getElementById(id+"capa_nuevo_nombre").style.display='inline';
}

</script>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
      		  	<td class="botones_botonera"><a href="index2.php?modulo=mod_galerias_imagenes&accion=nuevo" class="enlaces_botones_botonera"><br /><img src="images/nuevo.png" border="0"><br /><b>Nueva galeria</b></a>&nbsp;&nbsp;</td>
                <td class="botones_botonera"><a href="javascript:document.form_listado_seleccion.submit();" class="enlaces_botones_botonera"><br /><img src="images/eliminar.png" border="0"><br /><b>Borrar</b></a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><img src="images/photos.png" align="absmiddle" width="70px" alt="Galeria de imagenes" title="Galeria de imagenes"><span class="titulo_seccion">Galerias de imágenes</span></td>
        </tr>
        <tr>
          <td class="contenido" width="100%" valign="top">
          	<form name="form_listado_seleccion" id="form_listado_seleccion" method="post" action="index2.php?modulo=mod_galerias_imagenes&fns=1&accion=delete">
          	<input type="hidden" name="cadena" id="cadena">
          	<table width="100%" border="0">
          		<tr>
          			<td width="100%" valign="top">  
          			  
          <?php 
			if($aux['total']>0){
				$p = new pagination;
				$p->Items($aux['total']);
				$p->limit($items);
				if (isset($q))
					$p->target("index2.php?modulo=mod_galerias_imagenes&q=".urlencode($q));
				else $p->target("index2.php?modulo=mod_galerias_imagenes");						 	  								
				$p->currentPage($page);
		  ?>
		  	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		  	  <tr class="titulos">
		  	  	<td width="20px"><input type="checkbox" name="seleccion" onclick="checkAll(<?=($aux['total']+1)?>);" /></td>
		  	  	<td width="50%">Nombre</td>
		  	  	<td width="20%">Fecha de publicación</td>
		  	  	<td>Activa</td>
		  	  	<td width="20px">Editar</td>
		  	  </tr>
			  <?
              $r = 0;
              $num_fila = 0;
              while ($fila = mysql_fetch_array($query)) {
                $num_fila ++;
                if (($num_fila % 2) == 0)
                	$estilo = "fila_tabla_par";
                else $estilo = "fila_tabla_impar";
              ?>
              		<tr>              		
              	<td class="<?=$estilo?>" width="20px"><input type="checkbox" onclick="" name="seleccion<?=$num_fila;?>" value="<?=$fila['id']?>" /></td>
              	<td class="<?=$estilo?>" width="50%"><?=$fila['titulo']?></td>
              	<td class="<?=$estilo?>" width="20%"><?=$fila['fecha_publicacion']?></td>
              	<td class="<?=$estilo?>">
              		<? if ($fila['activo'] == 0) 
                    		echo "<a href='?modulo=mod_galerias_imagenes&accion=estado&fns=1&id=".$fila['id']."'><img src='images/cross.png' border='0'/></a>"; else echo "<a href='?modulo=mod_galerias_imagenes&accion=estado&fns=1&id=".$fila['id']."'><img src='images/tick.png' border='0'/></a>";?>
                </td>
              	<td class="<?=$estilo?>" width="20px" align="right"><a href="?modulo=mod_galerias_imagenes&accion=editar&id=<?=$fila['id']?>"><img src="images/page_edit.png" border="0" title="Editar" /></a></td>
              		</tr>
             
              <? 
                if($r%2==0)++$r;else--$r;
              }
              ?>
			  <tr>
			    <td colspan='9'><?=$p->show()?></td>
			  </tr>
			  <?	
			  $p->show();
			} ?>
				</td>
              </tr>
           </table>
          
          </td>
          </form>
        </tr>
      </table>
    </td>
</tr>