<section>
	<article class="text-justify">
		<h2><?= $post->title ?> <small><?= $post->date ?></small></h2>
		<div class="msg">
			<?= $post->content ?>
		</div>
		<div class="infos">
			<ul>
				<li>Rédigé par : <?= $post->pseudo ?></li>
			</ul>
		</div>
	</article>
</section>
