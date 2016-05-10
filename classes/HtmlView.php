<?php

/**
 * The plugin framework
 */
namespace Pfw;

/**
 * HTML views
 */
class HtmlView extends View
{
    protected function escape($string)
    {
        return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
    }
}
