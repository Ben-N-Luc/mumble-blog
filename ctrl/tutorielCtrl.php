<?php

class tutorielCtrl extends Ctrl {

	public function tutoriel() {
		$viewer = new Viewer();
		$this->set('url', $viewer->infos->url[0]);
	}

}
