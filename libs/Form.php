<?php

class Form {

	public $controller;
	public $errors;

	public function __construct(&$controller) {
		$this->controller = $controller;
	}

	/**
	 * Ouvre un formulaire
	 * @param $action Action du formulaire
	 * @param $method Méthode d'envoi de formulaire
	 * @param $options Tableau d'options à appliquer au formulaire (class, ...)
	 */
	public function startForm($action = null, $method = "post", $options = array()) {
		if($action === null) {
			$action = url(REQUEST_URI);
		}
		$html ="<form action=\"$action\" method=\"$method\" ";
		if (!empty($options)) {
			foreach ($options as $attr => $val) {
				$html .= "$attr=\"$val\" ";
			}
		}
		$html .= ">";
		return $html;
	}

	/**
	 * Ferme le formulaire
	 */
	public function endForm() {
		return "</form>";
	}

	/**
	 * Crée un bouton de submit
	 */
	public function submit($value='send', $options = array()) {
		$html = "<div class=\"actions\"><input type=\"submit\" value=\"$value\"";
		foreach ($options as $attr => $val) {
			$html .= "$attr=\"$val\" ";
		}
		return $html . '></div>';
	}

	/**
	 * Affiche un input ou un textarea
	 * @param $name Nom du champ qui sera transmis au model
	 * @param $label Texte affiché avant l'input, mettre hidden pour un champ caché
	 * @param $options Tableau d'options à appliquer à l'input (type, class, rows, cols, ...)
	 */
	public function input($name, $label, $options = array()) {
		$html = '';
		$error = false;
		$classError = '';
		if (isset($this->errors[$name])) {
			$error = $this->errors[$name];
			$classError = ' error';
		}
		if ($label == 'hidden') {
			if (!isset($options['value']) && isset($this->controller->Request->post->$name)) {
				$options['value'] = $this->controller->Request->post->$name; // Récup de la valeur dans le post
			}
			return '<input type="hidden" name="' . $name . '" value="' . $options['value'] . '">';
		} else {
			if (!isset($options['type']))
			{
				$options['type'] = 'text';
			}
			if (isset($this->controller->Request->post->$name) && $options['type'] != 'textarea' && $options['type'] != 'checkbox') {
				$options['value'] = $this->controller->Request->post->$name;
			}
			$html .= "<div class=\"row$classError\">";
			$html .= "<label for=\"$name\">$label</label>";
			$fin = "";
			if ($options['type'] == 'text') {
				$html .= "<input type=\"text\" ";
			} elseif ($options['type'] == 'email') {
				$html .= "<input type=\"email\" ";
			} elseif ($options['type'] == 'url') {
				$html .= "<input type=\"text\" pattern=\"http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?\" ";
			} elseif ($options['type'] == 'checkbox') {
				$html .= "<input type=\"hidden\" name=\"$name\" value=\"0\"><input type=\"checkbox\" ";
				if ($options['value'] == $this->controller->Request->post->$name) {
					$html .= 'checked ';
				}
			} elseif ($options['type'] == 'password') {
				$html .= "<input type=\"password\" ";
			} elseif ($options['type'] == 'textarea') {
				$html .= "<textarea ";
				$fin = $this->controller->Request->post->$name . "</textarea>";
			}
			$html .= "id=\"$name\" name=\"$name\" ";
			foreach ($options as $attr => $val) {
				$html .= "$attr=\"$val\" ";
			}
			$html .= '>' . $fin;
			if ($error) {
				$html .= '<span class="help-inline">' . $error . '</span>';
			}
			$html .= "</div>";
		}

		return $html . "\n";
	}

	public function radio($name, array $options = array()) {
	}

	public function select($name, array $options = array()) {
	}

	/**
	 * Transform a string in valid slug
	 * @param string $text Text to transform
	 * @return string Valid url
	 */
	public function slug($text) {
		$slug = str_replace(" ", "-", strtolower($text));
		$slug = str_replace("_", "-", $slug);
		$slug = removeAccents($slug);
		$slug = preg_filter('#[^a-z0-9|^\-]*#', "", $slug);

		return $slug;
	}


}
