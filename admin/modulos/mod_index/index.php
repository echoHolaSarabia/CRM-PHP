<? $funciones = new Funciones;?>
<style>
.titulo{
color:#666666;
text-align:center;
font-size:14px;
background-color:#EEEEEE;
}

.fila_par{
background-color:#DDDDDD;
}
.fila_impar{
background-color:#BBBBBB;
}
</style>
<tr>
	<td width="100%">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td height="200px">
            Aqui van las gráficas
            <img src="http://chart.apis.google.com/chart?cht=p3&chd=s:hW&chs=250x100&chl=Hello|World" alt="Ejemplo" />
          </td>
        </tr>
        <tr>
          <td height="200px" valign="top">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
              	<td>
              	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
              	  	<tr>
              	  	  <td class="titulo"><b>Noticias + leidas</b></td>
              	  	</tr>
              	  	<?
              	  	$r = $funciones->get_noticias_mas_leidas();
              	  	$i = 1;
              	  	while ($noticia = mysql_fetch_array($r)){
              	  	if (($i % 2) == 0) $estilo = "fila_par"; else $estilo = "fila_impar";	
              	  	?>
              	  	<tr>
              	  	  <td class="<?=$estilo?>"><?=$noticia['titular']?></td>
              	  	</tr>
              	  	<? $i++;} ?>
              	  </table>
              	</td>
              	<td>
              	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
              	  	<tr>
              	  	  <td class="titulo"><b>Secciones + leidas</b></td>
              	  	</tr>
              	  	<?
              	  	$r = $funciones->get_noticias_mas_leidas();
              	  	while ($noticia = mysql_fetch_array($r)){
              	  	if (($i % 2) == 0) $estilo = "fila_par"; else $estilo = "fila_impar";
              	  	?>
              	  	<tr>
              	  	  <td class="<?=$estilo?>"><?=$noticia['titular']?></td>
              	  	</tr>
              	  	<? $i++;} ?>
              	  </table>
              	</td>
              	<td>
              	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
              	  	<tr>
              	  	  <td class="titulo"><b>Noticias + comentadas</b></td>
              	  	</tr>
              	  	<?
              	  	$r = $funciones->get_noticias_mas_leidas();
              	  	while ($noticia = mysql_fetch_array($r)){
              	  	if (($i % 2) == 0) $estilo = "fila_par"; else $estilo = "fila_impar";
              	  	?>
              	  	<tr>
              	  	  <td class="<?=$estilo?>"><?=$noticia['titular']?></td>
              	  	</tr>
              	  	<? $i++;} ?>
              	  </table>
              	</td>
              	<td>
              	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
              	  	<tr>
              	  	  <td class="titulo"><b>Noticias + enviadas</b></td>
              	  	</tr>
              	  	<?
              	  	$r = $funciones->get_noticias_mas_leidas();
              	  	while ($noticia = mysql_fetch_array($r)){
              	  	if (($i % 2) == 0) $estilo = "fila_par"; else $estilo = "fila_impar";
              	  	?>
              	  	<tr>
              	  	  <td class="<?=$estilo?>"><?=$noticia['titular']?></td>
              	  	</tr>
              	  	<? $i++;} ?>
              	  </table>
              	</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
</tr>