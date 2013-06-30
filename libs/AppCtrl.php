<?php

class AppCtrl {

	public $Styles = array(
		'bootstrap.less',
		'style.less'
	);

	/**
	 * Contiendra l'objet de session
	 */
	public $Session;

	/**
	 * Modèles à charger dans tous les controllers
	 */
	public $DefaultModels = array('Config');

	/**
	 * Liste des objets utilisé par le controller
	 * (par exemple les modèles a charger pour chaque action du controller)
	 */
	public $uses = array(
		'Models' => array()
	);

	/**
	 * Données à passer du controller à la vue
	 */
	protected $_data = array();

	/**
	 * Rang des personnes autorisées à voir le contenu :
	 * all       : Tout le monde
	 * connected : Utilisateurs connectés
	 * admin     : Les administrateurs
	 */
	public $allowed = 'all';

	/**
	 * Url vers laquelle les utilisateurs sont redirigé en cas
	 * d'erreur d'authentification.
	 * Elle doit évidemment être accessible pour tous...
	 */
	public $connectionUrl = 'connexion';

	/**
	 * Layout par défaut
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
				$this->redirect(url($this->connectionUrl), 403);
			}
		}

		$this->Request = new Request($ctrl, $action, $params);
		//$this->Form = new Form($this);

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

			$this->Css .= '<link href="' . url('css/' . $name . '.css') . '" rel="stylesheet">' . "\n";
		}

		// Lancement de la fonction principale
		if(in_array($this->Request->action, array_diff(get_class_methods($this), get_class_methods('Ctrl')))) {
			$this->{$this->Request->action}();
		} else {
			require CTRL_DIR . DS . 'e404Ctrl.php';
			$ctrl = new e404Ctrl('e404', 'e404', array('Method not found (' . $this->Request->action . ')'));
			exit();
		}

		extract($this->_data);

		if(REQUEST_URI != '') {
			$title_for_layout = ucwords(str_replace('/', ' - ', str_replace('-', ' ', REQUEST_URI)));
		}

		/**
		 * Chargement de la vue
		 */
		ob_start();
		if(is_file(VIEWS_DIR . DS . $this->Request->ctrl . DS . $this->Request->action . '.php')) {
			require VIEWS_DIR . DS . $this->Request->ctrl . DS . $this->Request->action . '.php';
		} elseif($ctrl == '') {
			require VIEWS_DIR . DS . 'index' . DS . 'index.php';
		} else {
			header("HTTP/1.0 404 Not Found");
			$msg = 'View not found (' . VIEWS_DIR . DS . $this->Request->ctrl . DS . $this->Request->action . '.php)';
			require VIEWS_DIR . DS . 'e404' . DS . 'e404.php';
		}
		$content_for_layout = ob_get_clean();

		// Chargement du layout
		if(is_file(VIEWS_DIR . DS . $this->Layout . '.php')) {
			require VIEWS_DIR . DS . $this->Layout . '.php';
		} else {
			$this->error($this->Layout . '.php do not exist in ' . VIEWS_DIR . DS . $this->Layout . '.php', E_USER_ERROR);
		}
	}

	public function loadModels($models) {
		foreach ($models as $model) {
			$this->loadModel($model);
		}
	}

	public function loadModel($model) {
		$model = ucfirst($model);
		if(!isset($this->$model) || $this->$model === false) {
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
			$this->_data = array_merge($this->_data, $tabOrKey);
		} else {
			$this->_data[$tabOrKey] = $value;
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
	 * Trigger an error
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

}
