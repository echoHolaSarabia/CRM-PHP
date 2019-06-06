<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

if (!isset($html))
  $html = new Html();

$query = "SELECT n.id, n.titulo, n.titular_alt, n.img_cuadrada, n.tags
  FROM noticias n
  WHERE n.seccion =2
  AND n.activo =1
  AND n.fecha_publicacion < NOW( )
  AND n.img_cuadrada NOT LIKE ''
  ORDER BY n.fecha_publicacion DESC
  LIMIT 6";
$futbol = mysql_query($query);

/*$query = "
  SELECT n.id, n.titulo, n.titular_alt, n.img_cuadrada, n.tags, s.titulo nombre_subseccion, s.id_padre seccion_id
  FROM noticias n
  JOIN secciones s ON s.id = n.subseccion
  WHERE n.seccion =3
  OR n.seccion =4
  AND n.activo =1
  AND n.fecha_publicacion < NOW( )
  AND n.img_cuadrada NOT LIKE ''
  ORDER BY n.fecha_publicacion DESC
  LIMIT 6";*/
  /*$query = "
  SELECT n.id, n.titulo, n.titular_alt, n.img_cuadrada, n.tags, s.titulo nombre_subseccion, s.id_padre seccion_id
  FROM noticias n
  JOIN secciones s ON s.id = n.subseccion
  WHERE n.seccion =4
  AND n.activo =1
  AND n.fecha_publicacion < NOW( )
  AND n.img_cuadrada NOT LIKE ''
  ORDER BY n.fecha_publicacion DESC
  LIMIT 6";*/
  $query = "
  SELECT n.id, n.titulo, n.titular_alt, n.img_cuadrada, n.tags  FROM noticias n
  WHERE n.seccion =4
  AND n.activo =1
  AND n.fecha_publicacion < NOW( )
  AND n.img_cuadrada NOT LIKE ''
  ORDER BY n.fecha_publicacion DESC
  LIMIT 6";
$tennis = mysql_query($query);

$src_primera = "";
?>
<div class="iContDest02" align="left">
  <div class="iDest02L">
    <div class="iBckTitDest02">
      <div class="iTitDest02">
        <div class="iTxtTitDest02">P&aacute;del Profesional</div>
        <div class="iPuntaDest02"><img src="sitefiles/img/puntaTitDest02.jpg" width="19" alt="" /></div>
        <div class="iVerMas"><a href="/padel-profesional">ver mas</a></div>
      </div>
    </div>
    <div class="iCuerpoDest02">
      <div class="iImgDest02" id="imagen_futbol"></div>
      <?php
      $i = 1;
      $a = 0;
      $clase[0] = "iBtnDestA";
      $clase[1] = "iBtnDestB";
      $primero = 1;
      while ($noticia = mysql_fetch_assoc($futbol)) {
        $noticia["nombre_seccion"] = "padel-profesional";
        $i = ($i + 1) % 2;
        $src_aux = explode("/", $noticia["img_cuadrada"]);
        $aux = $src_aux[count($src_aux) - 1];
		
        $src_aux[count($src_aux) - 1] = "cuadrada_129";
        $src_aux[count($src_aux) - 1] = "";
        $src_aux[count($src_aux)] = $aux;
        $src = implode("/", $src_aux);
        $noticia["nombre_subseccion"] = getSlugify($noticia["nombre_subseccion"]);
        ?>
        <div style="display:none"><img src="<?php echo $src ?>" alt="padel"></div>
        <div id="titular_futbol<?php echo $a ?>" class="<?php echo ($primero) ? "iBtnDestAA" : $clase[$i] ?>" onmouseover="this.className='iBtnDestAA';mostrar_foto_futbol('<?php echo $src ?>');this.className='iBtnDestAA';" style="overflow:hidden;line-height:18px" ><a href="<?php echo $html->enlace_sin($noticia) ?>"><?php echo ($noticia["titular_alt"] == "") ? $noticia["titulo"] : $noticia["titular_alt"] ?></a></div>
        <?php
        if ($primero) {
          $src_primera = $src;
          $primero = 0;
        }
        $a++;
        ?>
<?php } ?>
    </div>
  </div>
  
  <script type="text/javascript">
    function mostrar_foto_futbol(src){clase=Array;clase[0]="iBtnDestA";clase[1]="iBtnDestB";document.getElementById("imagen_futbol").innerHTML="<img alt='padel' width='135px'  src='"+src+"'>";for(i=0;i<<?php echo $a ?>;i++){document.getElementById("titular_futbol"+i).className=clase[i%2]}}document.getElementById("imagen_futbol").innerHTML="<img width='135px' src='<?php echo $src_primera ?>'>";
  </script>
  
  
  <div class="iDest02">
    <div class="iBckTitDest02">
      <div class="iTitDest02">
        <div class="iTxtTitDest02">P&aacute;del Amateur</div>
        <div class="iPuntaDest02"><img src="sitefiles/img/puntaTitDest02.jpg" width="19" alt="" /></div>
        <div class="iVerMas"><a href="/padel-amateur<?php //echo 'Agenda Amateur' ?>">ver mas</a></div>
      </div>
    </div>
    <div class="iCuerpoDest02">
      <div class="iImgDest02" id="imagen_tennis"></div>
      <?php
      $i = 1;
      $a = 0;
      $clase[0] = "iBtnDestA";
      $clase[1] = "iBtnDestB";
      $primero = 1;
      while ($noticia = mysql_fetch_assoc($tennis)){
        $i = ($i + 1) % 2;
        $src_aux = explode("/", $noticia["img_cuadrada"]);
		
        $aux = $src_aux[count($src_aux) - 1];
        $src_aux[count($src_aux) - 1] = "cuadrada_129";
        $src_aux[count($src_aux) - 1] = "";
        $src_aux[count($src_aux)] = $aux;
        $src = implode("/", $src_aux);
        $noticia["nombre_subseccion"] = getSlugify($noticia["nombre_subseccion"]);
        $query = "
            SELECT `titulo`
            FROM `secciones`
            WHERE `id` =" . $noticia['seccion_id'];
        $result = mysql_query($query);
        $seccion = mysql_fetch_object($result);
        $noticia["nombre_seccion"] = $seccion->titulo;
        ?>
        <div style="display:none">patata<img src="<?php echo $src ?>"  alt="padel"></div>
        <div id="titular_tennis<?php echo $a ?>" class="<?php echo ($primero) ? "iBtnDestAA" : $clase[$i] ?>" onmouseover="mostrar_foto_tennis('<?php echo $src ?>');this.className='iBtnDestAA';"  style="overflow:hidden;line-height:18px" >      
        <a href="<?php echo 'http://www.padelspain.net/padel-amateur/'.$noticia["id"].'/'; ?>"><?php echo ($noticia["titular_alt"] == "") ? $noticia["titulo"] : $noticia["titular_alt"] ?></a></div>
        <?php
        if ($primero) {
          $src_primera = $src;
          $primero = 0;
        }
        $a++;
        ?>
<?php } ?>
    </div>
  </div>
</div>
<script type="text/javascript">
  function mostrar_foto_tennis(src){
	  clase=Array;clase[0]="iBtnDestA";clase[1] = "iBtnDestB";document.getElementById("imagen_tennis").innerHTML="<img alt='padel' width='135px' src='"+src+"'>";for(i=0;i<<?php echo $a ?>;i++){document.getElementById("titular_tennis"+i).className=clase[i%2]}}
  document.getElementById("imagen_tennis").innerHTML = "<img width='135px' src='<?php echo $src_primera ?>'>";
</script>