<?php

/**
 * Handling of HTML output.
 *
 * The classes in this namespace ease the generation of properly escaped HTML.
 * Whenever non-trivial HTML is required the recommended way is to create a PHP
 * template file which outlines the structure of the HTML, employing only a very
 * small subset of the general PHP syntax.  To actually generate the HTML, a
 * `View` object has to be instantiated, linked to the template, filled with the
 * necessary data and finally rendered.
 */
namespace Pfw\View;
