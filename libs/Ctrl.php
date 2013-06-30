<?php

class Ctrl extends AppCtrl {

	public function nav() {
		$html = '<nav>' . "\n";
		$html .= '<div class="expand">';
		for ($i=0; $i < 3; $i++) {
			$html .= '<span class="icon">-</span>';
		}
		$html .= '</div>' . "\n";
		$html .= '<ul>';
		$nav = ($this->Session->read('auth')) ? 'connected' : 'public';
		if($nav == "connected") {
			$user = $this->Session->read('user');
			if($user->rank == 'a') {
				$nav = 'admin';
			}
		}
		if($nav == 'admin') {
			if($this->Request->ctrl == 'admin') {
				$nav = Conf::$navLinks['admin'];
			} else {
				$nav = Conf::$navLinks['default'];
				$nav['left'] = Conf::$navLinks['admin']['left'];
			}
		} else {
			$nav = array_merge(Conf::$navLinks['default'], Conf::$navLinks[$nav]);
		}
		foreach ($nav as $url => $text) {
			if($url == 'left' && is_array($text)) {
				$html .= '<ul class="pull-right">';
				foreach ($text as $url => $text) {
					$html .= '<li' . ((REQUEST_URI == $url) ? ' class="active"' : '') . '><a href="' . url($url) . '">' . $text . '</a></li>' . "\n";
				}
				$html .= '</ul>';
			} else {
				$html .= '<li';
				if(($url && strpos(REQUEST_URI, $url) !== false) || $url == REQUEST_URI) {
					$html .= ' class="active"';
				}
				$html .= '><a href="' . url($url) . '">' . $text . '</a></li>' . "\n";
			}
		}
		$html .= '</ul>' . "\n";
		$html .= '<div class="clearfix"></nav>';

		echo $html;
	}

	public function badge($text, $cat = false) {
		if(array_key_exists($text, Conf::$ticketCategories) && !$cat) {
			$html = '<a href="' . url('ticket/ticket/' . $text) . '" class="badge';
			$html .= ' badge-' . Conf::$ticketCategories[$text]['type'] . ' ';
			$html .= '">' . $text . '</a>';
		} else {
			$html = '<span class="badge';
			if($cat) {
				$html .= ' badge-' . $cat;
			}
			$html .= '">' . $text . '</span>';
		}

		return $html;
	}

	public function viewer() {
		return false;

		$viewer = new Viewer();
		$info = $viewer->getInfo();

		return $viewer->get();
	}

	public function css() {
		echo $this->_Css;
	}
}
