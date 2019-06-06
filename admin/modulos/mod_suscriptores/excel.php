<?php  
include ("../../../configuracion.php");
include ("../../includes/conexion.inc.php");
$q = "SELECT s.id, s.nombre, s.apellidos, s.email, s.empresa, s.telefono, s.ciudad, s.activo FROM suscriptores s";
$r = mysql_query($q);
?>  
<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
<thead>
<tr>
<td>ID</td>
<td>Nombres</td>
<td>Apellidos</td>
<td>E-Mail</td>
<td>Empresa</td>
<td>Telefono</td>
<td>Ciudad</td>
<td>Activo</td>
</tr>
</thead>
<?php while($row = mysql_fetch_assoc($r)):?>
<tbody>
<tr>
<td><?php echo $row['id']?></td>
<td><?php echo $row['nombre']?></td>
<td><?php echo $row['apellidos']?></td>
<td><?php echo $row['email']?></td>
<td><?php echo $row['empresa']?></td>
<td><?php echo $row['telefono']?></td>
<td><?php echo $row['ciudad']?></td>
<td><?php echo $row['activo']?></td>
</tr>
</tbody>  
<?php endwhile;?>  
</table>  