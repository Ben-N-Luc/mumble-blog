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
					$this->Request->post->password = sha1($this->Request->post->password);
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
					$this->Form->errors = $this->User->errors;
				}
			} else {
				// Connexion
				$user = current($this->User->search(
					array('pseudo' => $this->Request->post->log_pseudo)
				));

				if ($user && $user->password == sha1($this->Request->post->log_password)) {
					unset($user->password);
					$this->Session->auth($user);
					$this->Session->setFlash('Vous êtes bien connecté', 'success');
					$this->redirect(url());
				}
				else {
					$this->Form->errors['log_pseudo'] = 'Pseudo incorrect ou innexistant.';
					if ($user->password != sha1($this->Request->post->log_password)) {
						$this->Form->errors['log_password'] = 'Mot de passe incorrect.';
					}

					$this->Session->setFlash('Erreur lors de la connexion', 'error');
				}
			}
		}
	}
}
