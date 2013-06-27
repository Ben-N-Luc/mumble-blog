<?php

class Ctrl {

	public $Styles = array(
		'bootstrap.less',
		'style.less'
	);

	/**
	 * Contain the session object
	 */
	public $Session;

	/**
	 * contain $_GET content as object
	 */
	public $Get;

	/**
	 * contain $_POST content as object
	 */
	public $Post;

	/**
	 * Contain true if a post has been detected
	 */
	public $Posted = false;

	/**
	 * Models loaded in every Ctrl
	 */
	public $DefaultModels = array('Config');

	/**
	 * Contain files needed for specifics controllers such as 'Models' wich are all the Models needed
	 */
	public $uses = array(
		'Models' => array()
	);

	/**
	 * Contain data to pass from Ctrl to View
	 */
	public $data = array();

	/**
	 * Contain categories allowed to view the page
	 * Could be : 'all', 'admin', 'connected'
	 */
	public $allowed = 'all';

	/**
	 * Url to redirect user which has to connect
	 * It has to be public !
	 */
	public $connectionUrl = 'connexion';

	/**
	 * Default layout file, overwrite it to use custom layout
	 */
	public $Layout = 'layout';

	public $Less;

	/**
	 * Récupération des variables $_POST et $_GET,
	 * chargement des Models,
	 * démarrage de la session,
	 * vérifie si l'utilisateur doit être identifié
	 * charge la vue et le layout
	 */
	public function __construct($ctrl, $action, $params) {
		// initialisation de la session
		$this->Session = new Session();

		// Chargement des models
		$models = array_merge($this->DefaultModels, $this->uses['Models']);
		$this->loadModels($models);

		// Redirection vers la page de connexion si nécessaire
		if($this->allowed != 'all') {
			$user = $this->Session->read('user');
			if(!$this->Session->read('auth')
			|| ($this->allowed == 'connected' && $user->rank != 'u' && $user->rank != 'a')
			|| ($this->allowed == 'admin' && $user->rank != 'a')) {
				$this->Session->setFlash('Connectez-vous !', 'warning');
				$this->Log->add(array("Tentative d'accès à une page sécurisée (/" . REQUEST_URI . ")", 'not conected'));
				$this->redirect(url($this->connectionUrl), 403);
			}
		}

		// Stockage des informations de la classe
		$this->ctrl = $ctrl;
		$this->action = str_replace('-', '_', $action);
		$this->params = $params;

		// Génération du css lessPHP
		$this->Less = new lessc;
		$this->Less->setFormatter('compressed');
		$this->Less->setVariables(array(
			'webroot' => "'" . URL_ROOT . "'"
		));
		$this->Css = '';
		foreach ($this->Styles as $v) {
			$tmp = explode('.', $v);
			$extension = array_pop($tmp);
			$name = implode('.', $tmp);
			if($extension == 'less') {
				$recompiled = $this->Less->checkedCompile(LESS_DIR . DS . $v, WEBROOT_DIR . DS . 'css' . DS . $name . '.css');
			}
			$this->Css .= file_get_contents(WEBROOT_DIR . DS . 'css' . DS . $name . '.css') . "\n";
		}

		// Précréation des objets Params, Post et Get
		if(!empty($_POST)) {
			$this->Post = new stdClass();
			$this->Posted = true;
		}
		if(!empty($_GET)) {
			$this->Get = new stdClass();
		}

		// Récupération du post
		foreach ($_POST as $k => $v) {
			$this->Post->$k = htmlentities($v, ENT_QUOTES, 'utf-8');
		}

		// Récupération du get
		foreach ($_GET as $k => $v) {
			$this->Get->$k = urldecode($v);
		}

		// Lancement de la fonction principale
		if(in_array($this->action, array_diff(get_class_methods($this), get_class_methods('Ctrl')))) {
			$this->{$this->action}();
		} else {
			require CTRL_DIR . DS . 'e404Ctrl.php';
			$ctrl = new e404Ctrl('e404', 'e404', array('Method not found (' . $this->action . ')'));
			exit();
		}

		extract($this->data);

		if(REQUEST_URI != '') {
			$title_for_layout = ucwords(str_replace('/', ' - ', str_replace('-', ' ', REQUEST_URI)));
		}

		/**
		 * Loading view
		 */
		ob_start();
		if(is_file(VIEWS_DIR . DS . $this->ctrl . DS . $this->action . '.php')) {
			require VIEWS_DIR . DS . $this->ctrl . DS . $this->action . '.php';
		} elseif($ctrl == '') {
			require VIEWS_DIR . DS . 'index' . DS . 'index.php';
		} else {
			header("HTTP/1.0 404 Not Found");
			$msg = 'View not found (' . VIEWS_DIR . DS . $this->ctrl . DS . $this->action . '.php)';
			require VIEWS_DIR . DS . 'e404' . DS . 'e404.php';
		}
		/* getting view */
		$content_for_layout = ob_get_clean();

		/**
		 * Loading layout
		 */
		if(is_file(VIEWS_DIR . DS . $this->Layout . '.php')) {
			require VIEWS_DIR . DS . $this->Layout . '.php';
		} else {
			$this->error($this->Layout . '.php do not exist in ' . VIEWS_DIR . DS . $this->Layout . '.php', E_USER_ERROR);
		}

	}

	public function loadModels($models) {
		foreach ($models as $model) {
			if(!isset($this->$model) || $this->$model !== false) {
				$this->loadModel($model);
			}
		}
	}

	public function loadModel($model) {
		if(!isset($this->$model) || $this->$model !== false) {
			$model = ucfirst($model);
			require_once MODEL_DIR . DS . $model . '.php';
			$this->$model = new $model(strtolower($model));
		}
	}

	/**
	 * Define data to pass from Ctrl to view
	 * @param array $tabOrKey Associative array to pass to view
	 * @param str $tabOrKey Value to store
	 * @param str $key Only necessary if $tabOrKey is a string, variable's name in view
	 */
	public function set($tabOrKey, $value = null) {
		if(is_array($tabOrKey)) {
			$this->data = array_merge($this->data, $tabOrKey);
		} else {
			$this->data[$tabOrKey] = $value;
		}
	}

	/**
	 * Load an element from VIEW_DIR/elements/elem.php
	 * @param str $elemName Filename without extension
	 * @return str Content of the element
	 */
	public function element($elemName) {
		$elem = '';
		if(is_file(VIEWS_DIR . DS . 'elements' . DS . $elemName . '.php')) {
			ob_start();
			require VIEWS_DIR . DS . 'elements' . DS . $elemName . '.php';
			$elem = ob_get_clean();
		} else {
			$this->error(VIEWS_DIR . DS . 'elements' . DS . $elemName . '.php not found', E_USER_WARNING);
		}
		return $elem;
	}

	/**
	 * Trigger an error and log it
	 * @param str $text Error description
	 * @param int/str $lvl Type d'erreur (E_USER_NOTICE, E_USER_WARNING, E_USER_ERROR, user-defined type...)
	 */
	public function error($text, $lvl = E_USER_NOTICE) {

		switch ($lvl) {
			case E_USER_ERROR:
				$levelText = 'Error';
				break;
			case E_USER_WARNING:
				$levelText = 'Warning';
				break;
			case E_USER_NOTICE:
				$levelText = 'Notice';
				break;
			default:
				$levelText = $lvl;
		}

		/* Log error if debug lvl */
		if((is_string($lvl))
		|| (Conf::$debugLvl == 'notice'  && $lvl >= E_USER_NOTICE)
		|| (Conf::$debugLvl == 'warning' && $lvl >= E_USER_WARNING)
		|| (Conf::$debugLvl == 'debug')) {
			$this->Log->add(
				array($text, $levelText)
			);
		}

		error($text, $lvl);
	}

	public function redirect($url = '', $code = null) {
		switch ($code) {
			case 301:
				header('HTTP/1.0 301 Moved Permanently');
				break;
			case 403:
				header('HTTP/1.0 403 Forbidden');
				break;
			case 404:
				header('HTTP/1.0 404 Not Found');
				break;
		}
		header('Location: ' . $url);
		exit();
	}

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
			if($this->ctrl == 'admin') {
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

}
