<?php

spl_autoload_register(function ($class) {

    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require(APPLICATION_ROOT . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . $classPath . ".php");
});
