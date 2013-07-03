<section class="middle">
	<h2>Votre compte</h2>
	<div class="account">
		<div class="img">
			<img src="<?php echo url("img/users/1.jpg") ?>" alt="">
		</div>
		<ul>
			<li>
				<?= $user->pseudo ?>
			</li>
			<li>
				<?= $user->mail ?>
			</li>
			<li>
				<?= ($user->rank == 'a') ? 'Administrateur' : 'Utilisateur' ?>
			</li>
		</ul>
		<div class="actions">
			<a href="<?= url("compte/edit") ?>" class="btn">Éditer votre compte</a>
			<a href="<?= url("compte/delete") ?>" class="btn btn-danger confirm">Supprimer votre compte</a>
		</div>
	</div>
</section>
<section class="middle tickets">
	<h2>Vos dernières demandes</h2>
	<?php if(empty($tickets)): ?>
		Pas de ticket ouvert pour le moment...
	<?php endif ?>
	<?php foreach($tickets as $ticket): ?>
		<article>
			<h3>
				<a href="<?= url('ticket/view/' . $ticket->id) ?>"><?= ucfirst($ticket->subject) ?></a>
				<small style="vertical-align: middle;">
					<a class="badge badge-<?= $ticket->closed ? 'important' : 'success' ?>">
						<?= $ticket->closed ? 'fermé' : 'ouvert' ?>
					</a>
				</small>
			</h3>
			<div class="msg">
				<?= $ticket->content ?>
			</div>
			<div class="infos">
				<ul>
					<li><?= date(Conf::$dateFormat, strtotime($ticket->last_answer)) ?></li>
					<li><?= $this->badge($ticket->type) ?></li>
				</ul>
			</div>
		</article>
	<?php endforeach; ?>
</section>
