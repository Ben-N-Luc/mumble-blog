<?php

class e404Ctrl extends Ctrl {

	public function e404() {
		$data = array(
			'message' => __FILE__ . ' introuvable',
			'token' => 404
		);
		$this->Log->add($data);
	}

}

?>