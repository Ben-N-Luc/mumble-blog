<section class="tickets">
	<?php foreach($posts as $post): ?>
		<article>
			<h3><?= $post->title ?></h3>
			<div class="msg">
				<?= $post->content ?>
			</div>
			<div class="infos">
				<ul>
					<li><?= date('d-m-Y H:i', strtotime($post->date)) ?></li>
					<li><a href="<?= url('posts/view/' . $post->id) ?>" class="">Voir plus...</a></li>
				</ul>
			</div>
		</article>
	<?php endforeach; ?>
</section>
