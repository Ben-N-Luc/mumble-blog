<?php

/**
 * Affiche le contenu d'une variable
 * @param mixed $var La variable à afficher
 */
function debug($var) {
	$debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	echo '<style>';
	echo '.debug-container { text-align: center; color: #222; }';
	echo '.debug { display: inline-block; clear: both; background-color: rgba(255,30,30, 0.8); max-width: 90%; margin: 10px auto; padding: 5px; border-radius: 10px; border: 1px solid #ddd; box-shadow: inset 0 0 2px rgba(0,0,0,0.8); font-family: Courier; }';
	echo '.debug .info, .debug pre { padding: 5px 8px; border: 1px solid #666; background-color: rgba(255,255,255,0.85); border-radius: 7px; box-shadow: 0 0 1px rgba(0,0,0,0.6); text-align: left; }';
	echo '.debug .info { margin: 3px 3px; }';
	echo '.debug pre { margin: 3px; }';
	echo '</style>';
	echo '<div class="debug-container">';
	echo '<div class="debug">';
	echo '<div class="info">';
	echo 'Debug line ' . $debug[0]['line'] . ' in file [' . str_replace(ROOT, 'ROOT' . DS, $debug[0]['file']) . ']';
	if(isset($debug[1])) {
		echo ', function ';
		if(isset($debug[1]['class'])) {
			echo $debug[1]['class'] . '::';
		}
		echo $debug[1]['function'] . '()';
	}
	echo '.';
	echo '</div>';
	// var_dump($var);
	echo '<pre>';
	print_r($var);
	echo '</div>';
	echo '</div>';
}

/**
 * Renvoie le chemin html vers le dossier webroot
 * @param string $file Chemin dont on souhaite l'adresse
 * @return string
 */
function url($file = '') {
	return URL_ROOT . '/' . trim($file, '/');
}

/**
 * Vérifie si un tableau est associatif
 * @param array $array Tableau à vérifié
 * @return bool True si c'est le cas
 */
function isAssociative($array) {
    return (array_keys($array) != array_keys(array_keys($array)));
}

/**
 * Renvoie la chaine de caractère sans les accents
 * @param type string $str Chaine de caract&egrave;re où supprimer les accents
 * @param type string $charset Encodage (d&eacute;faut = 'utf-8')
 * @return type string Chaine sans les caract&egrave;res accentu&eacute;s.
 */
function removeAccents($str, $charset = 'utf-8') {
	$str = htmlentities($str, ENT_NOQUOTES, $charset);

	$str = preg_replace('#&(.)(acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '$1', $str);
	$str = preg_replace('#&([A-zA-Z])(.+);#', '$1', $str); // pour les ligatures e.g. '&oelig;'
	$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caract&egrave;res

	return $str;
}

/**
 * Renvoie le contenu html sécurisé
 * @param string $html Contenu html à sécurisé
 * @param string $allowable Balises html acceptables
 * @return string
 */
function sanitize($html, $allowable = '<p><a><strong><h1><h2><h3><h4><blockquote><em><ul><li><ol><table><tr><td><th><thead><tbody><tfoot><div><span>') {
	return strip_tags(stripslashes($html), $allowable);
}

/**
 * Décompose des secondes en jours, heures, minutes...
 * @param int $text Nombre de secondes
 * @return array
 */
function secToTime($sec) {
	$time['sec'] = $sec;
	$time['j'] = floor($time['sec'] / (3600 * 24));
	$time['sec'] -= $time['j'] * 3600 * 24;
	$time['h'] = floor($time['sec'] / 3600);
	$time['sec'] -= $time['h'] * 3600;
	$time['min'] = floor($time['sec'] / 60);
	$time['sec'] -= $time['min'] * 60;
	return $time;
}

function object_keys($obj) {
	$keys = array();
	foreach ($obj as $k => $v) {
		$keys[] = $k;
	}
	return $keys;
}
