$(document).ready(
	function () {
		$('div.seccion').Sortable(
			{
				accept: 'groupItem',
				helperclass: 'sortHelper',
				activeclass : 	'sortableactive',
				hoverclass : 	'sortablehover',
				handle: 'div.itemHeader',
				tolerance: 'pointer',
				onChange : function(ser)
				{
				},
				onStart : function()
				{
					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
				},
				onStop : function()
				{
					$.iAutoscroller.stop();
				}
			}
		);
		$('div.groupItem').Sortable({});
		
		
		$('div.itemHeader').bind('click', toggleContent);
		$('h3.cabeceraNoticias').bind('click', toggleNoticias);
		$('h3.cabeceraRecursos').bind('click', toggleRecursos);
		$('#tipo').change(function () {
          if ($(this).val() == "tres_columnas"){
			$('#sort0').css("display","none");
          }else{
          	$('#sort0').css("display","");
          }
          if ($(this).val() == "una_destacada"){
          	$('#explicacion').html("Debe incluir <b><u>una</u></b> noticia en este espacio");
          }
          if ($(this).val() == "varias_destacadas"){
          	$('#explicacion').html("Debe incluir <b><u>cuatro</u></b> noticias en este espacio");
          }
        });
	}
);
var toggleContent = function(e)
{
	var targetContent = $('div.itemContent', this.parentNode);
	if (targetContent.css('display') == 'none') {
		targetContent.slideDown(300);
		//$(this).html('[-]');
	} else {
		targetContent.slideUp(300);
		//$(this).html('[+]');
	}
	return false;
};

var toggleNoticias = function(e)
{
	var targetContent = $('div.grupoNoticia', this.parentNode);
	if (targetContent.css('display') == 'none') {
		targetContent.slideDown(300);
		//$(this).html('[-]');
	} else {
		targetContent.slideUp(300);
		//$(this).html('[+]');
	}
	return false;
};
var toggleRecursos = function(e)
{
	var targetContent = $('div.grupoRecurso', this.parentNode);
	if (targetContent.css('display') == 'none') {
		targetContent.slideDown(300);
		//$(this).html('[-]');
	} else {
		targetContent.slideUp(300);
		//$(this).html('[+]');
	}
	return false;
};
function serialize(s)
{
	if ($('#fecha_publicacion').val() == ""){
		alert("Debe seleccionar una fecha para la publicación de la portada");
		return;
	}
	
	var sort0 = "";
	$("#sort0").children(".groupItem").each(function(i) {
	  var li = $(this);
	  sort0 = sort0 + li.attr("id") + ",";
	});
	var sort1 = "";
	$("#sort1").children(".groupItem").each(function(i) {
	  var li = $(this);
	  sort1 = sort1 + li.attr("id") + ",";
	});
	var sort2 = "";
	$("#sort2").children(".groupItem").each(function(i) {
	  var li = $(this);
	  sort2 = sort2 + li.attr("id") + ",";
	});
	var sort3 = "";
	$("#sort3").children(".groupItem").each(function(i) {
	  var li = $(this);
	  sort3 = sort3 + li.attr("id") + ",";
	});
	
	var cadena = sort0 + "%" + sort1 + "%" + sort2 + "%" + sort3;
	$("#portada").val(cadena);
	document.form_portadas.submit();
};