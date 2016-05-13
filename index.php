<?php

/**
 * Register the PFW plugin itself
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

Pfw\Plugin::register()
    ->copyright('2016 Christoph M. Becker')
    ->version('0.1')
    ->admin()
        ->route(array(
            'pfw&admin=plugin_config' => 'Pfw\\ConfigAdminController',
            'pfw&admin=plugin_language' => 'Pfw\\LanguageAdminController',
            'pfw&admin=plugin_stylesheet' => 'Pfw\\StylesheetAdminController',
            'pfw' => 'Pfw\\DefaultAdminController'
        ))
;

XH_afterPluginLoading('Pfw\\Plugin::runAll');
