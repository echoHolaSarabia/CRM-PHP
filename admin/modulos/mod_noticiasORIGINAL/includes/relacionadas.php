<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td>
      <?php if (isset($_GET['id']) && $_GET['id'] != "") { ?>
        <ul>
          <li><b>Noticias</b>
            <?php
            echo "<ul>";
            $r = $funciones->get_elementos_relacionados($_GET['id'], "noticias");
            while ($relacionada = mysql_fetch_array($r)) {
              ?>
            <li><?php echo $relacionada['titulo'] ?>&nbsp;<a href="index2.php?modulo=mod_noticias&fns=1&accion=elimina_relacion&id_noticia=<?php echo $relacionada['id_noticia'] ?>&id_relacionada=<?php echo $relacionada['id_relacionada'] ?>&tipo_elemento=noticias"><img src="images/cross.png" title="Eliminar relación" alt="Eliminar relación" border="0" align="absmiddle"></a></li>
          <?php
          }
          echo "</ul>";
          ?>
          </li>


          <li><b>Encuestas</b>
            <?php
            echo "<ul>";
            $r = $funciones->get_elementos_relacionados($_GET['id'], "encuestas");
            while ($relacionada = mysql_fetch_array($r)) {
              ?>
            <li><?php echo $relacionada['pregunta'] ?>&nbsp;<a href="index2.php?modulo=mod_noticias&fns=1&accion=elimina_relacion&id_noticia=<?php echo $relacionada['id_noticia'] ?>&id_relacionada=<?php echo $relacionada['id_relacionada'] ?>&tipo_elemento=encuestas"><img src="images/cross.png" title="Eliminar relación" alt="Eliminar relación" border="0" align="absmiddle"></a></li>
          <?php
          }
          echo "</ul>";
          ?>
          </li>
        </ul>

        <input type="button" name="relacionar" value="Relacionar" onclick="document.location.href='?modulo=mod_relacionadas&id=<?php echo $_GET['id'] ?>'">
      <?php } else { ?>
        <p>Para relacionar esta noticia primero debe guardarla</p>
<?php } ?>
    </td>
  </tr>
</table>