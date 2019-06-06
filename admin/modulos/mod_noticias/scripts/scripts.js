function eliminarFoto(campo){
  if (confirm("¿Seguro que desea eliminar la imagen?")){
    document.getElementById("miniatura_"+campo).innerHTML = "";
    document.getElementById(campo).value = "";
  }
}

function recarga_secciones (obj){
  var id_seccion = obj;
  $.ajax({
    type: "POST",
    url: "modulos/mod_noticias/ajax/recarga_secciones.php",
    data: "id_seccion="+id_seccion,
    success: function(datos){
      if (datos == "") {
        $('.tr_subseccion').hide();
      } else {
        $('.tr_subseccion').show();
        $('#subseccion').html(datos);
      }
      recargar_planillas(id_seccion,id_seccion);
    }
  });
}

function recargar_planillas(obj,id_seccion){
  if(id_seccion==4) obj = 4;
  if (isNaN(obj))
    var id_seccion = obj.value;
  else if (obj) id_seccion = obj;
  if (undefined==id_seccion) id_seccion="nada";
  $.ajax({
    type: "POST",
    url: "modulos/mod_noticias/ajax/recargar_planillas.php",
    data: "id_seccion="+id_seccion,
    success: function(datos){
      $('#planillas_seccion').html(datos);
    }
  });
}

function cambia_fuente_video(obj){
  if (obj.options[obj.selectedIndex].value == "cod_video"){
    document.getElementById('tipo1').style.display = '';
    document.getElementById('tipo2').style.display = 'none';
    document.getElementById('tipo3').style.display = 'none';
    document.getElementById('cod_video').disabled=false;
    document.getElementById('fichero').disabled=true;
    document.getElementById('importar').disabled=true;
  } else if (obj.options[obj.selectedIndex].value == "fichero"){
    document.getElementById('tipo1').style.display = 'none';
    document.getElementById('tipo2').style.display = '';
    document.getElementById('tipo3').style.display = 'none';
    document.getElementById('cod_video').disabled=true;
    document.getElementById('fichero').disabled=false;
    document.getElementById('importar').disabled=true;
  } else {
    document.getElementById('tipo1').style.display = 'none';
    document.getElementById('tipo2').style.display = 'none';
    document.getElementById('tipo3').style.display = '';
    document.getElementById('cod_video').disabled=true;
    document.getElementById('fichero').disabled=true;
    document.getElementById('importar').disabled=false;
    document.form_noticia.exportar.disabled=true;
  }
}

function eliminar_video(){
  document.getElementById('preview').innerHTML = "El video se eliminará definitivamente cuando guarde el elemento";
  document.getElementById('cod_video').value="";
}

function cambia_fuente_audio(obj){
  if (obj.options[obj.selectedIndex].value == "cod_audio"){
    document.getElementById('tipo1_audio').style.display = '';
    document.getElementById('tipo2_audio').style.display = 'none';
    document.getElementById('tipo3_audio').style.display = 'none';
    document.getElementById('cod_audio').disabled=false;
    document.getElementById('fichero_audio').disabled=true;
    document.getElementById('importar_audio').disabled=true;
  } else if (obj.options[obj.selectedIndex].value == "fichero_audio"){
    document.getElementById('tipo1_audio').style.display = 'none';
    document.getElementById('tipo2_audio').style.display = '';
    document.getElementById('tipo3_audio').style.display = 'none';
    document.getElementById('cod_audio').disabled=true;
    document.getElementById('fichero_audio').disabled=false;
    document.getElementById('importar_audio').disabled=true;
  } else {
    document.getElementById('tipo1_audio').style.display = 'none';
    document.getElementById('tipo2_audio').style.display = 'none';
    document.getElementById('tipo3_audio').style.display = '';
    document.getElementById('cod_audio').disabled=true;
    document.getElementById('fichero_audio').disabled=true;
    document.getElementById('importar_audio').disabled=false;
    document.form_noticia.exportar.disabled=true;
  }
}

function eliminar_audio(){
  document.getElementById('audio').innerHTML = "El audio se eliminará definitivamente cuando guarde el elemento";
  document.getElementById('cod_audio').value="";
}

/*
Cambia el contenido de las pestaï¿½as del cuadro de audiovisual
*/
function cambia_etiqueta_audiovisual(visible, num_elementos)  {
  for (var i = 1; i<=num_elementos; i++) {
    document.getElementById("audiovideo"+i).style.display="none";
  }
  document.getElementById("audiovideo"+visible).style.display="";
}


function valida_datos (form){
  txt = eval(form);
  if (txt.autor.value == ""){
    alert("Debe rellenar el campo autor");
    return false;
  }
  if (txt.titulo.value == ""){
    alert("La noticia debe tener un titular");
    return false;
  }
  if (txt.seccion.options[txt.seccion.selectedIndex].value == ""){
    alert("Debe asociar la noticia a una sección");
    return false;
  }
  //if (txt.multimedias.value != "") txt.multimedia.value = txt.multimedias.value;
  //alert (txt.multimedia.value);
  document.form_noticia.submit();
}

function guardar_y_volver(form,accion,extra){
  document.form_noticia.action = "?modulo=mod_noticias&fns=1&accion="+accion+"&redireccion=si"+extra;
  document.form_noticia.submit();
}


function borrar_imagen (imagen, id, tipo){
  if (confirm("ï¿½Desea eliminar la imagen"+imagen+"?")){
    document.location.href = "index2.php?modulo=mod_noticias&fns=1&accion=borra_imagen&id="+id+"&imagen="+imagen+"&tipo="+tipo;
  }
}

function borrar_doc (doc, id, tipo){
  if (confirm("ï¿½Desea eliminar el documento "+doc+"?")){
    document.location.href = "index2.php?modulo=mod_noticias&fns=1&accion=borra_doc&id="+id+"&doc="+doc+"&tipo="+tipo;
  }
}