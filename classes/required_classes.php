<?php

spl_autoload_register(function ($className) {
    $parts = explode('\\', $className);
    $parts[0] = lcfirst($parts[0]);
    array_splice($parts, 1, 0, array('classes'));
    array_unshift($parts, dirname(dirname(__DIR__)));
    $classFile = implode(DIRECTORY_SEPARATOR, $parts) . '.php';
    if (file_exists($classFile)) {
        include_once $classFile;
    }
});
