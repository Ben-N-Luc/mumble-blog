<h3>Édition de <?= ucfirst($user->pseudo) ?></h3>
<?php var_dump($user) ?>
<?php $this->Form->start() ?>
	<?= $this->Form->input('mail', array('value' => $user->mail, 'label' => 'Email')) ?>
	<?= $this->Form->select('rank', $ranks, array('value' => $user->rank)) ?>
	<?= $this->Form->submit() ?>
<?php $this->Form->end() ?>
