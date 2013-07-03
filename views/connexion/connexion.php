<section class="middle">
	<h2>Inscription</h2>
	<?= $this->Form->start() ?>
	<?= $this->Form->input('action', array('value' => 'signup', 'type' => 'hidden')) ?>
	<?= $this->Form->input('pseudo', array('label' => 'Pseudo')) ?>
	<?= $this->Form->input('mail', array('type' => 'email', 'label' => 'Email')) ?>
	<?= $this->Form->input('password', array('type' => 'password', 'label' => 'Mot de passe')) ?>
	<?= $this->Form->submit("S'inscrire") ?>
	<?= $this->Form->end() ?>
</section>

<section class="middle">
	<h2>Connexion</h2>
	<?= $this->Form->start(null, array('alignment' => 'right')) ?>
	<?= $this->Form->input('action', array('value' => 'signin', 'type' => 'hidden')) ?>
	<?= $this->Form->input('log_pseudo', array('label' => 'Pseudo')) ?>
	<?= $this->Form->input('log_password', array('type' => 'password', 'label' => 'Mot de passe')) ?>
	<?= $this->Form->submit("Connexion") ?>
	<?= $this->Form->end() ?>
</section>
