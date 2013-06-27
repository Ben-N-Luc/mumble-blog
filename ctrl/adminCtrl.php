<?php

class adminCtrl extends Ctrl {

	public $uses = array(
		'Models' => array('User', 'Ticket', 'Post')
	);
	public $allowed = 'admin';

	public function admin() {
		$d['tickets'] = $this->Ticket->search(array(
				'closed' => 0,
				'master IS NULL'
			),
			array(
				//'limit' => '16',
				'order' => array(
					'field' => 'date',
					'order' => 'desc'
				)
			)
		);

		$d['users'] = $this->User->search(array(), array(
			'limit' => 6,
			'order' => array(
				'field' => 'id',
				'order' => 'desc'
			)
		));

		foreach ($d['tickets'] as $k => $v) {
			if(strlen($d['tickets'][$k]->content) > 400) {
				$d['tickets'][$k]->content = substr($v->content, 0, 400) . '...';
			}
		}

		$d['rank_translation'] = array(
			'a' => 'Administrateur',
			'u' => 'Utilisateur',
			'b' => 'Bloqué'
		);

		$this->set($d);
	}

	public function tickets() {
		$this->tickets_list();
	}

	public function tickets_list() {
		if(isset($this->params[0]) && is_numeric($this->params[0])) {
			$user_id = $this->params[0];
		} else {
			$user_id = false;
		}

		$d['tickets'] = $this->Ticket->list($user_id);

		foreach ($d['tickets'] as $k => $v) {
			if(strlen($d['tickets'][$k]->ticket_content) > 800) {
				$d['tickets'][$k]->ticket_content = substr($v->ticket_content, 0, 800) . '...';
			}
			$d['tickets'][$k]->ticket_date = ($v->ticket_date) ? date('d-m-Y H:i', strtotime($v->ticket_date)) : 'NaN';
		}

		$this->set($d);
	}

	public function tickets_close() {
	}

	public function posts_list() {
		$d['posts'] = $this->Post->search(array(), array(
			'order' => array(
				'order' => 'DESC',
				'field' => 'date'
			)
		));

		$this->set($d);
	}

	public function posts_edit() {
	}

	public function posts_delete() {
	}

	public function tuto() {
	}

	public function users_list() {
	}

	public function mumble() {
	}

}
