<?php

class deconnexionCtrl extends Ctrl {

	public function deconnexion() {
		$this->Session->write('user', array());
		$this->Session->write('auth', false);
		$this->Session->setFlash("Vous êtes déconnecté...", "success");
		$this->redirect(url());
	}


}


?>