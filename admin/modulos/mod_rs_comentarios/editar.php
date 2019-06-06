<?
include("fckeditor/fckeditor.php");
$modulo = "mod_rs_comentarios";
$funciones = new Funciones;
$tipo = (isset($_GET['tipo'])) ? $_GET['tipo'] : "blog";
if (isset($_GET['id']) && $_GET['id'] != ""){// MODIFICAR
	$r = mysql_query("SELECT * FROM rs_comentarios WHERE id=".$_GET['id']);
	$categoria = mysql_fetch_array($r);
	$accion = "update&id=".$categoria['id'];
} else {// INSERTAR
	$accion = "insert";
}
?>
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
                <td class="botones_botonera"><a href="javascript:document.form_rs_cometarios.submit();" class="enlaces_botones_botonera"><img src="images/guardar.png" border="0"><br />Guardar</a></td>
                <td class="botones_botonera"><a href="index2.php?modulo=<?=$modulo?>" class="enlaces_botones_botonera"><img src="images/eliminar.png" border="0"><br />Cancelar</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="buscador"><span class="titulo_seccion"><? if ($accion == "insert") echo "Nueva ";else echo "Editar ";?><?=$tipo?></span></td>
        </tr>
        <tr>
          <td>
          <form name="form_rs_cometarios" method="post" action="?modulo=<?=$modulo?>&fns=1&accion=<?=$accion?>">
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
                      <td class="etiqueta_200px">Texto:</td>
                      <td>
                      	<?
								        $oFCKeditor = new FCKeditor('comentario');
								        $oFCKeditor->BasePath = 'fckeditor/';
								        $oFCKeditor->Value = (isset($categoria)) ? $categoria['comentario'] : "";
								        $oFCKeditor->Width  = '500' ;
								        $oFCKeditor->Height = '200' ;
								        $oFCKeditor->ToolbarSet = 'Basic';
								        $oFCKeditor->Create();
								        ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="separador" colspan="2"></td>
                    </tr>
                    <tr>
                      <td class="etiqueta_200px">Autor:</td>
                      <td><a href="index2.php?modulo=mod_rs_miembros&accion=editar&id=<?=$categoria['id_miembro']?>"><?=$funciones -> get_nombre_miembro($categoria['id_miembro']);?></a></td>
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
