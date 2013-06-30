<section class="middle">
	<h2>Inscription</h2>
	<?= $this->Form->startForm(url(REQUEST_URI)) ?>
	<?= $this->Form->input('action', 'hidden', array('value' => 'signup')) ?>
	<?= $this->Form->input('pseudo', 'Pseudo') ?>
	<?= $this->Form->input('mail', 'Email', array('type' => 'mail')) ?>
	<?= $this->Form->input('password', 'Mot de passe', array('type' => 'password')) ?>
	<?= $this->Form->submit("S'inscrire", array('class' => 'btn')) ?>
	<?= $this->Form->endForm() ?>
</section>
<!-- <section class="middle">
	<h2>Connexion</h2>
	<form action="<?php //echo url(REQUEST_URI) ?>" method="post">
		<label for="pseudo">Pseudo</label>
		<input type="text" id="pseudo" name="pseudo" required>
		<label for="mdp">Mot de passe</label>
		<input type="password" id="mdp" name="mdp" required>
		<div class="actions">
			<input type="submit" value="Valider" class="btn">
		</div>
	</form>
</section>
<div class="clearfix"></div> -->
