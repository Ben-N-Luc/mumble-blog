<?php

class e404Ctrl extends Ctrl {

	public function e404() {
		if(isset($this->params[0])) {
			$d['msg'] = $this->params[0];
		} else {
			$d['msg'] = '';
		}

		$this->set($d);
	}

}
