<?php

class compteCtrl extends Ctrl {

	public $uses = array(
		'Models' => array('User', 'Ticket')
	);

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
		//var_dump($tickets);
		$this->set('tickets', $tickets);
		$this->set('user', $user);
	}


}



?>