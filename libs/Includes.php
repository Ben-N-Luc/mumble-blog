<?php

$exclude = array('.', '..', 'Core');

$dir = opendir(LIBS_DIR . DS . 'Core');
while ($file = readdir()) {
	if(!in_array($file, $exclude)) {
		require LIBS_DIR . DS . 'Core' . DS . $file;
	}
}
closedir($dir);

$dir = opendir(LIBS_DIR);
while ($file = readdir()) {
	if($file != basename(__FILE__) && !in_array($file, $exclude)) {
		require LIBS_DIR . DS . $file;
	}
}
