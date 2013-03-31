<?php

class contactCtrl extends Ctrl {

	public $champs = array(
		'mail',
		'pseudo',
		'subject',
		'type',
		'msg'
	);

	public $uses = array(
		'Models' => array('User', 'Ticket')
	);

	public $allowed = 'connected';

	public function contact() {

		// Renvoi de l'adresse mail
		$user = $this->Session->read('user');
		if(!isset($user->mail)) {
			$user->mail = false;
		}
		$this->set('mail', $user->mail);

		// test du formulaire
		if(!empty($this->Post)) {
			if(object_keys($this->Post) == $this->champs) {
				if(filter_var($this->Post->mail, FILTER_VALIDATE_EMAIL)) {
					$user = $this->Session->read('user');

					$data = array(
						'subject' => $this->Post->subject, ENT_HTML5, 'utf-8',
						'content' => $this->Post->msg, ENT_HTML5, 'utf-8',
						'type' => $this->Post->type,
						'date' => time(),
						'user_id' => $user->id,
						'mail' => $this->Post->mail
					);
					if ($this->Ticket->add($data)) {
						$this->Session->setFlash("Votre message a bien été pris en compte !");
					} else {
						$this->Session->setFlash("Erreur lors de l'écriture en bdd !", 'error');
					}

					// envoie du mail sur l'adresse partagée
					$mail = new Mail(
						$this->Post->mail,
						'Formulaire de contact Mumble',
						'Nouveau message sur le <a href="mumble.wtgeek.be">site</a>, de la part de '.
						$this->Post->pseudo . ', sujet : ' . $this->Post->subject . ', type : ' . $this->Post->type
					);
					if($mail->send()) {
						$this->Session->setFlash('Votre message à bien été envoyé', 'success');
					} else {
						$this->Session->setFlash("Erreur lors de l'envoi du mail à un admin, il ne verra pas le ticket avant sa prochaine connexion...", 'error');
					}
				} else {
					$this->Session->setFlash('Mauvaise adresse Email', 'error');
				}
			} else {
				$this->Session->flash('Tous les champs sont requis', 'error');
			}

		}
	}


}

?>