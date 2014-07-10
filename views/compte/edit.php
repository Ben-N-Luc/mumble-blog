<h2>Éditer vos informations</h2>

<section class="middle">
    <h3>Modifier le mot de passe</h3>
    <?= $this->Form->start() ?>
        <?= $this->Form->input('action', array('value' => 'pass_edit', 'type' => 'hidden')) ?>
        <?= $this->Form->input('old_password', array('type' => 'password', 'label' => 'Ancien mot de passe')) ?>
        <?= $this->Form->input('password1', array('type' => 'password', 'label' => 'Nouveau mot de passe')) ?>
        <?= $this->Form->input('password2', array('type' => 'password', 'label' =>'Confirmation')) ?>
        <?= $this->Form->submit('Modifier') ?>
    <?= $this->Form->end() ?>
</section>
<section class="middle">
    <h3>Modifier l'email</h3>
    <?= $this->Form->start(null, array('alignment' => 'right')) ?>
        <?= $this->Form->input('action', array('value' => 'mail_edit', 'type' => 'hidden')) ?>
        <?= $this->Form->input('mail', array('type' => 'email', 'value' => $email, 'label' => 'Nouvelle adresse email')) ?>
        <?= $this->Form->submit('Modifier') ?>
    <?= $this->Form->end() ?>
</section>
