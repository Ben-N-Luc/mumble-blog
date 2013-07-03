<section class="tickets">
	<article>
		<h3>
			<?= $tickets['master']->subject ?>
			<small style="vertical-align: middle;">
				<span class="badge badge-<?= $tickets['master']->closed ? 'important' : 'success' ?>">
					<?= $tickets['master']->closed ? 'fermé' : 'ouvert' ?>
				</span>
			</small>
		</h3>
		<div class="msg">
			<?= $tickets['master']->content ?>
		</div>
		<div class="infos">
			<ul>
				<li>
					Utilisateur :
					<a href="<?= url('ticket/ticket/' . $tickets['master']->user_id) ?>"><?= $tickets['master']->pseudo ?></a>
				</li>
				<li><?= $this->badge($tickets['master']->type) ?></li>
				<li>Date : <?= $tickets['master']->date ?></li>
			</ul>
		</div>
	</article>
	<?php foreach($tickets['answers'] as $answer): ?>
		<article>
			<h3>RE : <?= $tickets['master']->subject ?></h3>
			<div class="msg">
				<?= $answer->content ?>
			</div>
			<div class="infos">
				<ul>
					<li>Date : <?= $answer->date ?></li>
				</ul>
			</div>
		</article>
	<?php endforeach; ?>
	<form action="<?= url('ticket/view/' . $id) ?>" method="post">
		<label for="content"><h3>Répondre</h3></label>
		<textarea name="content" id="content" class="input-xxlarge" rows="6"></textarea>
		<div class="controls">
			<input type="submit" class="btn">
			<a href="<?= url('ticket/close/' . $id) ?>" class="btn btn-<?= ($tickets['master']->closed) ? 'success' : 'danger' ?>">
				<?= ($tickets['master']->closed) ? 'Rouvrir' : 'Fermer' ?>
			</a>
		</div>
	</form>
</section>
