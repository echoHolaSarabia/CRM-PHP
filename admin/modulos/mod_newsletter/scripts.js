function valida_datos (form,redireccion){
	txt = eval(form);
	if (txt.nombre.value == ""){
		alert("Debe rellenar el nombre de la newsletter");
		return false;
	}
	if (txt.asunto.value == ""){
		alert("Debe rellenar el asunto de la newsletter");
		return false;
	}
	
	var resultado = "";
	for (i=1; i<=2;i++){
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
	$('#listas').val(resultado);
	document.form_newsletter.redireccion.value=redireccion;
	document.form_newsletter.submit();
}
  
function muestra_suscriptores(){
 	window.open("modulos/mod_suscriptores/listado_suscriptores.php","Preview","width=800,height=600,toolbar=no,directories=no,menubar=no,status=no,scrolling=yes");
}

 
 
 