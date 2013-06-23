<?php

class indexCtrl extends Ctrl {

	public function index() {
		$this->loadModel('Post');
		$d['posts'] = $this->Post->search(array(
				'online' => 1
			),
			array(
				'limit' => 6,
				'order' => array(
					'order' => 'DESC',
					'field' => 'date'
				)
			)
		);

		$this->set($d);
	}

}
