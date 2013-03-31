<?php

$exclude = array('.', '..', 'ModelBdd.php', 'ModelCVS.php');

$dir = opendir(LIBS_DIR);
while ($file = readdir()) {
	if($file != basename(__FILE__) && !in_array($file, $exclude)) {
		require_once LIBS_DIR . DS . $file;
	}
}

if (Conf::$db) {
	require_once 'ModelBdd.php';
} else {
	require_once 'ModelCVS.php';
}

?>