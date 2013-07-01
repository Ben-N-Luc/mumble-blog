<section class="middle tickets">
	<h2>Tickets récents</h2>
	<?php if (empty($tickets)): ?>
		<div class="alert alert-success">Bravo ! Pas de ticket à traiter...</div>
	<?php endif ?>
	<?php foreach($tickets as $ticket): ?>
		<article>
			<h3>
				<a href="<?= url('ticket/view/' . $ticket->id) ?>"><?= ucfirst($ticket->subject) ?></a>
				<small style="vertical-align: middle">
					<a href="<?= url('ticket/ticket/' . (($ticket->closed) ? 'closed' : 'opened')) ?>" class="badge badge-<?= ($ticket->closed) ? 'important' : 'success' ?>">
						<?= ($ticket->closed) ? 'Fermé' : 'Ouvert' ?>
					</a>
				</small>
			</h3>
			<div class="msg">
				<?= $ticket->content ?>
			</div>
			<div class="infos">
				<ul>
					<li><a href="<?= url('ticket/ticket/' . $ticket->user_id) ?>"><?= $ticket->pseudo ?></a></li>
					<li><?= $ticket->last_answer ?></li>
					<li><?= $this->badge($ticket->type) ?></li>
				</ul>
			</div>
		</article>
	<?php endforeach; ?>
</section>
<section class="middle tickets">
	<h2>Derniers inscrits</h2>
	<?php foreach($users as $user): ?>
		<article>
			<h3><a href="<?= url('admin/users-view/' . $user->id) ?>"><?= $user->pseudo ?></a></h3>
			<div class="msg">
				<ul>
					<li>id : <?= $user->id ?></li>
					<li>mail : <?= $user->mail ?></li>
					<li>rang : <?= $rank_translation[$user->rank] ?></li>
				</ul>
			</div>
		</article>
	<?php endforeach; ?>
</section>
