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

namespace Pfw\Forms;

use XMLWriter;

/**
 * Hidden input form controls
 */
class HiddenControl extends InputControl
{
    /**
     * Renders the type attribute of the input
     *
     * @param XMLWriter $writer
     *
     * @return void
     */
    public function renderTypeAttribute(XMLWriter $writer)
    {
        $writer->writeAttribute('type', 'hidden');
    }

    /**
     * Renders the control's label
     *
     * As hidden input controls don't need a label, we simply do nothing here.
     *
     * @param XMLWriter $writer
     *
     * @return void
     */
    public function renderLabel(XMLWriter $writer)
    {
        // do nothing
    }
}
