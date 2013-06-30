<section class="middle" style="text-align: left;">
	<h2>Mumble Viewer</h2>
	<?php echo $viewer ?>
</section>
<section class="middle">
	<h2>Informations de connexion</h2>
	<p>
		<strong>Version minimale requise : </strong> <?php echo $url['version'] ?>
	</p>
	<p>
		<strong>Host : </strong> <?php echo $url['host'] ?>
	</p>
	<p>
		<strong>Port :</strong> <?php echo $url['port'] ?>
	</p>
	<p>
		<strong><a href="<?php echo $url[0] ?>">Se connecter directement</a></strong>
	</p>
</section>
<div class="clearfix"></div>
