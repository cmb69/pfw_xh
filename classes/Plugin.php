<?php

/**
 * The plugin framework
 */
namespace Pfw;

/**
 * PFW plugins
 */
class Plugin
{
    /**
     * The plugin name
     *
     * @var string
     */
    private $name;

    /**
     * The plugin folder
     *
     * @var string
     */
    private $folder;

    private $version;

    /**
     * Constructs an instance
     */
    public function __construct()
    {
        global $plugin, $pth;

        $this->name = $plugin;
        $this->folder = $pth['folder']['plugin'];
        $this->version = 'UNKNOWN';
    }

    /**
     * Returns the plugin name
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Returns the plugin folder
     *
     * @return string
     */
    public function folder()
    {
        return $this->folder;
    }

    /**
     * Sets or returns the plugin version
     *
     * @return string
     */
    public function version($version = null)
    {
        if (!isset($version)) {
            return $this->version;
        }
        $this->version = $version;
        return $this;
    }
}
