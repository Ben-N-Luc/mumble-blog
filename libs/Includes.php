<?php

$exclude = array('.', '..');

$dir = opendir(CORE_DIR);
while ($file = readdir()) {
    if(!in_array($file, $exclude) && is_file(CORE_DIR . DS . $file)) {
        require CORE_DIR . DS . $file;
    }
}
closedir($dir);

$dir = opendir(LIBS_DIR);
while ($file = readdir()) {
    if($file != basename(__FILE__) && !in_array($file, $exclude) && is_file(LIBS_DIR . DS . $file)) {
        require LIBS_DIR . DS . $file;
    }
}
