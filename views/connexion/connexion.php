<section class="middle">
	<h2>Inscription</h2>
	<?= $this->Form->startForm(url(REQUEST_URI)) ?>
	<?= $this->Form->input('action', 'hidden', array('value' => 'signup')) ?>
	<?= $this->Form->input('pseudo', 'Pseudo') ?>
	<?= $this->Form->input('mail', 'Email', array('type' => 'email')) ?>
	<?= $this->Form->input('password', 'Mot de passe', array('type' => 'password')) ?>
	<?= $this->Form->submit("S'inscrire", array('class' => 'btn')) ?>
	<?= $this->Form->endForm() ?>
</section>

<section class="middle">
	<h2>Connexion</h2>
	<?= $this->Form->startForm(url(REQUEST_URI)) ?>
	<?= $this->Form->input('action', 'hidden', array('value' => 'signin')) ?>
	<?= $this->Form->input('log_pseudo', 'Pseudo') ?>
	<?= $this->Form->input('log_password', 'Mot de passe', array('type' => 'password')) ?>
	<?= $this->Form->submit("Connexion", array('class' => 'btn')) ?>
	<?= $this->Form->endForm() ?>
</section>
