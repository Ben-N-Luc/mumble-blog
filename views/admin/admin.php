<section class="middle tickets">
	<h2>Tickets récents</h2>
	<?php foreach($tickets as $ticket): ?>
		<article>
			<h3><?= ucfirst($ticket->subject) ?></h3>
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
<section class="middle ">
	<h2>Derniers inscrits</h2>
</section>
