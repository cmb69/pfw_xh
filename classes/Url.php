<?php

/**
 * The plugin framework.
 */
namespace Pfw;

/**
 * Internal URLs as value objects.
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */
class Url
{
    /**
     * The URL path.
     *
     * @var string
     */
    private $path;

    /**
     * The URL params.
     *
     * @var array
     */
    private $params;

    /**
     * Constructs an instance.
     *
     * @param string $path
     * @param array  $params
     */
    public function __construct($path, array $params)
    {
        $this->path = $path;
        $this->params = $params;
    }

    /**
     * Converts an instance to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->relative();
    }

    /**
     * Returns the relative URL.
     *
     * @return string
     */
    public function relative()
    {
        return $this->path . '?' . $this->queryString();
    }

    /**
     * Returns the absolute URL.
     *
     * @return string
     */
    public function absolute()
    {
        return CMSIMPLE_URL . '?' . $this->queryString();
    }

    /**
     * Returns the assembled query string.
     *
     * @return string
     */
    private function queryString()
    {
        $params = array_map(
            function ($name, $value) {
                if (!empty($value)) {
                    return "$name=$value";
                } else {
                    return $name;
                }
            },
            array_keys($this->params),
            array_values($this->params)
        );
        return implode('&', $params);
    }

    /**
     * Returns a new URL with a certain param.
     *
     * @param  string $param
     * @param  string $value
     * @return self
     */
    public function with($param, $value)
    {
        $params = $this->params;
        $params[$param] = $value;
        return new self($this->path, $params);
    }

    /**
     * Returns a new URL without a certain param.
     *
     * @param  string $param
     * @return self
     */
    public function without($param)
    {
        $params = $this->params;
        unset($params[$param]);
        return new self($this->path, $params);
    }
}
