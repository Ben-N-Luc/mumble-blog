<?php

class Model extends AppModel {

	public $validate = array();
	public $errors = array();

	/**
	 * Retourne true si les données envoyées correspondent aux règles données dans le model en cours
	 */
	public function validates($data) {
		$errors = array();

		foreach ($this->validate as $k => $v) {
			if (isset($data->$k)) {
				if ($v['verif'] == 'notEmpty' && empty($data->$k)) {
					$this->errors[$k] = $v['message'];
				} elseif ($v['verif'] == 'regex' && !preg_match('/^' . str_replace('/', '\/', $v['rule']) . '$/', $data->$k)) {
					$this->errors[$k] = $v['message'];
				} elseif ($v['verif'] == 'filtre') {
					if ($v['rule'] == 'mail' && !filter_var($data->$k, FILTER_VALIDATE_EMAIL)) {
						$this->errors[$k] = $v['message'];
					} elseif ($v['rule'] == 'url' && !preg_match('`(https?|ftp)://[a-z0-9][a-z0-9./_-]*(\?[a-z0-9./_%-]+=[a-z0-9./_%-]+(&[a-z0-9./_%-]+=[a-z0-9./_%-])*)?(#[a-z0-9./_%-])?`', $url)) {
						$this->errors[$k] = $v['message'];
					}
				} elseif($v['verif'] == 'in_array') {
					if(!in_array($data->$k, $v['rule'])) {
						$this->errors[$k] = $v['message'];
					}
				}
			}
		}

		return empty($this->errors);
	}
}
