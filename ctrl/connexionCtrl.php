<?php

class connexionCtrl extends Ctrl {

	public $uses = array(
		'Models' => array('User')
	);

	public function connexion() {
		if ($this->Request->posted) {
			// Inscription
			if ($this->Request->post->action == "signup") {
				$user = current($this->User->search(
					array('pseudo' => $this->Request->post->pseudo)
				));
				$mail = current($this->User->search(
					array('mail' => $this->Request->post->mail)
				));

				if($user) {
					$this->Form->errors['pseudo'] = 'Pseudo déjà utilisé';
				}
				if($mail) {
					$this->Form->errors['mail'] = 'Mail déjà utilisé';
				}

				if ($this->User->validates($this->Request->post)) {
					if ($this->User->add($data)) {
						$this->Session->setFlash('Vous êtes bien inscrit', 'success');
					} else {
						$this->Session->setFlash('Erreur interne, réessayez plus tard', 'error');
						$this->Log->add(array(
							'message' => "Erreur interne lors de l'inscription sur la requête " . $this->User->lastRequest,
							'token' => 'internal error'
						));
					}
				} else {
					$this->Session->setFlash('Erreur, vérifiez vos informations', 'error');
				}
			} else {
				// code...
			}
		}
	}
}
