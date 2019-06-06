function ancla(pstrId){
	var yPos = document.getElementById(pstrId).offsetTop;
	if(document.all){
		yPos = parseInt(yPos) + parseInt(300);
	}
	window.scrollTo(0,yPos);
}

//ancla es "comentarios-"+id_comentario_buscado
function mostrar_comentarios(id,pag,tipo,ancla_comentario_buscado){
	$.ajax({ type: "POST",
	 url: "/ajax/mostrar_comentarios.php", 
	 data: "pag="+pag+"&id="+id+"&tipo="+tipo+"&ancla_comentario_buscado="+ancla_comentario_buscado,
	 success: function(datos){ 
	 	document.getElementById('capa_comentarios').innerHTML=(datos);
	 	if(ancla_comentario_buscado!="") ancla(ancla_comentario_buscado); 
	 } 
	}); 
}

function validarEmail(valor) {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(valor)){
		return (true);
	} else {
		return (false);
	}
	}

function enviar_comentario(id,tipo){
	if (document.getElementById("enviarForm").nombre.value==""){
		alert("Debe introducir su nombre.");
	}
	else if (document.getElementById("enviarForm").email.value==""){
		alert("Debe introducir un email.");
	}
	else if(!validarEmail(document.getElementById("enviarForm").email.value)){
		alert("El email es incorrecto.");
	}
	else if (document.getElementById("enviarForm").coment.value==""){
		alert("Debe introducir un comentario.");
	}
	else {
		nombre = document.getElementById("enviarForm").nombre.value;
		email = document.getElementById("enviarForm").email.value;
		coment = document.getElementById("enviarForm").coment.value;
		document.getElementById("enviarForm").nombre.value = "";
		document.getElementById("enviarForm").email.value = "";
		document.getElementById("enviarForm").coment.value = "";
				
		$.ajax({ type: "POST",
			url: "/ajax/anadir_comentario.php", 
			data: "id="+id+"&tipo="+tipo+"&coment="+coment+"&nombre="+nombre+"&email="+email,
	 				success: function(datos){
	 					document.getElementById('capa_comentarios2').innerHTML=(datos); 
	 					document.getElementById('capa_comentarios2').innerHTML="<div style='font-family:Arial, Helvetica, sans-serif; color:#C00; float:left; height:55px;'>Su comentario ha sido insertado correctamente, pero ha de ser aprobado por un moderador antes de visualizarse.</div>"; 
						mostrar_comentarios(id,1,tipo,"");
	 			} 
			}); 
	}
}


function sumar_1_hit(id,tabla){
	$.ajax({ type: "POST",
	 url: "/ajax/sumar_1_hit.php", 
	 data: "id="+id+"&tabla="+tabla,
	 success: function(datos){ 
	 	//document.getElementById('capa_hits').innerHTML=(datos);
	 } 
	}); 
}