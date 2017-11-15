<?php

/*
 * Copyright 2016-2017 Christoph M. Becker
 *
 * This file is part of Pfw_XH.
 *
 * Pfw_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pfw_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * Work around for missing `selected` solution in CMSimple_XH < 1.7
 */
if (isset($_GET['selected']) && $su === $_GET['selected']) {
    $temp = ['/^' . preg_quote($su, '/') . '(?=&|$)/u', '/(?:^|&)selected=.*?(?=&|$)/u'];
    $temp = $su . preg_replace($temp, '', $_SERVER['QUERY_STRING']);
    header('Location: ' . CMSIMPLE_URL . "?$temp", true, 301);
    exit;
}

spl_autoload_register(function ($className) {
    $parts = explode('\\', $className);
    $parts[0] = lcfirst($parts[0]);
    array_splice($parts, 1, 0, ['classes']);
    array_unshift($parts, dirname(dirname(__DIR__)));
    $classFile = implode(DIRECTORY_SEPARATOR, $parts) . '.php';
    if (file_exists($classFile)) {
        include_once $classFile;
    }
});
