<section class="tickets">
	<?php foreach($tickets as $ticket): ?>
		<article>
			<h3>
				<a href="<?= url('ticket/view/' . $ticket->ticket_id) ?>">
					<?= $ticket->ticket_subject ?>
				</a>
			</h3>
			<div class="msg">
				<?= $ticket->ticket_content ?>
			</div>
			<div class="infos">
				<ul>
					<li>
						Dernière réponse : <?= $ticket->ticket_date ?>
					</li>
					<li>
						Nombre de réponses : <?= $ticket->answers ?>
					</li>
					<li>
						<a href="<?= url('ticket/view/' . $ticket->ticket_id) ?>" class="btn btn-success">
							Répondre
						</a>
					</li>
					<li>
						<a href="<?= url('ticket/close/' . $ticket->ticket_id) ?>" class="btn btn-danger">
							Fermer
						</a>
					</li>
				</ul>
			</div>
		</article>
	<?php endforeach; ?>
</section>
