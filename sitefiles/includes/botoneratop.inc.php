<?php
//$query = "
//  SELECT `id`, titulo
//  FROM `secciones`
//  WHERE `id` >2";
//$result = mysql_query($query);
//while($seccion = mysql_fetch_object($result)) {
//  $query = "
//    SELECT `id`
//    FROM `planillas`
//    WHERE `fecha_publicacion` <= 'now()'
//    AND `seccion` = " . $seccion->id . "
//    LIMIT 1";
//  $result2 = mysql_query($query);
//  if(mysql_num_rows($result2)){
//  $planilla = mysql_fetch_object($result2);
//  $query = "
//    UPDATE planillas_elementos SET `orden3` = '1', `planificado` = '1' WHERE id_elemento = 101 and id_planilla=" . $planilla->id . " LIMIT 1;
//    UPDATE planillas_elementos SET `orden3` = '2', `planificado` = '1' WHERE id_elemento = 144 and id_planilla=" . $planilla->id . " LIMIT 1;
//    UPDATE planillas_elementos SET `orden3` = '3', `planificado` = '1' WHERE id_elemento = 104 and id_planilla=" . $planilla->id . " LIMIT 1;
//    UPDATE planillas_elementos SET `orden3` = '4', `planificado` = '1' WHERE id_elemento = 139 and id_planilla=" . $planilla->id . " LIMIT 1;
//    UPDATE planillas_elementos SET `orden3` = '5', `planificado` = '1' WHERE id_elemento = 133 and id_planilla=" . $planilla->id . " LIMIT 1;
//    UPDATE planillas_elementos SET `orden3` = '6', `planificado` = '1' WHERE id_elemento = 105 and id_planilla=" . $planilla->id . " LIMIT 1;
//    UPDATE planillas_elementos SET `orden3` = '7', `planificado` = '1' WHERE id_elemento = 136 and id_planilla=" . $planilla->id . " LIMIT 1;
//    UPDATE planillas_elementos SET `orden3` = '8', `planificado` = '1' WHERE id_elemento = 136 and id_planilla=" . $planilla->id . " LIMIT 1;
//      ";
//  echo $query;
//  mysql_query($query);
//  }
//}


$query = "
  SELECT `id`,`titulo`
  FROM `secciones`
  WHERE `id_padre` = -1
    AND `activo` =1";
$result = mysql_query($query);

function getSlugify($text)
{
  $b = array("Á","É","Í","Ó","Ú","á","é","í","ó","ú","ä","ë","ï","ö","ü","à","è","ì","ò","ù","ñ","Ñ","%"," ",",",".",";",":","¡","!","¿","?",'"',"\"","'");
  $c = array("A","E","I","O","U","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","n","N","","-","","","","","","","","",'',"","");
  $text = str_replace($b,$c,$text);

  // replace all non letters or digits by -
  $text = preg_replace('/\W+/', '-', $text);

  // trim and lowercase
  $text = strtolower(trim($text, '-'));

  return $text;
}
function getFirstSubSeccion($seccion)
{
  $query = "
    SELECT titulo
    FROM `secciones`
    WHERE `id_padre` = " . $seccion->id . "
    AND `activo` =1
    ORDER BY `secciones`.`id` ASC
    LIMIT 1";
  $result = mysql_query($query);
  return mysql_num_rows($result) ? "/" . getSlugify(mysql_fetch_object($result)->titulo) : '' ;
}
?>
<ul id="nav">
  <?php while($seccion = mysql_fetch_object($result)) : ?>
  <li<?php ($id_seccion==$seccion->id) and print ' class="activa"' ?>>
    <a href="/<?php ($seccion->id>1) and print getSlugify($seccion->titulo) /*. getFirstSubSeccion($seccion)*/ ?>"><span><?php echo $seccion->titulo ?></span></a>
  </li>
  <?php endwhile; ?>
  <!--<li><a href="http://foro.padelspain.com" target="_blank"><span>foro</span></a></li>-->
  <!--<li><a href="/contacto"><span>contacto</span></a></li>-->
</ul>
<script type="text/javascript">
initPage();function initPage(){initAutoScalingNav({menuId:"nav",tag:"span",minPaddings:3,spacing:2,sideClasses:true})}function initAutoScalingNav(d){if(!d.menuId){d.menuId="nav"}if(!d.tag){d.tag="a"}if(!d.spacing){d.spacing=0}if(!d.constant){d.constant=0}if(!d.minPaddings){d.minPaddings=0}if(!d.liHovering){d.liHovering=false}if(!d.sideClasses){d.sideClasses=false}if(!d.equalLinks){d.equalLinks=false}if(!d.flexible){d.flexible=false}var a=document.getElementById(d.menuId);if(a){a.className+=" scaling-active";var n=a.getElementsByTagName("li");var l=[];var e=[];var c=0;for(var g=0,f=0;g<n.length;g++){if(n[g].parentNode==a){var m=n[g].getElementsByTagName(d.tag).item(0);l.push(m);l[f++].width=m.offsetWidth;e.push(n[g]);if(c<m.offsetWidth){c=m.offsetWidth}}if(d.liHovering){n[g].onmouseover=function(){this.className+=" hover"};n[g].onmouseout=function(){this.className=this.className.replace("hover","")}}}var h=a.clientWidth-l.length*d.spacing-d.constant;if(d.equalLinks&&c*l.length<h){for(var g=0;g<l.length;g++){l[g].width=c}}c=b(l);if(c<h){var k=navigator.userAgent.toLowerCase();for(var g=0;b(l)<h;g++){l[g].width++;if(!d.flexible){l[g].style.width=l[g].width+"px"}if(g>=l.length-1){g=-1}}if(d.flexible){for(var g=0;g<l.length;g++){c=(l[g].width-d.spacing-d.constant/l.length)/h*100;if(g!=l.length-1){e[g].style.width=c+"%"}else{if(navigator.appName.indexOf("Microsoft Internet Explorer")==-1||k.indexOf("msie 8")!=-1||k.indexOf("msie 9")!=-1){e[g].style.width=c+"%"}}}}}else{if(d.minPaddings>0){for(var g=0;g<l.length;g++){l[g].style.paddingLeft=d.minPaddings+"px";l[g].style.paddingRight=d.minPaddings+"px"}}}if(d.sideClasses){e[0].className+=" first-child";e[0].getElementsByTagName(d.tag).item(0).className+=" first-child-a";e[e.length-1].className+=" last-child";e[e.length-1].getElementsByTagName(d.tag).item(0).className+=" last-child-a"}a.className+=" scaling-ready"}function b(j){var i=0;for(var o=0;o<j.length;o++){i+=j[o].width}return i}}/*if(window.addEventListener){window.addEventListener("load",initPage,false)}else{if(window.attachEvent){window.attachEvent("onload",initPage)}}*/;
</script>

