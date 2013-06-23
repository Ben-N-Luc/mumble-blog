<?php

class Helper {

	/**
	 * Storing used id so if you wanna do more than one form
	 * on the same page with same names, you can !
	 */
	private $usedId = array();

	public function __construct() {
	}

	public function input($type, $name, array $options = array()) {
		$html = '';

		/* Setting the id of the field */
		$id = (isset($options['id'])) ? $options['id'] : 'form' . ucfirst($name);

		/* if the id has already been used, generate a new one */
		if(in_array($id, $this->usedId)) {
			$id = $id . uniqid();
		}

		/* saving used id's */
		array_push($this->$usedId, $id);

		/* generating label */
		if(isset($options['label'])) {
			$html .= '<label for="' . $id . '">' . $options['label'] . '</label>';
		}

		// TODO end of the input and easy html structure modifying
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
