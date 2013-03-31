<?php

class informationsCtrl extends Ctrl {

	public function informations() {
		$viewer = new Viewer();
		$info = $viewer->getInfo();
		$result = $viewer->get();

		// transformation en jour, heure...
		$uptime = secToTime($viewer->infos->uptime);

		$this->set('url', $viewer->infos->url);
		$this->set('uptime', $uptime);
		$this->set('viewer', $result);
	}

}

?>