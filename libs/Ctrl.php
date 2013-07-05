<?php

class Ctrl extends AppCtrl {

	/**
	 * Génère la navigation du site
	 * @return string Code html
	 */
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

	/**
	 * Transforme un texte en badge
	 * @param  string $text Texte
	 * @param  string $cat Catégorie (si false, cherche le badge correspondant aux catégorie de tickets)
	 * @return string Code du badge
	 */
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

	/**
	 * Affiche le viewer
	 * @return string Code html du viewer
	 */
	public function viewer() {
		return false;

		$viewer = new Viewer();

		return $viewer->get();
	}

	/**
	 * Affiche les balises css de link
	 */
	public function css() {
		echo $this->_Css;
	}

	/**
	 * Vérifie si un fichier qui vient d'être posté correspond à une image
	 * @param  array  $file
	 * @return bool
	 */
	public function isImg($file) {
		$type = explode('/', $file['type']);

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
			array_pop($name);
			if ($id == implode('.', $name)) {
				return $v;
			}
		}
	}

	/**
	 * Copie l'avatar par défaut
	 * @param  int $id Id du nouvel utilisateur
	 * @return bool Résultat de la fonction copy()
	 */
	public function generateAvatar($id) {
		$dir = WEBROOT_DIR . DS . 'img' . DS . 'users' . DS;
		return copy($dir . '0.png', $dir . $id . '.png');
	}

}
