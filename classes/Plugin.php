<?php

/**
 * The plugin framework
 *
 * @todo SystemCheck classes incl. builder
 */
namespace Pfw;

/**
 * The PFW plugin
 */
class Plugin extends AbstractPlugin
{
    /**
     * Returns the plugin version
     *
     * @return string
     */
    public function version()
    {
        return '0.1.0';
    }
}
