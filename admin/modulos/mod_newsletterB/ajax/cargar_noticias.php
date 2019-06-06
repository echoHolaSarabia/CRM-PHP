<?php
include ("../../../../configuracion.php");
include ("../../../includes/conexion.inc.php");
include ("../conf.php");
?>
<?
  $id_newsletter = $_POST['id'];
  $estado = $_POST['tipo'];
  $query = "(SELECT id,titulo FROM noticias_newsletter WHERE ".$estado."=1 AND id NOT IN (SELECT id_elemento FROM noticias_newsletter_relacionadas WHERE tabla_elemento = 'noticias_newsletter' AND id_newsletter=".$id_newsletter.")) ";
  $r = mysql_query($query);
  while ($origen = mysql_fetch_array($r)){
?>
	<li id="item_<?=$origen['id']?>,noticias" style="<?=$estilos['noticias_newsletter']?>" value="eo" title="Noticias">
		<div class="titulo"><?=htmlentities($origen['titulo'])?></div>
	</li>
<? } ?>
