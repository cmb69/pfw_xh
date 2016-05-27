<?php

/*
Copyright 2016 Christoph M. Becker
 
This file is part of Pfw_XH.

Pfw_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Pfw_XH is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Pfw;

/**
 * Users
 *
 * Currently this class has only a single method, namely to check
 * whether the current user is the administrator.
 * It might be extended in the future to offer an interface for
 * memberpages-like plugins.
 */
class User
{
    /**
     * Returns whether the current user is the administrator.
     *
     * @return bool
     */
    public static function isAdmin()
    {
        return defined('XH_ADM') && XH_ADM;
    }
}
