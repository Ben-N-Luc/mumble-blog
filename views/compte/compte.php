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
