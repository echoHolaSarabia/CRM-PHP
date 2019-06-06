<?php
if (isset($_GET['campo']))
  $campo = $_GET['campo'];
if (isset($_GET['orden']))
  $orden = $_GET['orden'];
cambia_orden($campo, $orden);
?>
<tr>
  <td width="100%">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="botonera" align="right">
          <table border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="botones_botonera"><a href="index2.php?modulo=mod_administradores&accion=nuevo" class="enlaces_botones_botonera"><img src="images/nuevo.png" border="0"><br />Nuevo</a></td>
              <td class="botones_botonera"><a href="javascript:document.form_listado_seleccion.submit();" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Borrar</a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="buscador"><span class="titulo_seccion">ADMINISTRADORES</span></td>
      </tr>
      <tr>
        <td class="contenido">
          <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <form name="form_listado_seleccion" method="post" action="index2.php?modulo=mod_administradores&fns=1&accion=delete">
              <?php
              $r = mysql_query("SELECT * FROM permisos p,administradores a WHERE p.id=a.permisos ORDER BY a." . $campo . " " . $orden . ";");
              $num_filas = mysql_num_rows($r);
              ?>
              <tr class="titulos">
                <td><a href="?modulo=mod_administradores&campo=id&orden=<?php echo $orden ?>"><?php echo muestra_flecha($campo, $orden, "id"); ?>Id</a></td>
                <td><input type="checkbox" name="seleccion" onclick="checkAll(<?php echo $num_filas + 1 ?>,'');" /></td>
                <td><a href="?modulo=mod_administradores&campo=nombre&orden=<?php echo $orden ?>"><?php echo muestra_flecha($campo, $orden, "nombre"); ?>Nombre</a></td>
                <td><a href="?modulo=mod_administradores&campo=usuario&orden=<?php echo $orden ?>"><?php echo muestra_flecha($campo, $orden, "usuario"); ?>Usuario</a></td>
                <td><a href="?modulo=mod_administradores&campo=permisos&orden=<?php echo $orden ?>"><?php echo muestra_flecha($campo, $orden, "permisos"); ?>Permisos</a></td>
                <td align="center"><a href="?modulo=mod_administradores&campo=activo&orden=<?php echo $orden ?>"><?php echo muestra_flecha($campo, $orden, "activo"); ?>Activo</a></td>
                <td><a href="?modulo=mod_administradores&campo=email&orden=<?php echo $orden ?>"><?php echo muestra_flecha($campo, $orden, "email"); ?>E-mail</a></td>
                <td><a href="?modulo=mod_administradores&campo=ultimo_acceso&orden=<?php echo $orden ?>"><?php echo muestra_flecha($campo, $orden, "ultimo_acceso"); ?>&Uacute;ltima visita</a></td>
                <td align="right">Editar</td>
              </tr>
              <?php
              //Muestra las categorías raiz
              $num_fila = 0;
              while ($fila = mysql_fetch_array($r)) {
                $num_fila++;
                $estilo = (($num_fila % 2) == 0) ? "fila_tabla_par" : "fila_tabla_impar";
                ?>
                <tr>
                  <td class="<?php echo $estilo ?>"><?php echo $fila['id'] ?></td>
                  <td class="<?php echo $estilo ?>"><input type="checkbox" name="seleccion<?php echo $num_fila; ?>" value="<?php echo $fila['id'] ?>" /></td>
                  <td class="<?php echo $estilo ?>"><strong><?php echo $fila['nombre'] ?></strong></td>
                  <td class="<?php echo $estilo ?>"><?php echo $fila['usuario'] ?></td>
                  <td class="<?php echo $estilo ?>"><?php echo $fila['tipo'] ?></td>
                  <td class="<?php echo $estilo ?>" align="center"><?php if ($fila['activo'] == 0)
                echo "<a href='index2.php?modulo=mod_administradores&accion=estado&fns=1&id=" . $fila['id'] . "'><img src='images/cross.png' border='0'/></a>"; else
                echo "<a href='index2.php?modulo=mod_administradores&accion=estado&fns=1&id=" . $fila['id'] . "'><img src='images/tick.png' border='0'/></a>"; ?></td>
                  <td class="<?php echo $estilo ?>"><?php echo $fila['email'] ?></td>
                  <td class="<?php echo $estilo ?>"><?php echo $fila['ultimo_acceso'] ?></td>
                  <td class="<?php echo $estilo ?>" align="right"><a href="?modulo=mod_rs_miembros&accion=editar&id_usuario=<?php echo $fila['id'] ?>"><img src="images/perfil.png" border="0" alt="Crear perfil" title="Crear perfil" /></a>&nbsp;<a href="?modulo=mod_administradores&accion=editar&id=<?php echo $fila['id'] ?>"><img src="images/page_edit.png" border="0" alt="Editar" title="Editar" /></a></td>
                </tr>
<?php } ?>
            </form>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>