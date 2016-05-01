<?php

/**
 * The autoloader
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

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
