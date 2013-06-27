<h2 class="pull-left">Tickets de <?= $user ?></h2>
<a href="<?= url('ticket/nouveau') ?>" class="btn btn-info pull-right">Nouveau ticket</a>
<div class="clearfix"></div>
<section class="tickets">
	<?php if(!$tickets): ?>
		<div class="alert alert-success">
			Bravo, pas de tickets pour le moment !
		</div>
	<?php endif ?>
	<?php foreach($tickets as $ticket): ?>
		<article>
			<h3>
				<a href="<?= url('ticket/view/' . $ticket->id) ?>">
					<?= $ticket->subject ?>
				</a>
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
					<li>Utilisateur : <?= $ticket->pseudo ?></li>
					<li>Réponses : <?= $ticket->answers ?></li>
					<li>Dernière réponse : <?= $ticket->date ?></li>
					<li><?= $this->badge($ticket->type) ?></li>
					<li>
						<a href="<?= url('ticket/view/' . $ticket->id) ?>" class="btn">
							Voir le ticket
						</a>
					</li>
					<li>
						<?php if ($ticket->closed): ?>
							<a href="<?= url('ticket/close/' . $ticket->id) ?>" class="btn btn-success">Rouvrir</a>
						<?php else: ?>
							<a href="<?= url('ticket/close/' . $ticket->id) ?>" class="btn btn-danger">
								Fermer
							</a>
						<?php endif ?>
					</li>
				</ul>
			</div>
		</article>
	<?php endforeach; ?>
</section>
