<h2>Édition de "<?= ucfirst($user->pseudo) ?>"</h2>
<?= $this->Form->start() ?>
	<?= $this->Form->input('mail', array('value' => $user->mail, 'label' => 'Email')) ?>
	<?= $this->Form->select('rank', $ranks, array('value' => $user->rank, 'label' => 'Rank')) ?>
	<?= $this->Form->submit() ?>
<?= $this->Form->end() ?>
