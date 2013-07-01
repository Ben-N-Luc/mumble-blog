<?php

$exclude = array('.', '..');

$dir = opendir(LIBS_DIR);
while ($file = readdir()) {
	if($file != basename(__FILE__) && !in_array($file, $exclude)) {
		require_once LIBS_DIR . DS . $file;
	}
}
