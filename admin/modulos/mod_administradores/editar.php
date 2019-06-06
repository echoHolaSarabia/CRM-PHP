<?php
if (isset($_GET['id']) && $_GET['id'] != "") {
  $r = mysql_query("SELECT * FROM administradores WHERE id = " . $_GET['id']);
  $administrador = mysql_fetch_array($r);
  $accion = "update&id=" . $administrador['id'];
} else {
  $accion = "insert";
}
?>
<script language="javascript">
  function enviadatos(form){
    form = eval(form);
    if (form.nombre.value == ""){
      alert('Debe rellenar el campo nombre');
      form.nombre.focus();
      return false;
    }
    if (form.email.value == ""){
      alert('Debe rellenar el campo email');
      form.email.focus();
      return false;
    }
    if (form.usuario.value == ""){
      alert('Debe rellenar el campo usuario');
      form.usuario.focus();
      return false;
    }
    if (document.getElementById('pass1').value == "" || document.getElementById('pass2').value == "" || document.getElementById('pass1').value != document.getElementById('pass2').value){
      alert('Las contraseñas no coinciden o están vacías');
      document.getElementById('pass1').focus();
      return false;
    }
    form.submit();
  }
</script>
<tr>
  <td width="100%">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="botonera" align="right">
          <table border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="botones_botonera"><a href="#" onclick="enviadatos('document.form_administradores');" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
              <td class="botones_botonera"><a href="index2.php?modulo=mod_administradores" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="buscador"><span class="titulo_seccion">EDITAR ADMINISTRADOR</span></td>
      </tr>
      <tr>
        <td>
          <form name="form_administradores" method="post" action="?modulo=mod_administradores&fns=1&accion=<?php echo $accion ?>">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="titulos" colspan="2">Detalles</td>
              </tr>
              <tr>
                <td class="separador" colspan="2"></td>
              </tr>
              <tr>
                <td class="contenido">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td class="etiqueta_200px">Nombre:</td>
                      <td><input type="text" name="nombre" value="<?php echo (isset($administrador)) ? $administrador['nombre'] : "" ?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">E-mail:</td>
                      <td><input type="text" name="email" value="<?php echo (isset($administrador)) ? $administrador['email'] : "" ?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Usuario:</td>
                      <td><input type="text" name="usuario" value="<?php echo (isset($administrador)) ? $administrador['usuario'] : "" ?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Contrase&ntilde;a:</td>
                      <td><input type="password" name="password" id="pass1" value="<?php echo (isset($administrador)) ? $administrador['password'] : "" ?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Repetir contrase&ntilde;a:</td>
                      <td><input type="password" name="password" id="pass2" value="<?php echo (isset($administrador)) ? $administrador['password'] : "" ?>" style="width:300px;"/></td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Permisos:</td>
                      <td>
                        <?php
                        $c = "SELECT * FROM permisos";
                        $r = mysql_query($c);
                        ?>
                        <select name="permisos">
                          <?php while ($fila = mysql_fetch_array($r)) { ?>
                            <option value="<?php echo $fila['id'] ?>" <?php if (isset($administrador) && $fila['id'] == $administrador['permisos'])
                            echo "selected"; ?>><?php echo $fila['tipo'] ?></option>
<?php } ?>
                        </select>
                        <input type="hidden" name="ultimo_acceso" value="<?php if ($accion == "insert")
  echo date("Y-m-d");else
  echo $administrador['ultimo_acceso']; ?>">
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>
  </td>
</tr>