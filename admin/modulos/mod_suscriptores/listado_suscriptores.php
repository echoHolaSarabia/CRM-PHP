<?php
include ("../../../configuracion.php");
include ("../../includes/conexion.inc.php");
$q = "SELECT email FROM suscriptores WHERE activo=1 AND ".$_GET["tipo"]." = 1";
$r = mysql_query($q);

if (mysql_num_rows($r) > 0) :

$Suscriptores = array();
$repetidos = array();
$first = true;

while ($Suscriptor = mysql_fetch_object($r))
{
  if (in_array($Suscriptor->email, $Suscriptores))
    $repetidos[] = $Suscriptor->email;
  else
    $Suscriptores[] = $Suscriptor->email;
}
?>
<script type="text/javascript">
cad="<?php echo implode(";", $Suscriptores); ?>";
window.opener.document.form_newsletter.suscriptores.value=cad;
<?php count($repetidos) and print 'parent.window.opener.alert("repetidos: '.implode(", ",$repetidos).'");' ?>
window.close();
</script>
<?php endif; ?>