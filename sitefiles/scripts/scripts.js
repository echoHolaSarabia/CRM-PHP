function linkSolapa(idsolapa,idgruposolapas,idsolapacontenedor,idsolapacontenedoron){
	RollSolapa(idgruposolapas,true);
	RollSolapa(idsolapa,false);
	RollSolapaContenedor(idsolapacontenedor)
	document.getElementById(idsolapacontenedoron).style.display='block';
}

function RollSolapa(id,estado){
	var par=document.getElementById(id);
	var clds=par.childNodes;
	for (var z0=0;z0<clds.length;z0++){
		if (clds[z0].nodeName=='DIV'){
			if(clds[z0].id!=''){
				RollSolapa(clds[z0].id,estado);
			}else{
				if(estado){
					clds[z0].className=clds[z0].className.replace('_On','_Off');
				}else{
					clds[z0].className=clds[z0].className.replace('_Off','_On');
				}
			}
		}
	}
}

function RollSolapaContenedor(id){
	var par=document.getElementById(id);
	var clds=par.childNodes;
	for (var z0=0;z0<clds.length;z0++){
		if (clds[z0].nodeName=='DIV'){
			clds[z0].style.display='none';
		}
	}
}

function Agrandar(id){
	first=id.substring(0,1);
	var par=document.getElementById(id);
	var clds=par.childNodes;
	for (var z0=0;z0<clds.length;z0++){
		if (clds[z0].nodeName=='DIV'){
			if(clds[z0].className==first+'Titulo3'){clds[z0].className=first+'Titulo4';}
			if(clds[z0].className==first+'Titulo2'){clds[z0].className=first+'Titulo3';}
			if(clds[z0].className==first+'Titulo1'){clds[z0].className=first+'Titulo2';}
			
			if(clds[z0].className==first+'Descripcion3'){clds[z0].className=first+'Descripcion4';}
			if(clds[z0].className==first+'Descripcion2'){clds[z0].className=first+'Descripcion3';}
			if(clds[z0].className==first+'Descripcion1'){clds[z0].className=first+'Descripcion2';}
			
			if(clds[z0].className==first+'Antetitulo3'){clds[z0].className=first+'Antetitulo4';}
			if(clds[z0].className==first+'Antetitulo2'){clds[z0].className=first+'Antetitulo3';}
			if(clds[z0].className==first+'Antetitulo1'){clds[z0].className=first+'Antetitulo2';}
			
			if(clds[z0].className==first+'Noticia3'){clds[z0].className=first+'Noticia4';}
			if(clds[z0].className==first+'Noticia2'){clds[z0].className=first+'Noticia3';}
			if(clds[z0].className==first+'Noticia1'){clds[z0].className=first+'Noticia2';}
			
			if(clds[z0].className==first+'ResultadoBusquedaDescripcion3'){clds[z0].className=first+'ResultadoBusquedaDescripcion4';}
			if(clds[z0].className==first+'ResultadoBusquedaDescripcion2'){clds[z0].className=first+'ResultadoBusquedaDescripcion3';}
			if(clds[z0].className==first+'ResultadoBusquedaDescripcion1'){clds[z0].className=first+'ResultadoBusquedaDescripcion2';}
			if(clds[z0].className==first+'ResultadoBusquedaCategoria3'){clds[z0].className=first+'ResultadoBusquedaCategoria4';}
			if(clds[z0].className==first+'ResultadoBusquedaCategoria2'){clds[z0].className=first+'ResultadoBusquedaCategoria3';}
			if(clds[z0].className==first+'ResultadoBusquedaCategoria1'){clds[z0].className=first+'ResultadoBusquedaCategoria2';}
			if(clds[z0].className==first+'TextoAzul223'){clds[z0].className=first+'TextoAzul224';}
			if(clds[z0].className==first+'TextoAzul222'){clds[z0].className=first+'TextoAzul223';}
			if(clds[z0].className==first+'TextoAzul221'){clds[z0].className=first+'TextoAzul222';}

		}
	}
}

function Achicar(id){
	first=id.substring(0,1);
	var par=document.getElementById(id);
	var clds=par.childNodes;
	for (var z0=0;z0<clds.length;z0++){
		if (clds[z0].nodeName=='DIV'){
			if(clds[z0].className==first+'Titulo2'){clds[z0].className=first+'Titulo1';}
			if(clds[z0].className==first+'Titulo3'){clds[z0].className=first+'Titulo2';}
			if(clds[z0].className==first+'Titulo4'){clds[z0].className=first+'Titulo3';}
			
			if(clds[z0].className==first+'Descripcion2'){clds[z0].className=first+'Descripcion1';}
			if(clds[z0].className==first+'Descripcion3'){clds[z0].className=first+'Descripcion2';}
			if(clds[z0].className==first+'Descripcion4'){clds[z0].className=first+'Descripcion3';}
			
			if(clds[z0].className==first+'Antetitulo2'){clds[z0].className=first+'Antetitulo1';}
			if(clds[z0].className==first+'Antetitulo3'){clds[z0].className=first+'Antetitulo2';}
			if(clds[z0].className==first+'Antetitulo4'){clds[z0].className=first+'Antetitulo3';}
			
			if(clds[z0].className==first+'Noticia2'){clds[z0].className=first+'Noticia1';}
			if(clds[z0].className==first+'Noticia3'){clds[z0].className=first+'Noticia2';}
			if(clds[z0].className==first+'Noticia4'){clds[z0].className=first+'Noticia3';}
			
			if(clds[z0].className==first+'ResultadoBusquedaDescripcion2'){clds[z0].className=first+'ResultadoBusquedaDescripcion1';}
			if(clds[z0].className==first+'ResultadoBusquedaDescripcion3'){clds[z0].className=first+'ResultadoBusquedaDescripcion2';}
			if(clds[z0].className==first+'ResultadoBusquedaDescripcion4'){clds[z0].className=first+'ResultadoBusquedaDescripcion3';}
			if(clds[z0].className==first+'ResultadoBusquedaCategoria2'){clds[z0].className=first+'ResultadoBusquedaCategoria1';}
			if(clds[z0].className==first+'ResultadoBusquedaCategoria3'){clds[z0].className=first+'ResultadoBusquedaCategoria2';}
			if(clds[z0].className==first+'ResultadoBusquedaCategoria4'){clds[z0].className=first+'ResultadoBusquedaCategoria3';}
			if(clds[z0].className==first+'TextoAzul222'){clds[z0].className=first+'TextoAzul221';}
			if(clds[z0].className==first+'TextoAzul223'){clds[z0].className=first+'TextoAzul222';}
			if(clds[z0].className==first+'TextoAzul224'){clds[z0].className=first+'TextoAzul223';}
			
				
		}
	}
}