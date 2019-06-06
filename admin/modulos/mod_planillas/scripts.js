function cambiar_capa (nombre_capa,num_capa,total){
  for (i=0;i<total;i++){
    document.getElementById(nombre_capa+i).style.display="none";
    $('#pestania'+i).css('background-color','#042B3F');
    $('#pestania'+i).css('color','#FFFFFF');
  }
  document.getElementById(nombre_capa+num_capa).style.display="block";
  $('#pestania'+num_capa).css('background-color','#D1E5EF');
  $('#pestania'+num_capa).css('color','#000000');
}

function muestra_contenido(id_elemento,tabla,num){
  if ($('#abiertocerrado-'+tabla+'-'+id_elemento).html()=="[+]"){
    $('#abiertocerrado-'+tabla+'-'+id_elemento).html("[-]");
    //$('#prev-'+tabla+'-'+id_elemento).html("<img src='/admin/images/loading2.gif'>");
    $.ajax({
      type: "POST",
      url: "modulos/mod_planillas/ajax/muestra_contenido.php",
      data: "id_elemento="+id_elemento+"&tabla="+tabla,
      success: function(datos){
        $('#prev-'+tabla+'-'+id_elemento).html(datos);
      }
    });
  }else{
    $('#abiertocerrado-'+tabla+'-'+id_elemento).html("[+]");
    $('#prev-'+tabla+'-'+id_elemento).html("");
  }
}

function serializar (num_subplanillas,num_tablas){
  serializar_planificadas(num_subplanillas);
  serializar_planificables(num_tablas);
  serializar_limites_columna(num_subplanillas);
  document.form_planillas.submit();
}

function serializar2 (num_subplanillas,num_tablas){
  serializar_planificadas(num_subplanillas);
  serializar_planificables(num_tablas);
}

function serializar_planificadas(num_subplanillas){
  var resultado = "";
  //Primero se serializa el rotador
  var cadena = $('#destino10').sortable('serialize');
  arr = cadena.split('&');
  unElemento = "";
  if (cadena == "") num_elementos = 0
  else num_elementos = arr.length;
  for (j=0;j<num_elementos;j++){
    elementoFinal = arr[j].substr(7);
    kk = elementoFinal.split(",");
    if (document.getElementById('item_'+kk[0]+','+kk[1]).title != "no-serializar"){
      unElemento += elementoFinal;
      /**/
      bloqueado = document.getElementById('bloqueado-'+kk[1]+"-"+kk[0]).value;
      unElemento += ","+bloqueado;
      /**/
      if (j<(num_elementos -1))
        unElemento += "/";
    }
  }
  resultado += unElemento;
  resultado += "#";

  //Despues se serializan todas las columnas en cada pestaña
  //Se recorren todas las columnas
  for (k=1;k<=6;k++){
    //Despues todas las pestañas
    for (i = 1; i<=num_subplanillas;i++){
      cadena = $('#destino'+k+i).sortable('serialize');
      arr = cadena.split('&');
      unElemento = "";
      var num_elementos = arr.length;

      //Al hacer el split la longutud del array vacío me da 1 por eso si la primera posicion del array es vacío el numero de elementos es 0.
      if (arr[0] == "")
        num_elementos=0;
      //Y dentro de cada página y cada columna se recorren todos los elementos
      for (j=0;j<num_elementos;j++){
        elementoFinal = arr[j].substr(7);
        kk = elementoFinal.split(",");
        if (document.getElementById('item_'+kk[0]+','+kk[1]).title != "no-serializar"){

          unElemento += elementoFinal;
          /**/
          bloqueado = document.getElementById('bloqueado-'+kk[1]+"-"+kk[0]).value;
          unElemento += ","+bloqueado;
          /**/

          if (j<(num_elementos -1))
            unElemento += "/";
        }

      }
      if (i<num_subplanillas)
        unElemento += "@";
      resultado += unElemento;
    }
    resultado += "#";
  }
  $('#listas').val(resultado);
}

function serializar_planificables (num_tablas){
  var resultado = "";
  var flag = true;
  for (i = 1; i<=num_tablas;i++){
    cadena = $('#origen'+i).sortable('serialize');
    arr = cadena.split('&');
    var unElemento = "";
    var num_elementos = arr.length;
    for (j=0;j<num_elementos;j++){

      var kk = arr[j].substr(7).split(",");
      var name = 'item_'+kk[0]+','+kk[1];
      var element = document.getElementById('item_'+kk[0]+','+kk[1]);

      if(element) {
        if ( element.title != "no-serializar" ){
          unElemento += arr[j].substr(7);
        } else {
          alert("Se excluyó de las noticias la " + '#item_'+kk[0]+','+kk[1] );
        }
      }

      if (j<(num_elementos -1))
        unElemento += "/";
    }
    resultado += unElemento;
    if (i<num_tablas && cadena!="")
      resultado += "/";
  }
  $('#no_planificadas').val(resultado);
}

/*
Serializa los imputs que indican el numero máximo de elementos que tiene una columna
*/
function serializar_limites_columna (num_subplanillas){
  var num_columnas = 6;
  var resultado = "";
  for (i = 1; i<=num_subplanillas;i++){
    for (j = 1; j<=num_columnas;j++){
      valor = document.getElementById('num_elem_col_'+j+'_hoja_'+i).value;
      resultado += valor;
      if (j<num_columnas) resultado += ",";
    }
    if (i<num_subplanillas) resultado += "/";
  }
  $('#num_elementos_x_columna').val(resultado);
}

function eliminar_elemento_de_planilla (id,tabla){
  document.getElementById('item_'+id+','+tabla).title="no-serializar";
  document.getElementById('item_'+id+','+tabla).style.display="none";
}

function serializar_noticia_ampliada (num_tablas){
  var resultado = "";
  for (i=1; i<=3;i++){
    cadena = $('#destino'+i).sortable('serialize');
    arr = cadena.split('&');
    var unElemento = "";
    var num_elementos = arr.length;
    for (j=0;j<num_elementos;j++){
      unElemento += arr[j].substr(7);
      if (j<(num_elementos -1))
        unElemento += "/";
    }
    if (i>1) resultado += "#"+unElemento;
    else resultado += unElemento;
  }
  serializar_planificables (num_tablas);
  $('#listas').val(resultado);
  document.form_planillas.submit();
}