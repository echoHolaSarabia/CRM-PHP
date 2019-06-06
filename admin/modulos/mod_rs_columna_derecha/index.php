<?
if (isset($_GET['sec'])){
	if (isset($_POST['cadena'])){
		$cadena = substr($_POST['cadena'],0,strlen($_POST['cadena'])-1);
		$idRecursos = explode(",",$cadena);
		mysql_query("DELETE FROM rs_columna_derecha WHERE seccion = ".$_GET['sec']);
		$i = 1;
		foreach ($idRecursos as $unRecurso){
			mysql_query("INSERT INTO rs_columna_derecha (seccion,id_recurso,orden) VALUES (".$_GET['sec'].",".$unRecurso.",".$i.")");
			$i++;
		}
	}
}
?>
<script type="text/javascript" src="scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-personalized-1.6rc2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	$('#origen').sortable({
		items: "li",
		connectWith: ['#destino'],
		cursor: 'move'
		
	});
	$('#destino').sortable({
		items: "li",
		connectWith: ['#origen'],
		cursor: 'move'
	});
});

function guardar(){
	var destino = "";
	$("#destino").children(".item").each(function(i) {
	  var li = $(this);
	  destino = destino + li.attr("id") + ",";
	});
	document.recursos.cadena.value = destino;
	document.recursos.submit();
}
</script>
<style type="text/css" media="all">
.ui-sortable-placeholder { border: 1px dotted black;width: auto !important; visibility: visible !important; }
.ui-sortable-placeholder * { visibility: hidden; }

.columna_derecha{
	width:300px;
	height:300px;
	background-color:#F56432;
	border:1px solid #000000;
	text-align:center;
}
.recursos{
	width:300px;
	background-color:#004500;
	border:1px solid #000000;
	text-align:center;
}
.item{
	width:250px;
	height:20px;
	background-color:#FFFFFF;
	border:1px solid #000000;
}
.sortHelper{
	border: 3px dashed #666;
	width: auto !important;
}
</style>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td align="center"><? include ("includes/menu_rs.inc.php");?></td>
        </tr>
      	<tr>
          <td class="botonera" align="right">
          	<table border="0" cellpadding="0" cellspacing="0" >
      		  <tr>
              	<td class="botones_botonera"><a href="javascript:guardar();" class="enlaces_botones_botonera"><img src="images/nuevo.png" border="0"><br />Guardar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="contenido">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
            	<tr>
            		<td align="center">
            				<ul id="destino" class="columna_derecha">
            				RECURSOS ASOCIADOS
            					<?
            					$c = "SELECT * FROM rs_columna_derecha cd, rs_recursos r WHERE cd.id_recurso=r.id AND cd.seccion = ".$_GET['sec']." ORDER BY cd.orden ASC";
											$r = mysql_query($c);
											while ($fila = mysql_fetch_assoc($r)){?>
            						<li id="<?=$fila['id_recurso']?>" class="item"><?=$fila['nombre_recurso']?></li>
            					<?}?>
              			</ul>
              	</td>
            		<td align="center">
            				<ul id="origen" class="recursos">
            				RECURSOS DISPONIBLES
            					<?
            					$c = "SELECT * FROM rs_recursos WHERE id NOT IN (SELECT id_recurso FROM rs_columna_derecha WHERE seccion = ".$_GET['sec'].") ORDER BY id ASC";
											$r = mysql_query($c);
											$recursos = array();
											while ($fila = mysql_fetch_assoc($r)){
            					?>
              				<li id="<?=$fila['id']?>" class="item"><?=$fila['nombre_recurso']?></li>
              				<?}?>
              			</ul>
            		</td>
            	</tr>
              <form action="" name="recursos" method="post">
              	<input type="hidden" name="cadena" value="">
              </form>
            </table>
          </td>
        </tr>
      </table>
    </td>
</tr>