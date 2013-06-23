<section class="middle">
	<h3>Dernières news</h3>
	<?php if (empty($posts)): ?>
		<article>
			Pas de news pour le moment...
		</article>
	<?php endif ?>
	<?php foreach($posts as $post): ?>
		<article>
			<h3>
				<a href="<?= url('posts/view/' . $post->id) ?>"><?= $post->title ?></a>
				<small style="font-family: arial"><?= date('d-m-Y H:i', strtotime($post->date)) ?></small>
			</h3>
			<p>
				<?= substr($post->content, 0, 500) ?>...
			</p>
			<div class="actions">
				<a href="<?= url('posts/view/' . $post->id) ?>" class="more">Voir plus...</a>
			</div>
		</article>
	<?php endforeach ?>
</section>
<section class="middle">
	<h3>Liens utiles</h3>

	<article>
		<h4>Lorem</h4>
		<p>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		</p>
	</article>
</section>
<div class="clearfix"></div>
<p>
	<!--<audio autoplay loop controls>
		<source src="<?php echo url('sounds/nyan.ogg') ?>" type="audio/ogg">
		<source src="<?php echo url('sounds/nyan.mp3') ?>" type="audio/mpeg">
		Votre navigateur ne supporte pas le son...
	</audio>-->
</p>
