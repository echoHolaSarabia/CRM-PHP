<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script>
	$(function(){
    //var header = $('#header').offset().top;
	var header = 269;
    $(window).scroll(function(){
        if( $(window).scrollTop() > header ) {   
		 	$('#contenido_a_mostrar33').css({position:'fixed', visibility:'hidden'});	         
		
        } else {
			$('#contenido_a_mostrar33').css({position:'fixed', top:'0px'});
			//$('#contenido_a_mostrar').css("display", "block");
           
					
        }
    });
});
</script>


<script>
	function muestra_oculta33(id){
	if (document.getElementById){ //se obtiene el id
	var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
	el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
	}
	}
	window.onload = function(){/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
	muestra_oculta('contenido_a_mostrar33');/* "contenido_a_mostrar" es el nombre de la etiqueta DIV que deseamos mostrar */
	}
</script>




<span id="contenido_a_mostrar33" style="display: block; z-index:100; width: 100%; height:50px; text-align:center; background-color:#2e2d2d; font-family:Arial, Helvetica, sans-serif; color:#eceaea; font-size:13px;">
	<div style="width: 990px; margin:0 auto; line-height: 18px; text-align:left; padding-top: 6px; padding-left: 31px;">
    EDS21 utiliza cookies propias y de terceros para mejorar tu experiencia de navegaci&oacute;n y realizar tareas de an&aacute;lisis <br/>
    Al continuar con tu navegaci&oacute;n entendemos que das tu consentimiento a nuestra <a href="http://www.padelspain.net/politicacookies.php" target="_blank" style="color:#eceaea; text-decoration:underline;">politica de cookies.</a> 
    <a style="cursor: pointer;" onclick="muestra_oculta33('contenido_a_mostrar33')" ><img src="http://www.rrhhdigital.com/sitefiles/img/continuar.jpg" style="position: absolute; margin-top: -13px; margin-left: 252px; border:0;"></a>
    </div>
</span>
