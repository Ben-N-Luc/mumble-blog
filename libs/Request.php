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

		$this->load_get();
		$this->load_post();
	}

	/**
	 * Récupération et échappement des infos de la variable $_GET
	 */
	public function load_get() {
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
	public function load_post() {
		if(!empty($_POST)) {
			$this->post = new stdClass();
			$this->posted = true;
		}

		foreach ($_POST as $k => $v) {
			$this->post->$k = htmlentities($v, ENT_QUOTES, 'utf-8');
		}
	}
}
