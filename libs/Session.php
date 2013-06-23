<?php

class Session {

	/**
	 * Démarre la session
	 */
	public function __construct() {
		if(!isset($_SESSION)) {
			session_start();
			if(!$this->read('flash')) {
				$this->write('flash', array());
			}
			if (!$this->read('auth')) {
				$this->write('auth', false);
			}
		}
	}

	/**
	 * stocke la valeur $value dans $_SESSION[$key]
	 * @param mixed $key Clé
	 * @param mixed $value Valeur stockée
	 */
	public function write($key, $value) {
		$_SESSION[$key] = $value;
	}

	/**
	 * Lis la valeur stockée en $_SESSION[$key]
	 */
	public function read($key) {
		return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
	}

	/**
	 * Stocke les données de l'utilisateur et le marque comme authentifié
	 * @param array $userData Données de l'utilisateur
	 */
	public function auth($userData) {
		$this->write('user', $userData);
		$this->write('auth', true);
	}

	/**
	 * Ajoute une information en session
	 * @param string $msg Message à afficher
	 * @param string $type Type de message (info | error | success | warning)
	 */
	public function setFlash($msg, $type = 'info') {
		$flash = array(
			'msg' => $msg,
			'type' => $type
		);
		$previousFlash = $this->read('flash');
		array_push($previousFlash, $flash);
		$this->write('flash', $previousFlash);
	}

	/**
	 */
	public function flash() {
		$html = '';
		foreach ($this->read('flash') as $v) {
			$flash = $v;
			$html .= '<div class="alert alert-' . $flash['type'] . '">';
			if($flash['type'] == 'error') {
				$html .= '<span class="icon-remove-circle"></span> ';
			} elseif($flash['type'] == 'success') {
				$html .= '<span class="icon-ok-circle"></span> ';
			}
			$html .= $flash['msg'] . '<button type="button" class="close" data-dismiss="alert">×</button></div>';
			$this->write('flash', array());
		}
		return $html;
	}

}
