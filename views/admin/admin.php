<section class="middle tickets">
	<h2>Tickets récents</h2>
	<?php foreach($tickets as $ticket): ?>
		<article>
			<h3><a href="<?= url('ticket/view/' . $ticket->id) ?>"><?= ucfirst($ticket->subject) ?></a></h3>
			<div class="msg">
				<?= $ticket->content ?>
			</div>
			<div class="infos">
				<ul>
					<li><?= $ticket->date ?></li>
					<li><?= ucfirst($ticket->type) ?></li>
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
