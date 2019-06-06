<?php
session_start();
include ("../configuracion.php");
include ("includes/conexion.inc.php");
include ("includes/acceso.inc.php");
if (isset($_GET['accion']) && ($_GET['accion'] == "salir")) {
	destruye_sesion();
}
$error = "";
if (isset($_POST['submit']) && $_POST['submit']){
  $user = mysql_real_escape_string($_POST['user']);
  $pass = mysql_real_escape_string($_POST['pass']);
  $guardar = (array_key_exists("guardar",$_POST)) ? $_POST['guardar'] : "";
  //escribe log
  $df = fopen("../logs/acceso.txt","a+");
  $cadena = "Usu: ".$user." ".date("H:i:s d-m-Y")." - ".$_SERVER['SERVER_ADDR']."\n";
  fwrite($df,$cadena);
  $error = check_user($user,$pass,$guardar);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gesti&oacute;n de publicaciones</title>
<link href="css/estilos_general.css" type="text/css" rel="stylesheet" />
</head>

<body onload="document.entrada.user.focus();">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td background="images/fondo.jpg">
  	  <table width="100%" border="0">
        <tr>
      	  <td></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
  	<td align="center">
    <div class="formulario_entrada" style="width:500px; height:300px;">
      <table width="500px" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td colspan="2" height="100px">&nbsp;</td>
        </tr>
      <form name="entrada" method="post" action="index.php">
      	<?php
         if ($error != "")
		 	echo '
		<tr>
          <td colspan="2" class="error">'.$error.'</td>
        </tr>
		<tr>
          <td colspan="2" class="separador"></td>
        </tr>';
		?>
        <tr>
          <td colspan="2"><strong>Introduzca su nombre de usuario y su contrase&ntilde;a para acceder al sistema</strong></td>
        </tr>
        <tr>
          <td colspan="2" class="separador"></td>
        </tr>
        <tr>
          <td>Usuario:</td>
          <td><input type="text" name="user" value="<?php if (array_key_exists('usr',$_COOKIE)) echo $_COOKIE['usr'];?>" size="50"></td>
        </tr>
        <tr>
          <td colspan="2" class="separador"></td>
        </tr>
        <tr>
          <td>Contrase&ntilde;a:</td>
          <td><input type="password" name="pass" value="<?php if (array_key_exists('pwd',$_COOKIE)) echo $_COOKIE['pwd'];?>" size="50"></td>
        </tr>
        <tr>
          <td colspan="2" class="separador"></td>
        </tr>
        <tr>
          <td colspan="2" align="left">Recordar contrase&ntilde;a:&nbsp;<input type="checkbox" name="guardar" value="guarda" <?php if (array_key_exists('pwd',$_COOKIE) && ($_COOKIE['tick_guardado'] == "guardado")) echo "checked";?>/></td>
        </tr>
        <tr>
          <td colspan="2" class="separador"></td>
        </tr>
        <tr>
          <td  colspan="2" align="right"><input type="submit" id="submit" name="submit" value="Entrar" /></td>
        </tr>
      </form>
      </table>
    </div>
    </td>
  </tr>
</table>
<?php include("includes/footer.inc.php");?>