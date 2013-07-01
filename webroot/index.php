<?php

/**
 * Définition des constantes
 */
define('DS',          DIRECTORY_SEPARATOR);
define('ROOT',        substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'webroot')));
define('URL_ROOT',    str_replace('/webroot/index.php', '', $_SERVER['SCRIPT_NAME']));
define('REQUEST_URI', trim(str_replace(URL_ROOT, '', current(explode('?', $_SERVER['REQUEST_URI']))), '/'));
define('VIEWS_DIR',   ROOT . 'views');
define('WEBROOT_DIR', ROOT . 'webroot');
define('LIBS_DIR',    ROOT . 'libs');
define('CORE_DIR',    LIBS_DIR . DS . 'Core');
define('CTRL_DIR',    ROOT . 'ctrl');
define('DATA_DIR',    ROOT . 'data');
define('MODEL_DIR',   ROOT . 'model');
define('LESS_DIR',    ROOT . 'less');
define('ONLINE',      (DS == '/'));

// Inclusion des librairies
include LIBS_DIR . DS . 'Includes.php';

// Error handler personnalisé (parce que c'est fun ^^)
set_error_handler('error_handler');

// Redirection des requêtes vides vers l'index
$script_name = (REQUEST_URI == '') ? 'index' : REQUEST_URI;

if(stripos($script_name, '/') !== false) {
	$params = explode('/', $script_name);
	$script_name = array_shift($params);
	$action = array_shift($params);
} else {
	$action = $script_name;
	$params = array();
}

// Si le controller demandé existe (sinon 404)
if(is_file(CTRL_DIR . DS . $script_name . 'Ctrl.php')) {
	require CTRL_DIR . DS . $script_name . 'Ctrl.php';
	$controllerName = $script_name . 'Ctrl';
	$ctrl = new $controllerName($script_name, $action, $params);
} else {
	header("HTTP/1.0 404 Not Found");
	// Ce serait sympa de vérifier si le ctrl 404 existe aussi
	require CTRL_DIR . DS . 'e404Ctrl.php';
	$ctrl = new e404Ctrl('e404', 'e404', array('Ctrl not found (' . CTRL_DIR . DS . $script_name . 'Ctrl.php' . ')'));
}


?>
