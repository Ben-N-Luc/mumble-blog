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
		$this->loadModel('User');

		if(!isset($this->Request->params[0]) || !is_numeric($this->Request->params[0])) {
			$this->redirect(url('posts'));
		}

		$tmp = $this->Post->innerJoin($this->User, array(
			'posts.id' => $this->Request->params[0]
		));
		$d['post'] = $tmp[0];

		if(!$d['post']->online && $this->Session->read('user')->rank != 'a') {
			$this->redirect(url('posts'));
		}

		$this->set($d);
	}

}
