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

		return $viewer->get();
	}

	public function css() {
		echo $this->_Css;
	}

	public function isImg($image) {
		$type = explode('/', $image['type']);

		if ($type[0] == 'image') {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Recherche le nom du fichier image de l'avatar avec son extension
	 */
	public function getAvatarName($id) {
		$files = $this->getDirFiles("/img/users/");

		foreach ($files as $k => $v) {
			$name = explode('.', $v);
			if ($id == $name[0]) {
				return $v;
			}
		}
	}

	/**
	 * Sauvegarde un fichier
	 * @param $image Array contenant le dataFile
	 * @param $dest url du dossier de destination de fichier format "/img/dossier/..."
	 * @param $name Nom du fichier sans extension
	 */
	public function saveFile($image, $dest, $name) {
		$chemin = WEBROOT_DIR . $dest;
		$type = explode('/', $image['type']);

		$chemin .= (substr($chemin, -1, 1) == '/') ? '' : '/' ;
		move_uploaded_file($image['tmp_name'], $chemin . $name . '.' . $type[1]);
	}

	/**
	 * Supprime le fichier passé en argument
	 */
	public function delFile($chemin) {
		if (file_exists(WEBROOT_DIR . $chemin)) {
			unlink(WEBROOT_DIR . $chemin);
		}
	}

	/**
	 * Retourne un tableau contenant uniquement les fichiers contenu dans le dossier donné
	 * @param $dir Dossier à scanner
	 */
	public function getDirFiles($dir) {
		return array_diff(scandir(WEBROOT_DIR . $dir), array('.', '..'));
	}
}
