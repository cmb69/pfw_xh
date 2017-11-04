<?php

/*
 * Copyright 2017 Christoph M. Becker
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

namespace Pfw;

final class SystemCheck
{
    const SUCCESS = 'success';

    const WARNING = 'warning';

    const FAILURE = 'fail';

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $label;

    /**
     * @param string $label
     * @param string $state
     */
    public function __construct($label, $state)
    {
        $this->label = (string) $label;
        $this->state = (string) $state;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getStateLabel()
    {
        global $plugin_tx;

        return $plugin_tx['pfw']["syscheck_{$this->state}"];
    }
}
