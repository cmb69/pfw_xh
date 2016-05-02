<?php

/**
 * The plugin framework
 */
namespace Pfw;

/**
 * The plugin base class
 */
abstract class AbstractPlugin
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

    /**
     * Constructs an instance
     */
    public function __construct()
    {
        global $plugin, $pth;

        $this->name = $plugin;
        $this->folder = $pth['folder']['plugin'];
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
     * Returns the plugin version
     *
     * @return string
     */
    abstract public function version();
}
