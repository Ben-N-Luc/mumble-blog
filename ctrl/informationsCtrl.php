<?php

class informationsCtrl extends Ctrl {

	public function informations() {
		$cacheViewer = new Cache('Viewer');

		if(!$cacheViewer->validate()) {
			$viewer = new Viewer();
			$cacheViewer->write($viewer);
			var_dump('not cached');
		} else {
			$viewer = $cacheViewer->read();
			var_dump('cached');
		}

		$result = $viewer->get();

		// transformation en jour, heure...
		$uptime = secToTime($viewer->infos->uptime);

		$this->set('url', $viewer->infos->url);
		$this->set('uptime', $uptime);
		$this->set('viewer', $result);
	}

}
