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
	}

	public function deconnexion() {
		$this->Session->write('user', array());
		$this->Session->write('auth', false);
		$this->Session->setFlash("Vous êtes déconnecté...", "success");
		$this->redirect(url());
	}

}
