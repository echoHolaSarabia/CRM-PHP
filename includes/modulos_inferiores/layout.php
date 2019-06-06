<div class="modInferior">
	<div class="modInferiorTitulo">
		<h1 style="background-image: url('/sitefiles/img/modulo_inferior/<?php echo $imagen; ?>')">
			<a href="<?php echo $enlace; ?>">+ <?php echo $titulo; ?></a>
		</h1>
	</div>
	<?php if( count($notinferiores) > 0 ) : ?>	
	<?php foreach ( $notinferiores as $notinferior ) : ?>
	<div class="notinferior">
		<img alt="" src="<?php echo $notinferior["img_cuadrada"]; ?>" />
		<div>
			<h3><?php echo $notinferior["antetitulo"]; ?></h3>
			<h2><?php echo $notinferior["titulo"]; ?></h2>
			<p><?php echo strip_tags(substr($notinferior["entradilla"], 0, 200)); ?>... <a href="<?php echo $enlace . $notinferior["id"] . "/" ; ?>">ampliar</a></p>
		</div>
	</div>
<!--	<hr/>-->
	<?php endforeach; ?>
	<?php endif; ?>
</div>
