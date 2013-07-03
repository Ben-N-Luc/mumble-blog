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
				} elseif($mail) {
					$this->Form->errors['mail'] = 'Mail déjà utilisé';
				} elseif ($this->User->validates($this->Request->post)) {
					$this->Request->post->password = sha1($this->Request->post->password);
					unset($this->Request->post->action);
					$this->User->add($this->Request->post);
					$this->Request->reset('post');
					$this->Session->setFlash('Vous êtes bien inscrit', 'success');
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
					if (!$user) {
						$this->Form->errors['log_pseudo'] = 'Pseudo incorrect ou innexistant.';
					}
					if ($user && $user->password != sha1($this->Request->post->log_password)) {
						$this->Form->errors['log_password'] = 'Mot de passe incorrect.';
					}

					$this->Session->setFlash('Erreur lors de la connexion', 'error');
				}
			}
		}
	}
}
