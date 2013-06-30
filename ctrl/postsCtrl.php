<?php

class postsCtrl extends Ctrl {

	public $uses = array(
		'Models' => array('Post')
	);

	public function posts() {
		$d['posts'] = $this->Post->search(
			array(
				'online' => 1
			),
			array(
				'order' => array(
					'order' => 'DESC',
					'field' => 'date'
			)
		));

		$this->set($d);
	}

	public function view() {
		if(!isset($this->params[0]) || !is_numeric($this->params[0])) {
			$this->redirect(url('posts'));
		}

		$tmp = $this->Post->search(array(
			'id' => $this->params[0]
		));
		$d['post'] = $tmp[0];

		if(!$d['post']->online && $this->Session->read('user')->rank != 'a') {
			$this->redirect(url('posts'));
		}

		$this->set($d);
	}

}
