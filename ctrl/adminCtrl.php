<?php

class adminCtrl extends Ctrl {

	public $uses = array(
		'Models' => array('User', 'Ticket')
	);
	public $allowed = 'admin';

	public function admin() {
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
	}


}


?>