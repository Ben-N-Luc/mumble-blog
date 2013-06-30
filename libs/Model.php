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
			if (!isset($data->$k)) {
				$errors[$k] = $v['message'];
			} else {
				if ($v['rule'] == 'notEmpty' && empty($data->$k)) {
					$errors[$k] = $v['message'];
				}
				if (isset($v['verif'])) {
					if ($v['verif'] == 'regex' && !preg_match('/^' . $v['rule'] . '$/', $data->$k)) {
						$errors[$k] = $v['message'];
					} elseif ($v['verif'] == 'filtre') {
						if ($v['rule'] == 'mail' && !filter_var($data->$k, FILTER_VALIDATE_EMAIL)) {
							$errors[$k] = $v['message'];
						} elseif ($v['rule'] == 'url' && !preg_match('`((?:https?|ftp)://\S+[[:alnum:]]/?)`si',$url)) {
							$errors[$k] = $v['message'];
						}
					}
				}
			}
		}
		$this->errors = $errors;

		return empty($errors);
	}
}
