<?php

class Request {

	public $get;
	public $post;
	public $posted = false;

	public function __construct($ctrl, $action, $params) {
		// Stockage des informations de la classe
		$this->ctrl = $ctrl;
		$this->action = str_replace('-', '_', $action);
		$this->params = $params;

		$this->_load_get();
		$this->_load_post();
	}

	/**
	 * Récupération et échappement des infos de la variable $_GET
	 */
	protected function _load_get() {
		if(!empty($_GET)) {
			$this->get = new stdClass();
		}

		foreach ($_GET as $k => $v) {
			$this->get->$k = urldecode($v);
		}
	}

	/**
	 * Récupération et échappement des infos de la variable $_POST
	 */
	protected function _load_post() {
		if(!empty($_POST)) {
			$this->post = new stdClass();
			$this->posted = true;
		}

		foreach ($_POST as $k => $v) {
			$this->post->$k = htmlentities($v, ENT_QUOTES, 'utf-8');
		}
	}

	public function reset($target = null) {
		if($target === null) {
			$this->get = new stdClass();
			$this->post = new stdClass();
		} elseif ($target == 'get') {
			$this->get = new stdClass();
		} elseif ($target == 'post') {
			$this->post = new stdClass();
		} else {
			return false;
		}

		return true;
	}
}
