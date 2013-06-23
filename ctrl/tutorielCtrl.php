<?php

class tutorielCtrl extends Ctrl {

	public function tutoriel() {
		$viewer = new Viewer();
		$info = $viewer->getInfo();
		$this->set('url', $viewer->infos->url[0]);
	}

}
