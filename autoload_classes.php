<?php

spl_autoload_register(function ($class) {

    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $filename = APPLICATION_ROOT . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . $classPath . ".php";

    $isExists = file_exists($filename);
    if($isExists){
        require($filename);
    }

});
