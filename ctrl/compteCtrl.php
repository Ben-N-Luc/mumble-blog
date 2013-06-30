<?php

class compteCtrl extends Ctrl {

	public $uses = array(
		'Models' => array('User', 'Ticket')
	);
	public $allowed = 'connected';

	public function compte() {
		$user = $this->Session->read('user');
		$tickets = $this->Ticket->search(array(
				'user_id' => $user->id,
				'closed' => 0
			),
			array(
				'limit' => '4',
				'order' => array(
					'field' => 'date',
					'order' => 'desc'
				)
			)
		);

		$this->set('tickets', $tickets);
		$this->set('user', $user);
	}

	public function delete() {
	}

	public function edit() {
		$user = $this->Session->read('user');
		$user = current($this->User->search(array(
			'id'	=> $user->id
		)));

		if ($this->Request->post) {
			// Changement de mot de passe
			if ($this->Request->post->action == "pass_edit") {
				unset($this->Request->post->action);
				if ($user->password == sha1($this->Request->post->old_password)) {
					if ($this->User->validates($this->Request->post) && $this->Request->post->password1 == $this->Request->post->password2) {
						$new_user->password = $this->Request->post->password1;
						$this->User->update(array('id' => $user->id), $new_user);
						$this->Request->post = new stdClass();
						$this->Session->setFlash('Mot de passe modifié avec succès !', 'success');
					}
					else {
						$this->User->errors['password2'] = 'Les deux mots de passe doivent être identique';
						$this->Session->setFlash('Erreur !', 'error');
					}
				}
				else {
					$this->User->errors['old_password'] = 'Le mot de passe est incorrect.';
					$this->Session->setFlash('Erreur !', 'error');
				}
			}
			else {
				// Changement d'email
			}
		}

		$this->set('email' , $user->mail);
	}

	public function deconnexion() {
		$this->Session->write('user', array());
		$this->Session->write('auth', false);
		$this->Session->setFlash("Vous êtes déconnecté", "success");
		$this->redirect(url());
	}

}
