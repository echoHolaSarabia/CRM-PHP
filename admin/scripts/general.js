/*
Chequea todo un listado de opciones
*/
function checkAll( n, fldName ) {
	if (!fldName) {
		fldName = 'seleccion';
	}
	var f = document.form_listado_seleccion;
	var c = f.seleccion.checked;
	var n2 = 0;
	for (i=0; i < n; i++) {
		cb = eval( 'f.' + fldName + '' + i );
	 	if (cb) {
	  		cb.checked = c;
	  		n2++;
	 	}
	}
}

/*
Muestra u oculta las subsecciones de cada sección dependiendo del id de la sección que le pases.
Además cambia la imagen del mas  por la del manos o viceversa.
*/
function muestra_oculta (nombre_capa,id){
	var etiqueta = nombre_capa + id;
	var imagen = "mas_menos_"+id;
	if (document.getElementById(etiqueta).style.display == ""){
		document.getElementById(etiqueta).style.display = 'none';
		document.getElementById(imagen).src = "images/mas.png";
	} else {
		document.getElementById(etiqueta).style.display = "";
		document.getElementById(imagen).src = "images/menos.png";
	}
}

/*
Cambia el contenido de las pestañas del módulo de noticias
*/
function cambia_opciones(visible, num_elementos)  {
	for (var i = 1; i<=num_elementos; i++) {
		document.getElementById("opcion"+i).style.display="none";
	}
	document.getElementById("opcion"+visible).style.display="";
}


function haz_submit (nombre_form,direccion){
	// He quitado esto (form = eval(nombre_form);) y he puesto directamente el nombre del formulario porque no funcionaba en explorer
	document.form_listado_seleccion.action = direccion;
	document.form_listado_seleccion.submit();
}

function recarga_provincias(id_pais){
		$.ajax({
	        type: "POST",
	        url: "ajax/recarga_provincias.php",
	        data: "id_pais="+id_pais,
	        success: function(datos){
	       		$('#provincia').html(datos);
	      	}
		});
}